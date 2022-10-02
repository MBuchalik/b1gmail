<?php
/*
 * b1gMail
 * Copyright (c) 2021 Patrick Schlangen et al
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 */

if (!defined('B1GMAIL_INIT')) {
    die('Directly calling this file is not supported');
}

/**
 * Clean up expired action tokens
 *
 */
function CleanupActionTokens() {
    global $db;

    $db->Query('DELETE FROM {pre}actiontokens WHERE `expires`<=?', time());
}

/**
 * Clean up the mail send stats
 *
 */
function CleanupSendStats() {
    global $db;

    $db->Query(
        'DELETE FROM {pre}sendstats WHERE `time`<?',
        time() - TIME_ONE_WEEK,
    );
}

/**
 * Clean up the mail receive stats
 *
 */
function CleanupRecvStats() {
    global $db;

    $db->Query(
        'DELETE FROM {pre}recvstats WHERE `time`<?',
        time() - TIME_ONE_WEEK,
    );
}

/**
 * Auto-delete users who never logged in
 *
 */
function ProcessNoSignupAutoDel() {
    global $bm_prefs, $db;

    if (
        $bm_prefs['nosignup_autodel'] == 'yes' &&
        $bm_prefs['nosignup_autodel_days'] >= 1
    ) {
        $userIDs = [];

        $res = $db->Query(
            'SELECT `id`,`email` FROM {pre}users WHERE `id`!=1 AND `lastlogin`=0 AND `last_pop3`=0 AND `last_imap`=0 AND `last_smtp`=0 AND `reg_date`<? AND `gesperrt`!=?',
            time() - TIME_ONE_DAY * $bm_prefs['nosignup_autodel_days'],
            'delete',
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            PutLog(
                sprintf(
                    'Marking user <%s> (#%d) as deleted because no login occured within %d days after signup',
                    $row['email'],
                    $row['id'],
                    $bm_prefs['nosignup_autodel_days'],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
            $userIDs[] = $row['id'];
        }
        $res->Free();

        if (count($userIDs) > 0) {
            $db->Query(
                'UPDATE {pre}users SET `gesperrt`=? WHERE `id` IN ?',
                'delete',
                $userIDs,
            );
        }
    }
}

/**
 * Delete old mail delivery status entries
 *
 */
function CleanupMailDeliveryStatus() {
    global $db;

    $db->Query(
        'DELETE FROM {pre}maildeliverystatus WHERE (`status`=? OR `outboxid`=0) AND `created`<?',
        MDSTATUS_INVALID,
        time() - TIME_ONE_HOUR,
    );
}

/**
 * clean up expired cert mails
 *
 */
function CleanupCertMails() {
    global $db, $bm_prefs;

    $date = time() - $bm_prefs['einsch_life'];

    $res = $db->Query(
        'SELECT DISTINCT(mail) AS mailID FROM {pre}certmails WHERE date<' .
            $date,
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $db->Query(
            'UPDATE {pre}mails SET flags=flags&(~' .
                FLAG_CERTMAIL .
                ') WHERE id=?',
            $row['mailID'],
        );
    }
    $res->Free();

    $db->Query('DELETE FROM {pre}certmails WHERE date<' . $date);
}

/**
 * delete outdated, pending aliases
 *
 * @return bool
 */
function CleanupAliases() {
    global $db;

    $db->Query(
        'DELETE FROM {pre}aliase WHERE (type&' .
            ALIAS_PENDING .
            ')!=0 AND date<' .
            (time() - TIME_ONE_WEEK),
    );
    return true;
}

/**
 * process store time row
 *
 * @param array $row Row
 */
function ProcessStoreTimeRow($row) {
    global $db;

    if (!class_exists('BMMailBox')) {
        include B1GMAIL_DIR . 'serverlib/mailbox.class.php';
    }

    $userID = $row['userID'];
    $userObject = _new('BMUser', [$userID]);
    $userMail = $userObject->_row['email'];
    $userMailbox = _new('BMMailBox', [$userID, $userMail, $userObject]);
    $mailIDs = explode(',', $row['mailIDs']);
    $blobStorages = explode(',', $row['blobStorages']);
    $freedSpace = $row['mailSizes'];

    foreach ($mailIDs as $entryNo => $mailID) {
        BMBlobStorage::createProvider(
            $blobStorages[$entryNo],
            $userID,
        )->deleteBlob(BMBLOB_TYPE_MAIL, $mailID);
        ModuleFunction('AfterDeleteMail', [$mailID, &$userMailbox]);
    }

    $db->Query('DELETE FROM {pre}mails WHERE `id` IN ?', $mailIDs);
    $db->Query('DELETE FROM {pre}certmails WHERE `mail` IN ?', $mailIDs);
    $db->Query(
        'UPDATE {pre}users SET `mailspace_used`=`mailspace_used`-LEAST(`mailspace_used`,' .
            ((int) abs($freedSpace)) .
            '),`mailbox_generation`=`mailbox_generation`+1 WHERE `id`=?',
        $userID,
    );
}

/**
 * enforce store times
 *
 * @return int Number of deleted mails
 */
function StoreTimeCron() {
    global $db, $cacheManager;

    // user folders
    $res = $db->Query(
        'SELECT GROUP_CONCAT({pre}mails.`id` SEPARATOR ?) AS mailIDs,GROUP_CONCAT({pre}mails.`blobstorage` SEPARATOR ?) AS blobStorages,SUM({pre}mails.`size`) AS mailSizes,{pre}mails.`userid` AS userID FROM {pre}mails,{pre}folders ' .
            'WHERE {pre}mails.folder>0 ' .
            'AND {pre}folders.id={pre}mails.folder ' .
            'AND {pre}folders.storetime>0 ' .
            'AND {pre}mails.datum<?-{pre}folders.storetime ' .
            'GROUP BY {pre}mails.`userid`',
        ',',
        ',',
        time(),
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        ProcessStoreTimeRow($row);
    }
    $res->Free();

    // system folders
    $res = $db->Query(
        'SELECT `userid` AS userID,GROUP_CONCAT(`key` SEPARATOR ?) AS `keys`,GROUP_CONCAT(`value` SEPARATOR ?) AS `values` FROM {pre}userprefs WHERE `key` LIKE ? AND `value`>0 GROUP BY `userid`',
        ',',
        ',',
        'storetime_%',
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $storeTimes = [];
        $keys = explode(',', $row['keys']);
        $values = explode(',', $row['values']);

        foreach ($keys as $index => $key) {
            $storeTimes[(int) substr($key, 10)] = $values[$index];
        }

        $cond = [];
        foreach ($storeTimes as $folder => $time) {
            $cond[] = sprintf(
                '(`folder`=%d AND `datum`<%d)',
                $folder,
                time() - $time,
            );
        }
        $cond = '(' . implode(' OR ', $cond) . ')';

        $res2 = $db->Query(
            'SELECT `userid` AS userID,GROUP_CONCAT(`id` SEPARATOR ?) AS mailIDs,GROUP_CONCAT(`blobstorage` SEPARATOR ?) AS blobStorages,SUM(`size`) AS mailSizes,`userid` FROM {pre}mails ' .
                'WHERE ' .
                $cond .
                ' ' .
                'AND `userid`=? ' .
                'GROUP BY `userid`',
            ',',
            ',',
            $row['userID'],
        );
        while ($row2 = $res2->FetchArray(MYSQLI_ASSOC)) {
            ProcessStoreTimeRow($row2);
        }
        $res2->Free();
    }
    $res->Free();
}

/**
 * auto-archive logs
 *
 */
function AutoArchiveLogs() {
    global $bm_prefs, $db;

    if (
        $bm_prefs['logs_autodelete'] == 'yes' &&
        $bm_prefs['logs_autodelete_days'] >= 1 &&
        $bm_prefs['logs_autodelete_last'] < time() - 86400
    ) {
        $db->Query('UPDATE {pre}prefs SET `logs_autodelete_last`=?', time());
        $date = time() - TIME_ONE_DAY * $bm_prefs['logs_autodelete_days'];
        $archiveLogs = $bm_prefs['logs_autodelete_archive'] == 'yes';

        $count = 0;
        if (ArchiveLogs($date, $archiveLogs, $count)) {
            PutLog(
                sprintf('Auto-archived %d log entries', $count),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
        }
    }
}

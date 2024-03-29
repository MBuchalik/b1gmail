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

define('ADMIN_MODE', true);
include '../serverlib/init.inc.php';

// tables
$customTextsHTML = [
    //	'imprint'						=> true
];
$permsTable = [
    'overview' => $lang_admin['overview'],
    'users' => $lang_admin['users'],
    'groups' => $lang_admin['groups'],
    'activity' => $lang_admin['activity'],
    'newsletter' => $lang_admin['newsletter'],
    'optimize' => $lang_admin['optimize'],
    'maintenance' => $lang_admin['maintenance'],
    'stats' => $lang_admin['stats'],
    'logs' => $lang_admin['logs'],
];
$fieldTypeTable = [
    FIELD_CHECKBOX => $lang_admin['checkbox'],
    FIELD_DROPDOWN => $lang_admin['dropdown'],
    FIELD_RADIO => $lang_admin['radio'],
    FIELD_TEXT => $lang_admin['text'],
    FIELD_DATE => $lang_admin['date'],
];
$pluginTypeTable = [
    BMPLUGIN_DEFAULT => $lang_admin['module'],
    BMPLUGIN_FILTER => $lang_admin['filter'],
];
$statusTable = [
    'yes' => $lang_admin['locked'],
    'no' => $lang_admin['active'],
    'locked' => $lang_admin['notactivated'],
    'delete' => $lang_admin['deleted'],
    'registered' => $lang_admin['registered'],
];
$statusImgTable = [
    'yes' => 'locked',
    'no' => 'active',
    'locked' => 'notactivated',
    'delete' => 'deleted',
    'registered' => 'nologin',
];
$aliasTypeTable = [
    ALIAS_RECIPIENT => $lang_admin['receive'],
    ALIAS_SENDER => $lang_admin['send'],
    ALIAS_SENDER | ALIAS_RECIPIENT =>
        $lang_admin['send'] . ', ' . $lang_admin['receive'],
    ALIAS_SENDER | ALIAS_PENDING => $lang_admin['notconfirmed'],
];
$ruleActionTable = [
    RECVRULE_ACTION_ISRECIPIENT => $lang_admin['isrecipient'],
    RECVRULE_ACTION_SETRECIPIENT => $lang_admin['setrecipient'],
    RECVRULE_ACTION_ADDRECIPIENT => $lang_admin['addrecipient'],
    RECVRULE_ACTION_DELETE => $lang_admin['delete'],
    RECVRULE_ACTION_BOUNCE => $lang_admin['bounce'],
    RECVRULE_ACTION_MARKSPAM => $lang_admin['markspam'],
    RECVRULE_ACTION_MARKINFECTED => $lang_admin['markinfected'],
    RECVRULE_ACTION_SETINFECTION => $lang_admin['setinfection'],
    RECVRULE_ACTION_MARKREAD => $lang_admin['markread'],
];
$ruleTypeTable = [
    RECVRULE_TYPE_INACTIVE => $lang_admin['inactive'],
    RECVRULE_TYPE_RECEIVERULE => $lang_admin['receiverule'],
    RECVRULE_TYPE_CUSTOMRULE => $lang_admin['custom'],
];
$faqRequirementTable = [
    'responder' => $lang_admin['autoresponder'],
    'forward' => $lang_admin['forward'],
    'pop3' => $lang_admin['pop3'],
    'imap' => $lang_admin['imap'],
    'wap' => $lang_admin['mobileaccess'],
    'ftsearch' => $lang_admin['ftsearch'],
];
$lockedTypeTable = [
    'start' => $lang_admin['startswith'],
    'mitte' => $lang_admin['contains'],
    'ende' => $lang_admin['endswith'],
    'gleich' => $lang_admin['isequal'],
];

// files and folders that should have write permissions
$writeableFiles = [
    'admin/templates/cache/',
    'logs/',
    'temp/',
    'temp/session/',
    'temp/cache/',
    'templates/' . $bm_prefs['template'] . '/cache/',
];

// htaccess files that should exist
$htaccessFiles = [
    B1GMAIL_DATA_DIR . '.htaccess',
    B1GMAIL_DIR . 'config/.htaccess',
    B1GMAIL_DIR . 'logs/.htaccess',
    B1GMAIL_DIR . 'temp/.htaccess',
    B1GMAIL_DIR . 'interface/.htaccess',

    // This is probably not super important, but just to make sure that clients don't call a file we did not expect them to call, we want this .htaccess file to exist.
    B1GMAIL_DIR . 'serverlib/.htaccess',
];

// The expected file content of the .htaccess files.
$htaccessContent = "<IfModule mod_authz_core.c>
  Require all denied
</IfModule>

<IfModule !mod_authz_core.c>
  Deny from all
</IfModule>";

// Files and folders that should not exist (they are most likely leftovers from previous versions/updates).
$unnecessaryFilesAndFolders = [
    'files' => [
        // These files were removed in b1gmail 8.0.0.
        B1GMAIL_DIR . 'organizer.php',
        B1GMAIL_DIR . 'organizer.notes.php',
        B1GMAIL_DIR . 'organizer.todo.php',
        B1GMAIL_DIR . 'sms.php',

        // These files were removed in b1gmail 9.0.0.
        B1GMAIL_DIR . 'cron.userpop3.php',
        B1GMAIL_DIR . 'organizer.calendar.php',
        B1GMAIL_DIR . 'organizer.notes.php',
        B1GMAIL_DIR . 'start.php',
        B1GMAIL_DIR . 'webdisk.php',
        // This file was automatically created when running cron.userpop3.php
        B1GMAIL_DIR . 'temp/cron.userpop3.lock',
    ],
    'folders' => [
        // The setup folder should always be deleted after running the setup.
        B1GMAIL_DIR . 'setup/',

        // The recommendation is to always rename a folder to "-old" when running the setup.
        B1GMAIL_DIR . 'admin-old/',
        B1GMAIL_DIR . 'interface-old/',
        B1GMAIL_DIR . 'm-old/',
        B1GMAIL_DIR . 'serverlib-old/',
        B1GMAIL_DIR . 'share-old/',
        B1GMAIL_DIR . 'templates-old/',

        // These directories were removed in b1gmail 8.0.0.
        B1GMAIL_DIR . 'clientlib/',
        B1GMAIL_DIR . 'languages/',
        B1GMAIL_DIR . 'plz/',
        B1GMAIL_DIR . 'res/',

        // This directory was removed in b1gmail 9.0.0.
        B1GMAIL_DIR . 'share/',
    ],
];

/**
 * check if admin is allowed to do sth.
 *
 * @param string $priv Privilege name
 * @return bool
 */
function AdminAllowed($priv) {
    global $adminRow;

    return $adminRow['type'] == 0 || isset($adminRow['privileges'][$priv]);
}

/**
 * require privilege
 *
 * @param string $priv
 */
function AdminRequirePrivilege($priv) {
    if (!AdminAllowed($priv)) {
        DisplayError(
            0x02,
            'Unauthorized',
            'You are not authrized to view or change this dataset or page. Possible reasons are too few permissions or an expired session.',
            sprintf("Requested privileges:\n%s", $priv),
            __FILE__,
            __LINE__,
        );
        exit();
    }
}

/**
 * get stat data
 *
 * @param mixed $types Stat type(s)
 * @param int $time Stat time
 * @return array
 */
function GetStatData($types, $time) {
    global $db;

    // types?
    if (!is_array($types)) {
        $types = [$types];
    }
    $typeList = '\'' . implode('\',\'', $types) . '\'';

    // pepare result array
    $result = $falseArray = $nullArray = [];
    foreach ($types as $type) {
        $nullArray[$type] = 0;
    }
    foreach ($types as $type) {
        $falseArray[$type] = false;
    }
    for (
        $i = 1;
        $i <= GetDaysInMonth(date('m', $time), date('Y', $time));
        $i++
    ) {
        $result[(int) $i] =
            mktime(0, 0, 0, date('m', $time), $i, date('Y', $time)) > time()
                ? $falseArray
                : $nullArray;
    }

    // fetch stats from DB
    $res = $db->Query(
        'SELECT typ,d,SUM(anzahl) AS anzahlSum FROM {pre}stats WHERE typ IN (' .
            $typeList .
            ') AND m=? AND y=? GROUP BY d ORDER BY d ASC',
        date('m', $time),
        date('Y', $time),
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $result[(int) $row['d']][$row['typ']] = in_array($row['typ'], [
            'wd_down',
            'wd_up',
        ])
            ? round($row['anzahlSum'] / 1024, 2)
            : $row['anzahlSum'];
    }
    $res->Free();

    return $result;
}

function GetDaysInMonth($month, $year) {
    return date('t', mktime(1, 1, 1, $month, 1, $year));
}

/**
 * get categorized space usage
 *
 * @return array
 */
function GetCategorizedSpaceUsage() {
    global $db, $mysql;

    $sizes = [];

    // data size for mails
    $res = $db->Query('SELECT SUM(size) FROM {pre}mails');
    [$emailSize] = $res->FetchArray(MYSQLI_NUM);
    $res->Free();
    $sizes['mails'] = $emailSize;

    // return
    return $sizes;
}

/**
 * get categorizes space usage
 *
 * @return array
 */
function GetGroupSpaceUsage() {
    global $db, $mysql;

    $sizes = [];

    // get groups
    $res = $db->Query('SELECT id,titel FROM {pre}gruppen ORDER BY titel ASC');
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        // get sizes
        $res2 = $db->Query(
            'SELECT SUM(mailspace_used),COUNT(*) FROM {pre}users WHERE gruppe=?',
            $row['id'],
        );
        [$mailSpace, $userCount] = $res2->FetchArray(MYSQLI_NUM);
        $res2->Free();
        $sizes[$row['id']] = [
            'title' => $row['titel'],
            'users' => $userCount,
            'size' => $mailSpace,
        ];
    }
    $res->Free();

    // return
    return $sizes;
}

/**
 * delete an user and associated data
 *
 * @param int $userID
 */
function DeleteUser($userID, $qAddAND = '') {
    global $db;

    if ($userID <= 0) {
        return false;
    }

    // get mail address
    $res = $db->Query(
        'SELECT email FROM {pre}users WHERE id=?' . $qAddAND,
        $userID,
    );
    if ($res->RowCount() == 0) {
        return false;
    }
    [$userMail] = $res->FetchArray(MYSQLI_NUM);
    $res->Free();

    // module handler
    ModuleFunction('OnDeleteUser', [$userID]);

    // delete blobs
    $blobStorageIDs = [];
    $res = $db->Query(
        'SELECT DISTINCT(`blobstorage`) FROM {pre}mails WHERE userid=?',
        $userID,
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $blobStorageIDs[] = $row['blobstorage'];
    }
    $res->Free();
    $res = $db->Query(
        'SELECT DISTINCT(`blobstorage`) FROM {pre}diskfiles WHERE `user`=?',
        $userID,
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $blobStorageIDs[] = $row['blobstorage'];
    }
    $res->Free();
    foreach (array_unique($blobStorageIDs) as $blobStorageID) {
        BMBlobStorage::createProvider($blobStorageID, $userID)->deleteUser();
    }

    // delete group<->member associations + groups
    $groupIDs = [];
    $res = $db->Query(
        'SELECT id FROM {pre}adressen_gruppen WHERE user=?',
        $userID,
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $groupIDs[] = $row['id'];
    }
    $res->Free();
    if (count($groupIDs) > 0) {
        $db->Query(
            'DELETE FROM {pre}adressen_gruppen_member WHERE gruppe IN(' .
                implode(',', $groupIDs) .
                ')',
        );
        $db->Query('DELETE FROM {pre}adressen_gruppen WHERE user=?', $userID);
    }

    // delete addresses
    $db->Query('DELETE FROM {pre}adressen WHERE user=?', $userID);

    // delete aliases
    $db->Query('DELETE FROM {pre}aliase WHERE user=?', $userID);

    // delete autoresponder
    $db->Query('DELETE FROM {pre}autoresponder WHERE userid=?', $userID);

    // delete cert mails
    $db->Query('DELETE FROM {pre}certmails WHERE user=?', $userID);

    // delete filters
    $filterIDs = [];
    $res = $db->Query('SELECT id FROM {pre}filter WHERE userid=?', $userID);
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $filterIDs[] = $row['id'];
    }
    $res->Free();
    if (count($filterIDs) > 0) {
        $db->Query(
            'DELETE FROM {pre}filter_actions WHERE filter IN(' .
                implode(',', $filterIDs) .
                ')',
        );
        $db->Query(
            'DELETE FROM {pre}filter_conditions WHERE filter IN(' .
                implode(',', $filterIDs) .
                ')',
        );
        $db->Query('DELETE FROM {pre}filter WHERE userid=?', $userID);
    }

    // delete folder conditions + folders
    $folderIDs = [];
    $res = $db->Query('SELECT id FROM {pre}folders WHERE userid=?', $userID);
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $folderIDs[] = $row['id'];
    }
    $res->Free();
    if (count($folderIDs) > 0) {
        $db->Query(
            'DELETE FROM {pre}folder_conditions WHERE folder IN(' .
                implode(',', $folderIDs) .
                ')',
        );
        $db->Query('DELETE FROM {pre}folders WHERE userid=?', $userID);
    }

    // delete mails
    $db->Query(
        'DELETE FROM {pre}mailnotes WHERE `mailid` IN (SELECT `id` FROM {pre}mails WHERE `userid`=?)',
        $userID,
    );
    $db->Query('DELETE FROM {pre}mails WHERE userid=?', $userID);
    $db->Query('DELETE FROM {pre}attachments WHERE userid=?', $userID);

    // sigs
    $db->Query('DELETE FROM {pre}signaturen WHERE user=?', $userID);

    // spam index
    $db->Query('DELETE FROM {pre}spamindex WHERE userid=?', $userID);

    // user prefs
    $db->Query('DELETE FROM {pre}userprefs WHERE userid=?', $userID);

    // search index
    $indexFileName = DataFilename($userID, 'idx', true);
    if (file_exists($indexFileName)) {
        @unlink($indexFileName);
    }

    // finally, the user record itself
    $db->Query('DELETE FROM {pre}users WHERE id=?', $userID);

    // log
    PutLog(
        sprintf('User <%s> (%d) deleted', $userMail, $userID),
        PRIO_NOTE,
        __FILE__,
        __LINE__,
    );

    return true;
}

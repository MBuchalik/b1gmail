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

include '../serverlib/admin.inc.php';
include '../serverlib/zip.class.php';
RequestPrivileges(PRIVILEGES_ADMIN);
AdminRequirePrivilege('prefs.email');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'common';
}

$tabs = [
    0 => [
        'title' => $lang_admin['common'],
        'relIcon' => 'ico_prefs_common.png',
        'link' => 'prefs.email.php?',
        'active' => $_REQUEST['action'] == 'common',
    ],
    1 => [
        'title' => $lang_admin['receive'],
        'relIcon' => 'ico_prefs_receiving.png',
        'link' => 'prefs.email.php?action=receive&',
        'active' => $_REQUEST['action'] == 'receive',
    ],
    2 => [
        'title' => $lang_admin['send'],
        'relIcon' => 'ico_prefs_sending.png',
        'link' => 'prefs.email.php?action=send&',
        'active' => $_REQUEST['action'] == 'send',
    ],
    3 => [
        'title' => $lang_admin['antispam'],
        'relIcon' => 'antispam.png',
        'link' => 'prefs.email.php?action=antispam&',
        'active' => $_REQUEST['action'] == 'antispam',
    ],
    4 => [
        'title' => $lang_admin['antivirus'],
        'relIcon' => 'antivirus.png',
        'link' => 'prefs.email.php?action=antivirus&',
        'active' => $_REQUEST['action'] == 'antivirus',
    ],
];

/**
 * common
 */
if ($_REQUEST['action'] == 'common') {
    if (isset($_REQUEST['save'])) {
        $db->Query(
            'UPDATE {pre}prefs SET blobstorage_provider=?, blobstorage_compress=?, fts_bg_indexing=?',
            $_REQUEST['blobstorage_provider'],
            isset($_REQUEST['blobstorage_compress']) ? 'yes' : 'no',
            isset($_REQUEST['fts_bg_indexing']) ? 'yes' : 'no',
        );
        ReadConfig();
    }

    // assign
    $tpl->assign(
        'bsUserDBAvailable',
        BMBlobStorage::createProvider(BMBLOBSTORAGE_USERDB)->isAvailable(),
    );
    $tpl->assign('page', 'prefs.email.common.tpl');
} /**
 * receive
 */ elseif ($_REQUEST['action'] == 'receive') {
    if (isset($_REQUEST['save'])) {
        $db->Query(
            'UPDATE {pre}prefs SET receive_method=?,pop3_host=?,pop3_port=?,pop3_user=?,pop3_pass=?,fetchcount=?,errormail=?,failure_forward=?,mailmax=?,recipient_detection=?,detect_duplicates=?,returnpath_check=?',
            $_REQUEST['receive_method'],
            $_REQUEST['pop3_host'],
            (int) $_REQUEST['pop3_port'],
            $_REQUEST['pop3_user'],
            $_REQUEST['pop3_pass'],
            (int) $_REQUEST['fetchcount'],
            $_REQUEST['errormail'],
            isset($_REQUEST['failure_forward']) ? 'yes' : 'no',
            (int) $_REQUEST['mailmax'] * 1024,
            $_REQUEST['recipient_detection'],
            isset($_REQUEST['detect_duplicates']) ? 'yes' : 'no',
            isset($_REQUEST['returnpath_check']) ? 'yes' : 'no',
        );
        ReadConfig();
    }

    // assign
    $tpl->assign('page', 'prefs.email.receive.tpl');
} /**
 * send
 */ elseif ($_REQUEST['action'] == 'send') {
    if (isset($_REQUEST['save'])) {
        $blockedArray = explode("\n", $_REQUEST['blocked']);
        foreach ($blockedArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $blockedArray[$key] = trim($val);
            } else {
                unset($blockedArray[$key]);
            }
        }
        $blocked = implode(':', $blockedArray);

        $db->Query(
            'UPDATE {pre}prefs SET send_method=?,smtp_host=?,smtp_port=?,smtp_auth=?,smtp_user=?,smtp_pass=?,blocked=?,sendmail_path=?,passmail_abs=?,einsch_life=?,write_xsenderip=?,min_draft_save_interval=?',
            $_REQUEST['send_method'],
            $_REQUEST['smtp_host'],
            (int) $_REQUEST['smtp_port'],
            isset($_REQUEST['smtp_auth']) ? 'yes' : 'no',
            $_REQUEST['smtp_user'],
            $_REQUEST['smtp_pass'],
            $blocked,
            $_REQUEST['sendmail_path'],
            EncodeEMail($_REQUEST['passmail_abs']),
            $_REQUEST['einsch_life'] * TIME_ONE_DAY,
            isset($_REQUEST['write_xsenderip']) ? 'yes' : 'no',
            max(5, $_REQUEST['min_draft_save_interval']),
        );
        ReadConfig();
    }

    // assign
    $bm_prefs['blocked'] = str_replace(':', "\n", $bm_prefs['blocked']);
    $tpl->assign('page', 'prefs.email.send.tpl');
} /**
 * antispam
 */ elseif ($_REQUEST['action'] == 'antispam') {
    if (isset($_REQUEST['save'])) {
        $dnsblArray = explode("\n", $_REQUEST['dnsbl']);
        foreach ($dnsblArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $dnsblArray[$key] = $val;
            } else {
                unset($dnsblArray[$key]);
            }
        }
        $dnsbl = implode(':', $dnsblArray);

        $db->Query(
            'UPDATE {pre}prefs SET spamcheck=?,dnsbl=?,use_bayes=?,bayes_mode=?,dnsbl_requiredservers=?',
            isset($_REQUEST['spamcheck']) ? 'yes' : 'no',
            $dnsbl,
            isset($_REQUEST['use_bayes']) ? 'yes' : 'no',
            $_REQUEST['bayes_mode'],
            $_REQUEST['dnsbl_requiredservers'],
        );
        ReadConfig();
    }

    if (isset($_REQUEST['resetBayesDB'])) {
        $db->Query('TRUNCATE TABLE {pre}spamindex');
        $db->Query('UPDATE {pre}prefs SET bayes_spam=0, bayes_nonspam=0');
        $db->Query('UPDATE {pre}users SET bayes_spam=0, bayes_nonspam=0');
    }

    // bayes resetable?
    $res = $db->Query('SELECT COUNT(*) FROM {pre}spamindex');
    [$bayesWordCount] = $res->FetchArray(MYSQLI_NUM);
    $res->Free();

    // assign
    $bm_prefs['dnsbl'] = str_replace(':', "\n", $bm_prefs['dnsbl']);
    $tpl->assign('bayesWordCount', $bayesWordCount);
    $tpl->assign('page', 'prefs.email.antispam.tpl');
} /**
 * antivirus
 */ elseif ($_REQUEST['action'] == 'antivirus') {
    if (isset($_REQUEST['save'])) {
        $db->Query(
            'UPDATE {pre}prefs SET use_clamd=?,clamd_host=?,clamd_port=?',
            isset($_REQUEST['use_clamd']) ? 'yes' : 'no',
            $_REQUEST['clamd_host'],
            (int) $_REQUEST['clamd_port'],
        );
        ReadConfig();
    }

    // assign
    $tpl->assign('page', 'prefs.email.antivirus.tpl');
}

$tpl->assign('bm_prefs', $bm_prefs);
$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['prefs'] . ' &raquo; ' . $lang_admin['email'],
);
$tpl->display('page.tpl');

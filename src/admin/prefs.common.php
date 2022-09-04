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
RequestPrivileges(PRIVILEGES_ADMIN);
AdminRequirePrivilege('prefs.common');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'common';
}

$tabs = [
    [
        'title' => $lang_admin['common'],
        'relIcon' => 'ico_prefs_common.png',
        'link' => 'prefs.common.php?',
        'active' => $_REQUEST['action'] == 'common',
    ],
    [
        'title' => $lang_admin['domains'],
        'relIcon' => 'domain32.png',
        'link' => 'prefs.common.php?action=domains&',
        'active' => $_REQUEST['action'] == 'domains',
    ],
    [
        'title' => $lang_admin['caching'],
        'relIcon' => 'cache.png',
        'link' => 'prefs.common.php?action=caching&',
        'active' => $_REQUEST['action'] == 'caching',
    ],
    [
        'title' => $lang_admin['safecode'],
        'relIcon' => 'captcha32.png',
        'link' => 'prefs.common.php?action=captcha&',
        'active' => $_REQUEST['action'] == 'captcha',
    ],
    [
        'title' => $lang_admin['account'],
        'relIcon' => 'ico_prefs_signup.png',
        'link' => 'prefs.common.php?action=account&',
        'active' => $_REQUEST['action'] == 'account',
    ],
    [
        'title' => $lang_admin['lockedusernames'],
        'relIcon' => 'lockedusername32.png',
        'link' => 'prefs.common.php?action=lockedusernames&',
        'active' => $_REQUEST['action'] == 'lockedusernames',
    ],
];

/**
 * common
 */
if ($_REQUEST['action'] == 'common') {
    if (
        isset($_REQUEST['save']) &&
        isset($_POST['titel']) &&
        isset($_POST['language'])
    ) {
        // trailing slashes
        if (substr($_POST['selfurl'], -1) != '/') {
            $_POST['selfurl'] .= '/';
        }
        if (substr($_POST['mobile_url'], -1) != '/') {
            $_POST['mobile_url'] .= '/';
        }
        if (substr($_POST['selffolder'], -1) != '/') {
            $_POST['selffolder'] .= '/';
        }
        if (substr($_POST['datafolder'], -1) != '/') {
            $_POST['datafolder'] .= '/';
        }
        if (
            trim($_POST['ssl_url']) != '' &&
            substr($_POST['ssl_url'], -1) != '/'
        ) {
            $_POST['ssl_url'] .= '/';
        }

        if ($_POST['language'] != $bm_prefs['language']) {
            setcookie(
                'bm_language',
                $_POST['language'],
                time() + TIME_ONE_YEAR,
            );
        }

        $db->Query(
            'UPDATE {pre}prefs SET titel=?, b1gmta_host=?, selffolder=?, selfurl=?, mobile_url=?, search_engine=?, datafolder=?, language=?, std_land=?, datumsformat=?, ordner_proseite=?,  autocancel=?, wartung=?, structstorage=?, cron_interval=?, logouturl=?, contact_history=?, ip_lock=?, cookie_lock=?, domain_combobox=?, ssl_url=?, ssl_login_option=?, ssl_login_enable=?, ssl_signup_enable=?, auto_tz=?, compress_pages=?, redirect_mobile=?, calendar_defaultviewmode=?, ' .
                'logs_autodelete=?, logs_autodelete_days=?, logs_autodelete_archive=?, hotkeys_default=?, contactform=?, contactform_to=?, contactform_name=?, notify_interval=?, notify_lifetime=?, mail_groupmode=?',
            $_POST['titel'],
            $_POST['b1gmta_host'],
            $_POST['selffolder'],
            $_POST['selfurl'],
            $_POST['mobile_url'],
            $_POST['search_engine'],
            $_POST['datafolder'],
            $_POST['language'],
            $_POST['std_land'],
            $_POST['datumsformat'],
            $_POST['ordner_proseite'],
            isset($_POST['autocancel']) ? 'yes' : 'no',
            isset($_POST['wartung']) ? 'yes' : 'no',
            isset($_POST['structstorage']) ? 'yes' : 'no',
            $_POST['cron_interval'],
            $_POST['logouturl'],
            isset($_POST['contact_history']) ? 'yes' : 'no',
            isset($_POST['ip_lock']) ? 'yes' : 'no',
            isset($_POST['cookie_lock']) ? 'yes' : 'no',
            isset($_POST['domain_combobox']) ? 'yes' : 'no',
            $_POST['ssl_url'],
            isset($_POST['ssl_login_option']) ? 'yes' : 'no',
            isset($_POST['ssl_login_enable']) ? 'yes' : 'no',
            isset($_POST['ssl_signup_enable']) ? 'yes' : 'no',
            isset($_POST['auto_tz']) ? 'yes' : 'no',
            isset($_POST['compress_pages']) ? 'yes' : 'no',
            isset($_POST['redirect_mobile']) ? 'yes' : 'no',
            $_REQUEST['calendar_defaultviewmode'],
            isset($_POST['logs_autodelete']) ? 'yes' : 'no',
            max(1, (int) $_POST['logs_autodelete_days']),
            isset($_POST['logs_autodelete_archive']) ? 'yes' : 'no',
            isset($_POST['hotkeys_default']) ? 'yes' : 'no',
            isset($_POST['contactform']) ? 'yes' : 'no',
            EncodeEMail($_POST['contactform_to']),
            isset($_POST['contactform_name']) ? 'yes' : 'no',
            max(1, $_REQUEST['notify_interval']),
            max(1, $_REQUEST['notify_lifetime']),
            $_POST['mail_groupmode'],
        );
        ReadConfig();

        $_SESSION['bm_sessionToken'] = SessionToken();
    }

    // get available languages
    $languages = GetAvailableLanguages();
    $countryList = CountryList();
    asort($countryList);

    // assign
    $tpl->assign('safemode', ini_get('safe_mode'));
    $tpl->assign('languages', $languages);
    $tpl->assign('countries', $countryList);
    $tpl->assign('page', 'prefs.common.tpl');
} /**
 * caching
 */ elseif ($_REQUEST['action'] == 'caching') {
    if (isset($_REQUEST['save'])) {
        $serversArray = explode("\n", $_REQUEST['memcache_servers']);
        foreach ($serversArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $serversArray[$key] = $val;
            } else {
                unset($serversArray[$key]);
            }
        }
        $servers = implode(';', $serversArray);

        $db->Query(
            'UPDATE {pre}prefs SET cache_type=?, filecache_size=?, memcache_servers=?, memcache_persistent=?, cache_parseonly=?',
            (int) $_REQUEST['cache_type'],
            $_REQUEST['filecache_size'] * 1024 * 1024,
            $servers,
            isset($_REQUEST['memcache_persistent']) ? 'yes' : 'no',
            isset($_REQUEST['cache_parseonly']) ? 'yes' : 'no',
        );
        ReadConfig();
    }

    // assign
    $bm_prefs['memcache_servers'] = str_replace(
        ';',
        "\n",
        $bm_prefs['memcache_servers'],
    );
    $tpl->assign(
        'memcache',
        class_exists('Memcache') || class_exists('Memcached'),
    );
    $tpl->assign('page', 'prefs.caching.tpl');
} /**
 * captcha
 */ elseif ($_REQUEST['action'] == 'captcha') {
    if (!class_exists('BMCaptcha')) {
        include B1GMAIL_DIR . 'serverlib/captcha.class.php';
    }

    $providers = BMCaptcha::getAvailableProviders();

    if (isset($_REQUEST['save']) && isset($_POST['captcha_provider'])) {
        $postPrefs =
            isset($_POST['prefs']) && is_array($_POST['prefs'])
                ? $_POST['prefs']
                : [];
        $config = [];

        foreach ($providers as $provKey => $prov) {
            $provPrefs = [];

            foreach ($prov['configFields'] as $fieldKey => $val) {
                switch ($val['type']) {
                    case FIELD_CHECKBOX:
                        $value = isset($postPrefs[$provKey][$fieldKey]) ? 1 : 0;
                        break;

                    default:
                        $value = $postPrefs[$provKey][$fieldKey];
                        break;
                }

                $provPrefs[$fieldKey] = $value;
            }

            if (count($provPrefs) > 0) {
                $config[$provKey] = $provPrefs;
            }
        }

        $db->Query(
            'UPDATE {pre}prefs SET `captcha_provider`=?,`captcha_config`=?',
            $_POST['captcha_provider'],
            serialize($config),
        );
        ReadConfig();
    }

    $config = @unserialize($bm_prefs['captcha_config']);
    if (!is_array($config)) {
        $config = [];
    }

    foreach ($providers as $provKey => $prov) {
        foreach ($prov['configFields'] as $fieldKey => $val) {
            if (isset($config[$provKey][$fieldKey])) {
                $providers[$provKey]['configFields'][$fieldKey]['value'] =
                    $config[$provKey][$fieldKey];
            } else {
                $providers[$provKey]['configFields'][$fieldKey]['value'] =
                    $val['default'];
            }
        }
    }

    $tpl->assign('defaultProvider', $bm_prefs['captcha_provider']);
    $tpl->assign('providers', $providers);
    $tpl->assign('page', 'prefs.captcha.tpl');
} elseif ($_REQUEST['action'] == 'account') {
    if (isset($_REQUEST['save'])) {
        $lamArray = explode("\n", $_POST['locked_altmail']);
        foreach ($lamArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $lamArray[$key] = $val;
            } else {
                unset($lamArray[$key]);
            }
        }
        $lockedAltMail = implode(':', $lamArray);

        $db->Query(
            'UPDATE {pre}prefs SET std_gruppe=?, minuserlength=?, min_pass_length=?, welcome_mail=?, f_strasse=?, f_telefon=?, f_fax=?, f_alternativ=?, f_safecode=?, alt_check=?, check_double_altmail=?, check_double_cellphone=?, f_anrede=?, locked_altmail=?, `nosignup_autodel`=?, `nosignup_autodel_days`=?',
            $_REQUEST['std_gruppe'],
            max(1, $_REQUEST['minuserlength']),
            max(1, $_REQUEST['min_pass_length']),
            isset($_REQUEST['welcome_mail']) ? 'yes' : 'no',
            $_REQUEST['f_strasse'],
            $_REQUEST['f_telefon'],
            $_REQUEST['f_fax'],
            $_REQUEST['f_alternativ'],
            $_REQUEST['f_safecode'],
            isset($_REQUEST['alt_check']) ? 'yes' : 'no',
            isset($_REQUEST['check_double_altmail']) ? 'yes' : 'no',
            isset($_REQUEST['check_double_cellphone']) ? 'yes' : 'no',
            $_REQUEST['f_anrede'],
            $lockedAltMail,
            isset($_REQUEST['nosignup_autodel']) ? 'yes' : 'no',
            max(1, $_REQUEST['nosignup_autodel_days']),
        );
        ReadConfig();
    }

    // assign
    $bm_prefs['locked_altmail'] = str_replace(
        ':',
        "\n",
        $bm_prefs['locked_altmail'],
    );
    $tpl->assign('groups', BMGroup::GetSimpleGroupList());
    $tpl->assign('page', 'prefs.account.tpl');
} /**
 * locked usernames
 */ elseif ($_REQUEST['action'] == 'lockedusernames') {
    // delete?
    if (isset($_REQUEST['delete'])) {
        $db->Query(
            'DELETE FROM {pre}locked WHERE id=?',
            (int) $_REQUEST['delete'],
        );
    }

    // add?
    elseif (isset($_REQUEST['add']) && trim($_REQUEST['benutzername']) != '') {
        $db->Query(
            'INSERT INTO {pre}locked(typ,benutzername) VALUES(?,?)',
            $_REQUEST['typ'],
            $_REQUEST['benutzername'],
        );
    }

    // mass action?
    elseif (isset($_REQUEST['executeMassAction'])) {
        // get locked username IDs
        $lockedIDs = [];
        foreach ($_POST as $key => $val) {
            if (substr($key, 0, 7) == 'locked_') {
                $lockedIDs[] = (int) substr($key, 7);
            }
        }

        if (count($lockedIDs) > 0) {
            if ($_REQUEST['massAction'] == 'delete') {
                // delete row
                $db->Query(
                    'DELETE FROM {pre}locked WHERE id IN(' .
                        implode(',', $lockedIDs) .
                        ')',
                );
            }
        }
    }

    // fetch
    $lockedUsernames = [];
    $res = $db->Query(
        'SELECT id,typ,benutzername FROM {pre}locked ORDER BY typ,benutzername ASC',
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $lockedUsernames[$row['id']] = [
            'id' => $row['id'],
            'type' => $lockedTypeTable[$row['typ']],
            'username' => $row['benutzername'],
        ];
    }
    $res->Free();

    // assign
    $tpl->assign('lockedUsernames', $lockedUsernames);
    $tpl->assign('lockedTypeTable', $lockedTypeTable);
    $tpl->assign('page', 'prefs.lockedusernames.tpl');
} /**
 * domains
 */ elseif ($_REQUEST['action'] == 'domains') {
    // mass save?
    if (isset($_POST['domains']) && is_array($_POST['domains'])) {
        foreach ($_POST['domains'] as $domain => $info) {
            $db->Query(
                'UPDATE {pre}domains SET `in_login`=?,`in_signup`=?,`in_aliases`=?,`pos`=? WHERE `domain`=?',
                isset($info['in_login']) ? 1 : 0,
                isset($info['in_signup']) ? 1 : 0,
                isset($info['in_aliases']) ? 1 : 0,
                (int) $info['pos'],
                $domain,
            );
        }
    }

    // delete?
    if (isset($_REQUEST['delete'])) {
        $db->Query(
            'DELETE FROM {pre}domains WHERE `domain`=?',
            $_REQUEST['delete'],
        );
    }

    // add?
    elseif (isset($_REQUEST['add']) && trim($_REQUEST['domain']) != '') {
        $db->Query(
            'REPLACE INTO {pre}domains(`domain`,`in_login`,`in_signup`,`in_aliases`,`pos`) VALUES(?,?,?,?,?)',
            EncodeDomain(trim($_REQUEST['domain'])),
            isset($_REQUEST['in_login']) ? 1 : 0,
            isset($_REQUEST['in_signup']) ? 1 : 0,
            isset($_REQUEST['in_aliases']) ? 1 : 0,
            (int) $_REQUEST['pos'],
        );
    }

    // mass action?
    elseif (
        isset($_REQUEST['executeMassAction']) &&
        isset($_POST['domains']) &&
        is_array($_POST['domains'])
    ) {
        // get domains
        $domains = [];
        foreach ($_POST['domains'] as $domain => $prefs) {
            if (isset($prefs['del'])) {
                $domains[] = $domain;
            }
        }

        if (count($domains) > 0) {
            if ($_REQUEST['massAction'] == 'delete') {
                // delete domain
                $db->Query(
                    'DELETE FROM {pre}domains WHERE `domain` IN ?',
                    $domains,
                );
            }
        }
    }

    // fetch
    $domains = [];
    $lockedUsernames = [];
    $res = $db->Query(
        'SELECT `domain`,`in_login`,`in_signup`,`in_aliases`,`pos` FROM {pre}domains ORDER BY `pos` ASC, `domain` ASC',
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $row['urlDomain'] = urlencode($row['domain']);
        $domains[$row['domain']] = $row;
    }
    $res->Free();

    // assign
    $tpl->assign('domains', $domains);
    $tpl->assign('page', 'prefs.domains.tpl');
}

$tpl->assign('bm_prefs', $bm_prefs);
$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['prefs'] . ' &raquo; ' . $lang_admin['common'],
);
$tpl->display('page.tpl');
?>

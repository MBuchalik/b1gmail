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

error_reporting(0);

define(
    'B1GMAIL_DIR',
    substr(
        dirname(__FILE__),
        0,
        -strlen(strrchr(str_replace('\\', '/', dirname(__FILE__)), '/')),
    ) . '/',
);
define('B1GMAIL_REL', file_exists('admin/') ? './' : '../');
define('B1GMAIL_INIT', true);

include B1GMAIL_DIR . 'config/config.php';

if (!defined('DEBUG')) {
    define('DEBUG', false);
}

if (DEBUG) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

assert_options(ASSERT_WARNING, DEBUG ? 1 : 0);

if (!defined('DEV_MOCK_MAIL_SENDING')) {
    // A setting used for development purposes that replaces actual email submission by a mock.
    define('DEV_MOCK_MAIL_SENDING', false);
}

/**
 * initialize php config
 */
@ini_set('session.referer_check', '');
@ini_set('session.use_cookies', '0');
@ini_set('session.use_only_cookies', '0');
@ini_set('session.use_trans_sid', '0');
@ini_set('session.gc_probability', '1');
@ini_set('session.gc_divisor', '100');
@ini_set('session.gc_maxlifetime', '86400');
session_name('sid');

/**
 * constants
 */
if (!defined('ADMIN_MODE')) {
    define('ADMIN_MODE', false);
}
if (!defined('INTERFACE_MODE')) {
    define('INTERFACE_MODE', false);
}
define('SERVER_WINDOWS', strtolower(substr(PHP_OS, 0, 3)) == 'win');
define(
    'SERVER_IIS',
    SERVER_WINDOWS &&
        isset($_SERVER['SERVER_SOFTWARE']) &&
        strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false,
);
define('SIGNATURE_OFFICIAL', 1); // signature types
define('SIGNATURE_VERIFIED', 2);
define('SIGNATURE_UNKNOWN', 3);
define('SIGNATURE_MALICIOUS', 4);
define('SOCKET_TIMEOUT', 30); // socket timeout
define('CACHE_DISABLE', 0); // cache types
define('CACHE_B1GMAIL', 1);
define('CACHE_MEMCACHE', 2);
define('PRIO_WARNING', 1); // log priorities
define('PRIO_NOTE', 2);
define('PRIO_ERROR', 4);
define('PRIO_DEBUG', 8);
define('PRIO_PLUGIN', 16);
define('FIELD_TEXT', 1); // ui field types
define('FIELD_CHECKBOX', 2);
define('FIELD_DROPDOWN', 4);
define('FIELD_RADIO', 8);
define('FIELD_TEXTAREA', 16);
define('FIELD_DATE', 32);
define('FIELD_IMAGE', 64);
define('USERID_UNKNOWN', 0); // special user IDs
define('USERID_SYSTEM', -1);
define('USERID_ADMIN', -2);
define('USER_OK', 0); // okay!
define('USER_DOES_NOT_EXIST', 1); // user does not exist
define('USER_BAD_PASSWORD', 2); // bad password
define('USER_LOGIN_BLOCK', 3); // account locked temporarily (bruteforce protection)
define('USER_LOCKED', 4); // locked acocunt
define('TIME_ONE_MINUTE', 60); // time constants
define('TIME_ONE_HOUR', 60 * TIME_ONE_MINUTE);
define('TIME_ONE_DAY', 24 * TIME_ONE_HOUR);
define('TIME_ONE_WEEK', 7 * TIME_ONE_DAY);
define('TIME_ONE_MONTH', 31 * TIME_ONE_DAY);
define('TIME_ONE_YEAR', 365 * TIME_ONE_DAY);
define('ACCOUNT_LOCK_TIME', 5 * TIME_ONE_MINUTE);
define('PRIVILEGES_USER', 1); // privileges
define('PRIVILEGES_ADMIN', 2);
define('PRIVILEGES_MOBILE', 4);
define('FOLDER_INBOX', 0); // special folders
define('FOLDER_OUTBOX', -2);
define('FOLDER_DRAFTS', -3);
define('FOLDER_SPAM', -4);
define('FOLDER_TRASH', -5);
define('FOLDER_ROOT', -128);
define('BMOP_EQUAL', 1); // filter ops
define('BMOP_NOTEQUAL', 2);
define('BMOP_CONTAINS', 3);
define('BMOP_NOTCONTAINS', 4);
define('BMOP_STARTSWITH', 5);
define('BMOP_ENDSWITH', 6);
define('MAILFIELD_SUBJECT', 1); // mail fields
define('MAILFIELD_FROM', 2);
define('MAILFIELD_TO', 3);
define('MAILFIELD_CC', 4);
define('MAILFIELD_BCC', 5);
define('MAILFIELD_READ', 6);
define('MAILFIELD_ANSWERED', 7);
define('MAILFIELD_FORWARDED', 8);
define('MAILFIELD_PRIORITY', 9);
define('MAILFIELD_ATTACHMENT', 10);
define('MAILFIELD_FLAGGED', 11);
define('MAILFIELD_FOLDER', 12);
define('MAILFIELD_ATTACHLIST', 13);
define('MAILFIELD_COLOR', 14);
define('MAILFIELD_DONE', 15);
define('FILTER_ACTION_MOVETO', 1); // filter actions
define('FILTER_ACTION_BLOCK', 2);
define('FILTER_ACTION_DELETE', 3);
define('FILTER_ACTION_MARKREAD', 4);
define('FILTER_ACTION_MARKSPAM', 5);
define('FILTER_ACTION_MARK', 6);
define('FILTER_ACTION_STOP', 7);
define('FILTER_ACTION_RESPOND', 9);
define('FILTER_ACTION_FORWARD', 10);
define('FILTER_ACTION_SETCOLOR', 11);
define('FILTER_ACTION_MARKDONE', 12);
define('FILTER_ACTIONFLAG_DO_NOT_OVERRIDE_SPAMFILTER', 2); // filter action flags
define('FILTER_ACTIONFLAG_RESPOND', 4);
define('FILTER_ACTIONFLAG_FORWARD', 8);
define('FILTER_ACTIONFLAG_NOTIFY', 16);
define('BMLINK_AND', 1); // logic link (and/or)
define('BMLINK_OR', 2);
define('FLAG_UNREAD', 1); // message flags
define('FLAG_ANSWERED', 2);
define('FLAG_FORWARDED', 4);
define('FLAG_DELETED', 8);
define('FLAG_FLAGGED', 16);
define('FLAG_SEEN', 32);
define('FLAG_ATTACHMENT', 64);
define('FLAG_INFECTED', 128);
define('FLAG_SPAM', 256);
define('FLAG_CERTMAIL', 512);
define('FLAG_DRAFT', 1024);
define('FLAG_DECEPTIVE', 2048);
define('FLAG_DONE', 4096);
define('FLAG_SHOWEXTERNAL', 8192);
define('FLAG_DNSENT', 16384);
define('FLAG_INDEXED', 32768);
define('FLAG_AUTOSAVEDDRAFT', 65536);
define('FLAG_NODRAFTNOTIFY', 131072);
define('STORE_FILE', 1); // storage methods
define('STORE_DB', 2);
define('PART_TYPE_TEXT', 0); // mail part types
define('PART_TYPE_ATTACHMENT', 1);
define('PART_CHUNK_SIZE', 4096); // must be a multiple of 4
define('ITEMPRIO_LOW', -1); // item priorities
define('ITEMPRIO_NORMAL', 0);
define('ITEMPRIO_HIGH', 1);
define('ALIAS_SENDER', 1); // alias types
define('ALIAS_RECIPIENT', 2);
define('ALIAS_PENDING', 4);
define('STORE_RESULT_OK', 0); // store results
define('STORE_RESULT_INTERNALERROR', 1);
define('STORE_RESULT_NOTENOUGHSPACE', 2);
define('STORE_RESULT_MAILTOOBIG', 3);
define('RECEIVE_RESULT_NO_RECIPIENTS', 4);
define('RECEIVE_RESULT_BLOCKED', 5);
define('RECEIVE_RESULT_DELETE', 6);
define('RECVRULE_ACTION_ISRECIPIENT', 0); // receive rule actions
define('RECVRULE_ACTION_SETRECIPIENT', 1);
define('RECVRULE_ACTION_ADDRECIPIENT', 2);
define('RECVRULE_ACTION_DELETE', 3);
define('RECVRULE_ACTION_BOUNCE', 4);
define('RECVRULE_ACTION_MARKSPAM', 5);
define('RECVRULE_ACTION_MARKINFECTED', 6);
define('RECVRULE_ACTION_SETINFECTION', 7);
define('RECVRULE_ACTION_MARKREAD', 8);
define('RECVRULE_TYPE_INACTIVE', 0); // receive rule types
define('RECVRULE_TYPE_RECEIVERULE', 1);
define('RECVRULE_TYPE_CUSTOMRULE', 2);
define('PASSWORD_LENGTH', 8); // password generation
define(
    'PASSWORD_CHARS',
    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.,_-&$',
);
define('VKCODE_LENGTH', 8); // vk code generation
define('VKCODE_CHARS', 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789');
define('MAX_SEARCH_RESULTS', 20); // max no of search results
define('SIGNATURE_LINE_LENGTH', 54);
define('SIGNATURE_LINE_CHAR', '_');
define('MAIL_DB_MAX', 200000);
define('ATT_FLAG_VIEWABLE', 1);
define('ORDER_STATUS_CREATED', 0);
define('ORDER_STATUS_ACTIVATED', 1);
define('STORETIME_CRON_INTERVAL', TIME_ONE_HOUR);
define(
    'FTS_SUPPORT',
    class_exists('SQLite3') ||
        (class_exists('PDO') && in_array('sqlite', PDO::getAvailableDrivers())),
);
define(
    'IDN_SUPPORT',
    function_exists('idn_to_ascii') && function_exists('idn_to_utf8'),
);
define('FALLBACK_CHARSET', 'ISO-8859-15');
define('BMCL_TYPE_CONTACT', 0); // changelog/sync item types
define('BMCL_TYPE_CONTACTGROUP', 3);
define('FTS_BGINDEX_COUNT', 10); // no of mails to index in each fts bg index step
define('ATACTION_SENDMAIL', 1); // action token action IDs
define('TRANSACTION_CREATED', 0); // transaction status values
define('TRANSACTION_BOOKED', 1);
define('TRANSACTION_CANCELLED', 2);
define('BMSFLAG_IS_SPAM', 1);
define('BMSFLAG_IS_INFECTED', 2);
$VIEWABLE_TYPES = [
    'text/html',
    'image/jpeg',
    'image/gif',
    'image/png',
    'image/jpg',
    'image/pjpeg',
    'application/pdf',
    'text/plain',
];

/**
 * plugin return constants
 */
define('BM_OK', 64);
define('BM_BAD', 128);
define('BM_BLOCK', 256);
define('BM_DELETE', 512);
define('BM_LOCKED', 1024);
define('BM_WRONGLOGIN', 2048);
define('BM_IS_INFECTED', 4096);
define('BM_IS_SPAM', 8192);
define('BM_UPDATE_UNKNOWN', 0);
define('BM_UPDATE_NOT_AVAILABLE', 1);
define('BM_UPDATE_AVAILABLE', 2);

/**
 * required files
 */
include B1GMAIL_DIR . 'serverlib/version.inc.php';
include B1GMAIL_DIR . 'serverlib/common.inc.php';
include B1GMAIL_DIR . 'serverlib/string.inc.php';
include B1GMAIL_DIR . 'serverlib/cache.class.php';
include B1GMAIL_DIR . 'serverlib/db.class.php';
include B1GMAIL_DIR . 'serverlib/blobstorage.class.php';
include B1GMAIL_DIR . 'serverlib/template.class.php';
include B1GMAIL_DIR . 'serverlib/user.class.php';
include B1GMAIL_DIR . 'serverlib/group.class.php';
include B1GMAIL_DIR . 'serverlib/plugin.class.php';
if (
    !class_exists('SQLite3') &&
    class_exists('PDO') &&
    in_array('sqlite', PDO::getAvailableDrivers())
) {
    include B1GMAIL_DIR . 'serverlib/sqlite3.class.php';
}

/**
 * try to determine request_uri if not set or server is IIS
 */
if (!isset($_SERVER['REQUEST_URI']) || SERVER_IIS) {
    // file
    if (isset($_SERVER['URL'])) {
        $requestURI = $_SERVER['URL'];
    } elseif (isset($_SERVER['SCRIPT_NAME'])) {
        $requestURI = $_SERVER['SCRIPT_NAME'];
    } elseif (isset($_SERVER['PHP_SELF'])) {
        $requestURI = $_SERVER['PHP_SELF'];
    }

    if (strpos($requestURI, '?') === false) {
        // query string
        if (isset($_SERVER['QUERY_STRING'])) {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestURI .= '?' . $_SERVER['QUERY_STRING'];
            }
        } elseif (
            isset($_SERVER['argv']) &&
            is_array($_SERVER['argv']) &&
            count($_SERVER['argv']) == 1
        ) {
            if (!empty($_SERVER['argv'][0])) {
                $requestURI .= '?' . $_SERVER['argv'][0];
            }
        }
    }

    $_SERVER['REQUEST_URI'] = $requestURI;
}

/**
 * initialize b1gMail
 */
if (session_module_name() == 'files') {
    @session_save_path(B1GMAIL_DIR . 'temp/session/');
}
register_shutdown_function('b1gMailShutdown');
PrepareInputVars();
ConnectDB();
ReadConfig();

/**
 * maintenance mode?
 */
define(
    'MAINTENANCE_MODE',
    $bm_prefs['wartung'] == 'yes' &&
        strpos($_SERVER['PHP_SELF'], 'clientlang.php') === false &&
        !ADMIN_MODE,
);

/**
 * data dir
 */
define('B1GMAIL_DATA_DIR', $bm_prefs['datafolder']);

/**
 * cache
 */
if (MAINTENANCE_MODE || $bm_prefs['cache_type'] == CACHE_DISABLE) {
    $cacheManager = new BMCache_None();
} elseif ($bm_prefs['cache_type'] == CACHE_B1GMAIL) {
    $cacheManager = new BMCache_b1gMail();
} elseif ($bm_prefs['cache_type'] == CACHE_MEMCACHE) {
    $cacheManager = new BMCache_memcache();
}

/**
 * initialize b1gMail
 */
$tempFilesToReleaseAtShutdown = [];
ReadLanguage();
InitializePlugins();
if (!MAINTENANCE_MODE) {
    // module handler
    ModuleFunction('OnReadLang', [
        &$lang_user,
        &$lang_client,
        &$lang_custom,
        &$lang_admin,
        $currentLanguage,
    ]);
}
ReadCustomLanguage();

/**
 * initialize template engine
 */
$templateFolder = B1GMAIL_DIR . 'templates/' . $bm_prefs['template'] . '/';
if (!file_exists($templateFolder)) {
    // 0x06
    DisplayError(
        0x06,
        'Template not found',
        'The requested template folder does not exist.',
        sprintf("Template:\n%s", $bm_prefs['template']),
        __FILE__,
        __LINE__,
    );
    exit();
} elseif (!file_exists($templateFolder . 'cache/')) {
    // 0x07
    DisplayError(
        0x07,
        'Template cache folder not found',
        'The requested template does not have a cache folder.',
        sprintf("Template:\n%s", $bm_prefs['template']),
        __FILE__,
        __LINE__,
    );
    exit();
} elseif (!is_writeable($templateFolder . 'cache/')) {
    // 0x08
    DisplayError(
        0x08,
        'Template cache folder not writeable',
        'The requested template\'s cache folder is not writeable.',
        sprintf("Template:\n%s", $bm_prefs['template']),
        __FILE__,
        __LINE__,
    );
    exit();
}
$tpl = _new('Template');

/**
 * maintenance mode => error
 */
if (MAINTENANCE_MODE && INTERFACE_MODE) {
    echo 'System down for maintenance. Please try again later.';
    exit(1);
} elseif (MAINTENANCE_MODE) {
    $tpl->assign('text', $lang_custom['maintenance']);
    $tpl->display('nli/maintenance.tpl');
    exit();
}

/**
 * mobile redirect override
 */
if (isset($_GET['noMobileRedirect'])) {
    if ($_GET['noMobileRedirect'] == 'false') {
        setcookie('noMobileRedirect', false, time() - TIME_ONE_HOUR, '/');
        unset($_COOKIE['noMobileRedirect']);
    } else {
        setcookie('noMobileRedirect', true, time() + TIME_ONE_YEAR, '/');
        $_COOKIE['noMobileRedirect'] = true;
    }
}

/**
 * after init module handler
 */
ModuleFunction('AfterInit');

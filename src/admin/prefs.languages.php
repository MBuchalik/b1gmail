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
AdminRequirePrivilege('prefs.languages');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'languages';
}

$tabs = [
    0 => [
        'title' => $lang_admin['languages'],
        'relIcon' => 'lang32.png',
        'link' => 'prefs.languages.php?',
        'active' => $_REQUEST['action'] == 'languages',
    ],
    1 => [
        'title' => $lang_admin['customtexts'],
        'relIcon' => 'phrases32.png',
        'link' => 'prefs.languages.php?action=texts&',
        'active' => $_REQUEST['action'] == 'texts',
    ],
];

function updateDisabledLanguages(array $newListOfDisabledLanguages): void {
    global $db;

    $serializedLanguages = null;
    if (count($newListOfDisabledLanguages) > 0) {
        $serializedLanguages = serialize($newListOfDisabledLanguages);
    }

    $db->Query(
        'UPDATE {pre}prefs SET disabled_languages = ?',
        $serializedLanguages,
    );
}

/**
 * fields
 */
if ($_REQUEST['action'] == 'languages') {
    $languages = GetAvailableLanguages(true);

    if (
        isset($_REQUEST['disable']) &&
        array_key_exists($_REQUEST['disable'], $languages) &&
        !$languages[$_REQUEST['disable']]['default']
    ) {
        $allDisabledLanguages = [];
        foreach ($languages as $langKey => $langInfo) {
            if (!$langInfo['disabled']) {
                continue;
            }
            $allDisabledLanguages[] = $langKey;
        }

        $allDisabledLanguages[] = $_REQUEST['disable'];

        updateDisabledLanguages($allDisabledLanguages);
        header('Location: prefs.languages.php?sid=' . session_id());
        exit();
    }

    if (
        isset($_REQUEST['enable']) &&
        array_key_exists($_REQUEST['enable'], $languages)
    ) {
        $allDisabledLanguages = [];
        foreach ($languages as $langKey => $langInfo) {
            if (!$langInfo['disabled']) {
                continue;
            }
            if ($langKey === $_REQUEST['enable']) {
                continue;
            }
            $allDisabledLanguages[] = $langKey;
        }

        updateDisabledLanguages($allDisabledLanguages);
        header('Location: prefs.languages.php?sid=' . session_id());
        exit();
    }

    $tpl->assign('languages', $languages);
    $tpl->assign('page', 'prefs.languages.tpl');
} /**
 * texts
 */ elseif ($_REQUEST['action'] == 'texts') {
    // language given?
    $selectedLang = isset($_REQUEST['lang'])
        ? $_REQUEST['lang']
        : $currentLanguage;

    // get custom lang of lang file
    function GetCustomLang($langfile) {
        $lang_client = $lang_user = $lang_admin = $lang_custom = [];
        include B1GMAIL_DIR . 'languages/' . $langfile . '.lang.php';
        ModuleFunction('OnReadLang', [
            &$lang_user,
            &$lang_client,
            &$lang_custom,
            &$lang_admin,
            $langfile,
        ]);
        return $lang_custom;
    }
    if ($selectedLang) {
        $lang_custom = GetCustomLang($selectedLang);
    }

    // db texts
    $dbTexts = [];
    $res = $db->Query(
        'SELECT `key`,`text` FROM {pre}texts WHERE language=?',
        $selectedLang,
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $lang_custom[$row['key']] = $row['text'];
    }
    $res->Free();

    // save?
    if ($selectedLang && isset($_REQUEST['save'])) {
        foreach ($_POST as $key => $val) {
            if (
                substr($key, 0, 5) == 'text-' &&
                trim($lang_custom[substr($key, 5)]) != trim($val)
            ) {
                $db->Query(
                    'REPLACE INTO {pre}texts(language,`key`,`text`) VALUES(?,?,?)',
                    $selectedLang,
                    substr($key, 5),
                    $val,
                );
                $lang_custom[substr($key, 5)] = $val;
            }
        }

        $cacheManager->Delete('langCustom:' . $selectedLang);
    }

    // get available languages
    $languages = GetAvailableLanguages();

    // get texts
    $texts = [];
    if ($selectedLang) {
        // lang texts
        foreach ($lang_custom as $key => $val) {
            $texts[$key] = [
                'key' => $key,
                'title' => $lang_admin['text_' . $key],
                'text' => $val,
            ];
        }
    }

    // assign
    $tpl->assign(
        'usertpldir',
        B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/',
    );
    $tpl->assign('customTextsHTML', $customTextsHTML);
    $tpl->assign('languages', $languages);
    $tpl->assign('selectedLang', $selectedLang);
    $tpl->assign('texts', $texts);
    $tpl->assign('page', 'prefs.languages.texts.tpl');
}

$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['prefs'] . ' &raquo; ' . $lang_admin['languages'],
);
$tpl->display('page.tpl');
?>

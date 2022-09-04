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
AdminRequirePrivilege('prefs.webdisk');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'common';
}

$tabs = [
    0 => [
        'title' => $lang_admin['common'],
        'relIcon' => 'ico_disk.png',
        'link' => 'prefs.webdisk.php?',
        'active' => $_REQUEST['action'] == 'common',
    ],
    1 => [
        'title' => $lang_admin['limits'],
        'relIcon' => 'filetype.png',
        'link' => 'prefs.webdisk.php?action=limits&',
        'active' => $_REQUEST['action'] == 'limits',
    ],
];

/**
 * common
 */
if ($_REQUEST['action'] == 'common') {
    if (isset($_REQUEST['save'])) {
        $db->Query(
            'UPDATE {pre}prefs SET blobstorage_provider_webdisk=?, blobstorage_webdisk_compress=?',
            $_REQUEST['blobstorage_provider_webdisk'],
            isset($_REQUEST['blobstorage_webdisk_compress']) ? 'yes' : 'no',
        );
        ReadConfig();
    }

    // assign
    $tpl->assign(
        'bsUserDBAvailable',
        BMBlobStorage::createProvider(BMBLOBSTORAGE_USERDB)->isAvailable(),
    );
    $tpl->assign('page', 'prefs.webdisk.common.tpl');
} /**
 * webdisk
 */ elseif ($_REQUEST['action'] == 'limits') {
    if (isset($_REQUEST['save'])) {
        $forbiddenExtensionsArray = explode(
            "\n",
            $_REQUEST['forbidden_extensions'],
        );
        foreach ($forbiddenExtensionsArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $forbiddenExtensionsArray[$key] =
                    ($val[0] != '.' ? '.' : '') . $val;
            } else {
                unset($forbiddenExtensionsArray[$key]);
            }
        }
        $forbiddenExtensions = implode(':', $forbiddenExtensionsArray);

        $forbiddenMIMETypesArray = explode(
            "\n",
            $_REQUEST['forbidden_mimetypes'],
        );
        foreach ($forbiddenMIMETypesArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $forbiddenMIMETypesArray[$key] = $val;
            } else {
                unset($forbiddenMIMETypesArray[$key]);
            }
        }
        $forbiddenMIMETypes = implode(':', $forbiddenMIMETypesArray);

        $db->Query(
            'UPDATE {pre}prefs SET forbidden_extensions=?,forbidden_mimetypes=?',
            $forbiddenExtensions,
            $forbiddenMIMETypes,
        );
        ReadConfig();
    }

    $bm_prefs['forbidden_extensions'] = str_replace(
        ':',
        "\n",
        $bm_prefs['forbidden_extensions'],
    );
    $bm_prefs['forbidden_mimetypes'] = str_replace(
        ':',
        "\n",
        $bm_prefs['forbidden_mimetypes'],
    );
    $tpl->assign('page', 'prefs.webdisk.limits.tpl');
}

$tpl->assign('bm_prefs', $bm_prefs);
$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['prefs'] . ' &raquo; ' . $lang_admin['webdisk'],
);
$tpl->display('page.tpl');
?>

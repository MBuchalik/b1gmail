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
AdminRequirePrivilege('optimize');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'filesystem';
}

$tabs = [
    0 => [
        'title' => $lang_admin['filesystem'],
        'relIcon' => 'tempfiles.png',
        'link' => 'optimize.php?action=filesystem&',
        'active' => $_REQUEST['action'] == 'filesystem',
    ],
    1 => [
        'title' => $lang_admin['cache'],
        'relIcon' => 'cache.png',
        'link' => 'optimize.php?action=cache&',
        'active' => $_REQUEST['action'] == 'cache',
    ],
];

/**
 * optimize filesystem
 */ if ($_REQUEST['action'] == 'filesystem') {
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'cleanupTempFiles') {
        CleanupTempFiles();
    } elseif (isset($_REQUEST['do']) && $_REQUEST['do'] == 'vacuumBlobStor') {
        $perPage = max(1, $_REQUEST['perpage']);
        $pos = (int) $_REQUEST['pos'];

        $res = $db->Query('SELECT COUNT(*) FROM {pre}users');
        [$count] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        if ($pos >= $count) {
            die('DONE');
        } else {
            $res = $db->Query(
                'SELECT `id` FROM {pre}users ORDER BY `id` ASC LIMIT ' .
                    (int) $pos .
                    ',' .
                    (int) $perPage,
            );
            while ($row = $res->FetchArray()) {
                $dbFileName = DataFilename($row['id'], 'blobdb');
                if (file_exists($dbFileName)) {
                    try {
                        $sdb = new SQLite3($dbFileName);
                        $sdb->busyTimeout(500);
                        $sdb->query('VACUUM');
                        unset($sdb);
                    } catch (Exception $ex) {
                    }
                }

                $pos++;
            }
            $res->Free();

            if ($pos >= $count) {
                die('DONE');
            } else {
                die($pos . '/' . $count);
            }
        }
    } elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'rebuildBlobStor' &&
        isset($_REQUEST['rebuild'])
    ) {
        $perPage = max(1, $_REQUEST['perpage']);

        if ($_REQUEST['rebuild'] == 'email') {
            $destBlobStorage = $bm_prefs['blobstorage_provider'];
            $blobType = BMBLOB_TYPE_MAIL;
            $queryAll =
                'SELECT COUNT(*) FROM {pre}mails ' .
                'LEFT JOIN {pre}blobstate ON {pre}blobstate.`blobstorage`={pre}mails.`blobstorage` AND {pre}blobstate.blobid={pre}mails.`id` AND {pre}blobstate.`blobtype`=' .
                BMBLOB_TYPE_MAIL .
                ' ' .
                'WHERE {pre}mails.`userid`!=-1 AND {pre}mails.`blobstorage`!=? AND ({pre}blobstate.`defect` IS NULL OR {pre}blobstate.`defect`=0)';
            $query =
                'SELECT {pre}mails.`id`,{pre}mails.`userid`,{pre}mails.`blobstorage` FROM {pre}mails ' .
                'LEFT JOIN {pre}blobstate ON {pre}blobstate.`blobstorage`={pre}mails.`blobstorage` AND {pre}blobstate.blobid={pre}mails.`id` AND {pre}blobstate.`blobtype`=' .
                BMBLOB_TYPE_MAIL .
                ' ' .
                'WHERE {pre}mails.`userid`!=-1 AND {pre}mails.`blobstorage`!=? AND ({pre}blobstate.`defect` IS NULL OR {pre}blobstate.`defect`=0) ORDER BY {pre}mails.`userid` ASC, {pre}mails.`blobstorage` ASC LIMIT ' .
                (int) $perPage;
            $queryUpdate = 'UPDATE {pre}mails SET `blobstorage`=? WHERE `id`=?';
        } else {
            die('Invalid rebuild type');
        }

        if (!isset($_REQUEST['all'])) {
            $db->Query(
                'DELETE FROM {pre}blobstate WHERE `blobtype`=?',
                $blobType,
            );

            $res = $db->Query($queryAll, $destBlobStorage);
            while ($row = $res->FetchArray(MYSQLI_NUM)) {
                $all = $row[0];
            }
            $res->Free();
        } else {
            $all = max(0, (int) $_REQUEST['all']);
        }

        if (!isset($all) || $all == 0) {
            die('DONE');
        }

        $processedCount = 0;
        $currentUserID = 0;
        $currentSourceProvider = $currentDestProvider = false;
        $currentToDelete = [];
        $currentToUpdate = [];

        $res = $db->Query($query, $destBlobStorage);
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            if ($currentUserID != $row['userid']) {
                $currentUserID = $row['userid'];

                if (is_object($currentDestProvider)) {
                    $currentDestProvider->endTx();

                    foreach ($currentToUpdate as $rowID) {
                        $db->Query(
                            $queryUpdate,
                            $currentDestProvider->providerID,
                            $rowID,
                        );
                    }
                    $currentToUpdate = [];
                }

                $currentDestProvider = BMBlobStorage::createProvider(
                    $destBlobStorage,
                    $row['userid'],
                );
                $currentDestProvider->beginTx();

                if (is_object($currentSourceProvider)) {
                    foreach ($currentToDelete as $rowID) {
                        $currentSourceProvider->deleteBlob($blobType, $rowID);
                    }
                    $currentSourceProvider->endTx();
                    $currentToDelete = [];
                }

                $currentSourceProvider = false;
            }

            if (
                !is_object($currentSourceProvider) ||
                $currentSourceProvider->providerID != $row['blobstorage']
            ) {
                if (is_object($currentSourceProvider)) {
                    foreach ($currentToDelete as $rowID) {
                        $currentSourceProvider->deleteBlob($blobType, $rowID);
                    }
                    $currentSourceProvider->endTx();
                    $currentToDelete = [];
                }

                $currentSourceProvider = BMBlobStorage::createProvider(
                    $row['blobstorage'],
                    $row['userid'],
                );
                $currentSourceProvider->beginTx();
            }

            $defect = false;

            $fpSource = $currentSourceProvider->loadBlob($blobType, $row['id']);
            if ($fpSource) {
                if (
                    $currentDestProvider->storeBlob(
                        $blobType,
                        $row['id'],
                        $fpSource,
                    )
                ) {
                    fclose($fpSource);

                    $currentToDelete[] = $row['id'];
                    $currentToUpdate[] = $row['id'];
                } else {
                    $defect = true;
                }
            } else {
                $defect = true;
            }

            if ($defect) {
                $db->Query(
                    'REPLACE INTO {pre}blobstate(`blobstorage`,`blobtype`,`blobid`,`defect`) VALUES(?,?,?,?)',
                    $row['blobstorage'],
                    $blobType,
                    $row['id'],
                    1,
                );
            }

            ++$processedCount;
        }
        $res->Free();

        if (is_object($currentSourceProvider)) {
            foreach ($currentToDelete as $rowID) {
                $currentSourceProvider->deleteBlob($blobType, $rowID);
            }
            $currentSourceProvider->endTx();

            unset($currentSourceProvider);
            $currentToDelete = [];
        }

        if (is_object($currentDestProvider)) {
            $currentDestProvider->endTx();

            foreach ($currentToUpdate as $rowID) {
                $db->Query(
                    $queryUpdate,
                    $currentDestProvider->providerID,
                    $rowID,
                );
            }

            unset($currentDestProvider);
            $currentToUpdate = [];
        }

        if ($processedCount == 0 || $processedCount >= $all) {
            echo 'DONE';
        } else {
            printf('%d/%d', $processedCount, $all);
        }
        exit();
    }

    //
    // temp files
    //
    $tempFileCount = 0;
    $tempFileSize = 0;
    $res = $db->Query('SELECT id FROM {pre}tempfiles');
    while ($row = $res->FetchArray()) {
        $tempFileCount++;
        $fileName = TempFileName($row['id']);
        $tempFileSize += @filesize($fileName);
    }
    $res->Free();

    $tpl->assign('haveSQLite3', class_exists('SQLite3'));
    $tpl->assign('tempFileCount', $tempFileCount);
    $tpl->assign('tempFileSize', $tempFileSize);
    $tpl->assign(
        'msTitle',
        $bm_prefs['storein'] == 'db'
            ? $lang_admin['file2db']
            : $lang_admin['db2file'],
    );
    $tpl->assign(
        'msDesc',
        $bm_prefs['storein'] == 'db'
            ? $lang_admin['file2db_desc']
            : $lang_admin['db2file_desc'],
    );
    $tpl->assign('page', 'optimize.filesystem.tpl');
} /**
 * optimize caches
 */ elseif ($_REQUEST['action'] == 'cache') {
    //
    // empty file cache
    //
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'cleanupFileCache' &&
        $bm_prefs['cache_type'] == CACHE_B1GMAIL
    ) {
        $cacheManager->CleanUp(true);
    }

    //
    // rebuild caches
    //
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'rebuild' &&
        isset($_REQUEST['perpage']) &&
        isset($_REQUEST['pos'])
    ) {
        $perpage = (int) $_REQUEST['perpage'];
        $pos = (int) $_REQUEST['pos'];

        //
        // rebuild mailsizes
        //
        if ($_REQUEST['rebuild'] == 'mailsizes') {
            $res = $db->Query(
                'SELECT COUNT(*) FROM {pre}mails WHERE (`flags`&' .
                    FLAG_DECEPTIVE .
                    ')=0',
            );
            [$count] = $res->FetchArray(MYSQLI_NUM);
            $res->Free();

            if ($pos >= $count) {
                die('DONE');
            } else {
                $res = $db->Query(
                    'SELECT id,size,blobstorage,userid FROM {pre}mails WHERE (`flags`&' .
                        FLAG_DECEPTIVE .
                        ')=0 ORDER BY id DESC LIMIT ' .
                        (int) $pos .
                        ',' .
                        (int) $perpage,
                );
                while ($row = $res->FetchArray()) {
                    $cachedSize = $row['size'];
                    $actualSize = BMBlobStorage::createProvider(
                        $row['blobstorage'],
                        $row['userid'],
                    )->getBlobSize(BMBLOB_TYPE_MAIL, $row['id']);

                    if ($actualSize != $cachedSize) {
                        $db->Query(
                            'UPDATE {pre}mails SET size=? WHERE id=?',
                            $actualSize,
                            $row['id'],
                        );
                    }

                    $pos++;
                }
                $res->Free();

                if ($pos >= $count) {
                    die('DONE');
                } else {
                    die($pos . '/' . $count);
                }
            }
        }

        //
        // rebuild user sizes
        //
        elseif ($_REQUEST['rebuild'] == 'usersizes') {
            $res = $db->Query('SELECT COUNT(*) FROM {pre}users');
            [$count] = $res->FetchArray(MYSQLI_NUM);
            $res->Free();

            if ($pos >= $count) {
                die('DONE');
            } else {
                $res = $db->Query(
                    'SELECT id,email,mailspace_used FROM {pre}users ORDER BY id DESC LIMIT ' .
                        (int) $pos .
                        ',' .
                        (int) $perpage,
                );
                while ($row = $res->FetchArray()) {
                    $cachedMailSize = $row['mailspace_used'];

                    $res2 = $db->Query(
                        'SELECT SUM(size) FROM {pre}mails WHERE userid=?',
                        $row['id'],
                    );
                    [$actualMailSize] = $res2->FetchArray(MYSQLI_NUM);
                    $res2->Free();

                    if ($actualMailSize != $cachedMailSize) {
                        $db->Query(
                            'UPDATE {pre}users SET mailspace_used=? WHERE id=?',
                            $actualMailSize,
                            $row['id'],
                        );
                    }

                    $pos++;
                }
                $res->Free();

                if ($pos >= $count) {
                    die('DONE');
                } else {
                    die($pos . '/' . $count);
                }
            }
        }
    }

    // retrieve cache info
    $res = $db->Query('SELECT COUNT(*),SUM(size) FROM {pre}file_cache');
    [$cacheFileCount, $cacheFileSize] = $res->FetchArray(MYSQLI_NUM);
    $res->Free();

    // assign
    $tpl->assign('fileCache', $bm_prefs['cache_type'] == CACHE_B1GMAIL);
    $tpl->assign('cacheFileCount', $cacheFileCount);
    $tpl->assign('cacheFileSize', $cacheFileSize);
    $tpl->assign('page', 'optimize.cache.tpl');
}

$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['tools'] . ' &raquo; ' . $lang_admin['optimize'],
);
$tpl->display('page.tpl');
?>

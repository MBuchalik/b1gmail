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
 * webdisk interface class
 */
class BMWebdisk {
    var $_userID;

    /**
     * constructor
     *
     * @param int $userID User ID
     * @return BMWebdisk
     */
    function __construct($userID) {
        global $userRow, $db;

        $this->_userID = $userID;
    }

    /**
     * get file info
     *
     * @param int $fileID File ID
     * @return array
     */
    function GetFileInfo($fileID) {
        global $db, $VIEWABLE_TYPES;

        $res = $db->Query(
            'SELECT * FROM {pre}diskfiles WHERE id=? AND user=?',
            $fileID,
            $this->_userID,
        );
        if ($res->RowCount() == 0) {
            return false;
        }
        $info = $res->FetchArray(MYSQLI_ASSOC);
        $res->Free();

        return $info;
    }

    /**
     * delete a file
     *
     * @param int $fileID File ID
     * @return bool
     */
    function DeleteFile($fileID) {
        global $db;

        $success = false;

        $info = $this->GetFileInfo($fileID);

        $db->Query('BEGIN');
        $db->Query(
            'DELETE FROM {pre}diskfiles WHERE id=? AND user=?',
            $fileID,
            $this->_userID,
        );
        if ($db->AffectedRows() == 1) {
            $success = true;
        }
        $db->Query('COMMIT');

        if ($success) {
            BMBlobStorage::createProvider(
                $info['blobstorage'],
                $this->_userID,
            )->deleteBlob(BMBLOB_TYPE_WEBDISK, $fileID);
        }

        return $success;
    }
}

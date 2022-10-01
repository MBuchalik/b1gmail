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

class Migration_9_0_0__0 extends SingleMigrationStep {
    function applyMigration($dbConnection): bool {
        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_prefs DROP COLUMN calendar_defaultviewmode',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_gruppen
                    DROP COLUMN checker,
                    DROP COLUMN tbx_webdisk,
                    DROP COLUMN organizerdav,
                    DROP COLUMN syncml,
                    DROP COLUMN webdav
            ',
            )
        ) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_notes')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_tasks')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_tasklists')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_tbx_versions')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_disklocks')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_dates')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_dates_attendees')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_dates_groups')) {
            return false;
        }

        return true;
    }
}

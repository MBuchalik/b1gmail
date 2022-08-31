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

class Migration_8_0_0__0 extends SingleMigrationStep {
    function applyMigration($dbConnection): bool {
        // Add column "migration_level". We want this column to be not-null and there is already content, so we first create a nullable column, insert data, and then make the column not-null.

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_prefs ADD COLUMN migration_level VARCHAR(255)',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                "UPDATE bm60_prefs SET migration_level = '8-0-0--0'",
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_prefs MODIFY migration_level VARCHAR(255) NOT NULL',
            )
        ) {
            return false;
        }

        return true;
    }
}

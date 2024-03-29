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
                'ALTER TABLE bm60_users
                    DROP COLUMN traffic_down,
                    DROP COLUMN traffic_up,
                    DROP COLUMN traffic_status,
                    DROP COLUMN traffic_add,
                    DROP COLUMN diskspace_used,
                    DROP COLUMN diskspace_add,
                    DROP COLUMN notify_email,
                    DROP COLUMN notify_birthday,
                    DROP COLUMN notify_sound
            ',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_prefs
                    DROP COLUMN calendar_defaultviewmode,
                    DROP COLUMN forbidden_extensions,
                    DROP COLUMN forbidden_mimetypes,
                    DROP COLUMN search_engine,
                    DROP COLUMN notify_interval,
                    DROP COLUMN notify_lifetime,
                    DROP COLUMN widget_order_start,
                    DROP COLUMN widget_order_organizer,
                    DROP COLUMN last_userpop3_cron
            ',
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
                    DROP COLUMN webdav,
                    DROP COLUMN traffic,
                    DROP COLUMN share,
                    DROP COLUMN webdisk,
                    DROP COLUMN wd_member_kbs,
                    DROP COLUMN wd_open_kbs,
                    DROP COLUMN notifications,
                    DROP COLUMN maildeliverystatus,
                    DROP COLUMN ownpop3_interval,
                    DROP COLUMN ownpop3,
                    DROP COLUMN selfpop3_check,
                    DROP COLUMN smime,
                    DROP COLUMN issue_certificates,
                    DROP COLUMN upload_certificates
            ',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_adressen DROP COLUMN last_bd_reminder',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                "DELETE FROM bm60_userprefs
                WHERE `key` IN
                    (
                        'webdisk_hideHidden',
                        'webdiskViewMode',
                        'widgetOrderStart',
                        'widgetOrderOrganizer',
                        'smimeSign',
                        'smimeEncrypt'
                    )
            ",
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'DELETE FROM bm60_filter_actions WHERE op=13',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                "DELETE FROM bm60_stats WHERE typ='wd_down' OR typ='wd_up'",
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

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_diskprops')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_diskfolders')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_workgroups')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_workgroups_member')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_workgroups_shares')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_notifications')) {
            return false;
        }

        if (
            !mysqli_query($dbConnection, 'DROP TABLE bm60_maildeliverystatus')
        ) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_pop3')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_uidindex')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_certificates')) {
            return false;
        }

        return true;
    }
}

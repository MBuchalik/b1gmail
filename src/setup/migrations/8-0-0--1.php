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

class Migration_8_0_0__1 extends SingleMigrationStep {
    function applyMigration($dbConnection): bool {
        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_prefs ADD COLUMN disabled_languages TEXT AFTER language',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                "DELETE FROM bm60_stats WHERE typ='sms'",
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_prefs
                    DROP COLUMN taborder,
                    DROP COLUMN regenabled,
                    DROP COLUMN usr_status,
                    DROP COLUMN user_count_limit,
                    DROP COLUMN reg_iplock,
                    DROP COLUMN signup_dnsbl,
                    DROP COLUMN signup_dnsbl_enable,
                    DROP COLUMN signup_dnsbl_action,
                    DROP COLUMN reg_validation,
                    DROP COLUMN reg_validation_max_resend_times,
                    DROP COLUMN reg_validation_min_resend_interval,
                    DROP COLUMN signup_suggestions,
                    DROP COLUMN notify_mail,
                    DROP COLUMN notify_to,
                    DROP COLUMN gut_regged,
                    DROP COLUMN plz_check,
                    DROP COLUMN ap_medium_limit,
                    DROP COLUMN ap_hard_limit,
                    DROP COLUMN ap_expire_time,
                    DROP COLUMN ap_expire_mode,
                    DROP COLUMN ap_autolock,
                    DROP COLUMN ap_autolock_notify,
                    DROP COLUMN ap_autolock_notify_to,
                    DROP COLUMN sms_enable_charge,
                    DROP COLUMN f_mail2sms_nummer,
                    DROP COLUMN enable_vk
                ',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_profilfelder DROP COLUMN show_signup',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_users
                    DROP COLUMN uid,
                    DROP COLUMN sms_validation,
                    DROP COLUMN sms_validation_code,
                    DROP COLUMN sms_validation_last_send,
                    DROP COLUMN sms_validation_send_times,
                    DROP COLUMN mail2sms,
                    DROP COLUMN mail2sms_nummer
                ',
            )
        ) {
            return false;
        }

        if (
            !mysqli_query(
                $dbConnection,
                'ALTER TABLE bm60_gruppen
                    DROP COLUMN abuseprotect,
                    DROP COLUMN sms_monat,
                    DROP COLUMN sms_pre,
                    DROP COLUMN mail2sms,
                    DROP COLUMN sms_ownfrom,
                    DROP COLUMN tbx_smsmanager,
                    DROP COLUMN sms_price_per_credit,
                    DROP COLUMN sms_from,
                    DROP COLUMN sms_sig,
                    DROP COLUMN smsvalidation,
                    DROP COLUMN sms_send_code
            ',
            )
        ) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_codes')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_orders')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_invoices')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_transactions')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_smsend')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_smstypen')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_smsgateways')) {
            return false;
        }

        if (!mysqli_query($dbConnection, 'DROP TABLE bm60_abuse_points')) {
            return false;
        }

        if (
            !mysqli_query($dbConnection, 'DROP TABLE bm60_abuse_points_config')
        ) {
            return false;
        }

        return true;
    }
}

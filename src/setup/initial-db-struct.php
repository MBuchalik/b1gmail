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

$initialDbStruct = [];

$initialDbStruct[] = 'CREATE TABLE bm60_abuse_points(
  `entryid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `points` tinyint(4) NOT NULL DEFAULT 0,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `expired` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`entryid`),
  KEY `userid`(`userid`),
  KEY `expired`(`expired`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_abuse_points_config(
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `points` tinyint(4) NOT NULL DEFAULT 0,
  `prefs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`type`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_actiontokens(
  `actiontokenid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `token` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` tinyint(4) NOT NULL DEFAULT 0,
  `created` int(14) NOT NULL DEFAULT 0,
  `expires` int(14) NOT NULL DEFAULT 0,
  PRIMARY KEY (`actiontokenid`),
  KEY `token`(`token`),
  KEY `expires`(`expires`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_admins(
  `adminid` int(11) NOT NULL auto_increment,
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'admin\',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password_salt` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `firstname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `privileges` text NOT NULL,
  `notes` text NOT NULL,
  `last_try` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`adminid`),
  KEY `username`(`username`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_adressen(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `vorname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fax` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `strassenr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ort` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `plz` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `land` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `web` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nachname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `handy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `firma` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `kommentar` text NOT NULL,
  `picture` mediumblob NOT NULL,
  `work_strassenr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_plz` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_ort` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_land` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_fax` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `work_handy` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `default_address` tinyint(4) NOT NULL DEFAULT 1,
  `anrede` enum(\'herr\',\'frau\',\'\') NOT NULL,
  `position` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `geburtsdatum` bigint(20) NOT NULL DEFAULT 0,
  `last_bd_reminder` int(11) NOT NULL DEFAULT 0,
  `invitationCode` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uri` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email`(`email`),
  KEY `email_2`(`email`,`user`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_adressen_gruppen(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uri` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_adressen_gruppen_member(
  `adresse` int(11) NOT NULL DEFAULT 0,
  `gruppe` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`adresse`,`gruppe`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_ads(
  `id` int(11) NOT NULL auto_increment,
  `code` text NOT NULL,
  `views` int(11) NOT NULL,
  `paused` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `weight` tinyint(4) NOT NULL DEFAULT 100,
  `category` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_aliase(
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 3,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_attachments(
  `attachmentid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `mailid` int(11) NOT NULL DEFAULT 0,
  `partid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `filename` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `size` int(11) NOT NULL DEFAULT 0,
  `contenttype` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `flags` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`attachmentid`),
  KEY `userid`(`userid`),
  KEY `mailid`(`mailid`),
  KEY `filename`(`filename`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_autoresponder(
  `id` int(11) NOT NULL auto_increment,
  `active` enum(\'yes\',\'no\') NOT NULL,
  `userid` int(11) NOT NULL DEFAULT 0,
  `betreff` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mitteilung` text NOT NULL,
  `last_send` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_blobstate(
  `blobstorage` tinyint(4) NOT NULL DEFAULT 0,
  `blobtype` tinyint(4) NOT NULL DEFAULT 0,
  `blobid` int(11) NOT NULL DEFAULT 0,
  `defect` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`blobstorage`,`blobtype`,`blobid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_certificates(
  `certificateid` int(11) NOT NULL auto_increment,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `userid` int(11) NOT NULL DEFAULT 0,
  `hash` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cn` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `validfrom` bigint(20) NOT NULL,
  `validto` bigint(20) NOT NULL,
  `pemdata` text NOT NULL,
  PRIMARY KEY (`certificateid`),
  KEY `userid,type,hash`(`userid`,`type`,`hash`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_certmails(
  `id` int(11) NOT NULL auto_increment,
  `mail` int(11) NOT NULL,
  `recipient` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `confirmation_sent` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_changelog(
  `itemtype` tinyint(4) NOT NULL DEFAULT 0,
  `itemid` int(11) NOT NULL DEFAULT 0,
  `created` int(11) NOT NULL DEFAULT 0,
  `updated` int(11) NOT NULL DEFAULT 0,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `userid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`itemtype`,`itemid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_codes(
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `von` int(11) NOT NULL,
  `bis` int(11) NOT NULL,
  `anzahl` int(11) NOT NULL,
  `ver` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `usedby` text NOT NULL,
  `valid_signup` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `valid_loggedin` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_dates(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `location` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `text` text NOT NULL,
  `group` int(11) NOT NULL DEFAULT -1,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `reminder` int(11) NOT NULL DEFAULT 300,
  `flags` tinyint(4) NOT NULL DEFAULT 0,
  `repeat_flags` int(11) NOT NULL DEFAULT 0,
  `repeat_times` int(11) NOT NULL,
  `repeat_value` int(11) NOT NULL,
  `repeat_extra1` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `repeat_extra2` tinyint(4) NOT NULL DEFAULT 0,
  `last_reminder` int(11) NOT NULL DEFAULT 0,
  `dav_uri` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user`(`user`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_dates_attendees(
  `date` int(11) NOT NULL,
  `address` int(11) NOT NULL,
  PRIMARY KEY (`date`,`address`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_dates_groups(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` tinyint(4) NOT NULL DEFAULT 0,
  `dav_uri` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_diskfiles(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `dateiname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ordner` int(11) NOT NULL,
  `size` int(14) NOT NULL,
  `contenttype` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` int(11) NOT NULL DEFAULT -1,
  `modified` int(11) NOT NULL,
  `accessed` int(11) NOT NULL DEFAULT 0,
  `blobstorage` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_diskfolders(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `titel` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `share` enum(\'yes\',\'no\') NOT NULL,
  `share_pw` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` int(11) NOT NULL DEFAULT -1,
  `modified` int(11) NOT NULL DEFAULT 0,
  `accessed` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_disklocks(
  `user` int(11) NOT NULL,
  `path` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'write\',
  `created` int(11) NOT NULL DEFAULT 0,
  `modified` int(11) NOT NULL DEFAULT 0,
  `expires` int(11) NOT NULL DEFAULT 0,
  `scope` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'exclusive\',
  `owner` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`user`,`path`,`token`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_diskprops(
  `user` int(11) NOT NULL,
  `path` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `xmlns` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`user`,`path`,`xmlns`,`name`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_domains(
  `domain` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `in_login` tinyint(4) NOT NULL DEFAULT 1,
  `in_signup` tinyint(4) NOT NULL DEFAULT 1,
  `in_aliases` tinyint(4) NOT NULL DEFAULT 1,
  `pos` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`domain`),
  KEY `sort`(`pos`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_extensions(
  `id` int(11) NOT NULL auto_increment,
  `ext` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ctype` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bild` text NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_faq(
  `id` int(11) NOT NULL auto_increment,
  `typ` enum(\'nli\',\'li\',\'both\') NOT NULL,
  `required` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `frage` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `antwort` text NOT NULL,
  `lang` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \':all:\',
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_file_cache(
  `key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `expires` int(11) NOT NULL,
  `size` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`key`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_filter(
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `applied` int(11) unsigned NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `link` tinyint(4) NOT NULL DEFAULT 1,
  `orderpos` int(11) NOT NULL DEFAULT 0,
  `flags` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_filter_actions(
  `id` int(11) NOT NULL auto_increment,
  `filter` int(11) NOT NULL DEFAULT 0,
  `op` tinyint(4) NOT NULL,
  `val` int(11) NOT NULL DEFAULT 0,
  `text_val` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_filter_conditions(
  `id` int(11) NOT NULL auto_increment,
  `filter` int(11) NOT NULL DEFAULT 0,
  `field` tinyint(4) NOT NULL,
  `op` tinyint(4) NOT NULL DEFAULT 0,
  `val` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filter`(`filter`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_folder_conditions(
  `id` int(11) NOT NULL auto_increment,
  `folder` int(11) NOT NULL,
  `field` tinyint(4) NOT NULL,
  `op` tinyint(4) NOT NULL,
  `val` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `folder`(`folder`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_folders(
  `id` int(11) NOT NULL auto_increment,
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userid` int(11) NOT NULL DEFAULT 0,
  `parent` int(11) NOT NULL DEFAULT -1,
  `perpage` int(11) NOT NULL DEFAULT 25,
  `storetime` int(11) NOT NULL DEFAULT -1,
  `group_mode` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'-\',
  `intelligent` tinyint(4) NOT NULL DEFAULT 0,
  `intelligent_link` tinyint(4) NOT NULL DEFAULT 1,
  `subscribed` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `parent`(`parent`),
  KEY `user`(`parent`,`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_groupoptions(
  `gruppe` int(11) NOT NULL DEFAULT 0,
  `module` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `key` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`gruppe`,`module`,`key`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_gruppen(
  `id` int(11) NOT NULL auto_increment,
  `storage` bigint(20) NOT NULL DEFAULT 15728640,
  `maxsize` int(11) NOT NULL DEFAULT 2097152,
  `soforthtml` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `pop3` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `anlagen` int(11) NOT NULL DEFAULT 2097152,
  `webdisk` bigint(20) NOT NULL DEFAULT 15728640,
  `sms_sig` varchar(160) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \' - powered by b1gMail\',
  `sms_monat` int(20) NOT NULL DEFAULT 0,
  `mail2sms` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `responder` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `forward` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `signatur` text NOT NULL,
  `wap` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `aliase` int(11) NOT NULL DEFAULT 5,
  `checker` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `sms_from` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_ownfrom` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `sms_pre` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ownpop3` int(11) NOT NULL DEFAULT 5,
  `selfpop3_check` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `traffic` bigint(20) NOT NULL DEFAULT 104857600,
  `ads` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `saliase` text NOT NULL,
  `sms_price_per_credit` int(11) NOT NULL DEFAULT 7,
  `share` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `wd_open_kbs` int(11) NOT NULL DEFAULT 50,
  `wd_member_kbs` int(11) NOT NULL DEFAULT 100,
  `imap` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `webdav` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `ownpop3_interval` int(11) NOT NULL DEFAULT 300,
  `smtp` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `smime` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `issue_certificates` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `upload_certificates` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `smsvalidation` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `max_recps` int(11) NOT NULL DEFAULT 5,
  `sender_aliases` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `syncml` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `allow_newsletter_optout` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `tbx_webdisk` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `tbx_smsmanager` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `abuseprotect` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `organizerdav` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `ftsearch` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `send_limit_count` int(11) NOT NULL DEFAULT 100,
  `send_limit_time` int(11) NOT NULL DEFAULT 1440,
  `notifications` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `maildeliverystatus` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `mail_send_code` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `sms_send_code` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `auto_save_drafts` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_invoices(
  `orderid` int(11) NOT NULL auto_increment,
  `invoice` longblob NOT NULL,
  PRIMARY KEY (`orderid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_locked(
  `id` int(11) NOT NULL auto_increment,
  `typ` enum(\'start\',\'mitte\',\'ende\',\'gleich\'),
  `benutzername` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_logs(
  `id` int(11) NOT NULL auto_increment,
  `eintrag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `zeitstempel` int(14) NOT NULL,
  `prio` tinyint(1) NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_maildeliverystatus(
  `deliverystatusid` int(11) NOT NULL auto_increment,
  `outboxid` int(11) NOT NULL DEFAULT 0,
  `userid` int(11) NOT NULL DEFAULT 0,
  `recipient` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` int(11) NOT NULL DEFAULT 0,
  `updated` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `delivered_to` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`deliverystatusid`),
  KEY `outboxid`(`outboxid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_mailnotes(
  `mailid` int(11) NOT NULL DEFAULT 0,
  `notes` text NOT NULL,
  PRIMARY KEY (`mailid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_mails(
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `betreff` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `von` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `an` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `folder` int(11) NOT NULL,
  `datum` int(11) NOT NULL DEFAULT 0,
  `trashstamp` int(11) NOT NULL,
  `priority` enum(\'low\',\'normal\',\'high\') NOT NULL,
  `fetched` int(11) NOT NULL DEFAULT 0,
  `msg_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `virnam` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `trained` tinyint(4) NOT NULL DEFAULT 0,
  `refs` text NOT NULL,
  `flags` int(11) NOT NULL DEFAULT -1,
  `size` int(11) NOT NULL,
  `color` tinyint(4) NOT NULL DEFAULT 0,
  `blobstorage` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`,`userid`),
  KEY `mailUser`(`userid`),
  KEY `mailUserFolder`(`userid`,`folder`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_mods(
  `modname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `installed` tinyint(4) NOT NULL DEFAULT 0,
  `paused` tinyint(4) NOT NULL DEFAULT 0,
  `pos` int(11) NOT NULL,
  `packageName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `signature` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `files` text NOT NULL,
  PRIMARY KEY (`modname`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_newsletter_templates(
  `templateid` int(11) NOT NULL auto_increment,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mode` enum(\'text\',\'html\') NOT NULL DEFAULT \'html\',
  `from` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT 0,
  `body` longtext NOT NULL,
  PRIMARY KEY (`templateid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_notes(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL DEFAULT 0,
  `priority` tinyint(4) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_notifications(
  `notificationid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `date` int(14) NOT NULL DEFAULT 0,
  `expires` int(14) NOT NULL DEFAULT 0,
  `read` tinyint(4) NOT NULL DEFAULT 0,
  `flags` int(11) NOT NULL DEFAULT 0,
  `text_phrase` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `text_params` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `link` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `icon` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `class` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`notificationid`),
  KEY `userid`(`userid`),
  KEY `expires`(`expires`),
  KEY `date`(`date`),
  KEY `unread_query`(`userid`,`read`,`expires`,`date`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_orders(
  `orderid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `vkcode` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `txnid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cart` text,
  `paymethod` int(11) NOT NULL DEFAULT 0,
  `paymethod_params` text NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `inv_firstname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inv_lastname` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inv_street` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inv_no` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inv_zip` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inv_city` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `inv_country` int(11) NOT NULL DEFAULT 0,
  `created` int(11) NOT NULL DEFAULT 0,
  `activated` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`orderid`),
  KEY `userid`(`userid`),
  KEY `vkcode`(`vkcode`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_paymethods(
  `methodid` int(11) NOT NULL auto_increment,
  `enabled` tinyint(4) NOT NULL DEFAULT 1,
  `invoice` enum(\'at_order\',\'at_activation\') NOT NULL DEFAULT \'at_activation\',
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY (`methodid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_pop3(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `p_host` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `p_user` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `p_pass` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `p_target` int(11) NOT NULL,
  `p_port` int(11) NOT NULL DEFAULT 110,
  `p_keep` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `p_ssl` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `paused` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `last_fetch` int(11) NOT NULL,
  `last_success` tinyint(4) NOT NULL DEFAULT -1,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_prefs(
  `id` int(11) NOT NULL auto_increment,
  `domains` text NOT NULL,
  `send_method` enum(\'smtp\',\'php\',\'sendmail\') NOT NULL,
  `smtp_host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'localhost\',
  `smtp_port` int(6) NOT NULL DEFAULT 25,
  `pop3_host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'localhost\',
  `pop3_port` int(6) NOT NULL DEFAULT 110,
  `pop3_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pop3_pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `template` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'default\',
  `serial` varchar(22) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'deutsch\',
  `adminpw` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'b1gMail\',
  `f_strasse` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'p\',
  `f_telefon` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'v\',
  `f_fax` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'v\',
  `f_alternativ` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'v\',
  `f_safecode` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'p\',
  `std_gruppe` int(11) NOT NULL DEFAULT 1,
  `usr_status` enum(\'yes\',\'no\',\'delete\',\'locked\') NOT NULL DEFAULT \'no\',
  `passmail_abs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `regenabled` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `datumsformat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'d.m.Y H:i:s\',
  `mailmax` int(11) NOT NULL DEFAULT 2097152,
  `einsch_life` int(14) NOT NULL DEFAULT 2678400,
  `selfurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fetchcount` int(11) NOT NULL DEFAULT 50,
  `storein` enum(\'file\',\'db\') NOT NULL DEFAULT \'file\',
  `sms_gateway` int(11) NOT NULL DEFAULT 1,
  `mail2sms_abs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'b1gMail\',
  `datafolder` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `b1gmta_host` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'localhost\',
  `clndr_sms_abs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'b1gMail\',
  `minuserlength` int(3) NOT NULL DEFAULT 3,
  `dnsbl` text NOT NULL,
  `spamcheck` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `ordner_proseite` int(11) NOT NULL DEFAULT 25,
  `max_bcc` int(11) NOT NULL DEFAULT 5,
  `std_land` int(4) NOT NULL DEFAULT 25,
  `plz_check` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `receive_method` enum(\'pop3\',\'pipe\') NOT NULL DEFAULT \'pop3\',
  `smtp_auth` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `smtp_user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `smtp_pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `errormail` enum(\'yes\',\'no\',\'soft\') NOT NULL DEFAULT \'yes\',
  `failure_forward` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `notify_mail` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `notify_to` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_enable_paypal` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `sms_paypal_mail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `blocked` text NOT NULL,
  `gut_regged` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `wartung` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `smsreply_abs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `patchlevel` int(11) NOT NULL DEFAULT 0,
  `use_clamd` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `clamd_port` int(11) NOT NULL DEFAULT 3310,
  `clamd_host` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'127.0.0.1\',
  `use_bayes` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `bayes_mode` enum(\'global\',\'local\') NOT NULL DEFAULT \'local\',
  `bayes_spam` int(11) NOT NULL DEFAULT 0,
  `bayes_nonspam` int(11) NOT NULL DEFAULT 0,
  `last_admin_try` int(11) NOT NULL DEFAULT 0,
  `reg_iplock` int(11) NOT NULL DEFAULT 3600,
  `max_userpicture_size` int(11) NOT NULL DEFAULT 153600,
  `search_engine` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'http://www.google.de/search?q=%s\',
  `widget_order_start` text NOT NULL,
  `widget_order_organizer` text NOT NULL,
  `logouturl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'index.php\',
  `notes` text NOT NULL,
  `alt_check` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `cron_interval` int(11) NOT NULL DEFAULT 30,
  `last_cron` int(11) NOT NULL DEFAULT 0,
  `recipient_detection` enum(\'static\',\'dynamic\') NOT NULL DEFAULT \'static\',
  `dnsbl_requiredservers` tinyint(4) NOT NULL DEFAULT 1,
  `cache_type` tinyint(4) NOT NULL DEFAULT 1,
  `cache_parseonly` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `filecache_size` int(11) NOT NULL DEFAULT 5242880,
  `memcache_servers` text NOT NULL,
  `memcache_persistent` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `sendmail_path` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'/usr/sbin/sendmail\',
  `structstorage` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `autocancel` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `contact_history` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `forbidden_extensions` text NOT NULL,
  `forbidden_mimetypes` text NOT NULL,
  `welcome_mail` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `search_in` text NOT NULL,
  `ftp_host` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'localhost\',
  `ftp_port` int(11) NOT NULL DEFAULT 21,
  `ftp_user` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_pass` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ftp_dir` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'/\',
  `ftp_permissions` int(11) NOT NULL DEFAULT 644,
  `ftp_active` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `detect_duplicates` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `currency` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'EUR\',
  `last_userpop3_cron` int(11) NOT NULL DEFAULT 0,
  `charge_min_amount` int(11) NOT NULL DEFAULT 150,
  `f_mail2sms_nummer` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'v\',
  `ip_lock` enum(\'yes\',\'no\') DEFAULT \'no\',
  `cookie_lock` enum(\'yes\',\'no\') DEFAULT \'yes\',
  `user_count_limit` int(11) NOT NULL DEFAULT 0,
  `allow_newsletter_optout` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `ca_cert_pk_pass` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ca_cert_pk` text NOT NULL,
  `ca_cert` text NOT NULL,
  `sms_enable_su` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `sms_su_kdnr` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_su_prjnr` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_su_prjpass` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `last_storetime_cron` int(11) NOT NULL DEFAULT 0,
  `domain_combobox` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `reg_smsvalidation` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `ssl_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ssl_login_option` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `ssl_login_enable` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `smsvalidation_type` int(11) NOT NULL DEFAULT 0,
  `db_is_utf8` tinyint(4) NOT NULL DEFAULT 0,
  `reg_validation` enum(\'off\',\'sms\',\'email\') NOT NULL DEFAULT \'off\',
  `selffolder` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auto_tz` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `taborder` text,
  `returnpath_check` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `check_double_altmail` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `check_double_cellphone` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `f_anrede` enum(\'p\',\'v\',\'n\') NOT NULL DEFAULT \'n\',
  `send_pay_notification` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `pay_notification_to` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pay_emailfrom` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pay_emailfromemail` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mwst` enum(\'add\',\'enthalten\',\'nomwst\') NOT NULL DEFAULT \'enthalten\',
  `enable_paypal` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `paypal_mail` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `enable_su` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `su_kdnr` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `su_prjnr` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `su_prjpass` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `su_inputcheck` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `enable_vk` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `vk_kto_inh` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vk_kto_nr` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vk_kto_blz` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vk_kto_inst` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vk_kto_iban` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vk_kto_bic` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sendrg` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `rgnrfmt` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'RG4510?\',
  `kdnrfmt` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'KD2150?\',
  `rgtemplate` text NOT NULL,
  `sms_enable_charge` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `su_notifypass` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `default_paymethod` tinyint(4) NOT NULL DEFAULT 1,
  `flexspans` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `locked_altmail` text NOT NULL,
  `redirect_mobile` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `mobile_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reg_validation_min_resend_interval` int(11) NOT NULL DEFAULT 300,
  `reg_validation_max_resend_times` int(11) NOT NULL DEFAULT 5,
  `abuse_soft_border` tinyint(4) NOT NULL DEFAULT 35,
  `abuse_hard_border` tinyint(4) NOT NULL DEFAULT 75,
  `abuse_hard_lock` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `abuse_reset_method` enum(\'fixed\',\'dynamic\') NOT NULL DEFAULT \'dynamic\',
  `abuse_reset_interval` int(11) NOT NULL DEFAULT 86400,
  `calendar_defaultviewmode` enum(\'day\',\'week\',\'month\') NOT NULL DEFAULT \'day\',
  `logs_autodelete` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `logs_autodelete_days` int(11) NOT NULL DEFAULT 31,
  `logs_autodelete_archive` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `logs_autodelete_last` int(11) NOT NULL DEFAULT 0,
  `hotkeys_default` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `compress_pages` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `enable_skrill` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `skrill_mail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `skrill_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `signup_dnsbl_enable` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `signup_dnsbl` text NOT NULL,
  `signup_dnsbl_action` enum(\'lock\',\'block\') DEFAULT \'block\',
  `mail2sms_type` int(11) NOT NULL DEFAULT 0,
  `clndr_sms_type` int(11) NOT NULL DEFAULT 0,
  `ap_medium_limit` int(11) NOT NULL DEFAULT 50,
  `ap_hard_limit` int(11) NOT NULL DEFAULT 100,
  `ap_expire_mode` enum(\'static\',\'dynamic\') NOT NULL DEFAULT \'dynamic\',
  `ap_expire_time` int(11) NOT NULL DEFAULT 86400,
  `ap_expire_last_run` int(11) NOT NULL DEFAULT 0,
  `ap_autolock` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `ap_autolock_notify` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `ap_autolock_notify_to` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `safecode_provider` enum(\'builtin\',\'recaptcha\') NOT NULL DEFAULT \'builtin\',
  `recaptcha_key_pub` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `recaptcha_key_priv` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ssl_signup_enable` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `signup_suggestions` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `fts_bg_indexing` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `write_xsenderip` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `captcha_provider` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'default.php/BMCaptchaProvider_Default\',
  `captcha_config` text NOT NULL,
  `contactform` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `contactform_name` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `contactform_to` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `contactform_subject` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `blobstorage_provider` tinyint(4) NOT NULL DEFAULT 0,
  `blobstorage_provider_webdisk` tinyint(4) NOT NULL DEFAULT 0,
  `blobstorage_compress` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `blobstorage_webdisk_compress` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `notify_interval` int(11) NOT NULL DEFAULT 30,
  `notify_lifetime` int(11) NOT NULL DEFAULT 14,
  `nosignup_autodel` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `nosignup_autodel_days` int(11) NOT NULL DEFAULT 7,
  `min_pass_length` tinyint(4) NOT NULL DEFAULT 8,
  `min_draft_save_interval` int(11) NOT NULL DEFAULT 15,
  `mail_groupmode` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'-\',
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_profilfelder(
  `id` int(11) NOT NULL auto_increment,
  `feld` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `typ` tinyint(4),
  `pflicht` enum(\'yes\',\'no\'),
  `rule` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `extra` text,
  `show_signup` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `show_li` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_recvrules(
  `id` int(11) NOT NULL auto_increment,
  `field` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `expression` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `action` tinyint(4) NOT NULL DEFAULT 0,
  `value` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_recvstats(
  `recvstatid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `size` int(11) NOT NULL DEFAULT 0,
  `time` int(14) NOT NULL DEFAULT 0,
  PRIMARY KEY (`recvstatid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_safecode(
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `generation` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_savedlogins(
  `id` int(11) NOT NULL auto_increment,
  `expires` int(11) NOT NULL DEFAULT 0,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_sendstats(
  `sendstatid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `recipients` int(11) NOT NULL DEFAULT 1,
  `time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sendstatid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_signaturen(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11),
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `text` text,
  `html` text NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_smsend(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `monat` int(5) NOT NULL,
  `price` tinyint(255) NOT NULL DEFAULT 1,
  `isSMS` tinyint(4) NOT NULL DEFAULT 0,
  `from` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `to` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `text` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `statusid` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_smsgateways(
  `id` int(11) NOT NULL auto_increment,
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `getstring` text,
  `success` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_smstypen(
  `id` int(11) NOT NULL auto_increment,
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `typ` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `std` tinyint(4) NOT NULL DEFAULT 0,
  `price` int(11) DEFAULT 1,
  `gateway` int(11) NOT NULL DEFAULT 0,
  `flags` tinyint(4) NOT NULL DEFAULT 0,
  `maxlength` int(11) NOT NULL DEFAULT 160,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_spamindex(
  `hash` varchar(34) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userid` int(11) NOT NULL DEFAULT 0,
  `inspam` int(11) NOT NULL,
  `innonspam` int(11) NOT NULL,
  PRIMARY KEY (`hash`,`userid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_staaten(
  `id` int(4) NOT NULL auto_increment,
  `land` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `is_eu` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `vat` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_stats(
  `id` int(11) NOT NULL auto_increment,
  `d` int(4),
  `m` int(4),
  `y` int(8),
  `typ` enum(\'login\',\'signup\',\'receive\',\'send\',\'sms\',\'infected\',\'spam\',\'send_intern\',\'send_extern\',\'wd_down\',\'wd_up\',\'sysmail\',\'mobile_login\'),
  `anzahl` int(14),
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_sync(
  `syncid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `clientid` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `clientanchor` int(11) NOT NULL DEFAULT 0,
  `serveranchor` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`syncid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_tasklists(
  `tasklistid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uri` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`tasklistid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_tasks(
  `id` int(11) NOT NULL auto_increment,
  `user` int(11),
  `tasklistid` int(11) NOT NULL DEFAULT 0,
  `beginn` int(14),
  `faellig` int(14),
  `akt_status` int(3),
  `titel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  `priority` enum(\'low\',\'normal\',\'high\'),
  `erledigt` int(3),
  `comments` text,
  `dav_uri` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dav_uid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tasklistid`(`tasklistid`),
  KEY `user`(`user`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_tbx_versions(
  `versionid` int(11) NOT NULL auto_increment,
  `base_version` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum(\'created\',\'released\') NOT NULL DEFAULT \'created\',
  `create_date` int(11) NOT NULL DEFAULT 0,
  `release_date` int(11) NOT NULL DEFAULT 0,
  `config` longtext NOT NULL,
  `release_files` text NOT NULL,
  PRIMARY KEY (`versionid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_tempfiles(
  `id` int(11) NOT NULL auto_increment,
  `expires` int(11) NOT NULL DEFAULT 0,
  `user` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_templateprefs(
  `template` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `key` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`template`,`key`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_texts(
  `language` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `key` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`language`,`key`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_transactions(
  `transactionid` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL DEFAULT 0,
  `description` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `date` int(14) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`transactionid`),
  KEY `userid`(`userid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_uidindex(
  `pop3` int(11) NOT NULL DEFAULT 0,
  `uid` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`pop3`,`uid`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_userprefs(
  `userid` int(11) NOT NULL,
  `key` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text,
  PRIMARY KEY (`userid`,`key`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_users(
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `vorname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `nachname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `strasse` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `hnr` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `plz` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ort` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `land` int(4) NOT NULL,
  `tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fax` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `altmail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `passwort` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `gruppe` int(11) NOT NULL DEFAULT 1,
  `locked` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `re` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'Re:\',
  `fwd` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'Fwd:\',
  `mail2sms` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `mail2sms_nummer` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `c_firstday` int(1) NOT NULL DEFAULT 1,
  `in_refresh` int(11) NOT NULL DEFAULT 120,
  `forward` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `forward_to` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `forward_delete` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `gesperrt` enum(\'yes\',\'no\',\'delete\',\'locked\') NOT NULL DEFAULT \'no\',
  `last_notify` int(14) NOT NULL DEFAULT 0,
  `ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastlogin` int(14) NOT NULL DEFAULT 0,
  `spamaction` int(11) NOT NULL DEFAULT -4,
  `last_send` int(14) NOT NULL DEFAULT 0,
  `pw_reset_new` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pw_reset_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_kontigent` int(11) NOT NULL DEFAULT 0,
  `last_pop3` int(14) NOT NULL DEFAULT 0,
  `mta_sentmails` int(11) NOT NULL DEFAULT 0,
  `traffic_down` bigint(20) unsigned NOT NULL DEFAULT 0,
  `traffic_up` bigint(20) unsigned NOT NULL DEFAULT 0,
  `traffic_status` int(11) NOT NULL DEFAULT 0,
  `datumsformat` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'d.m.Y H:i:s\',
  `absendername` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reg_ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'n/a\',
  `reg_date` int(14) NOT NULL DEFAULT 0,
  `soforthtml` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `profilfelder` text NOT NULL,
  `last_login_attempt` int(11) NOT NULL DEFAULT 0,
  `last_imap` int(11) NOT NULL DEFAULT 0,
  `last_forward` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0,
  `mailspace_used` bigint(20) unsigned NOT NULL DEFAULT 0,
  `defaultSender` int(11) NOT NULL DEFAULT 1,
  `diskspace_used` bigint(20) unsigned NOT NULL DEFAULT 0,
  `notes` text NOT NULL,
  `received_mails` int(11) NOT NULL DEFAULT 0,
  `sent_mails` int(11) NOT NULL DEFAULT 0,
  `bayes_spam` int(11) NOT NULL DEFAULT 0,
  `bayes_nonspam` int(11) NOT NULL DEFAULT 0,
  `spamfilter` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `unspamme` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `charset` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'iso-8859-1\',
  `language` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'deutsch\',
  `preferred_language` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `workday_start` tinyint(4) NOT NULL DEFAULT 12,
  `workday_end` tinyint(4) NOT NULL DEFAULT 36,
  `preview` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `contactHistory` longtext NOT NULL,
  `virusfilter` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `virusaction` int(11) NOT NULL DEFAULT -256,
  `uid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `conversation_view` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `today_sent` int(11) NOT NULL DEFAULT 0,
  `today_key` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0,
  `mailbox_generation` int(11) NOT NULL DEFAULT 0,
  `mailbox_structure_generation` int(11) NOT NULL DEFAULT 0,
  `newsletter_optin` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `plaintext_courier` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `bayes_border` tinyint(4) NOT NULL DEFAULT 90,
  `sms_validation_code` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_validation_time` int(11) NOT NULL DEFAULT 0,
  `sms_validation` int(11) NOT NULL DEFAULT 0,
  `reply_quote` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `anrede` enum(\'\',\'herr\',\'frau\') NOT NULL,
  `last_smtp` int(11) NOT NULL DEFAULT 0,
  `attcheck` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `saliase` text NOT NULL,
  `storage_node` int(11) NOT NULL DEFAULT 0,
  `storage_status` tinyint(4) NOT NULL DEFAULT 0,
  `addressbook_nospam` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `passwort_salt` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sms_validation_last_send` int(11) NOT NULL DEFAULT 0,
  `sms_validation_send_times` int(11) NOT NULL DEFAULT 0,
  `search_details_default` enum(\'yes\',\'no\') NOT NULL DEFAULT \'no\',
  `notify_sound` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `notify_email` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `notify_birthday` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `mailspace_add` bigint(20) NOT NULL DEFAULT 0,
  `diskspace_add` bigint(20) DEFAULT 0,
  `traffic_add` bigint(20) NOT NULL DEFAULT 0,
  `last_timezone` int(11) NOT NULL DEFAULT 0,
  `auto_save_drafts` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `auto_save_drafts_interval` int(11) NOT NULL DEFAULT 30,
  PRIMARY KEY (`id`),
  KEY `email`(`email`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_workgroups(
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `addressbook` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `calendar` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `webdisk` bigint(20) NOT NULL DEFAULT 15728640,
  `todo` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  `notes` enum(\'yes\',\'no\') NOT NULL DEFAULT \'yes\',
  PRIMARY KEY (`id`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_workgroups_member(
  `workgroup` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`workgroup`,`user`)
 )';
$initialDbStruct[] = 'CREATE TABLE bm60_workgroups_shares(
  `workgroupid` int(11) NOT NULL DEFAULT 0,
  `sharetype` tinyint(4) NOT NULL DEFAULT 0,
  `shareid` int(11) NOT NULL DEFAULT 0,
  `writeaccess` tinyint(4) NOT NULL DEFAULT 0,
  `space_used` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`workgroupid`,`sharetype`,`shareid`)
 )';

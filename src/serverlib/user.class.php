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
 * user class
 */
class BMUser {
    var $_id;
    var $_row;

    /**
     * constructor
     *
     * @param int $id User ID
     * @return BMUser
     */
    function __construct($id) {
        $this->_id = $id;
        $this->_row = $this->Fetch();
    }

    /**
     * get user's group
     *
     * @return BMGroup
     */
    function GetGroup() {
        return _new('BMGroup', [$this->_row['gruppe']]);
    }

    /**
     * check if user is allowed to send email (check send limit)
     *
     * @param $recipientCount Number of recipients
     * @return bool
     */
    function MaySendMail($recipientCount) {
        global $db;

        $group = $this->GetGroup();
        $groupRow = $group->Fetch();

        if ($recipientCount < 1) {
            return false;
        }

        if (
            $groupRow['send_limit_count'] <= 0 ||
            $groupRow['send_limit_time'] <= 0
        ) {
            return true;
        }

        if ($recipientCount > $groupRow['send_limit_count']) {
            return false;
        }

        $res = $db->Query(
            'SELECT SUM(`recipients`) FROM {pre}sendstats WHERE `userid`=? AND `time`>=?',
            $this->_id,
            time() - 60 * $groupRow['send_limit_time'],
        );
        $row = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        $count = (int) $row[0];

        return $count + $recipientCount <= $groupRow['send_limit_count'];
    }

    /**
     * add email to send stats (for send limit)
     *
     * @param $recipientCount Number of recipients
     */
    function AddSendStat($recipientCount) {
        global $db;

        $db->Query(
            'INSERT INTO {pre}sendstats(`userid`,`recipients`,`time`) VALUES(?,?,?)',
            $this->_id,
            max(1, $recipientCount),
            time(),
        );
    }

    /**
     * add email to receive stats (for incoming limits)
     *
     * @param int $size Size of email
     */
    function AddRecvStat($size) {
        global $db;

        $db->Query(
            'INSERT INTO {pre}recvstats(`userid`,`size`,`time`) VALUES(?,?,?)',
            $this->_id,
            $size,
            time(),
        );
    }

    /**
     * Get count of received mails since a certain time.
     *
     * @param int $since Start time
     * @return int Count
     */
    function GetReceivedMailsCount($since) {
        global $db;

        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}recvstats WHERE `userid`=? AND `time`>=?',
            $this->_id,
            $since,
        );
        [$result] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        return (int) $result;
    }

    /**
     * Get size of received mails since a certain time.
     *
     * @param int $since Start time
     * @return int Size in bytes
     */
    function GetReceivedMailsSize($since) {
        global $db;

        $res = $db->Query(
            'SELECT SUM(`size`) FROM {pre}recvstats WHERE `userid`=? AND `time`>=?',
            $this->_id,
            $since,
        );
        [$result] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        return (int) $result;
    }

    /**
     * get unread notifications count
     *
     * @return int
     */
    function GetUnreadNotifications() {
        global $db;

        $result = 0;

        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}notifications WHERE `userid`=? AND `read`=0 AND (`expires`=0 OR `expires`<?)',
            $this->_id,
            time(),
            time(),
        );
        while ($row = $res->FetchArray(MYSQLI_NUM)) {
            $result = (int) $row[0];
        }
        $res->Free();

        return $result;
    }

    /**
     * get latest notifications
     *
     * @param bool $markRead Whether to mark all notifications as read after fetching them
     * @return array
     */
    function GetNotifications($markRead = true) {
        global $db, $tpl, $lang_custom;

        $result = [];

        $res = $db->Query(
            'SELECT `notificationid`,`date`,`read`,`flags`,`text_phrase`,`text_params`,`link`,`icon` FROM {pre}notifications WHERE `userid`=? AND (`expires`=0 OR `expires`<?) ORDER BY `notificationid` DESC LIMIT ' .
                NOTIFICATION_LIMIT,
            $this->_id,
            time(),
            time(),
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            switch ($row['icon']) {
                case '%%tpldir%%images/li/notify_newemail.png':
                    $row['faIcon'] = str_replace(
                        '%%tpldir%%images/li/notify_newemail.png',
                        'fa-envelope-square',
                        $row['icon'],
                    );
                    break;
                case '%%tpldir%%images/li/notify_email.png':
                    $row['faIcon'] = str_replace(
                        '%%tpldir%%images/li/notify_email.png',
                        'fa-envelope-o',
                        $row['icon'],
                    );
                    break;
                case '%%tpldir%%images/li/notify_birthday.png':
                    $row['faIcon'] = str_replace(
                        '%%tpldir%%images/li/notify_birthday.png',
                        'fa-birthday-cake',
                        $row['icon'],
                    );
                    break;
                case '%%tpldir%%images/li/notify_calendar.png':
                    $row['faIcon'] = str_replace(
                        '%%tpldir%%images/li/notify_calendar.png',
                        'fa-calendar',
                        $row['icon'],
                    );
                    break;
            }
            $row['icon'] = str_replace(
                '%%tpldir%%',
                $tpl->tplDir,
                $row['icon'],
            );

            if (($row['flags'] & NOTIFICATION_FLAG_USELANG) != 0) {
                $row['text_phrase'] = $lang_custom[$row['text_phrase']];
            }

            $row['text'] = vsprintf(
                $row['text_phrase'],
                ExplodeOutsideOfQuotation($row['text_params'], ','),
            );

            $row['old'] = $row['read'] && $row['date'] < mktime(0, 0, 0);

            $result[] = $row;
        }
        $res->Free();

        if ($markRead) {
            $db->Query(
                'UPDATE {pre}notifications SET `read`=1 WHERE `userid`=? AND (`expires`=0 OR `expires`<?)',
                $this->_id,
                time(),
                time(),
            );
        }

        return $result;
    }

    /**
     * post a new notification
     *
     * @param string $textPhrase Text phrase or key in $lang_custom array when used with NOTIFICATION_FLAG_USELANG
     * @param array $textParams Parameters array for format string
     * @param string $link Notification link
     * @param string $icon Icon path (can use %%tpldir%% variable)
     * @param int $date Notification date (0 = now)
     * @param int $expires Expiration date (0 = never)
     * @param int $flags Flags
     * @param string $class Unique name of notification class (optional)
     * @param bool $uniqueClass Set to true to remove all previous notifications of the same class
     * @return int Notification ID
     */
    function PostNotification(
        $textPhrase,
        $textParams = [],
        $link = '',
        $icon = '',
        $date = 0,
        $expires = 0,
        $flags = 0,
        $class = '',
        $uniqueClass = false
    ) {
        global $db;

        if ($date == 0) {
            $date = time();
        }

        if (count($textParams)) {
            $textParams =
                '"' .
                implode('","', array_map('addslashes', $textParams)) .
                '"';
        } else {
            $textParams = '';
        }

        if ($uniqueClass && !empty($class)) {
            $db->Query(
                'DELETE FROM {pre}notifications WHERE `userid`=? AND `class`=?',
                $this->_id,
                $class,
            );
        }

        $db->Query(
            'INSERT INTO {pre}notifications(`userid`,`date`,`expires`,`flags`,`text_phrase`,`text_params`,`link`,`icon`,`class`) ' .
                'VALUES(?,?,?,?,?,?,?,?,?)',
            $this->_id,
            $date,
            $expires,
            $flags,
            $textPhrase,
            $textParams,
            $link,
            $icon,
            $class,
        );
        return $db->InsertId();
    }

    /**
     * update bayes training values for user
     *
     * @param int $nonSpam Count of NON spam mails
     * @param int $spam Count of spam mails
     * @param int $userID User ID
     * @return bool
     */
    static function UpdateBayesValues($nonSpam, $spam, $userID) {
        global $db;

        $db->Query(
            'UPDATE {pre}users SET bayes_nonspam=?, bayes_spam=? WHERE id=?',
            $nonSpam,
            $spam,
            $userID,
        );

        return $db->AffectedRows() == 1;
    }

    /**
     * get bayes training values for an user
     *
     * @param int $userID
     * @return array bayes_nonspam, bayes_spam, bayes_border (%)
     */
    static function GetBayesValues($userID) {
        global $db;

        $res = $db->Query(
            'SELECT bayes_nonspam,bayes_spam,bayes_border FROM {pre}users WHERE id=?',
            $userID,
        );
        if ($res->RowCount() == 1) {
            $ret = $res->FetchArray(MYSQLI_NUM);
            $res->Free();
        } else {
            $ret = [0, 0, 90];
        }

        return $ret;
    }

    /**
     * set preference
     *
     * @param string $key Key
     * @param string $value Value
     * @return bool
     */
    function SetPref($key, $value) {
        global $db;
        $db->Query(
            'REPLACE INTO {pre}userprefs(userID, `key`,`value`) VALUES(?, ?, ?)',
            (int) $this->_id,
            $key,
            $value,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * get preference
     *
     * @param string $key Key
     * @return string
     */
    function GetPref($key) {
        global $db;
        $res = $db->Query(
            'SELECT `value` FROM {pre}userprefs WHERE userID=? AND `key`=?',
            (int) $this->_id,
            $key,
        );
        if ($res->RowCount() == 1) {
            $row = $res->FetchArray(MYSQLI_NUM);
            $res->Free();
            return $row[0];
        } else {
            $res->Free();
            return false;
        }
    }

    /**
     * delete preference
     *
     * @param string $key Key
     * @return bool
     */
    function DeletePref($key) {
        global $db;

        $db->Query(
            'DELETE FROM {pre}userprefs WHERE userID=? AND `key`=?',
            (int) $this->_id,
            $key,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * check if address is locked
     *
     * @param string $userm
     * @return bool
     */
    static function AddressLocked($userm) {
        global $db;

        $userm = strtolower($userm);
        $locked = false;
        $res = $db->Query('SELECT * FROM {pre}locked');
        while ($row = $res->FetchObject()) {
            $laenge = strlen($row->benutzername);
            $row->benutzername = strtolower($row->benutzername);

            if (
                $row->typ == 'start' &&
                preg_match('/^' . preg_quote($row->benutzername) . '/i', $userm)
            ) {
                $locked = true;
            } elseif (
                $row->typ == 'ende' &&
                preg_match('/' . preg_quote($row->benutzername) . '$/i', $userm)
            ) {
                $locked = true;
            } elseif (
                $row->typ == 'mitte' &&
                strstr($userm, $row->benutzername) !== false
            ) {
                $locked = true;
            } elseif ($row->typ == 'gleich' && $row->benutzername == $userm) {
                $locked = true;
            }
        }
        $res->Free();

        if (!$locked) {
            $res = $db->Query(
                'SELECT COUNT(*) FROM {pre}workgroups WHERE email=?',
                $userm,
            );
            [$wgCount] = $res->FetchArray(MYSQLI_NUM);
            $res->Free();

            if ($wgCount > 0) {
                $locked = true;
            }
        }

        return $locked;
    }

    /**
     * check address availability
     *
     * @param string $address
     * @return bool
     */
    static function AddressAvailable($address) {
        global $db;

        if (BMUser::GetID($address) != 0) {
            return false;
        }

        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}workgroups WHERE `email`=?',
            $address,
        );
        [$wgCount] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        return $wgCount == 0;
    }

    /**
     * check address validity
     *
     * @param string $address
     * @return bool
     */
    static function AddressValid($address, $forRegistration = true) {
        @[$preAt, $afterAt] = explode('@', $address);
        if (
            preg_match(
                '/^[a-zA-Z0-9&\'\\.\\-_\\+]+@[a-zA-Z0-9.-]+\\.+[a-zA-Z]{2,12}$/',
                $address,
            ) == 1
        ) {
            if (
                $forRegistration &&
                (substr($preAt, -1) == '.' ||
                    substr($preAt, -1) == '_' ||
                    substr($preAt, -1) == '-' ||
                    substr($preAt, 0, 1) == '.' ||
                    substr($preAt, 0, 1) == '_' ||
                    substr($preAt, 0, 1) == '-' ||
                    strpos($preAt, '..') !== false)
            ) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * update last send timestamp and recipient count
     *
     * @param int $recipientCount Recipient count
     * @return bool
     */
    function UpdateLastSend($recipientCount = 1) {
        global $db;
        $db->Query(
            'UPDATE {pre}users SET last_send=?,sent_mails=sent_mails+? WHERE id=?',
            time(),
            (int) $recipientCount,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * update receive count
     *
     * @param int $mailCount Mail count
     * @return bool
     */
    function UpdateLastReceive($mailCount = 1) {
        global $db;
        $db->Query(
            'UPDATE {pre}users SET received_mails=received_mails+? WHERE id=?',
            (int) $mailCount,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * password reset / activation
     *
     * @param int $userID User ID
     * @param string $resetKey Reset key
     */
    function ResetPassword($userID, $resetKey) {
        global $db;

        $result = false;

        // prepare variables
        $userID = (int) $userID;
        $resetKey = trim($resetKey);

        // do not accept empty keys
        if (strlen($resetKey) == 32 && $userID > 0) {
            // check key, activate password
            $db->Query(
                'UPDATE {pre}users SET passwort=pw_reset_new,pw_reset_new=?,pw_reset_key=? WHERE id=? AND LENGTH(pw_reset_new)=32 AND LENGTH(pw_reset_key)=32 AND pw_reset_key=?',
                '',
                '',
                $userID,
                $resetKey,
            );
            $result = $db->AffectedRows() == 1;
        }

        // log & return
        if ($result) {
            // log
            PutLog(
                sprintf(
                    'Password reset for user <%d> confirmed (key: %s, IP: %s)',
                    $userID,
                    $resetKey,
                    $_SERVER['REMOTE_ADDR'],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
            return true;
        } else {
            // log
            PutLog(
                sprintf(
                    'Password reset for user <%d> failed (key: %s, IP: %s)',
                    $userID,
                    $resetKey,
                    $_SERVER['REMOTE_ADDR'],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
            return false;
        }
    }

    /**
     * password reset request
     *
     * @param string $email User's E-Mail address
     * @return bool
     */
    static function LostPassword($email) {
        global $db, $bm_prefs, $lang_custom;

        // user ID?
        $userID = BMUser::GetID($email, true);
        if ($userID > 0) {
            // get alt. mail address
            $res = $db->Query(
                'SELECT altmail,vorname,nachname,anrede,passwort_salt FROM {pre}users WHERE id=?',
                $userID,
            );
            [
                $altMail,
                $firstName,
                $lastName,
                $salutation,
                $salt,
            ] = $res->FetchArray(MYSQLI_NUM);
            $res->Free();

            // extract mail address
            $altMail = ExtractMailAddress($altMail);

            // alt mail specified?
            if (strlen(trim($altMail)) > 5) {
                // generate new password
                $pwResetNew = '';
                for ($i = 0; $i < PASSWORD_LENGTH; $i++) {
                    $pwResetNew .= substr(
                        PASSWORD_CHARS,
                        mt_rand(0, strlen(PASSWORD_CHARS) - 1),
                        1,
                    );
                }

                // generate key
                $pwResetKey = GenerateRandomKey('pwResetKey');

                // update row
                $db->Query(
                    'UPDATE {pre}users SET pw_reset_new=?,pw_reset_key=? WHERE id=?',
                    md5(md5($pwResetNew) . $salt),
                    $pwResetKey,
                    $userID,
                );

                // link
                $vars = [
                    'mail' => DecodeEMail($email),
                    'anrede' => ucfirst($salutation),
                    'vorname' => $firstName,
                    'nachname' => $lastName,
                    'passwort' => $pwResetNew,
                    'link' => sprintf(
                        '%sindex.php?action=resetPassword&user=%d&key=%s',
                        $bm_prefs['selfurl'],
                        $userID,
                        $pwResetKey,
                    ),
                ];
                if (
                    SystemMail(
                        $bm_prefs['passmail_abs'],
                        $altMail,
                        $lang_custom['passmail_sub'],
                        'passmail_text',
                        $vars,
                    )
                ) {
                    // log
                    PutLog(
                        sprintf(
                            'User <%s> (%d) requested password reset (IP: %s)',
                            $email,
                            $userID,
                            $_SERVER['REMOTE_ADDR'],
                        ),
                        PRIO_NOTE,
                        __FILE__,
                        __LINE__,
                    );
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Create a new user account.
     *
     * This method returns the User ID if the account creation was successful.
     * This method returns false if something went wrong.
     */
    static function CreateAccount(
        $email,
        $firstname,
        $surname,
        $street,
        $no,
        $zip,
        $city,
        $country,
        $phone,
        $fax,
        $altmail,
        $password,
        $profilefields = [],
        $salutation = ''
    ) {
        global $db, $bm_prefs, $currentCharset, $currentLanguage, $lang_custom;

        // serialize profile fields
        if (!is_array($profilefields)) {
            $profilefields = [];
        }
        $profilefields = serialize($profilefields);

        // check if user already exists and if address is valid
        if (
            !BMUser::AddressAvailable($email) ||
            !BMUser::AddressValid($email)
        ) {
            return false;
        }
        $defaultGroupRow = BMGroup::FetchGroupById($bm_prefs['std_gruppe']);
        $instantHTML = $defaultGroupRow['soforthtml'];

        // create salt
        $salt = GenerateRandomSalt(8);

        // create account
        $db->Query(
            'INSERT INTO {pre}users(email,vorname,nachname,strasse,hnr,plz,ort,land,tel,fax,altmail,passwort,passwort_salt,gruppe,gesperrt,c_firstday,lastlogin,reg_ip,reg_date,profilfelder,datumsformat,charset,language,soforthtml,anrede,preview) ' .
                'VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
            $email, // email
            $firstname, // vorname
            $surname, // nachname
            $street, // strasse
            $no, // hnr
            $zip, // plz
            $city, // ort
            $country, // land
            $phone, // tel
            $fax, // fax
            $altmail, // altmail
            md5(md5($password) . $salt), // passwort
            $salt, // passwort_salt
            $bm_prefs['std_gruppe'], // gruppe
            'no', // gesperrt
            1, // c_firstday
            0, // lastlogin
            $_SERVER['REMOTE_ADDR'], // reg_ip
            time(), // reg_date
            $profilefields, // profilfelder
            $bm_prefs['datumsformat'], // datumsformat
            $currentCharset, // charset
            $currentLanguage, // language
            $instantHTML, // soforthtml
            $salutation, // anrede
            'yes', // preview
        );
        $uid = $db->InsertId();

        // prefs
        if ($bm_prefs['hotkeys_default'] == 'yes') {
            $db->Query(
                'INSERT INTO {pre}userprefs(`userid`,`key`,`value`) VALUES(?,?,?)',
                $uid,
                'hotkeys',
                1,
            );
        }

        // send welcome mail
        if ($bm_prefs['welcome_mail'] == 'yes') {
            [, $userDomain] = explode('@', $email);
            $countryList = CountryList();
            $countryName = $countryList[$country];
            $vars = [
                'datum' => FormatDate(),
                'email' => DecodeEMail($email),
                'domain' => $userDomain,
                'anrede' => ucfirst($salutation),
                'vorname' => $firstname,
                'nachname' => $surname,
                'strasse' => $street . ' ' . $no,
                'plzort' => $zip . ' ' . $city,
                'land' => $countryName . ' (#' . $country . ')',
                'tel' => $phone,
                'fax' => $fax,
                'altmail' => $altmail,
            ];
            SystemMail(
                $bm_prefs['passmail_abs'],
                $email,
                $lang_custom['welcome_sub'],
                'welcome_text',
                $vars,
            );
        }

        // module handler
        ModuleFunction('OnSignup', [$uid, $email]);

        // log
        PutLog(
            sprintf('User <%s> (%d) created', $email, $uid),
            PRIO_NOTE,
            __FILE__,
            __LINE__,
        );

        return $uid;
    }

    /**
     * get id for user identified by e-mail address
     *
     * @param string $email
     * @param bool $excludeDeleted
     * @param bool $isAlias Output indicating if $email is an alias
     * @return int
     */
    static function GetID($email, $excludeDeleted = false, &$isAlias = null) {
        global $db;
        $userID = 0;

        // look in user-table
        $res = $db->Query(
            'SELECT id FROM {pre}users WHERE email=? ' .
                ($excludeDeleted ? 'AND gesperrt!=\'delete\' ' : '') .
                'LIMIT 1',
            $email,
        );
        if ($res->RowCount() == 1) {
            [$userID] = $res->FetchArray(MYSQLI_NUM);
        }
        $res->Free();

        // not in user-table -> alias?
        if ($userID == 0) {
            $res = $db->Query(
                'SELECT {pre}users.id AS id FROM {pre}users,{pre}aliase WHERE {pre}aliase.email=? AND ({pre}aliase.type&' .
                    ALIAS_RECIPIENT .
                    ')!=0 AND {pre}users.id={pre}aliase.user ' .
                    ($excludeDeleted
                        ? 'AND {pre}users.gesperrt!=\'delete\' '
                        : '') .
                    'LIMIT 1',
                $email,
            );
            if ($res->RowCount() == 1) {
                [$userID] = $res->FetchArray(MYSQLI_NUM);
                $isAlias = true;
            }
            $res->Free();
        } else {
            $isAlias = false;
        }

        // return ID
        return $userID;
    }

    /**
     * plugin auth helper
     *
     * @param string $email
     * @param string $password
     * @return mixed false or array
     */
    static function _pluginAuth($email, $passwordMD5, $passwordPlain) {
        global $plugins;

        // prepare variables
        $userParts = explode('@', trim($email));
        $userName = isset($userParts[0]) ? $userParts[0] : '';
        $userDomain = isset($userParts[1]) ? $userParts[1] : '';

        // search for an auth handler
        foreach ($plugins->_plugins as $className => $pluginInfo) {
            if (
                ($result = $plugins->callFunction(
                    'OnAuthenticate',
                    $className,
                    false,
                    [$userName, $userDomain, $passwordMD5, $passwordPlain],
                )) !== false &&
                is_array($result)
            ) {
                return $result;
            }
        }

        // no auth handler useful
        return false;
    }

    /**
     * login a user
     *
     * @param string $email E-Mail
     * @param string $passwordPlain Password (PLAIN)
     * @param bool $createSession Create session?
     * @param bool $successLog Log successful logins?
     * @return string Session-ID
     */
    static function Login(
        $email,
        $passwordPlain,
        $createSession = true,
        $successLog = true,
        $skipSalting = false
    ) {
        global $db, $currentCharset, $currentLanguage, $bm_prefs;

        $passwordPlain = CharsetDecode($passwordPlain, false, 'ISO-8859-15');
        $result = [USER_DOES_NOT_EXIST, false];
        $row = false;
        $userID = 0;
        $password = LooksLikeMD5Hash($passwordPlain)
            ? $passwordPlain
            : md5($passwordPlain);

        // try plugin authentication first
        $pluginAuth = BMUSer::_pluginAuth($email, $password, $passwordPlain);

        // no plugin auth
        if (!is_array($pluginAuth)) {
            // get user ID
            $userID = BMUser::GetID($email);
            $res = $db->Query(
                'SELECT id,gesperrt,passwort,passwort_salt,email,last_login_attempt,ip,lastlogin,preferred_language,last_timezone FROM {pre}users WHERE id=? LIMIT 1',
                $userID,
            );
            $row = $res->FetchArray();
            $res->Free();
        }

        // plugin auth
        else {
            // find user
            $res = $db->Query(
                'SELECT id,gesperrt,passwort,passwort_salt,email,last_login_attempt,ip,lastlogin,preferred_language,last_timezone FROM {pre}users WHERE uid=? LIMIT 1',
                $pluginAuth['uid'],
            );
            if ($res->RowCount() == 1) {
                $row = $res->FetchArray();
                $res->Free();

                // vars
                $row['passwort'] = md5($password . $row['passwort_salt']);
                $userID = $row['id'];

                // update profile
                if (isset($pluginAuth['profile']) && $row['gesperrt'] == 'no') {
                    $theOldUserRow = $theUserRow = BMUser::Fetch($row['id']);

                    $theUserRow['passwort'] = md5(
                        $password . $row['passwort_salt'],
                    );
                    foreach ($pluginAuth['profile'] as $key => $val) {
                        $theUserRow[$key] = $val;
                    }

                    if ($theOldUserRow != $theUserRow) {
                        BMUser::UpdateContactData(
                            $theUserRow,
                            false,
                            true,
                            $userID,
                        );
                    }
                }
            }
        }

        if (isset($row) && $userID > 0) {
            $adminAuthOK = false;
            if (isset($_REQUEST['adminAuth'])) {
                $adminAuth = @explode(
                    ',',
                    @base64_decode($_REQUEST['adminAuth']),
                );

                if (
                    is_array($adminAuth) &&
                    count($adminAuth) == 3 &&
                    $adminAuth[0] == $userID
                ) {
                    $ares = $db->Query(
                        'SELECT * FROM {pre}admins WHERE `adminid`=?',
                        $adminAuth[1],
                    );
                    while ($arow = $ares->FetchArray(MYSQLI_ASSOC)) {
                        $adminPrivs = @unserialize($arow['privileges']);
                        if (!is_array($adminPrivs)) {
                            $adminPrivs = [];
                        }
                        if (
                            $arow['type'] != 0 &&
                            !in_array('users', $adminPrivs)
                        ) {
                            continue;
                        }

                        $correctToken = md5(
                            sprintf('%d,%d', $userID, $adminAuth[1]) .
                                md5(
                                    $arow['password'] .
                                        $_SERVER['HTTP_USER_AGENT'],
                                ),
                        );

                        if ($correctToken === $adminAuth[2]) {
                            $adminAuthOK = true;
                        }
                    }
                    $ares->Free();
                }
            }

            if ($skipSalting) {
                $saltedPassword = $passwordPlain;
            } else {
                $saltedPassword = md5($password . $row['passwort_salt']);
            }

            // user exists
            if (
                (strtolower($row['passwort']) === strtolower($saltedPassword) ||
                    $adminAuthOK) &&
                ($row['last_login_attempt'] < 100 ||
                    $row['last_login_attempt'] + ACCOUNT_LOCK_TIME < time())
            ) {
                // password ok
                if ($row['gesperrt'] == 'no') {
                    if (
                        isset($row['preferred_language']) &&
                        !empty($row['preferred_language'])
                    ) {
                        $userLanguage = $row['preferred_language'];
                    } else {
                        $userLanguage = false;
                    }

                    $availableLanguages = GetAvailableLanguages();
                    if (!isset($availableLanguages[$userLanguage])) {
                        $userLanguage = false;
                    }

                    // okay => update user row
                    $db->Query(
                        'UPDATE {pre}users SET ip=?,lastlogin=?,last_login_attempt=0,charset=?,language=?,last_timezone=? WHERE id=?',
                        $adminAuthOK ? $row['ip'] : $_SERVER['REMOTE_ADDR'],
                        $adminAuthOK ? $row['lastlogin'] : time(),
                        $currentCharset,
                        $userLanguage ? $userLanguage : $currentLanguage,
                        isset($_SESSION['bm_timezone'])
                            ? (int) $_SESSION['bm_timezone']
                            : (isset($_REQUEST['timezone'])
                                ? $_REQUEST['timezone']
                                : $row['last_timezone']),
                        $userID,
                    );

                    // create session
                    if ($createSession) {
                        @session_start();
                        $sessionID = session_id();

                        if ($bm_prefs['cookie_lock'] == 'yes') {
                            $sessionSecret = GenerateRandomKey('sessionSecret');
                            setcookie(
                                'sessionSecret_' . substr($sessionID, 0, 16),
                                $sessionSecret,
                                0,
                                '/',
                            );
                            $_COOKIE[
                                'sessionSecret_' . substr($sessionID, 0, 16)
                            ] = $sessionSecret;
                        }

                        $_SESSION['bm_userLoggedIn'] = true;
                        $_SESSION['bm_userID'] = $userID;
                        $_SESSION['bm_loginTime'] = time();
                        $_SESSION['bm_sessionToken'] = SessionToken();
                        $_SESSION[
                            'bm_xorCryptKey'
                        ] = BMUser::GenerateXORCryptKey(
                            $userID,
                            $passwordPlain,
                        );

                        if ($userLanguage) {
                            $_SESSION['bm_sessionLanguage'] = $userLanguage;
                        }
                    } else {
                        $sessionID = $userID;
                    }

                    // set result
                    $result = [USER_OK, $sessionID];
                    ModuleFunction('OnLogin', [$userID]);
                } else {
                    // locked
                    $result = [USER_LOCKED, false];
                    ModuleFunction('OnLoginFailed', [
                        $email,
                        $password,
                        BM_LOCKED,
                    ]);
                }
            } else {
                // bad password or login lock
                $result = [USER_BAD_PASSWORD, false];
                ModuleFunction('OnLoginFailed', [
                    $email,
                    $password,
                    BM_WRONGLOGIN,
                ]);

                // bruteforce login protection
                $lastLoginAttempt = $row['last_login_attempt'];
                if ($lastLoginAttempt < 100) {
                    // register new attempt
                    $result = [USER_BAD_PASSWORD, $lastLoginAttempt + 1];
                    if (++$lastLoginAttempt >= 5) {
                        $lastLoginAttempt = time();
                    }
                    $db->Query(
                        'UPDATE {pre}users SET last_login_attempt=? WHERE id=?',
                        $lastLoginAttempt,
                        $userID,
                    );
                } else {
                    // account still locked
                    $lockedUntil = $lastLoginAttempt + ACCOUNT_LOCK_TIME;
                    if ($lockedUntil < time()) {
                        // first attempt
                        $db->Query(
                            'UPDATE {pre}users SET last_login_attempt=? WHERE id=?',
                            1,
                            $userID,
                        );
                        $result = [USER_BAD_PASSWORD, 1];
                    } else {
                        // locked
                        $result = [USER_LOGIN_BLOCK, $lockedUntil];
                    }
                }
            }
        }

        // log
        if ($result[0] != USER_OK || $successLog) {
            PutLog(
                sprintf(
                    'Login attempt as <%s> %s (%s; IP: %s)',
                    $email,
                    $result[0] == USER_OK ? 'succeeded' : 'failed',
                    $result[0] == USER_LOGIN_BLOCK
                        ? 'account locked because of too many login attempts'
                        : ($result[0] == USER_BAD_PASSWORD
                            ? 'bad password'
                            : ($result[0] == USER_OK
                                ? 'success'
                                : ($result[0] == USER_DOES_NOT_EXIST
                                    ? 'user does not exist'
                                    : ($result[0] == USER_LOCKED
                                        ? 'account locked'
                                        : 'unknown reason')))),
                    $_SERVER['REMOTE_ADDR'],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
        }
        return $result;
    }

    /**
     * log out
     *
     */
    static function Logout() {
        ModuleFunction('OnLogout', [$_SESSION['bm_userID']]);

        $_SESSION['bm_userLoggedIn'] = false;
        $_SESSION['bm_userID'] = -1;

        if (!isset($_SESSION['bm_adminLoggedIn'])) {
            if (
                isset($_COOKIE['sessionSecret_' . substr(session_id(), 0, 16)])
            ) {
                setcookie(
                    'sessionSecret_' . substr(session_id(), 0, 16),
                    '',
                    time() - TIME_ONE_HOUR,
                    '/',
                );
            }
            session_destroy();
        }
    }

    /**
     * fetch a user row (assoc)
     *
     * @param int $id
     * @return array
     */
    function Fetch($id = -1, $re = false) {
        global $db;

        if ($id == -1) {
            $id = $this->_id;
            if (!$re && is_array($this->_row)) {
                return $this->_row;
            }
        }

        $res = $db->Query('SELECT * FROM {pre}users WHERE id=?', $id);
        if ($res->RowCount() == 0) {
            return false;
        }
        $row = $res->FetchArray(MYSQLI_ASSOC);
        $res->Free();

        return $row;
    }

    /**
     * refresh user row
     *
     * @return array
     */
    function ReFetch() {
        $this->_row = $this->Fetch(-1, true);
        return $this->_row;
    }

    /**
     * get user's pop3 accounts
     *
     * @param string $sortColumn
     * @param string $sortOrder
     * @return array
     */
    function GetPOP3Accounts(
        $sortColumn = 'p_user',
        $sortOrder = 'ASC',
        $activeOnly = false
    ) {
        global $db;

        $accounts = [];
        $res = $db->Query(
            'SELECT id,p_host,p_user,p_pass,p_target,p_port,p_keep,last_fetch,last_success,p_ssl,paused FROM {pre}pop3 WHERE user=? ' .
                ($activeOnly ? 'AND `paused`=\'no\' ' : '') .
                'ORDER BY ' .
                $sortColumn .
                ' ' .
                $sortOrder,
            $this->_id,
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $accounts[$row['id']] = [
                'id' => $row['id'],
                'p_host' => $row['p_host'],
                'p_user' => $row['p_user'],
                'p_pass' => $row['p_pass'],
                'p_target' => $row['p_target'],
                'p_port' => $row['p_port'],
                'p_keep' => $row['p_keep'] == 'yes',
                'p_ssl' => $row['p_ssl'] == 'yes',
                'paused' => $row['paused'] == 'yes',
                'last_fetch' => $row['last_fetch'],
                'last_success' => $row['last_success'],
            ];
        }
        $res->Free();

        return $accounts;
    }

    /**
     * get pop3 account
     *
     * @param int $id
     * @return array
     */
    function GetPOP3Account($id) {
        global $db;

        $res = $db->Query(
            'SELECT id,p_host,p_user,p_pass,p_target,p_port,p_keep,last_fetch,p_ssl,paused FROM {pre}pop3 WHERE id=? AND user=?',
            $id,
            $this->_id,
        );
        if ($res->RowCount() == 0) {
            return false;
        }
        $row = $res->FetchArray(MYSQLI_ASSOC);
        $res->Free();

        $result = [
            'id' => $row['id'],
            'p_host' => $row['p_host'],
            'p_user' => $row['p_user'],
            'p_pass' => $row['p_pass'],
            'p_target' => $row['p_target'],
            'p_port' => $row['p_port'],
            'p_keep' => $row['p_keep'] == 'yes',
            'p_ssl' => $row['p_ssl'] == 'yes',
            'last_fetch' => $row['last_fetch'],
            'paused' => $row['paused'] == 'yes',
        ];
        return $result;
    }

    function UpdatePOP3Account(
        $id,
        $p_host,
        $p_user,
        $p_pass,
        $p_target,
        $p_port,
        $p_keep,
        $p_ssl,
        $paused
    ) {
        global $db;

        $db->Query(
            'UPDATE {pre}pop3 SET p_host=?,p_user=?,p_pass=?,p_target=?,p_port=?,p_keep=?,p_ssl=?,paused=? WHERE id=? AND user=?',
            $p_host,
            $p_user,
            $p_pass,
            (int) $p_target,
            (int) $p_port,
            $p_keep ? 'yes' : 'no',
            $p_ssl ? 'yes' : 'no',
            $paused ? 'yes' : 'no',
            (int) $id,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * add pop3 account
     *
     * @param string $p_host
     * @param string $p_user
     * @param string $p_pass
     * @param int $p_target
     * @param int $p_port
     * @param bool $p_keep
     * @param bool $p_ssl
     * @return int
     */
    function AddPOP3Account(
        $p_host,
        $p_user,
        $p_pass,
        $p_target,
        $p_port,
        $p_keep,
        $p_ssl = false
    ) {
        global $db;

        $db->Query(
            'INSERT INTO {pre}pop3(user,p_host,p_user,p_pass,p_target,p_port,p_keep,p_ssl) ' .
                'VALUES(?,?,?,?,?,?,?,?)',
            $this->_id,
            $p_host,
            $p_user,
            $p_pass,
            (int) $p_target,
            (int) $p_port,
            $p_keep ? 'yes' : 'no',
            $p_ssl ? 'yes' : 'no',
        );
        return $db->InsertId();
    }

    /**
     * delete pop3 account
     *
     * @param int $id Account ID
     * @return bool
     */
    function DeletePOP3Account($id) {
        global $db;

        $db->Query(
            'DELETE FROM {pre}pop3 WHERE id=? AND user=?',
            $id,
            $this->_id,
        );

        if ($db->AffectedRows() == 1) {
            $db->Query('DELETE FROM {pre}uidindex WHERE pop3=?', $id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * get user's signatures
     *
     * @param string $sortColumn
     * @param string $sortOrder
     * @return array
     */
    function GetSignatures($sortColumn = 'titel', $sortOrder = 'ASC') {
        global $db, $lang_user;

        $signatures = [];
        $res = $db->Query(
            'SELECT id,titel,text,html FROM {pre}signaturen WHERE user=? ' .
                'ORDER BY ' .
                $sortColumn .
                ' ' .
                $sortOrder,
            $this->_id,
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $signatures[$row['id']] = $row;
        }
        $res->Free();

        return $signatures;
    }

    /**
     * delete signature
     *
     * @param int $signatureID Signature ID
     * @return bool
     */
    function DeleteSignature($signatureID) {
        global $db;

        // delete
        $db->Query(
            'DELETE FROM {pre}signaturen WHERE id=? AND user=?',
            (int) $signatureID,
            $this->_id,
        );

        // return
        return $db->AffectedRows() == 1;
    }

    /**
     * get signature
     *
     * @param int $signatureID Signature ID
     * @return array
     */
    function GetSignature($signatureID) {
        global $db;

        // get signature
        $res = $db->Query(
            'SELECT id,titel,text,html FROM {pre}signaturen WHERE id=? AND user=?',
            $signatureID,
            $this->_id,
        );
        if ($res->RowCount() != 1) {
            return false;
        }
        $row = $res->FetchArray(MYSQLI_ASSOC);
        $res->Free();

        return $row;
    }

    /**
     * update signature
     *
     * @param int $id Signature ID
     * @param string $title Title
     * @param string $text Text
     * @param string $html HTML
     * @return bool
     */
    function UpdateSignature($signatureID, $title, $text, $html) {
        global $db;

        $db->Query(
            'UPDATE {pre}signaturen SET titel=?,text=?,html=? WHERE id=? AND user=?',
            $title,
            $text,
            $html,
            $signatureID,
            $this->_id,
        );

        return $db->AffectedRows() == 1;
    }

    /**
     * add signature
     *
     * @param string $title Title
     * @param string $text Text
     * @param string $html HTML
     * @return int
     */
    function AddSignature($title, $text, $html) {
        global $db;

        $db->Query(
            'INSERT INTO {pre}signaturen(user,titel,text,html) VALUES(?,?,?,?)',
            $this->_id,
            $title,
            $text,
            $html,
        );

        return $db->InsertId();
    }

    /**
     * get user's aliases
     *
     * @param string $sortColumn
     * @param string $sortOrder
     * @return array
     */
    function GetAliases($sortColumn = 'email', $sortOrder = 'ASC') {
        global $db, $lang_user;

        $aliases = [];
        $res = $db->Query(
            'SELECT id,email,type FROM {pre}aliase WHERE user=? ' .
                'ORDER BY ' .
                $sortColumn .
                ' ' .
                $sortOrder,
            $this->_id,
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $typeTexts = [];
            if ($row['type'] & ALIAS_SENDER) {
                $typeTexts[] = $lang_user['aliastype_1'];
            }
            if ($row['type'] & ALIAS_RECIPIENT) {
                $typeTexts[] = $lang_user['aliastype_2'];
            }
            if ($row['type'] & ALIAS_PENDING) {
                $typeTexts = [$lang_user['aliastype_4']];
            }
            $row['typeText'] = implode(', ', $typeTexts);
            $aliases[$row['id']] = $row;
        }
        $res->Free();

        return $aliases;
    }

    /**
     * get possible senders
     *
     * @return array
     */
    function GetPossibleSenders() {
        $senders = [];
        $aliases = $this->GetAliases();
        $worgroups = $this->GetWorkgroups(false);

        $senders[] = sprintf('<%s>', $this->_row['email']);
        foreach ($aliases as $alias) {
            if (
                ($alias['type'] & ALIAS_SENDER) != 0 &&
                ($alias['type'] & ALIAS_PENDING) == 0
            ) {
                $senders[] = sprintf('<%s>', $alias['email']);
            }
        }

        foreach ($worgroups as $workgroup) {
            $senders[] = sprintf('<%s>', $workgroup['email']);
        }

        if (trim($this->_row['absendername']) != '') {
            $senders[] = sprintf(
                '"%s" <%s>',
                $this->_row['absendername'],
                $this->_row['email'],
            );
        } else {
            $senders[] = sprintf(
                '"%s %s" <%s>',
                $this->_row['vorname'],
                $this->_row['nachname'],
                $this->_row['email'],
            );
        }

        foreach ($aliases as $alias) {
            if (
                ($alias['type'] & ALIAS_SENDER) != 0 &&
                ($alias['type'] & ALIAS_PENDING) == 0
            ) {
                if (trim($this->_row['absendername']) != '') {
                    $senders[] = sprintf(
                        '"%s" <%s>',
                        $this->_row['absendername'],
                        $alias['email'],
                    );
                } else {
                    $senders[] = sprintf(
                        '"%s %s" <%s>',
                        $this->_row['vorname'],
                        $this->_row['nachname'],
                        $alias['email'],
                    );
                }
            }
        }

        foreach ($worgroups as $workgroup) {
            $senders[] = sprintf(
                '"%s" <%s>',
                $workgroup['title'],
                $workgroup['email'],
            );
        }

        return $senders;
    }

    /**
     * get default sender
     *
     * @return string
     */
    function GetDefaultSender() {
        $senders = $this->GetPossibleSenders();
        return isset($senders[$this->_row['defaultSender']])
            ? $senders[$this->_row['defaultSender']]
            : array_shift($senders);
    }

    /**
     * set default sender
     *
     * @param int $senderID Sender ID (from possible senders table)
     * @return bool
     */
    function SetDefaultSender($senderID) {
        global $db;

        $db->Query(
            'UPDATE {pre}users SET defaultSender=? WHERE id=?',
            $senderID,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * delete an alias
     *
     * @param int $aliasID Alias ID
     * @return bool
     */
    function DeleteAlias($aliasID) {
        global $db;

        // sender
        $defaultSender = $this->GetDefaultSender();

        // get email
        $res = $db->Query(
            'SELECT email FROM {pre}aliase WHERE id=? AND user=?',
            (int) $aliasID,
            $this->_id,
        );
        assert('$res->RowCount() != 0');
        [$aliasEMail] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        // delete
        $db->Query(
            'DELETE FROM {pre}aliase WHERE id=? AND user=?',
            (int) $aliasID,
            $this->_id,
        );

        // save sender
        $possibleSenders = $this->GetPossibleSenders();
        foreach ($possibleSenders as $senderID => $senderString) {
            if ($defaultSender == $senderString) {
                $this->SetDefaultSender($senderID);
                break;
            }
        }

        // log
        PutLog(
            sprintf(
                'User <%s> (%d) deleted alias <%s> (%d)',
                $this->_row['email'],
                $this->_id,
                $aliasEMail,
                $aliasID,
            ),
            PRIO_NOTE,
            __FILE__,
            __LINE__,
        );

        // return
        return $db->AffectedRows() == 1;
    }

    /**
     * confirm an alias
     *
     * @param int $id Alias ID
     * @param string $code Confirmation code
     * @return bool
     */
    function ConfirmAlias($id, $code) {
        global $db;

        // get user id
        $res = $db->Query(
            'SELECT `user` FROM {pre}aliase WHERE `id`=? AND `code`=? AND `code`!=?',
            $id,
            $code,
            '',
        );
        if ($res->RowCount() != 1) {
            return false;
        }
        [$userID] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        // store sender
        $_obj = _new('BMUser', [$userID]);
        if ($_obj->_row === false) {
            return false;
        }
        $defaultSender = $_obj->GetDefaultSender();

        $db->Query(
            'UPDATE {pre}aliase SET type=(type^' .
                ALIAS_PENDING .
                '),code=? WHERE id=? AND code=? AND code!=?',
            '',
            $id,
            $code,
            '',
        );
        // log
        if ($db->AffectedRows() == 1) {
            // save sender
            $possibleSenders = $_obj->GetPossibleSenders();
            foreach ($possibleSenders as $senderID => $senderString) {
                if ($defaultSender == $senderString) {
                    $_obj->SetDefaultSender($senderID);
                    break;
                }
            }

            PutLog(
                sprintf(
                    'External alias <%d> confirmed with code <%s> from <%s>',
                    $id,
                    $code,
                    $_SERVER['REMOTE_ADDR'],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
            return true;
        }
        return false;
    }

    /**
     * add an alias
     *
     * @param string $email Alias e-mail address
     * @param int $type Alias type
     * @return in
     */
    function AddAlias($email, $type) {
        global $db, $lang_custom, $bm_prefs, $thisUser;

        $result = 0;

        // default sender
        $defaultSender = $this->GetDefaultSender();

        //
        // internal alias
        //
        if ($type == (ALIAS_RECIPIENT | ALIAS_SENDER)) {
            // add
            $db->Query(
                'INSERT INTO {pre}aliase(email,user,type,date) VALUES(?,?,?,?)',
                $email,
                $this->_id,
                $type,
                time(),
            );
            $id = $db->InsertId();

            // log
            PutLog(
                sprintf(
                    'User <%s> (%d) created internal alias <%s> (%d)',
                    $this->_row['email'],
                    $this->_id,
                    $email,
                    $id,
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
            $result = $id;
        }

        //
        // external alias
        //
        elseif ($type == ALIAS_SENDER) {
            // add
            $code = GenerateRandomKey('aliasCode');
            $db->Query(
                'INSERT INTO {pre}aliase(email,user,type,code,date) VALUES(?,?,?,?,?)',
                $email,
                $this->_id,
                $type | ALIAS_PENDING,
                $code,
                time(),
            );
            $id = $db->InsertId();

            // send mail
            $link =
                $bm_prefs['selfurl'] .
                'index.php?action=confirmAlias&id=' .
                $id .
                '&code=' .
                $code;
            $vars = [
                'email' => DecodeEMail($this->_row['email']),
                'aliasemail' => DecodeEMail($email),
                'link' => $link,
            ];
            SystemMail(
                $thisUser->GetDefaultSender(),
                $email,
                $lang_custom['alias_sub'],
                'alias_text',
                $vars,
            );

            // log
            PutLog(
                sprintf(
                    'User <%s> (%d) created external alias <%s> (%d)',
                    $this->_row['email'],
                    $this->_id,
                    $email,
                    $id,
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
            $result = $id;
        }

        // save sender
        $possibleSenders = $this->GetPossibleSenders();
        foreach ($possibleSenders as $senderID => $senderString) {
            if ($defaultSender == $senderString) {
                $this->SetDefaultSender($senderID);
                break;
            }
        }

        return $result;
    }

    /**
     * get user count
     *
     * @return int
     */
    static function GetUserCount() {
        global $db;

        $res = $db->Query('SELECT COUNT(*) FROM {pre}users');
        [$userCount] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        return $userCount;
    }

    /**
     * get user's workgroups
     *
     * @param bool $withMembers Include members?
     * @return array
     */
    function GetWorkgroups($withMembers = false) {
        return BMWorkgroup::GetSimpleWorkgroupList($this->_id, $withMembers);
    }

    /**
     * cancel account
     *
     * @return bool
     */
    function CancelAccount() {
        global $db;

        $db->Query(
            'UPDATE {pre}users SET gesperrt=? WHERE id=?',
            'delete',
            $this->_id,
        );

        return $db->AffectedRows() == 1;
    }

    /**
     * get user autoresponder
     *
     * @return array $active, $subject, $text
     */
    function GetAutoresponder() {
        global $db;

        $active = 'no';
        $subject = $text = '';
        $lastSend = 0;

        $res = $db->Query(
            'SELECT active,betreff,mitteilung,last_send FROM {pre}autoresponder WHERE userid=?',
            $this->_id,
        );
        if ($res->RowCount() > 0) {
            [$active, $subject, $text, $lastSend] = $res->FetchArray(
                MYSQLI_NUM,
            );
            $res->Free();
        }

        return [$active == 'yes', $subject, $text, $lastSend];
    }

    /**
     * set last_sent field of autoresponder
     *
     * @param string $lastSend Last mail address
     * @return bool
     */
    function SetAutoresponderLastSend($lastSend) {
        global $db;

        $db->Query(
            'UPDATE {pre}autoresponder SET last_send=? WHERE userid=?',
            strtolower($lastSend),
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * set autoresponder settings
     *
     * @param bool $active Active?
     * @param string $subject Subject
     * @param string $text Text
     * @return int
     */
    function SetAutoresponder($active, $subject, $text) {
        global $db;

        $res = $db->Query(
            'SELECT id FROM {pre}autoresponder WHERE userid=?',
            $this->_id,
        );
        if ($res->RowCount() > 0) {
            [$id] = $res->FetchArray(MYSQLI_NUM);
            $res->Free();

            $db->Query(
                'UPDATE {pre}autoresponder SET active=?,betreff=?,mitteilung=? WHERE id=?',
                $active ? 'yes' : 'no',
                $subject,
                $text,
                $id,
            );
            return $db->AffectedRows() == 1;
        } else {
            $db->Query(
                'INSERT INTO {pre}autoresponder(active,userid,betreff,mitteilung) VALUES(?,?,?,?)',
                $active ? 'yes' : 'no',
                $this->_id,
                $subject,
                $text,
            );
            return $db->InsertId() != 0;
        }
    }

    /**
     * get spam index size (entry count)
     *
     * @return int
     */
    function GetSpamIndexSize() {
        global $db;

        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}spamindex WHERE userid=?',
            $this->_id,
        );
        [$size] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        return $size;
    }

    /**
     * reset spam index
     *
     * @return bool
     */
    function ResetSpamIndex() {
        global $db;

        $db->Query('DELETE FROM {pre}spamindex WHERE userid=?', $this->_id);
        $db->Query(
            'UPDATE {pre}users SET bayes_spam=0, bayes_nonspam=0 WHERE id=?',
            $this->_id,
        );

        return true;
    }

    /**
     * set antivirus settings
     *
     * @param bool $active Filter active?
     * @param int $action Virus action
     * @return bool
     */
    function SetAntivirusSettings($active, $action) {
        global $db;
        $db->Query(
            'UPDATE {pre}users SET virusfilter=?, virusaction=? WHERE id=?',
            $active ? 'yes' : 'no',
            $action,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * set antispam settings
     *
     * @param bool $active Filter active?
     * @param int $action Spam action
     * @param bool $unspamMe Mark sent mails as NON-spam?
     * @param int $bayesBorder Bayes border (%)
     * @param bool $addressbookNoSpam Mark as NON-spam when sender is in the address book?
     * @return bool
     */
    function SetAntispamSettings(
        $active,
        $action,
        $unspamMe,
        $bayesBorder = false,
        $addressbookNoSpam = false
    ) {
        global $db;
        $db->Query(
            'UPDATE {pre}users SET spamfilter=?, spamaction=?, unspamme=?, addressbook_nospam=?' .
                ($bayesBorder !== false
                    ? ', bayes_border=' . (int) $bayesBorder
                    : '') .
                ' WHERE id=?',
            $active ? 'yes' : 'no',
            $action,
            $unspamMe ? 'yes' : 'no',
            $addressbookNoSpam ? 'yes' : 'no',
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * get user's filters
     *
     * @return array
     */
    function GetFilters($sortColumn = 'orderpos', $sortOrder = 'ASC') {
        global $db;

        $filters = [];
        $res = $db->Query(
            'SELECT id,title,applied,active,link,orderpos,flags FROM {pre}filter WHERE userid=? ' .
                'ORDER BY ' .
                $sortColumn .
                ' ' .
                $sortOrder,
            $this->_id,
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $filters[$row['id']] = $row;
        }
        $res->Free();

        return $filters;
    }

    /**
     * move filter
     *
     * @param int $id Filter ID
     * @param int $direction Direction (-1 = up, 1 = down)
     */
    function MoveFilter($id, $direction) {
        global $db;

        $filters = $this->GetFilters();
        $newFilters = [];
        $maxPos = 0;

        foreach ($filters as $filter) {
            if ($filter['orderpos'] > $maxPos) {
                $maxPos = $filter['orderpos'];
            }
        }

        $newPos = max(1, min($maxPos, $filters[$id]['orderpos'] + $direction));

        foreach ($filters as $filterID => $filter) {
            if (count($newFilters) + 1 == $newPos) {
                $newFilters[$id] = $filters[$id];
                $newFilters[$id]['orderpos'] = $newPos;
            }

            if ($filterID != $id) {
                $filter['orderpos'] = count($newFilters) + 1;
                $newFilters[$filterID] = $filter;
            }
        }

        if (!isset($newFilters[$id])) {
            $newFilters[$id] = $filters[$id];
            $newFilters[$id]['orderpos'] = $newPos;
        }

        foreach ($newFilters as $filterID => $newFilter) {
            if ($newFilter['orderpos'] != $filters[$filterID]['orderpos']) {
                $db->Query(
                    'UPDATE {pre}filter SET orderpos=? WHERE id=?',
                    $newFilter['orderpos'],
                    $filterID,
                );
            }
        }
    }

    /**
     * get a filter
     *
     * @param int $id Filter ID
     * @return array
     */
    function GetFilter($id) {
        global $db;

        $res = $db->Query(
            'SELECT id,userid,title,applied,active,link,orderpos,flags FROM {pre}filter WHERE id=? AND userid=?',
            (int) $id,
            $this->_id,
        );
        if ($res->RowCount() == 1) {
            $row = $res->FetchArray(MYSQLI_ASSOC);
            $res->Free();
            return $row;
        }

        return false;
    }

    /**
     * add a filter
     *
     * @param string $title Title
     * @param bool $active Active?
     * @return int
     */
    function AddFilter($title, $active) {
        global $db;

        $orderPos = 0;
        $res = $db->Query(
            'SELECT orderpos FROM {pre}filter WHERE userid=? ORDER BY orderpos DESC LIMIT 1',
            $this->_id,
        );
        if ($res->RowCount() == 1) {
            [$orderPos] = $res->FetchArray(MYSQLI_NUM);
            $res->Free();
        }

        $db->Query(
            'INSERT INTO {pre}filter(userid,title,active,orderpos) VALUES(?,?,?,?)',
            $this->_id,
            $title,
            $active ? 1 : 0,
            ++$orderPos,
        );
        $id = $db->InsertId();

        if ($id > 0) {
            $this->AddFilterCondition($id);
            $this->AddFilterAction($id);
            return $id;
        }

        return 0;
    }

    /**
     * update a filter
     *
     * @param int $id Filter ID
     * @param string $title Title
     * @param bool $active Active?
     * @param int $link Link type
     * @param int $flags Filter flags
     * @return bool
     */
    function UpdateFilter($id, $title, $active, $link, $flags = 0) {
        global $db;

        $db->Query(
            'UPDATE {pre}filter SET title=?,active=?,link=?,flags=? WHERE id=? AND userid=?',
            $title,
            $active ? 1 : 0,
            (int) $link,
            (int) $flags,
            (int) $id,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * delete a filter
     *
     * @param int $id Filter ID
     * @return bool
     */
    function DeleteFilter($id) {
        global $db;

        $db->Query(
            'DELETE FROM {pre}filter WHERE id=? AND userid=?',
            (int) $id,
            $this->_id,
        );
        if ($db->AffectedRows() == 1) {
            $db->Query(
                'DELETE FROM {pre}filter_conditions WHERE filter=?',
                (int) $id,
            );
            return true;
        }

        return false;
    }

    /**
     * get filter conditions
     *
     * @param int $filterID Filter ID
     * @return array
     */
    function GetFilterConditions($filterID) {
        global $db;

        $result = [];
        $res = $db->Query(
            'SELECT id,field,op,val FROM {pre}filter_conditions WHERE filter=? ORDER BY id ASC',
            (int) $filterID,
        );
        while ($row = $res->FetchArray()) {
            $result[$row['id']] = $row;
        }
        $res->Free();

        return $result;
    }

    /**
     * delete filter condition
     *
     * @param int $conditionID Condition ID
     * @param int $filterID Filter ID
     * @return bool
     */
    function DeleteFilterCondition($conditionID, $filterID) {
        global $db;

        $db->Query(
            'DELETE FROM {pre}filter_conditions WHERE id=? AND filter=?',
            (int) $conditionID,
            (int) $filterID,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * add filter condition
     *
     * @param int $filterID Filter ID
     * @return int
     */
    function AddFilterCondition($filterID) {
        global $db;

        $db->Query(
            'INSERT INTO {pre}filter_conditions(filter,field,op,val) VALUES(?,?,?,?)',
            (int) $filterID,
            1,
            1,
            '',
        );
        return $db->InsertID();
    }

    /**
     * update filter condition
     *
     * @param int $conditionID Condition ID
     * @param int $filterID Filter ID
     * @param int $field Field constant
     * @param int $op Op constant
     * @param string $val Value
     * @return bool
     */
    function UpdateFilterCondition($conditionID, $filterID, $field, $op, $val) {
        global $db;

        $db->Query(
            'UPDATE {pre}filter_conditions SET field=?,op=?,val=? WHERE id=? AND filter=?',
            (int) $field,
            (int) $op,
            $val,
            (int) $conditionID,
            (int) $filterID,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * get filter actions
     *
     * @param int $filterID Filter ID
     * @return array
     */
    function GetFilterActions($filterID) {
        global $db;

        $result = [];
        $res = $db->Query(
            'SELECT id,filter,op,val,text_val FROM {pre}filter_actions WHERE filter=? ORDER BY id ASC',
            (int) $filterID,
        );
        while ($row = $res->FetchArray()) {
            $result[$row['id']] = $row;
        }
        $res->Free();

        return $result;
    }

    /**
     * delete filter action
     *
     * @param int $actionID Action ID
     * @param int $filterID Filter ID
     * @return bool
     */
    function DeleteFilterAction($actionID, $filterID) {
        global $db;

        $db->Query(
            'DELETE FROM {pre}filter_actions WHERE id=? AND filter=?',
            (int) $actionID,
            (int) $filterID,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * add filter action
     *
     * @param int $filterID Filter ID
     * @return int
     */
    function AddFilterAction($filterID) {
        global $db;

        $db->Query(
            'INSERT INTO {pre}filter_actions(filter,op,val) VALUES(?,?,?)',
            (int) $filterID,
            1,
            0,
        );
        return $db->InsertID();
    }

    /**
     * update filter action
     *
     * @param int $actionID Action ID
     * @param int $filterID Filter ID
     * @param int $field Field constant
     * @param int $op Op constant
     * @param string $val Value
     * @return bool
     */
    function UpdateFilterAction($actionID, $filterID, $op, $val, $textVal) {
        global $db;

        $db->Query(
            'UPDATE {pre}filter_actions SET op=?,val=?,text_val=? WHERE id=? AND filter=?',
            (int) $op,
            $val,
            $textVal,
            (int) $actionID,
            (int) $filterID,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * increment filter applied-counter
     *
     * @param int $filterID Filter ID
     * @return bool
     */
    function IncFilter($filterID) {
        global $db;

        $db->Query(
            'UPDATE {pre}filter SET applied=applied+1 WHERE id=? AND userid=?',
            (int) $filterID,
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * Update common preferences
     *
     * @param int $inboxRefresh
     * @param bool $instantHTML
     * @param int $firstDayOfWeek
     * @param string $dateFormat
     * @param string $senderName
     * @param int $defaultSender
     * @param string $rePrefix
     * @param string $fwdPrefix
     * @param bool $forwardEnabled
     * @param string $forwardTo
     * @param bool $forwardDelete
     * @param bool $enablePreview
     * @param bool $conversationView
     * @return bool
     */
    function UpdateCommonSettings(
        $inboxRefresh,
        $instantHTML,
        $firstDayOfWeek,
        $dateFormat,
        $senderName,
        $defaultSender,
        $rePrefix,
        $fwdPrefix,
        $forwardEnabled,
        $forwardTo,
        $forwardDelete,
        $enablePreview,
        $conversationView,
        $newsletterOptIn,
        $plaintextCourier,
        $replyQuote,
        $hotkeys,
        $attCheck,
        $searchDetailsDefault,
        $preferredLanguage,
        $notifySound,
        $notifyEMail,
        $notifyBirthday,
        $autoSaveDrafts,
        $autoSaveDraftsInterval
    ) {
        global $db, $bm_prefs;

        $this->SetPref('hotkeys', $hotkeys);

        $db->Query(
            'UPDATE {pre}users SET in_refresh=?, soforthtml=?, c_firstday=?, datumsformat=?, absendername=?, defaultSender=?, re=?, fwd=?, forward=?, forward_to=?, forward_delete=?, preview=?, conversation_view=?, newsletter_optin=?, plaintext_courier=?, reply_quote=?, attcheck=?, search_details_default=?, preferred_language=?, notify_sound=?, notify_email=?, notify_birthday=?, auto_save_drafts=?, auto_save_drafts_interval=? WHERE id=?',
            $inboxRefresh,
            $instantHTML ? 'yes' : 'no',
            $firstDayOfWeek,
            $dateFormat,
            $senderName,
            $defaultSender,
            $rePrefix,
            $fwdPrefix,
            $forwardEnabled ? 'yes' : 'no',
            $forwardTo,
            $forwardDelete ? 'yes' : 'no',
            $enablePreview ? 'yes' : 'no',
            $conversationView ? 'yes' : 'no',
            $newsletterOptIn ? 'yes' : 'no',
            $plaintextCourier ? 'yes' : 'no',
            $replyQuote ? 'yes' : 'no',
            $attCheck ? 'yes' : 'no',
            $searchDetailsDefault ? 'yes' : 'no',
            $preferredLanguage,
            $notifySound ? 'yes' : 'no',
            $notifyEMail ? 'yes' : 'no',
            $notifyBirthday ? 'yes' : 'no',
            $autoSaveDrafts ? 'yes' : 'no',
            max($bm_prefs['min_draft_save_interval'], $autoSaveDraftsInterval),
            $this->_id,
        );
        return $db->AffectedRows() == 1;
    }

    /**
     * update user contact data
     *
     * @param array $userRow Updates user row
     * @param array $profileFields Profile field data
     * @param bool $noHistory No history?
     * @return bool
     */
    function UpdateContactData(
        $userRow,
        $profileFields,
        $noHistory = false,
        $userID = 0,
        $passwordPlain = false
    ) {
        global $db, $bm_prefs;

        if (
            $noHistory ||
            $userRow != $this->_row ||
            ($profileFields !== false &&
                $profileFields != @unserialize($userRow['profilfelder']))
        ) {
            // save contact history?
            if (!$noHistory) {
                $contactHistory = $this->_row['contactHistory'];
                if ($bm_prefs['contact_history'] == 'yes') {
                    $contactHistory = @unserialize(
                        $this->_row['contactHistory'],
                    );
                    if (!is_array($contactHistory)) {
                        $contactHistory = [];
                    }
                    $contactHistory[] = [
                        'anrede' => $this->_row['anrede'],
                        'vorname' => $this->_row['vorname'],
                        'nachname' => $this->_row['nachname'],
                        'strasse' => $this->_row['strasse'],
                        'hnr' => $this->_row['hnr'],
                        'plz' => $this->_row['plz'],
                        'ort' => $this->_row['ort'],
                        'land' => (int) $this->_row['land'],
                        'tel' => $this->_row['tel'],
                        'fax' => $this->_row['fax'],
                        'altmail' => $this->_row['altmail'],
                        'profilfelder' => $this->_row['profilfelder'],
                        'changeDate' => time(),
                    ];
                    $contactHistory = serialize($contactHistory);
                }
            } else {
                if ($userID == 0) {
                    $contactHistory = $this->_row['contactHistory'];
                } else {
                    $user = _new('BMUser', [$userID]);
                    $row = $user->Fetch();
                    $contactHistory = $row['contactHistory'];
                }
            }

            // profile fields
            if ($profileFields === false) {
                $profileFields = @unserialize($userRow['profilfelder']);
                if (!is_array($profileFields)) {
                    $profileFields = [];
                }
            }

            // store data
            $db->Query(
                'UPDATE {pre}users SET vorname=?, nachname=?, strasse=?, hnr=?, plz=?, ort=?, land=?, tel=?, fax=?, altmail=?, profilfelder=?, passwort=?, contactHistory=?, anrede=? WHERE id=?',
                $userRow['vorname'],
                $userRow['nachname'],
                $userRow['strasse'],
                $userRow['hnr'],
                $userRow['plz'],
                $userRow['ort'],
                (int) $userRow['land'],
                $userRow['tel'],
                $userRow['fax'],
                $userRow['altmail'],
                serialize($profileFields),
                $userRow['passwort'],
                $contactHistory,
                $userRow['anrede'],
                $userID != 0 ? $userID : $this->_id,
            );
            if ($db->AffectedRows() == 1 || true) {
                // pw changed?
                if (
                    $userID == 0 &&
                    $this->_row['passwort'] != $userRow['passwort']
                ) {
                    ModuleFunction('OnUserPasswordChange', [
                        $this->_id,
                        $this->_row['passwort'],
                        $userRow['passwort'],
                        $passwordPlain,
                    ]);

                    if (
                        isset($_SESSION['bm_xorCryptKey']) &&
                        $passwordPlain !== false
                    ) {
                        $privateKeyPasswords = $this->GetPrivateKeyPasswords();

                        $_SESSION[
                            'bm_xorCryptKey'
                        ] = $this->GenerateXORCryptKey(
                            $this->_id,
                            $passwordPlain,
                        );

                        if ($privateKeyPasswords) {
                            $this->SetPrivateKeyPasswords($privateKeyPasswords);
                        }
                    }
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * get user's VCard
     *
     * @return string
     */
    function BuildVCard() {
        if (!class_exists('VCardBuilder')) {
            include B1GMAIL_DIR . 'serverlib/vcard.class.php';
        }

        // fields
        $countryList = CountryList();
        $fields = [
            'vorname' => $this->_row['vorname'],
            'nachname' => $this->_row['nachname'],
            'strassenr' => trim(
                $this->_row['strasse'] . ' ' . $this->_row['hnr'],
            ),
            'plz' => $this->_row['plz'],
            'ort' => $this->_row['ort'],
            'land' => $countryList[$this->_row['land']],
            'tel' => $this->_row['tel'],
            'fax' => $this->_row['fax'],
            'email' => ExtractMailAddress($this->GetDefaultSender()),
        ];

        // generate vcf
        $vcardBuilder = _new('VCardBuilder', [$fields]);
        return $vcardBuilder->Build();
    }

    /**
     * get root certificates of user
     *
     * @return array
     */
    function GetRootCertificates() {
        global $db;

        $certs = [];
        $res = $db->Query(
            'SELECT `hash`,`pemdata` FROM {pre}certificates WHERE `userid`=? AND `type`=?',
            $this->_id,
            CERTIFICATE_TYPE_ROOT,
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $certs[$row['hash']] = $row['pemdata'];
        }
        $res->Free();

        return $certs;
    }

    /**
     * get certificate for e-mail address
     *
     * @param string $email E-Mail address
     * @param int $type Certificate type
     * @return mixed Array with certificate info or false on error
     */
    function GetCertificateForAddress($email, $type = CERTIFICATE_TYPE_PUBLIC) {
        global $db;

        $result = false;
        $res = $db->Query(
            'SELECT `certificateid`,`hash`,`cn`,`email`,`validfrom`,`validto`,`pemdata`,`type` FROM {pre}certificates WHERE `userid`=? AND `type`=? AND `email`=? AND `validfrom`<=? AND `validto`>=? ORDER BY `validfrom` ASC LIMIT 1',
            $this->_id,
            $type,
            $email,
            time(),
            time(),
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $result = $row;
        }
        $res->Free();

        return $result;
    }

    /**
     * get keyring of user
     *
     * @return array
     */
    function GetKeyRing(
        $sortColumn = 'certificateid',
        $sortOrder = 'ASC',
        $type = CERTIFICATE_TYPE_PUBLIC
    ) {
        global $db;

        $certs = [];
        $res = $db->Query(
            'SELECT `certificateid`,`hash`,`cn`,`email`,`validfrom`,`validto`,`pemdata`,`type` FROM {pre}certificates WHERE `userid`=? AND `type`=? ORDER BY ' .
                $sortColumn .
                ' ' .
                $sortOrder,
            $this->_id,
            $type,
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $certs[$row['certificateid']] = $row;
        }
        $res->Free();

        return $certs;
    }

    /**
     * store a x509-certificate in PEM format in the user's keyring
     *
     * @param string $pemData PEM data
     * @return mixed Certificate hash or false on error
     */
    function StoreCertificate($pemData, $certType = CERTIFICATE_TYPE_PUBLIC) {
        global $db;

        // parse cert
        $cert = openssl_x509_read($pemData);
        if (!$cert) {
            return false;
        }
        $certInfo = openssl_x509_parse($cert);
        openssl_x509_free($cert);

        // check purpose
        $smimeSign = $smimeEncrypt = false;
        foreach ($certInfo['purposes'] as $purpose) {
            if ($purpose[2] == 'smimeencrypt' && $purpose[0]) {
                $smimeEncrypt = true;
            }
            if ($purpose[2] == 'smimesign' && $purpose[0]) {
                $smimeSign = true;
            }
            if ($purpose[2] == 'any' && $purpose[0]) {
                $smimeEncrypt = true;
                $smimeSign = true;
            }
        }
        if (!$smimeSign && !$smimeEncrypt) {
            return false;
        }

        // check if exists
        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}certificates WHERE `hash`=? AND `userid`=? AND `type`=?',
            $certInfo['hash'],
            $this->_id,
            $certType,
        );
        [$certCount] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        // add?
        if ($certCount == 0) {
            $certMail = '';

            if (
                isset($certInfo['extensions']['subjectAltName']) &&
                substr($certInfo['extensions']['subjectAltName'], 0, 6) ==
                    'email:'
            ) {
                $certMail = substr(
                    $certInfo['extensions']['subjectAltName'],
                    6,
                );
            } elseif (isset($certInfo['subject']['emailAddress'])) {
                $certMail = $certInfo['subject']['emailAddress'];
            }

            $db->Query(
                'INSERT INTO {pre}certificates(`type`,`userid`,`hash`,`cn`,`email`,`validfrom`,`validto`,`pemdata`) VALUES(?,?,?,?,?,?,?,?)',
                $certType,
                $this->_id,
                $certInfo['hash'],
                is_array($certInfo['subject']['CN'])
                    ? array_unshift($certInfo['subject']['CN'])
                    : $certInfo['subject']['CN'],
                $certMail,
                $certInfo['validFrom_time_t'],
                $certInfo['validTo_time_t'],
                $pemData,
            );
        }

        // return
        return $certInfo['hash'];
    }

    /**
     * export cert + pk + chain certs as PKCS12 file
     *
     * @param string $hash Certificate hash
     * @param string $pass Password for PKCS12 file
     * @return mixed String with PKCS12 data or false on error
     */
    function ExportPrivateCertificateAsPKCS12($hash, $pass) {
        $result = false;

        $certData = $this->GetCertificateByHash($hash);
        if (!$certData) {
            return false;
        }

        $privKeyPEMData = $this->GetPrivateKey($hash);
        if (!$privKeyPEMData) {
            return false;
        }

        $privKeyPass = $this->GetPrivateKeyPassword($hash);
        $privKey = !empty($privKeyPass)
            ? [$privKeyPEMData, $privKeyPass]
            : $privKeyPEMData;

        $chainCerts = $this->GetChainCerts($hash);
        if ($chainCerts && is_array($chainCerts) && count($chainCerts) > 0) {
            $args = ['extracerts' => $chainCerts];
        } else {
            $args = [];
        }

        if (
            openssl_pkcs12_export(
                $certData['pemdata'],
                $result,
                $privKey,
                $pass,
                $args,
            )
        ) {
            return $result;
        }

        return false;
    }

    /**
     * store a private certificate
     *
     * @param string $certData Certificate PEM data
     * @param string $keyData Private key PEM data
     * @param string $pw Private key password
     * @param array $chainCerts Chain certs array
     * @return mixed Certificate hash or false on error
     */
    function StorePrivateCertificate(
        $certData,
        $keyData,
        $pw,
        $chainCerts = false
    ) {
        if (
            $certData &&
            $keyData &&
            strlen($certData) > 5 &&
            strlen($keyData) > 5
        ) {
            $certData = str_replace(' TRUSTED ', ' ', $certData);
            $cert = @openssl_x509_read(trim($certData));

            if ($cert) {
                // check if PK fits
                if (
                    @openssl_x509_check_private_key(
                        $cert,
                        !empty($pw) ? [$keyData, $pw] : $keyData,
                    )
                ) {
                    $certInfo = openssl_x509_parse($cert);

                    // check purpose
                    $smimeSign = $smimeEncrypt = false;
                    foreach ($certInfo['purposes'] as $purpose) {
                        if ($purpose[2] == 'smimeencrypt' && $purpose[0]) {
                            $smimeEncrypt = true;
                        }
                        if ($purpose[2] == 'smimesign' && $purpose[0]) {
                            $smimeSign = true;
                        }
                        if ($purpose[2] == 'any' && $purpose[0]) {
                            $smimeEncrypt = true;
                            $smimeSign = true;
                        }
                    }
                    if (!$smimeSign && !$smimeEncrypt) {
                        return false;
                    }

                    // add cert
                    if (
                        ($hash = $this->StoreCertificate(
                            $certData,
                            CERTIFICATE_TYPE_PRIVATE,
                        )) !== false
                    ) {
                        $this->SetPrivateKey($hash, $keyData);
                        if (!empty($pw)) {
                            $this->SetPrivateKeyPassword($hash, $pw);
                        }
                        if (
                            $chainCerts !== false &&
                            is_array($chainCerts) &&
                            count($chainCerts) > 0
                        ) {
                            $this->SetChainCerts($hash, $chainCerts);
                        }
                        return $hash;
                    }
                }
            }
        }

        return false;
    }

    /**
     * delete certificate by supplying the certificate hash
     *
     * @param string $hash Certificate hash
     * @return bool
     */
    function DeleteCertificateByHash($hash, $type = 0) {
        global $db;

        if ($type == CERTIFICATE_TYPE_PRIVATE) {
            $this->DeletePref('ChainCerts_' . $hash);
            $this->DeletePref('PrivateKey_' . $hash);
            $this->DeletePref('PrivateKeyPassword_' . $hash);
        }

        $db->Query(
            'DELETE FROM {pre}certificates WHERE `hash`=? AND `userid`=?' .
                ($type > 0 ? ' AND `type`=' . (int) $type : ''),
            $hash,
            $this->_id,
        );
        if ($db->AffectedRows() == 1) {
            return true;
        }

        return false;
    }

    /**
     * set chain certs
     *
     * @param string $hash Certificate hash
     * @param array $certs Chain certs
     */
    function SetChainCerts($hash, $certs) {
        $this->SetPref('ChainCerts_' . $hash, serialize($certs));
    }

    /**
     * get chain certs
     *
     * @param string $hash Certificate hash
     * @return array
     */
    function GetChainCerts($hash) {
        $result = @unserialize($this->GetPref('ChainCerts_' . $hash));
        return is_array($result) ? $result : false;
    }

    /**
     * return an array of recipients with missing certificate
     *
     * @param array $recipients Recipient list
     * @return array
     */
    function GetRecipientsWithMissingCertificate(
        $recipients,
        $type = CERTIFICATE_TYPE_PUBLIC
    ) {
        global $db;

        foreach ($recipients as $key => $val) {
            $recipients[$key] = strtolower($val);
        }

        $res = $db->Query(
            'SELECT `email` FROM {pre}certificates WHERE `userid`=? AND `email` IN ? AND `type`=? AND `validfrom`<=? AND `validto`>=?',
            $this->_id,
            $recipients,
            $type,
            time(),
            time(),
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            while (
                ($arrayKey = array_search(
                    strtolower($row['email']),
                    $recipients,
                )) !== false
            ) {
                unset($recipients[$arrayKey]);
            }
        }
        $res->Free();

        return $recipients;
    }

    /**
     * fetch a certificate from keyring by supplying the certificate hash
     *
     * @param string $hash Certificate hash
     * @return array
     */
    function GetCertificateByHash($hash) {
        global $db;

        $res = $db->Query(
            'SELECT `type`,`userid`,`hash`,`cn`,`email`,`validfrom`,`validto`,`pemdata` FROM {pre}certificates WHERE `userid`=? AND `hash`=? LIMIT 1',
            $this->_id,
            $hash,
        );
        if ($res->RowCount() == 1) {
            $result = $res->FetchArray(MYSQLI_ASSOC);
            $res->Free();

            return $result;
        }

        return false;
    }

    /**
     * get the user's xor key salt
     *
     * @return string
     */
    function GetXORSalt() {
        $salt = $this->GetPref('XORKeySalt');

        if (!$salt || strlen($salt) < 64) {
            $salt = '';
            for ($i = 0; $i < 64; $i++) {
                $salt .= chr(mt_rand(0, 255));
            }
            $salt = base64_encode($salt);
            $this->SetPref('XORKeySalt', $salt);
        }

        $salt = base64_decode($salt);
        return $salt;
    }

    /**
     * generate XOR crypt key for user
     *
     * @param int $userID User ID
     * @param string $passwordPlain Plaintext user password
     * @return string
     */
    static function GenerateXORCryptKey($userID, $passwordPlain) {
        $user = _new('BMUser', [$userID]);
        $salt = $user->GetXORSalt();
        return md5($passwordPlain . $salt);
    }

    /**
     * encrypt and set private key password
     *
     * @param string $pw Plaintext password
     * @return bool
     */
    function SetPrivateKeyPassword($certID, $pw) {
        if (!isset($_SESSION['bm_xorCryptKey'])) {
            return false;
        }

        $encryptedPW = XORCrypt($pw, $_SESSION['bm_xorCryptKey']);
        $this->SetPref(
            'PrivateKeyPassword_' . $certID,
            base64_encode($encryptedPW),
        );

        return true;
    }

    /**
     * get and decrypt private key password
     *
     * @return string Plaintext password
     */
    function GetPrivateKeyPassword($certID) {
        if (!isset($_SESSION['bm_xorCryptKey'])) {
            return false;
        }

        $encryptedPW = $this->GetPref('PrivateKeyPassword_' . $certID);
        if (!$encryptedPW || strlen($encryptedPW) == 0) {
            return '';
        }

        $pw = XORCrypt(
            base64_decode($encryptedPW),
            $_SESSION['bm_xorCryptKey'],
        );
        return $pw;
    }

    /**
     * get all available private key passwords
     *
     * @return array
     */
    function GetPrivateKeyPasswords() {
        global $db;

        if (!isset($_SESSION['bm_xorCryptKey'])) {
            return false;
        }

        $result = [];

        $res = $db->Query(
            'SELECT `key`,`value` FROM {pre}userprefs WHERE userID=? AND `key` LIKE ?',
            (int) $this->_id,
            'PrivateKeyPassword_%',
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            [, $certID] = explode('_', $row['key']);
            $result[$certID] = XORCrypt(
                base64_decode($row['value']),
                $_SESSION['bm_xorCryptKey'],
            );
        }
        $res->Free();

        return $result;
    }

    /**
     * set private key passwords
     *
     * @param array $in Input (hash => pw)
     * @return bool
     */
    function SetPrivateKeyPasswords($in) {
        if (!isset($_SESSION['bm_xorCryptKey'])) {
            return false;
        }

        foreach ($in as $certID => $pw) {
            $encryptedPW = XORCrypt($pw, $_SESSION['bm_xorCryptKey']);
            $this->SetPref(
                'PrivateKeyPassword_' . $certID,
                base64_encode($encryptedPW),
            );
        }

        return true;
    }

    /**
     * set private key for cert
     *
     * @param int $certID Certificate hash
     * @param string $data PEM data
     */
    function SetPrivateKey($certID, $data) {
        $this->SetPref('PrivateKey_' . $certID, $data);
    }

    /**
     * get private key for cert
     *
     * @param string $certID Certificate hash
     * @return string PEM data
     */
    function GetPrivateKey($certID) {
        return $this->GetPref('PrivateKey_' . $certID);
    }

    /**
     * get all user-specific domains
     *
     * @return array
     */
    function GetUserDomains() {
        global $db;

        $domains = [];
        $res = $db->Query(
            'SELECT saliase FROM {pre}users WHERE LENGTH(saliase)!=0',
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $userDomains = explode(':', strtolower($row['saliase']));
            foreach ($userDomains as $domain) {
                if (!in_array($domain, $domains)) {
                    $domains[] = $domain;
                }
            }
        }
        $res->Free();

        return $domains;
    }

    /**
     * OTP-encrypt user password and store key in DB
     *
     * @param string $passwordPlain Plaintext password
     * @return string Encrypted password (cookie token)
     */
    function SaveLogin($passwordPlain) {
        global $db;

        $pwLength = strlen($passwordPlain);
        $cookieToken = '';
        $dbToken = '';

        for ($i = 0; $i < $pwLength; $i++) {
            $rand = mt_rand(0, 255);
            $dbToken .= chr($rand);
            $cookieToken .= chr(ord($passwordPlain[$i]) ^ $rand);
        }

        $dbToken = base64_encode($dbToken);
        $cookieToken = base64_encode($cookieToken);

        $db->Query(
            'INSERT INTO {pre}savedlogins(`expires`,`token`) VALUES(?,?)',
            time() + TIME_ONE_YEAR,
            $dbToken,
        );
        return $db->InsertId() . ':' . $cookieToken;
    }

    /**
     * decrypt saved password using DB token
     *
     * @param string $token Cookie token
     * @return string
     */
    function LoadLogin($token) {
        global $db;

        if (strlen($token) < 3 || strpos($token, ':') === false) {
            return false;
        }

        [$tokenID, $encryptedPW] = explode(':', $token);
        $res = $db->Query(
            'SELECT `token` FROM {pre}savedlogins WHERE `id`=?',
            $tokenID,
        );
        if ($res->RowCount() != 1) {
            return false;
        }
        [$dbToken] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        $dbToken = base64_decode($dbToken);
        $encryptedPW = base64_decode($encryptedPW);

        if (strlen($dbToken) != strlen($encryptedPW)) {
            return false;
        }

        $passwordPlain = '';
        for ($i = 0; $i < strlen($dbToken); $i++) {
            $passwordPlain .= chr(ord($encryptedPW[$i]) ^ ord($dbToken[$i]));
        }

        return $passwordPlain;
    }

    /**
     * delete a saved login token
     *
     * @param string $token Cookie token
     */
    function DeleteSavedLogin($token) {
        global $db;

        if (strlen($token) < 3 || strpos($token, ':') === false) {
            return false;
        }

        [$tokenID, $encryptedPW] = explode(':', $token);
        $db->Query('DELETE FROM {pre}savedlogins WHERE `id`=?', $tokenID);
        return true;
    }
}

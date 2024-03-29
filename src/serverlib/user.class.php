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

        return $locked;
    }

    /**
     * check address availability
     *
     * @param string $address
     * @return bool
     */
    static function AddressAvailable($address) {
        if (BMUser::GetID($address) != 0) {
            return false;
        }
        return true;
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
    static function ResetPassword($userID, $resetKey) {
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
                    $theOldUserRow = $theUserRow = BMUser::FetchUserById(
                        $row['id'],
                    );

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

        return BMUser::FetchUserById($id);
    }

    /**
     * This method is similar to `Fetch()`, but with the difference that it is static.
     */
    static function FetchUserById($id) {
        global $db;

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

        $senders[] = sprintf('<%s>', $this->_row['email']);
        foreach ($aliases as $alias) {
            if (
                ($alias['type'] & ALIAS_SENDER) != 0 &&
                ($alias['type'] & ALIAS_PENDING) == 0
            ) {
                $senders[] = sprintf('<%s>', $alias['email']);
            }
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
        $autoSaveDrafts,
        $autoSaveDraftsInterval
    ) {
        global $db, $bm_prefs;

        $this->SetPref('hotkeys', $hotkeys);

        $db->Query(
            'UPDATE {pre}users SET in_refresh=?, soforthtml=?, c_firstday=?, datumsformat=?, absendername=?, defaultSender=?, re=?, fwd=?, forward=?, forward_to=?, forward_delete=?, preview=?, conversation_view=?, newsletter_optin=?, plaintext_courier=?, reply_quote=?, attcheck=?, search_details_default=?, preferred_language=?, auto_save_drafts=?, auto_save_drafts_interval=? WHERE id=?',
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
}

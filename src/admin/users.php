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
include '../serverlib/mailbox.class.php';
RequestPrivileges(PRIVILEGES_ADMIN);
AdminRequirePrivilege('users');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'users';
}

$tabs = [
    0 => [
        'title' => $lang_admin['users'],
        'relIcon' => 'ico_users.png',
        'link' => 'users.php?',
        'active' => $_REQUEST['action'] == 'users',
    ],
    1 => [
        'title' => $lang_admin['search'],
        'relIcon' => 'user_search.png',
        'link' => 'users.php?action=search&',
        'active' => $_REQUEST['action'] == 'search',
    ],
    2 => [
        'title' => $lang_admin['create'],
        'relIcon' => 'ico_users.png',
        'link' => 'users.php?action=create&',
        'active' => $_REQUEST['action'] == 'create',
    ],
];

/**
 * users
 */
if ($_REQUEST['action'] == 'users') {
    if (!isset($_REQUEST['do'])) {
        $_REQUEST['do'] = 'list';
    }

    //
    // list
    //
    if ($_REQUEST['do'] == 'list') {
        // single action?
        if (isset($_REQUEST['singleAction'])) {
            if ($_REQUEST['singleAction'] == 'lock') {
                $db->Query(
                    'UPDATE {pre}users SET gesperrt=? WHERE id=?',
                    'yes',
                    $_REQUEST['singleID'],
                );
            } elseif (
                $_REQUEST['singleAction'] == 'unlock' ||
                $_REQUEST['singleAction'] == 'activate' ||
                $_REQUEST['singleAction'] == 'recover'
            ) {
                $db->Query(
                    'UPDATE {pre}users SET gesperrt=? WHERE id=?',
                    'no',
                    $_REQUEST['singleID'],
                );
            } elseif ($_REQUEST['singleAction'] == 'delete') {
                $res = $db->Query(
                    'SELECT gesperrt FROM {pre}users WHERE id=?',
                    $_REQUEST['singleID'],
                );
                [$userStatus] = $res->FetchArray(MYSQLI_NUM);
                $res->Free();

                if ($userStatus != 'delete') {
                    $db->Query(
                        'UPDATE {pre}users SET gesperrt=? WHERE id=?',
                        'delete',
                        $_REQUEST['singleID'],
                    );
                } else {
                    DeleteUser((int) $_REQUEST['singleID']);
                }
            } elseif ($_REQUEST['singleAction'] == 'emptyTrash') {
                // get user info
                $userObject = _new('BMUser', [$_REQUEST['singleID']]);
                $userRow = $userObject->Fetch();
                $userMail = $userRow['email'];

                // open mailbox
                $mailbox = _new('BMMailbox', [
                    $_REQUEST['singleID'],
                    $userMail,
                    $userObject,
                ]);

                // empty trash
                $deletedMails = $mailbox->EmptyFolder(FOLDER_TRASH);
            }
        }

        // mass action
        if (isset($_REQUEST['executeMassAction'])) {
            // get user IDs
            $userIDs = [];
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 5) == 'user_') {
                    $userIDs[] = (int) substr($key, 5);
                }
            }

            if (count($userIDs) > 0) {
                if ($_REQUEST['massAction'] == 'delete') {
                    // get states
                    $markIDs = $deleteIDs = [];
                    $res = $db->Query(
                        'SELECT id,gesperrt FROM {pre}users WHERE id IN(' .
                            implode(',', $userIDs) .
                            ')',
                    );
                    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                        if ($row['gesperrt'] == 'delete') {
                            $deleteIDs[] = $row['id'];
                        } else {
                            $markIDs[] = $row['id'];
                        }
                    }

                    // mark users
                    if (count($markIDs) > 0) {
                        $db->Query(
                            'UPDATE {pre}users SET gesperrt=? WHERE id IN(' .
                                implode(',', $markIDs) .
                                ')',
                            'delete',
                        );
                    }

                    // delete users
                    foreach ($deleteIDs as $userID) {
                        DeleteUser($userID);
                    }
                } elseif (
                    $_REQUEST['massAction'] == 'restore' ||
                    $_REQUEST['massAction'] == 'unlock'
                ) {
                    $db->Query(
                        'UPDATE {pre}users SET gesperrt=? WHERE id IN(' .
                            implode(',', $userIDs) .
                            ')',
                        'no',
                    );
                } elseif ($_REQUEST['massAction'] == 'lock') {
                    $db->Query(
                        'UPDATE {pre}users SET gesperrt=? WHERE id IN(' .
                            implode(',', $userIDs) .
                            ')',
                        'yes',
                    );
                } elseif (substr($_REQUEST['massAction'], 0, 7) == 'moveto_') {
                    $groupID = (int) substr($_REQUEST['massAction'], 7);
                    $db->Query(
                        'UPDATE {pre}users SET gruppe=? WHERE id IN(' .
                            implode(',', $userIDs) .
                            ')',
                        $groupID,
                    );
                }
            }
        }

        // sort options
        $sortBy = isset($_REQUEST['sortBy']) ? $_REQUEST['sortBy'] : 'email';
        $sortOrder = isset($_REQUEST['sortOrder'])
            ? strtolower($_REQUEST['sortOrder'])
            : 'asc';
        $perPage = max(
            1,
            isset($_REQUEST['perPage']) ? (int) $_REQUEST['perPage'] : 50,
        );

        // filter options
        $statusRegistered = $statusActive = $statusLocked = $statusNotActivated = $statusDeleted = true;
        if (isset($_REQUEST['filter'])) {
            $statusRegistered = isset($_REQUEST['statusRegistered']);
            $statusActive = isset($_REQUEST['statusActive']);
            $statusLocked = isset($_REQUEST['statusLocked']);
            $statusNotActivated = isset($_REQUEST['statusNotActivated']);
            $statusDeleted = isset($_REQUEST['statusDeleted']);
        }
        $groups = BMGroup::GetSimpleGroupList();

        // profile fields
        $fields = [];
        $res = $db->Query('SELECT id,feld,typ FROM {pre}profilfelder');
        while ($row = $res->FetchArray()) {
            $row['checked'] = isset($_REQUEST['field_' . $row['id']]);
            $fields[$row['id']] = $row;
        }
        $res->Free();

        // query stuff
        $groupIDs = [];
        foreach ($groups as $groupID => $groupInfo) {
            $groups[$groupID]['checked'] =
                (!isset($_REQUEST['filter']) &&
                    !isset($_REQUEST['onlyGroup'])) ||
                isset($_REQUEST['group_' . $groupID]) ||
                (isset($_REQUEST['onlyGroup']) &&
                    $_REQUEST['onlyGroup'] == $groupID) ||
                isset($_REQUEST['allGroups']);
            if ($groups[$groupID]['checked']) {
                $groupIDs[] = $groupID;
            }
        }
        $lockedValues = [];
        if ($statusActive) {
            $lockedValues[] = '\'no\'';
        }
        if ($statusLocked) {
            $lockedValues[] = '\'yes\'';
        }
        if ($statusNotActivated) {
            $lockedValues[] = '\'locked\'';
        }
        if ($statusDeleted) {
            $lockedValues[] = '\'delete\'';
        }
        $queryGroups = count($groupIDs) > 0 ? implode(',', $groupIDs) : '0';
        $queryLocked =
            count($lockedValues) > 0 ? implode(',', $lockedValues) : '0';
        $theQuery =
            'FROM {pre}users WHERE (' .
            'gruppe IN(' .
            $queryGroups .
            ') AND ' .
            '(gesperrt IN (' .
            $queryLocked .
            ')';
        if ($statusRegistered) {
            $theQuery .= ' OR (lastlogin=0 AND gesperrt=\'no\')';
        } else {
            $theQuery .= ' AND (lastlogin>0 OR gesperrt!=\'no\')';
        }
        $theQuery .= '))';

        // search?
        if (isset($_REQUEST['query'])) {
            $query = unserialize($_REQUEST['query']);

            if (
                is_array($query) &&
                count($query) == 2 &&
                is_array($query[1]) &&
                is_string($query[0]) &&
                count($query[1]) > 0
            ) {
                [$queryString, $queryFields] = $query;

                // query suffix
                $theQuery .= sprintf(
                    ' AND (CAST(CONCAT(`%s`) AS CHAR) LIKE \'%%%s%%\'',
                    implode('`,\' \',`', $queryFields),
                    $db->Escape($queryString),
                );

                // alias search?
                if (in_array('email', $queryFields)) {
                    $aliasUserIDs = [];

                    $res = $db->Query(
                        'SELECT `user` FROM {pre}aliase WHERE `email` LIKE \'%' .
                            $db->Escape($queryString) .
                            '%\'',
                    );
                    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                        if (!in_array($row['user'], $aliasUserIDs)) {
                            $aliasUserIDs[] = $row['user'];
                        }
                    }
                    $res->Free();

                    if (count($aliasUserIDs) > 0) {
                        $theQuery .=
                            ' OR `id` IN (' . implode(',', $aliasUserIDs) . ')';
                    }
                }

                $theQuery .= ')';

                // template stuff
                $tabs[0]['active'] = false;
                $tabs[1]['active'] = true;
                $tpl->assign('searchQuery', $queryString);
            }
        }

        // page calculation
        $res = $db->Query('SELECT COUNT(*) ' . $theQuery);
        [$userCount] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();
        $pageCount = ceil($userCount / $perPage);
        $pageNo = isset($_REQUEST['page'])
            ? max(1, min($pageCount, (int) $_REQUEST['page']))
            : 1;
        $startPos = max(0, min($perPage * ($pageNo - 1), $userCount));

        // do the query!
        $users = [];
        $res = $db->Query(
            'SELECT id,email,vorname,nachname,strasse,hnr,plz,ort,gruppe,gesperrt,lastlogin,profilfelder ' .
                $theQuery .
                ' ' .
                'ORDER BY ' .
                $sortBy .
                ' ' .
                $sortOrder .
                ' ' .
                'LIMIT ' .
                $startPos .
                ',' .
                $perPage,
        );
        while ($row = $res->FetchArray()) {
            $aliases = [];
            $aliasRes = $db->Query(
                'SELECT email FROM {pre}aliase WHERE type=? AND user=? ORDER BY email ASC',
                ALIAS_RECIPIENT | ALIAS_SENDER,
                $row['id'],
            );
            while ($aliasRow = $aliasRes->FetchArray()) {
                $aliases[] = DecodeSingleEMail($aliasRow['email']);
            }
            $aliasRes->Free();

            $row['groupName'] = isset($groups[$row['gruppe']])
                ? $groups[$row['gruppe']]['title']
                : $lang_admin['missing'];
            $row['aliases'] =
                count($aliases) > 0 ? implode(', ', $aliases) : '';

            if ($row['lastlogin'] == 0 && $row['gesperrt'] == 'no') {
                $row['status'] = $statusTable['registered'];
                $row['statusImg'] = $statusImgTable['registered'];
            } else {
                $row['status'] = $statusTable[$row['gesperrt']];
                $row['statusImg'] = $statusImgTable[$row['gesperrt']];
            }

            $profileData = @unserialize($row['profilfelder']);
            if (!is_array($profileData)) {
                $profileData = [];
            }
            $row['profileData'] = $profileData;

            $users[$row['id']] = $row;
        }
        $res->Free();

        // assign
        $tpl->assign('users', $users);
        $tpl->assign('fields', $fields);
        $tpl->assign('pageNo', $pageNo);
        $tpl->assign('pageCount', $pageCount);
        $tpl->assign('sortBy', $sortBy);
        $tpl->assign('sortOrder', $sortOrder);
        $tpl->assign('sortOrderInv', $sortOrder == 'asc' ? 'desc' : 'asc');
        $tpl->assign('statusRegistered', $statusRegistered);
        $tpl->assign('statusActive', $statusActive);
        $tpl->assign('statusLocked', $statusLocked);
        $tpl->assign('statusNotActivated', $statusNotActivated);
        $tpl->assign('statusDeleted', $statusDeleted);
        $tpl->assign(
            'queryString',
            isset($_REQUEST['query']) ? $_REQUEST['query'] : '',
        );
        $tpl->assign('groups', $groups);
        $tpl->assign('perPage', $perPage);
        $tpl->assign('page', 'users.list.tpl');
    }

    //
    // edit
    //
    elseif ($_REQUEST['do'] == 'edit') {
        // save?
        if (isset($_REQUEST['save']) && isset($_POST['email'])) {
            // prepare aliases
            $saliaseArray = explode("\n", $_REQUEST['saliase']);
            foreach ($saliaseArray as $key => $val) {
                if (($val = trim($val)) != '') {
                    $saliaseArray[$key] = EncodeDomain($val);
                } else {
                    unset($saliaseArray[$key]);
                }
            }
            $saliase = implode(':', $saliaseArray);

            // profile fields
            $profileData = [];
            $res = $db->Query(
                'SELECT id,typ FROM {pre}profilfelder ORDER BY id ASC',
            );
            while ($row = $res->FetchArray()) {
                if ($row['typ'] == FIELD_DATE) {
                    $profileData[$row['id']] = sprintf(
                        '%04d-%02d-%02d',
                        $_POST['field_' . $row['id'] . 'Year'],
                        $_POST['field_' . $row['id'] . 'Month'],
                        $_POST['field_' . $row['id'] . 'Day'],
                    );
                } else {
                    $profileData[$row['id']] =
                        $row['typ'] == FIELD_CHECKBOX
                            ? isset($_REQUEST['field_' . $row['id']])
                            : (isset($_REQUEST['field_' . $row['id']])
                                ? $_REQUEST['field_' . $row['id']]
                                : false);
                }
            }
            $res->Free();

            // update common stuff
            $db->Query(
                'UPDATE {pre}users SET profilfelder=?, email=?, vorname=?, nachname=?, strasse=?, hnr=?, plz=?, ort=?, land=?, tel=?, fax=?, altmail=?, gruppe=?, gesperrt=?, notes=?, re=?, fwd=?, forward=?, forward_to=?, `newsletter_optin`=?, datumsformat=?, absendername=?, anrede=?, saliase=?, mailspace_add=?, diskspace_add=?, traffic_add=? WHERE id=?',
                serialize($profileData),
                EncodeEMail($_REQUEST['email']),
                $_REQUEST['vorname'],
                $_REQUEST['nachname'],
                $_REQUEST['strasse'],
                $_REQUEST['hnr'],
                $_REQUEST['plz'],
                $_REQUEST['ort'],
                $_REQUEST['land'],
                $_REQUEST['tel'],
                $_REQUEST['fax'],
                EncodeEMail($_REQUEST['altmail']),
                $_REQUEST['gruppe'],
                $_REQUEST['gesperrt'],
                $_REQUEST['notes'],
                $_REQUEST['re'],
                $_REQUEST['fwd'],
                $_REQUEST['forward'],
                EncodeEMail($_REQUEST['forward_to']),
                $_REQUEST['newsletter_optin'],
                $_REQUEST['datumsformat'],
                $_REQUEST['absendername'],
                $_REQUEST['anrede'],
                $saliase,
                $_REQUEST['mailspace_add'] * 1024 * 1024,
                $_REQUEST['diskspace_add'] * 1024 * 1024,
                $_REQUEST['traffic_add'] * 1024 * 1024,
                $_REQUEST['id'],
            );

            // update password?
            if (
                isset($_REQUEST['passwort']) &&
                strlen(trim($_REQUEST['passwort'])) > 0
            ) {
                $salt = GenerateRandomSalt(8);
                $db->Query(
                    'UPDATE {pre}users SET passwort=?,passwort_salt=? WHERE id=?',
                    md5(
                        md5(
                            CharsetDecode(
                                $_REQUEST['passwort'],
                                false,
                                'ISO-8859-15',
                            ),
                        ) . $salt,
                    ),
                    $salt,
                    $_REQUEST['id'],
                );
            }
        }

        // move?
        if (isset($_REQUEST['moveToGroup'])) {
            $db->Query(
                'UPDATE {pre}users SET gruppe=? WHERE id=?',
                (int) $_REQUEST['moveToGroup'],
                (int) $_REQUEST['id'],
            );
        }

        // delete alias?
        if (isset($_REQUEST['deleteAlias'])) {
            $db->Query(
                'DELETE FROM {pre}aliase WHERE id=? AND user=?',
                (int) $_REQUEST['deleteAlias'],
                (int) $_REQUEST['id'],
            );
            $tpl->assign('showAliases', true);
        }

        // get user data
        $userObject = _new('BMUser', [(int) $_REQUEST['id']]);
        $userRow = $user = $userObject->Fetch();
        $userMailbox = _new('BMMailbox', [
            $userRow['id'],
            $userRow['email'],
            $userObject,
        ]);

        // aliases
        $aliases = $userObject->GetAliases();
        foreach ($aliases as $key => $val) {
            $aliases[$key]['type'] = $aliasTypeTable[$val['type']];
        }

        // get group data
        $groupObject = $userObject->GetGroup();
        $group = $groupObject->Fetch();

        // traffic?
        if ($user['traffic_status'] != (int) date('m')) {
            $user['traffic_down'] = $user['traffic_up'] = 0;
        }

        // get usage stuff
        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}mails WHERE userid=?',
            $user['id'],
        );
        [$emailMails] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();
        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}folders WHERE userid=?',
            $user['id'],
        );
        [$emailFolders] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();
        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}diskfiles WHERE user=?',
            $user['id'],
        );
        [$diskFiles] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();
        $res = $db->Query(
            'SELECT COUNT(*) FROM {pre}diskfolders WHERE user=?',
            $user['id'],
        );
        [$diskFolders] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        // profile fields
        $profileFields = [];
        $profileData = [];
        if (strlen($user['profilfelder']) > 2) {
            $profileData = @unserialize($user['profilfelder']);
            if (!is_array($profileData)) {
                $profileData = [];
            }
        }
        $res = $db->Query(
            'SELECT id,typ,feld,extra FROM {pre}profilfelder ORDER BY feld ASC',
        );
        while ($row = $res->FetchArray()) {
            $profileFields[] = [
                'id' => $row['id'],
                'title' => $row['feld'],
                'type' => $row['typ'],
                'extra' => explode(',', $row['extra']),
                'value' => isset($profileData[$row['id']])
                    ? $profileData[$row['id']]
                    : false,
            ];
        }
        $res->Free();

        // history?
        $historyCount = 0;
        if (trim($user['contactHistory']) != '') {
            $contactHistory = @unserialize($user['contactHistory']);
            if (is_array($contactHistory)) {
                $historyCount = count($contactHistory);
            }
        }

        $user['saliase'] = implode(
            "\n",
            array_map('DecodeDomain', explode(':', $user['saliase'])),
        );

        $countryList = CountryList();
        asort($countryList);

        // assign
        $tpl->assign('historyCount', $historyCount);
        $tpl->assign('user', $user);
        $tpl->assign('group', $group);
        $tpl->assign('groups', BMGroup::GetSimpleGroupList());
        $tpl->assign('aliases', $aliases);
        $tpl->assign('countries', $countryList);
        $tpl->assign('emailMails', $emailMails);
        $tpl->assign('emailFolders', $emailFolders);
        $tpl->assign('profileFields', $profileFields);
        $tpl->assign('diskFiles', $diskFiles);
        $tpl->assign('diskFolders', $diskFolders);
        $tpl->assign('page', 'users.edit.tpl');
    }

    //
    // contact history
    //
    elseif ($_REQUEST['do'] == 'contactHistory' && isset($_REQUEST['id'])) {
        // get user data
        $userObject = _new('BMUser', [(int) $_REQUEST['id']]);
        $user = $userObject->Fetch();
        $history = @unserialize($user['contactHistory']);
        if (!is_array($history)) {
            $history = [];
        }
        $history[] = $user;
        $history = array_reverse($history);

        $countryList = CountryList();
        asort($countryList);

        // assign
        $tpl->assign('countries', $countryList);
        $tpl->assign('history', $history);
        $tpl->assign('user', $user);
        $tpl->assign('page', 'users.contacthistory.tpl');
    }

    //
    // clear contact history
    //
    elseif ($_REQUEST['do'] == 'clearHistory' && isset($_REQUEST['id'])) {
        $db->Query(
            'UPDATE {pre}users SET contactHistory=? WHERE id=?',
            '',
            (int) $_REQUEST['id'],
        );
        header(
            'Location: users.php?do=edit&id=' .
                $_REQUEST['id'] .
                '&sid=' .
                session_id(),
        );
        exit();
    }

    //
    // login
    //
    elseif ($_REQUEST['do'] == 'login') {
        $userObject = _new('BMUser', [(int) $_REQUEST['id']]);
        $userRow = $userObject->Fetch();

        // log this
        PutLog(
            sprintf(
                'Admin logs in as user <%s> (%d) from <%s>',
                $userRow['email'],
                $userRow['id'],
                $_SERVER['REMOTE_ADDR'],
            ),
            PRIO_NOTE,
            __FILE__,
            __LINE__,
        );
        $adminAuth = sprintf('%d,%d', $userRow['id'], $adminRow['adminid']);
        $adminAuth .= ',' . md5($adminAuth . $_SESSION['bm_adminAuth']);

        // create new session
        header(
            sprintf(
                'Location: ../index.php?do=login&email_full=%s&adminAuth=%s',
                urlencode($userRow['email']),
                urlencode(base64_encode($adminAuth)),
            ),
        );
        exit();
    }
} /**
 * search
 */ elseif ($_REQUEST['action'] == 'search') {
    // display form
    if (!isset($_REQUEST['do'])) {
        // assign
        $tpl->assign('page', 'users.search.tpl');
    }

    // build search URL and redirect
    elseif ($_REQUEST['do'] == 'search') {
        // check params
        if (
            !isset($_REQUEST['searchIn']) ||
            !is_array($_REQUEST['searchIn']) ||
            strlen(trim($_REQUEST['q'])) < 1
        ) {
            header('Location: users.php?action=search&sid=' . session_id());
            exit();
        }

        // collect fields
        $fields = [];
        foreach ($_REQUEST['searchIn'] as $field => $val) {
            if ($field == 'id') {
                $fields[] = 'id';
            } elseif ($field == 'email') {
                $fields[] = 'email';
            } elseif ($field == 'altmail') {
                $fields[] = 'altmail';
            } elseif ($field == 'name') {
                $fields = array_merge($fields, ['vorname', 'nachname']);
            } elseif ($field == 'address') {
                $fields = array_merge($fields, [
                    'strasse',
                    'hnr',
                    'plz',
                    'ort',
                    'land',
                ]);
            } elseif ($field == 'telfaxmobile') {
                $fields = array_merge($fields, ['tel', 'fax']);
            }
        }

        // build query string
        $queryString = serialize([trim($_REQUEST['q']), $fields]);
        header(
            'Location: users.php?query=' .
                urlencode($queryString) .
                '&sid=' .
                session_id(),
        );
        exit();
    }
} /**
 * create user
 */ elseif ($_REQUEST['action'] == 'create') {
    // create user
    if (isset($_REQUEST['create'])) {
        $msgIcon = 'error32';
        $msgText = '?';

        // check address syntax
        $email = trim($_REQUEST['email']) . '@' . $_REQUEST['emailDomain'];
        if (BMUser::AddressValid($email)) {
            // check address availability
            if (BMUser::AddressAvailable($email)) {
                // profile fields
                $profileData = [];
                $res = $db->Query(
                    'SELECT id,typ FROM {pre}profilfelder ORDER BY id ASC',
                );
                while ($row = $res->FetchArray()) {
                    if ($row['typ'] == FIELD_DATE) {
                        $profileData[$row['id']] = sprintf(
                            '%04d-%02d-%02d',
                            $_POST['field_' . $row['id'] . 'Year'],
                            $_POST['field_' . $row['id'] . 'Month'],
                            $_POST['field_' . $row['id'] . 'Day'],
                        );
                    } else {
                        $profileData[$row['id']] =
                            $row['typ'] == FIELD_CHECKBOX
                                ? isset($_REQUEST['field_' . $row['id']])
                                : (isset($_REQUEST['field_' . $row['id']])
                                    ? $_REQUEST['field_' . $row['id']]
                                    : false);
                    }
                }
                $res->Free();

                // create account
                $userID = BMUser::CreateAccount(
                    $email,
                    $_REQUEST['vorname'],
                    $_REQUEST['nachname'],
                    $_REQUEST['strasse'],
                    $_REQUEST['hnr'],
                    $_REQUEST['plz'],
                    $_REQUEST['ort'],
                    $_REQUEST['land'],
                    $_REQUEST['tel'],
                    $_REQUEST['fax'],
                    $_REQUEST['altmail'],
                    $_REQUEST['passwort'],
                    $profileData,
                    $_REQUEST['anrede'],
                );

                // update misc stuff
                $db->Query(
                    'UPDATE {pre}users SET gruppe=?, gesperrt=?, notes=? WHERE id=?',
                    $_REQUEST['gruppe'],
                    $_REQUEST['gesperrt'],
                    $_REQUEST['notes'],
                    $userID,
                );

                $msgIcon = 'info32';
                $msgText = sprintf(
                    $lang_admin['accountcreated'],
                    $userID,
                    session_id(),
                );
                $tpl->assign('backLink', 'users.php?action=create&');
            } else {
                $msgText = $lang_admin['addresstaken'];
                $msgIcon = 'error32';
            }
        } else {
            $msgText = $lang_admin['addressinvalid'];
            $msgIcon = 'error32';
        }

        // assign
        $tpl->assign('msgTitle', $lang_admin['create']);
        $tpl->assign('msgText', $msgText);
        $tpl->assign('msgIcon', $msgIcon);
        $tpl->assign('page', 'msg.tpl');
    }

    // display form
    else {
        // profile fields
        $profileFields = [];
        $res = $db->Query(
            'SELECT id,typ,feld,extra FROM {pre}profilfelder ORDER BY feld ASC',
        );
        while ($row = $res->FetchArray()) {
            $profileFields[] = [
                'id' => $row['id'],
                'title' => $row['feld'],
                'type' => $row['typ'],
                'extra' => explode(',', $row['extra']),
            ];
        }
        $res->Free();

        $countryList = CountryList();
        asort($countryList);

        // assign
        $tpl->assign('profileFields', $profileFields);
        $tpl->assign('groups', BMGroup::GetSimpleGroupList());
        $tpl->assign('defaultGroup', $bm_prefs['std_gruppe']);
        $tpl->assign('countries', $countryList);
        $tpl->assign('defaultCountry', $bm_prefs['std_land']);
        $tpl->assign('domainList', GetDomainList());
        $tpl->assign('page', 'users.create.tpl');
    }
}

$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['usersgroups'] . ' &raquo; ' . $lang_admin['users'],
);
$tpl->display('page.tpl');
?>

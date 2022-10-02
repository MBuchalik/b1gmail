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

if (!file_exists('./serverlib/init.inc.php')) {
    // Maybe, an update (or similar maintenance work) is currently taking place.
    exit();
}

include './serverlib/init.inc.php';
include './serverlib/mailbox.class.php';
RequestPrivileges(PRIVILEGES_USER);

$prefsItems = $prefsImages = $prefsIcons = [];

/**
 * file handler for modules
 */
ModuleFunction('FileHandler', [
    substr(__FILE__, strlen(__DIR__) + 1),
    isset($_REQUEST['action']) ? $_REQUEST['action'] : '',
]);

/**
 * default action = start
 */
$tpl->addJSFile('li', $tpl->tplDir . 'js/prefs.js');
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'start';
}
$tpl->assign('activeTab', 'prefs');
$tpl->assign('pageTitle', $lang_user['prefs']);
$null = null;

/**
 * open mailbox
 */
$mailbox = _new('BMMailbox', [$userRow['id'], $userRow['email'], $thisUser]);

/**
 * features
 */
$prefsItems['common'] = true;
$prefsItems['contact'] = true;
if ($groupRow['smime'] == 'yes') {
    $prefsItems['keyring'] = true;
}
$prefsItems['signatures'] = true;
$prefsItems['filters'] = true;
if ($bm_prefs['use_clamd'] == 'yes') {
    $prefsItems['antivirus'] = true;
}
if ($bm_prefs['use_bayes'] == 'yes' || $bm_prefs['spamcheck'] == 'yes') {
    $prefsItems['antispam'] = true;
}
if ($groupRow['aliase'] > 0) {
    $prefsItems['aliases'] = true;
}
if ($groupRow['responder'] == 'yes') {
    $prefsItems['autoresponder'] = true;
}
if ($groupRow['ownpop3'] > 0) {
    $prefsItems['extpop3'] = true;
}
$prefsItems['faq'] = true;
$prefsItems['membership'] = true;

function PrefsDone() {
    header('Location: prefs.php?sid=' . session_id());
    exit();
}

function _prefsItemsSort($a, $b) {
    global $lang_user;
    return strcmp($lang_user[$a], $lang_user[$b]);
}
uksort($prefsItems, '_prefsItemsSort');

$tpl->assign('prefsItems', $prefsItems);
$tpl->assign('prefsImages', $prefsImages);
$tpl->assign('prefsIcons', $prefsIcons);

/**
 * page sidebar
 */
$tpl->assign('pageMenuFile', 'li/prefs.sidebar.tpl');

/**
 * start page
 */
if ($_REQUEST['action'] == 'start') {
    $tpl->assign('pageContent', 'li/prefs.start.tpl');
    $tpl->display('li/index.tpl');
} /**
 * common
 */ elseif ($_REQUEST['action'] == 'common') {
    $defaultName = $userRow['vorname'] . ' ' . $userRow['nachname'];
    $composeDefaults = @unserialize($thisUser->GetPref('composeDefaults'));
    if (!is_array($composeDefaults)) {
        $composeDefaults = [];
    }

    // save?
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'save' &&
        IsPOSTRequest()
    ) {
        if ($_REQUEST['absendername'] == $defaultName) {
            $_REQUEST['absendername'] = '';
        }
        $thisUser->UpdateCommonSettings(
            isset($_REQUEST['in_refresh_active'])
                ? max(15, (int) $_REQUEST['in_refresh'])
                : 0,
            isset($_REQUEST['soforthtml']),
            (int) $_REQUEST['c_firstday'],
            $_REQUEST['datumsformat'],
            $_REQUEST['absendername'],
            (int) $_REQUEST['defaultSender'],
            $_REQUEST['re'],
            $_REQUEST['fwd'],
            isset($_REQUEST['forward']),
            $groupRow['forward'] == 'yes'
                ? EncodeEMail($_REQUEST['forward_to'])
                : '',
            isset($_REQUEST['forward_delete']),
            isset($_REQUEST['preview']),
            isset($_REQUEST['conversation_view']),
            isset($_REQUEST['newsletter_optin']),
            isset($_REQUEST['plaintext_courier']),
            isset($_REQUEST['reply_quote']),
            isset($_REQUEST['hotkeys']),
            isset($_REQUEST['attcheck']),
            isset($_REQUEST['search_details_default']),
            $_REQUEST['preferred_language'],
            isset($_REQUEST['notify_sound']),
            isset($_REQUEST['notify_email']),
            isset($_REQUEST['notify_birthday']),
            isset($_REQUEST['auto_save_drafts']),
            $_REQUEST['auto_save_drafts_interval'],
        );
        if (!empty($_REQUEST['preferred_language'])) {
            $_SESSION['bm_sessionLanguage'] = $_REQUEST['preferred_language'];
        } else {
            unset($_SESSION['bm_sessionLanguage']);
        }
        if ($groupRow['smime'] == 'yes') {
            $thisUser->SetPref('smimeSign', isset($_REQUEST['smimeSign']));
            $thisUser->SetPref(
                'smimeEncrypt',
                isset($_REQUEST['smimeEncrypt']),
            );
        }
        $thisUser->SetPref('autosend_dn', isset($_REQUEST['autosend_dn']));
        $thisUser->SetPref('linesep', isset($_REQUEST['linesep']));
        $composeDefaults =
            isset($_REQUEST['composeDefaults']) &&
            is_array($_REQUEST['composeDefaults'])
                ? $_REQUEST['composeDefaults']
                : [];
        $thisUser->SetPref('composeDefaults', serialize($composeDefaults));

        PrefsDone();
    }

    // assign prefs
    $tpl->assign('composeDefaults', $composeDefaults);
    $tpl->assign('allownewsoptout', $groupRow['allow_newsletter_optout']);
    $tpl->assign('newsletter_optin', $userRow['newsletter_optin']);
    $tpl->assign('preview', $userRow['preview']);
    $tpl->assign('forward', $userRow['forward']);
    $tpl->assign('forward_to', $userRow['forward_to']);
    $tpl->assign('forward_delete', $userRow['forward_delete']);
    $tpl->assign('in_refresh', (int) $userRow['in_refresh']);
    $tpl->assign('soforthtml', $userRow['soforthtml']);
    $tpl->assign('datumsformat', $userRow['datumsformat']);
    $tpl->assign('c_firstday', $userRow['c_firstday']);
    $tpl->assign('defaultSender', $userRow['defaultSender']);
    $tpl->assign('re', $userRow['re']);
    $tpl->assign('fwd', $userRow['fwd']);
    $tpl->assign('conversation_view', $userRow['conversation_view']);
    $tpl->assign('preferred_language', $userRow['preferred_language']);
    $tpl->assign(
        'absendername',
        trim($userRow['absendername']) != ''
            ? $userRow['absendername']
            : $defaultName,
    );
    $tpl->assign('plaintext_courier', $userRow['plaintext_courier']);
    $tpl->assign('smimeSign', $thisUser->GetPref('smimeSign'));
    $tpl->assign('smimeEncrypt', $thisUser->GetPref('smimeEncrypt'));
    $tpl->assign('reply_quote', $userRow['reply_quote']);
    $tpl->assign('hotkeys', $thisUser->GetPref('hotkeys'));
    $tpl->assign('attcheck', $userRow['attcheck']);
    $tpl->assign('autosend_dn', $thisUser->GetPref('autosend_dn'));
    $tpl->assign('linesep', $thisUser->GetPref('linesep'));
    $tpl->assign('notifySound', $userRow['notify_sound'] == 'yes');
    $tpl->assign('notifyEMail', $userRow['notify_email'] == 'yes');
    $tpl->assign('notifyBirthday', $userRow['notify_birthday'] == 'yes');
    $tpl->assign('autoSaveDrafts', $userRow['auto_save_drafts'] == 'yes');
    $tpl->assign(
        'autoSaveDraftsInterval',
        max(
            $bm_prefs['min_draft_save_interval'],
            $userRow['auto_save_drafts_interval'],
        ),
    );

    // display
    $tpl->assign('availableLanguages', GetAvailableLanguages());
    $tpl->assign('signatures', $thisUser->GetSignatures());
    $tpl->assign(
        'dropdownFolderList',
        $mailbox->GetDropdownFolderList(-1, $null),
    );
    $tpl->assign('smimeAllowed', $groupRow['smime'] == 'yes');
    $tpl->assign('forwardingAllowed', $groupRow['forward'] == 'yes');
    $tpl->assign(
        'draftAutoSaveAllowed',
        $groupRow['auto_save_drafts'] == 'yes',
    );
    $tpl->assign('fullWeekdays', $lang_user['full_weekdays']);
    $tpl->assign('possibleSenders', $thisUser->GetPossibleSenders());
    $tpl->assign('pageContent', 'li/prefs.common.tpl');
    $tpl->assign('activeItem', 'common');
    $tpl->display('li/index.tpl');
} /**
 * contact
 */ elseif ($_REQUEST['action'] == 'contact') {
    // save?
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'save' &&
        IsPOSTRequest()
    ) {
        $invalidFields = [];
        $errorInfo = '';

        //
        // check fields
        //

        $userRow['vorname'] = trim($_POST['vorname']);
        if (strlen($userRow['vorname']) < 2) {
            $invalidFields[] = 'vorname';
        }

        $userRow['nachname'] = $_POST['nachname'];
        if (strlen($userRow['nachname']) < 2) {
            $invalidFields[] = 'nachname';
        }

        // salutation
        if ($bm_prefs['f_anrede'] != 'n') {
            $userRow['anrede'] = trim($_POST['salutation']);
            if (
                (!in_array($userRow['anrede'], ['herr', 'frau']) &&
                    $bm_prefs['f_anrede'] == 'p') ||
                ($bm_prefs['f_anrede'] == 'v' &&
                    !in_array($userRow['anrede'], ['herr', 'frau', '']))
            ) {
                $invalidFields[] = 'salutation';
            }
        } else {
            $userRow['anrede'] = '';
        }

        // 'strasse'-group
        if ($bm_prefs['f_strasse'] != 'n') {
            // street
            $userRow['strasse'] = trim($_POST['strasse']);
            if (
                strlen($userRow['strasse']) < 3 &&
                (strlen($userRow['strasse']) > 0 ||
                    $bm_prefs['f_strasse'] == 'p')
            ) {
                $invalidFields[] = 'strasse';
            }

            // no
            $userRow['hnr'] = trim($_POST['hnr']);
            if (
                strlen($userRow['hnr']) < 1 &&
                (strlen($userRow['hnr']) > 0 || $bm_prefs['f_strasse'] == 'p')
            ) {
                $invalidFields[] = 'hnr';
            }

            // zip
            $userRow['plz'] = trim($_POST['plz']);
            if (
                strlen($userRow['plz']) < 3 &&
                (strlen($userRow['plz']) > 0 || $bm_prefs['f_strasse'] == 'p')
            ) {
                $invalidFields[] = 'plz';
            }

            // city
            $userRow['ort'] = trim($_POST['ort']);
            if (
                strlen($userRow['ort']) < 3 &&
                (strlen($userRow['ort']) > 0 || $bm_prefs['f_strasse'] == 'p')
            ) {
                $invalidFields[] = 'ort';
            }

            // country
            $userRow['land'] = (int) $_POST['land'];
        }

        // 'telefon'-field
        if ($bm_prefs['f_telefon'] != 'n') {
            $userRow['tel'] = trim($_POST['tel']);
            if (
                strlen($userRow['tel']) < 5 &&
                (strlen($userRow['tel']) > 0 || $bm_prefs['f_telefon'] == 'p')
            ) {
                $invalidFields[] = 'tel';
            }
        }

        // 'fax'-field
        if ($bm_prefs['f_fax'] != 'n') {
            $userRow['fax'] = trim($_POST['fax']);
            if (
                strlen($userRow['fax']) < 5 &&
                (strlen($userRow['fax']) > 0 || $bm_prefs['f_fax'] == 'p')
            ) {
                $invalidFields[] = 'fax';
            }
        }

        // 'altmail'-field
        if ($bm_prefs['f_alternativ'] != 'n') {
            $userRow['altmail'] = trim($_POST['altmail']);
            if (
                (strlen($userRow['altmail']) > 0 ||
                    $bm_prefs['f_alternativ'] == 'p') &&
                (!BMUser::AddressValid($userRow['altmail'], false) ||
                    AltMailLocked($userRow['altmail']) ||
                    ($bm_prefs['alt_check'] == 'yes' &&
                        !ValidateMailAddress($userRow['altmail'])))
            ) {
                $invalidFields[] = 'altmail';
            } else {
                if (
                    $bm_prefs['check_double_altmail'] == 'yes' &&
                    strlen($userRow['altmail']) > 0
                ) {
                    $res = $db->Query(
                        'SELECT COUNT(*) FROM {pre}users WHERE `altmail`=? AND `id`!=?',
                        $userRow['altmail'],
                        $userRow['id'],
                    );
                    [$altMailCount] = $res->FetchArray(MYSQLI_NUM);
                    $res->Free();

                    if ($altMailCount > 0) {
                        $invalidFields[] = 'altmail';
                        $errorInfo .= ' ' . $lang_user['doublealtmail'];
                    }
                }
            }
        }

        // profile fields
        $suProfile = [];
        if (strlen($userRow['profilfelder']) > 2) {
            $suProfile = @unserialize($userRow['profilfelder']);
            if (!is_array($suProfile)) {
                $suProfile = [];
            }
        }
        $res = $db->Query(
            'SELECT id,rule,pflicht,typ FROM {pre}profilfelder WHERE show_li=\'yes\'',
        );
        while ($row = $res->FetchArray()) {
            $feld_ok = false;
            $feld_name = 'field_' . $row['id'];
            switch ($row['typ']) {
                case FIELD_CHECKBOX:
                    $feld_ok = true;
                    $suProfile[$row['id']] = isset($_POST[$feld_name]);
                    break;
                case FIELD_DROPDOWN:
                    $feld_ok = true;
                    if ($feld_ok) {
                        $suProfile[$row['id']] = $_POST[$feld_name];
                    }
                    break;
                case FIELD_RADIO:
                    $feld_ok = isset($_POST[$feld_name]);
                    if ($feld_ok) {
                        $suProfile[$row['id']] = $_POST[$feld_name];
                    }
                    break;
                case FIELD_TEXT:
                    $feld_ok =
                        trim($row['rule']) == '' ||
                        preg_match(
                            '/' . $row['rule'] . '/',
                            $_POST[$feld_name],
                        );
                    if (isset($_POST[$feld_name])) {
                        $suProfile[$row['id']] = $_POST[$feld_name];
                    }
                    break;
                case FIELD_DATE:
                    $feld_ok =
                        !empty($_POST[$feld_name . 'Day']) &&
                        !empty($_POST[$feld_name . 'Month']) &&
                        !empty($_POST[$feld_name . 'Year']) &&
                        $_POST[$feld_name . 'Day'] != '--' &&
                        $_POST[$feld_name . 'Month'] != '--' &&
                        $_POST[$feld_name . 'Year'] != '--' &&
                        CheckDateValidity(
                            mktime(
                                0,
                                0,
                                0,
                                $_POST[$feld_name . 'Month'],
                                $_POST[$feld_name . 'Day'],
                                $_POST[$feld_name . 'Year'],
                            ),
                            $row['rule'],
                        );
                    if ($feld_ok) {
                        $suProfile[$row['id']] = sprintf(
                            '%04d-%02d-%02d',
                            $_POST[$feld_name . 'Year'],
                            $_POST[$feld_name . 'Month'],
                            $_POST[$feld_name . 'Day'],
                        );
                    }
                    break;
            }
            if (
                ($row['pflicht'] == 'yes' ||
                    (isset($_POST[$feld_name]) &&
                        strlen($_POST[$feld_name]) > 0)) &&
                !$feld_ok
            ) {
                if ($row['typ'] != FIELD_DATE) {
                    $invalidFields[] = $feld_name;
                } else {
                    $invalidFields[] = $feld_name . 'Day';
                    $invalidFields[] = $feld_name . 'Month';
                    $invalidFields[] = $feld_name . 'Year';
                }
            }
        }
        $res->Free();

        // go on
        if (count($invalidFields) > 0) {
            // errors => mark fields red and show form again
            $tpl->assign('errorStep', true);
            $tpl->assign('errorInfo', $lang_user['checkfields'] . $errorInfo);
            $tpl->assign('invalidFields', $invalidFields);
        } else {
            $thisUser->UpdateContactData($userRow, $suProfile);
            PrefsDone();
        }
    }

    // contact data
    $tpl->assign('anrede', $userRow['anrede']);
    $tpl->assign('vorname', $userRow['vorname']);
    $tpl->assign('nachname', $userRow['nachname']);
    $tpl->assign('strasse', $userRow['strasse']);
    $tpl->assign('hnr', $userRow['hnr']);
    $tpl->assign('plz', $userRow['plz']);
    $tpl->assign('ort', $userRow['ort']);
    $tpl->assign('land', $userRow['land']);
    $tpl->assign('tel', $userRow['tel']);
    $tpl->assign('fax', $userRow['fax']);
    $tpl->assign('altmail', $userRow['altmail']);

    // profile fields
    $profileFields = [];
    $profileData = [];
    if (strlen($userRow['profilfelder']) > 2) {
        $profileData = @unserialize($userRow['profilfelder']);
        if (!is_array($profileData)) {
            $profileData = [];
        }
    }
    $res = $db->Query(
        'SELECT id,pflicht,typ,feld,extra FROM {pre}profilfelder WHERE show_li=\'yes\' ORDER BY feld ASC',
    );
    while ($row = $res->FetchArray()) {
        $profileFields[] = [
            'id' => $row['id'],
            'title' => $row['feld'],
            'needed' => $row['pflicht'] == 'yes',
            'type' => $row['typ'],
            'extra' => explode(',', $row['extra']),
            'value' => isset($profileData[$row['id']])
                ? $profileData[$row['id']]
                : false,
        ];
    }
    $res->Free();

    // required fields
    $tpl->assign('f_anrede', $bm_prefs['f_anrede']);
    $tpl->assign('f_strasse', $bm_prefs['f_strasse']);
    $tpl->assign('f_telefon', $bm_prefs['f_telefon']);
    $tpl->assign('f_fax', $bm_prefs['f_fax']);
    $tpl->assign('f_alternativ', $bm_prefs['f_alternativ']);

    // display
    $tpl->assign('profileFields', $profileFields);
    $tpl->assign('countryList', CountryList());
    $tpl->assign('pageContent', 'li/prefs.contact.tpl');
    $tpl->assign('activeItem', 'contact');
    $tpl->display('li/index.tpl');
} /**
 * filters
 */ elseif ($_REQUEST['action'] == 'filters' && isset($prefsItems['filters'])) {
    $tpl->assign('activeItem', 'filters');

    //
    // edit
    //
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'edit' &&
        isset($_REQUEST['id'])
    ) {
        $filter = $thisUser->GetFilter((int) $_REQUEST['id']);

        if ($filter !== false) {
            // page output
            $tpl->assign('filter', $filter);
            $tpl->assign('pageContent', 'li/prefs.filters.edit.tpl');
            $tpl->display('li/index.tpl');
        }
    }

    //
    // add
    //
    elseif (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add') {
        // page output
        $tpl->assign('pageContent', 'li/prefs.filters.add.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // save filter
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'saveFilter' &&
        isset($_REQUEST['id']) &&
        IsPOSTRequest()
    ) {
        $thisUser->UpdateFilter(
            (int) $_REQUEST['id'],
            $_REQUEST['title'],
            isset($_REQUEST['active']),
            (int) $_REQUEST['link'],
            isset($_REQUEST['flags']) && is_array($_REQUEST['flags'])
                ? (int) array_sum($_REQUEST['flags'])
                : 0,
        );
        header('Location: prefs.php?action=filters&sid=' . session_id());
        exit();
    }

    //
    // edit conditions
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'editConditions' &&
        isset($_REQUEST['id'])
    ) {
        $filter = $thisUser->GetFilter((int) $_REQUEST['id']);
        if ($filter !== false) {
            if (isset($_REQUEST['do2']) && $_REQUEST['do2'] == 'save') {
                $conditions = $thisUser->GetFilterConditions(
                    (int) $_REQUEST['id'],
                );

                // save
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 6) == 'field_') {
                        $id = substr($key, 6);
                        if (isset($conditions[$id])) {
                            $field = $val;
                            $op = 1;

                            if (in_array($field, [10])) {
                                $val = $_POST['bool_val_' . $id];
                            } elseif ($field == 9) {
                                $val = $_POST['priority_val_' . $id];
                            } elseif ($field == 13) {
                                $op = BMOP_CONTAINS;
                                $val = $_POST['att_val_' . $id];
                            } else {
                                $op = $_POST['op_' . $id];
                                $val = $_POST['text_val_' . $id];
                            }

                            $thisUser->UpdateFilterCondition(
                                $id,
                                (int) $_REQUEST['id'],
                                $field,
                                $op,
                                $val,
                            );
                        }
                    }
                }

                // delete a condition?
                if (count($conditions) > 1) {
                    foreach ($_POST as $key => $val) {
                        if (substr($key, 0, 7) == 'remove_') {
                            $id = substr($key, 7);
                            if (
                                isset($conditions[$id]) &&
                                count($conditions) > 1
                            ) {
                                $thisUser->DeleteFilterCondition(
                                    $id,
                                    (int) $_REQUEST['id'],
                                );
                            }
                        }
                    }
                }

                // add a condition?
                if (isset($_POST['add'])) {
                    $thisUser->AddFilterCondition((int) $_REQUEST['id']);
                }
            }

            $conditions = $thisUser->GetFilterConditions((int) $_REQUEST['id']);
            $tpl->assign('id', (int) $_REQUEST['id']);
            $tpl->assign('conditions', $conditions);
            $tpl->assign('conditionCount', count($conditions));
            $tpl->display('li/prefs.filters.conditions.tpl');
        }
    }

    //
    // edit actions
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'editActions' &&
        isset($_REQUEST['id'])
    ) {
        $filter = $thisUser->GetFilter((int) $_REQUEST['id']);
        if ($filter !== false) {
            if (isset($_REQUEST['do2']) && $_REQUEST['do2'] == 'save') {
                $actions = $thisUser->GetFilterActions((int) $_REQUEST['id']);

                // save
                foreach ($_POST as $key => $val) {
                    if (substr($key, 0, 3) == 'op_') {
                        $id = substr($key, 3);
                        if (isset($actions[$id])) {
                            $op = $val;

                            $val = 0;
                            $textVal = '';

                            if ($op == 1) {
                                $val = $_POST['folder_val_' . $id];
                            } elseif ($op == FILTER_ACTION_RESPOND) {
                                $val = $_POST['draft_val_' . $id];
                            } elseif ($op == FILTER_ACTION_FORWARD) {
                                $textVal = $_POST['mail_val_' . $id];
                            } elseif ($op == FILTER_ACTION_SETCOLOR) {
                                $val = $_POST['color_val_' . $id];
                            }

                            $thisUser->UpdateFilterAction(
                                $id,
                                (int) $_REQUEST['id'],
                                $op,
                                $val,
                                $textVal,
                            );
                        }
                    }
                }

                // delete a action?
                if (count($actions) > 1) {
                    foreach ($_POST as $key => $val) {
                        if (substr($key, 0, 7) == 'remove_') {
                            $id = substr($key, 7);
                            if (isset($actions[$id]) && count($actions) > 1) {
                                $thisUser->DeleteFilterAction(
                                    $id,
                                    (int) $_REQUEST['id'],
                                );
                            }
                        }
                    }
                }

                // add a action?
                if (isset($_POST['add'])) {
                    $thisUser->AddFilterAction((int) $_REQUEST['id']);
                }
            }

            $actions = $thisUser->GetFilterActions((int) $_REQUEST['id']);
            $tpl->assign(
                'draftList',
                $mailbox->GetMailList(FOLDER_DRAFTS, 1, 100, 'betreff', 'ASC'),
            );
            $tpl->assign(
                'dropdownFolderList',
                $mailbox->GetDropdownFolderList(-1, $null),
            );
            $tpl->assign('id', (int) $_REQUEST['id']);
            $tpl->assign('actions', $actions);
            $tpl->assign('actionCount', count($actions));
            $tpl->assign('forwardingAllowed', $groupRow['forward'] == 'yes');
            $tpl->display('li/prefs.filters.actions.tpl');
        }
    }

    //
    // create filter
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'createFilter' &&
        IsPOSTRequest()
    ) {
        $id = $thisUser->AddFilter(
            $_REQUEST['title'],
            isset($_REQUEST['active']),
        );

        // redirect to edit form
        header(
            'Location: prefs.php?action=filters&do=edit&id=' .
                $id .
                '&sid=' .
                session_id(),
        );
        exit();
    }

    //
    // list
    //
    else {
        // delete?
        if (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'delete' &&
            isset($_REQUEST['id'])
        ) {
            $thisUser->DeleteFilter((int) $_REQUEST['id']);
        }

        // mass delete?
        elseif (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'action' &&
            isset($_REQUEST['do2']) &&
            $_REQUEST['do2'] == 'delete'
        ) {
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 7) == 'filter_') {
                    $id = (int) substr($key, 7);
                    $thisUser->DeleteFilter($id);
                }
            }
        }

        // up?
        elseif (isset($_REQUEST['up'])) {
            $thisUser->MoveFilter((int) $_REQUEST['up'], -1);
        }

        // down?
        elseif (isset($_REQUEST['down'])) {
            $thisUser->MoveFilter((int) $_REQUEST['down'], 1);
        }

        $sortColumns = ['orderpos', 'title', 'applied', 'active'];

        // get sort info
        $sortColumn =
            isset($_REQUEST['sort']) &&
            in_array($_REQUEST['sort'], $sortColumns)
                ? $_REQUEST['sort']
                : 'orderpos';
        $sortOrder =
            isset($_REQUEST['order']) &&
            in_array($_REQUEST['order'], ['asc', 'desc'])
                ? $_REQUEST['order']
                : 'asc';
        $sortOrderFA = $sortOrder == 'desc' ? 'fa-arrow-down' : 'fa-arrow-up';

        // filter list
        $filterList = $thisUser->GetFilters($sortColumn, $sortOrder);

        // page output
        $tpl->assign('filterList', $filterList);
        $tpl->assign('sortColumn', $sortColumn);
        $tpl->assign('sortOrder', $sortOrderFA);
        $tpl->assign('sortOrderInv', $sortOrder == 'asc' ? 'desc' : 'asc');
        $tpl->assign('pageContent', 'li/prefs.filters.tpl');
        $tpl->display('li/index.tpl');
    }
} /**
 * antivirus
 */ elseif (
    $_REQUEST['action'] == 'antivirus' &&
    isset($prefsItems['antivirus'])
) {
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'save' &&
        IsPOSTRequest()
    ) {
        $thisUser->SetAntivirusSettings(
            isset($_REQUEST['virusfilter']),
            $_REQUEST['virusaction'],
        );
        PrefsDone();
    }

    // page output
    $tpl->assign('virusFilter', $userRow['virusfilter'] == 'yes');
    $tpl->assign('virusAction', $userRow['virusaction']);
    $tpl->assign(
        'dropdownFolderList',
        $mailbox->GetDropdownFolderList(-1, $null),
    );
    $tpl->assign('pageContent', 'li/prefs.antivirus.tpl');
    $tpl->assign('activeItem', 'antivirus');
    $tpl->display('li/index.tpl');
} /**
 * antispam
 */ elseif (
    $_REQUEST['action'] == 'antispam' &&
    isset($prefsItems['antispam'])
) {
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'resetDB' &&
        IsPOSTRequest()
    ) {
        $thisUser->ResetSpamIndex();
    } elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'save' &&
        IsPOSTRequest()
    ) {
        $thisUser->SetAntispamSettings(
            isset($_REQUEST['spamfilter']),
            $_REQUEST['spamaction'],
            isset($_REQUEST['unspamme']),
            isset($_REQUEST['bayes_border'])
                ? (int) $_REQUEST['bayes_border']
                : false,
            isset($_REQUEST['addressbook_nospam']),
        );
        PrefsDone();
    }

    // filter
    if ($bm_prefs['bayes_mode'] == 'local') {
        $tpl->assign('dbEntries', $thisUser->GetSpamIndexSize());
    }
    $tpl->assign('localMode', $bm_prefs['bayes_mode'] == 'local');
    $tpl->assign('bayes_border', $userRow['bayes_border']);

    // page output
    $tpl->assign('spamFilter', $userRow['spamfilter'] == 'yes');
    $tpl->assign('unspamMe', $userRow['unspamme'] == 'yes');
    $tpl->assign('addressbookNoSpam', $userRow['addressbook_nospam'] == 'yes');
    $tpl->assign('spamAction', $userRow['spamaction']);
    $tpl->assign(
        'dropdownFolderList',
        $mailbox->GetDropdownFolderList(-1, $null),
    );
    $tpl->assign('pageContent', 'li/prefs.antispam.tpl');
    $tpl->assign('activeItem', 'antispam');
    $tpl->display('li/index.tpl');
} /**
 * autoresponder
 */ elseif (
    $_REQUEST['action'] == 'autoresponder' &&
    isset($prefsItems['autoresponder'])
) {
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'save' &&
        IsPOSTRequest()
    ) {
        $thisUser->SetAutoresponder(
            isset($_REQUEST['active']),
            $_REQUEST['betreff'],
            $_REQUEST['mitteilung'],
        );
        PrefsDone();
    }

    // fetch info
    [$active, $subject, $text] = $thisUser->GetAutoresponder();

    // page output
    $tpl->assign('active', $active);
    $tpl->assign('betreff', $subject);
    $tpl->assign('mitteilung', $text);
    $tpl->assign('pageContent', 'li/prefs.autoresponder.tpl');
    $tpl->assign('activeItem', 'autoresponder');
    $tpl->display('li/index.tpl');
} /**
 * signatures
 */ elseif ($_REQUEST['action'] == 'signatures') {
    $tpl->assign('activeItem', 'signatures');

    //
    // add
    //
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add') {
        $tpl->assign('pageContent', 'li/prefs.signatures.edit.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // create signature
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'createSignature' &&
        IsPOSTRequest()
    ) {
        $id = $thisUser->AddSignature(
            $_REQUEST['titel'],
            $_REQUEST['text'],
            $_REQUEST['html'],
        );
        header('Location: prefs.php?action=signatures&sid=' . session_id());
        exit();
    }

    //
    // edit
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'edit' &&
        isset($_REQUEST['id'])
    ) {
        $signature = $thisUser->GetSignature((int) $_REQUEST['id']);

        if ($signature !== false) {
            // page output
            $tpl->assign('signature', $signature);
            $tpl->assign('lineSep', $thisUser->GetPref('linesep'));
            $tpl->assign('pageContent', 'li/prefs.signatures.edit.tpl');
            $tpl->display('li/index.tpl');
        }
    }

    //
    // save signature
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'saveSignature' &&
        isset($_REQUEST['id']) &&
        IsPOSTRequest()
    ) {
        $id = $thisUser->UpdateSignature(
            $_REQUEST['id'],
            $_REQUEST['titel'],
            $_REQUEST['text'],
            $_REQUEST['html'],
        );
        header('Location: prefs.php?action=signatures&sid=' . session_id());
        exit();
    }

    //
    // list
    //
    else {
        // delete?
        if (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'delete' &&
            isset($_REQUEST['id'])
        ) {
            $thisUser->DeleteSignature((int) $_REQUEST['id']);
        }

        // mass delete?
        elseif (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'action' &&
            isset($_REQUEST['do2']) &&
            $_REQUEST['do2'] == 'delete'
        ) {
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 10) == 'signature_') {
                    $id = (int) substr($key, 10);
                    $thisUser->DeleteSignature($id);
                }
            }
        }

        $tpl->assign('signatureList', $thisUser->GetSignatures());
        $tpl->assign('pageContent', 'li/prefs.signatures.tpl');
        $tpl->display('li/index.tpl');
    }
} /**
 * aliases
 */ elseif ($_REQUEST['action'] == 'aliases' && isset($prefsItems['aliases'])) {
    $domainList = GetDomainList('aliases');
    if (trim($groupRow['saliase']) != '') {
        $domainList = array_merge(
            $domainList,
            explode(':', $groupRow['saliase']),
        );
    }
    if (trim($userRow['saliase']) != '') {
        $domainList = array_merge(
            $domainList,
            explode(':', $userRow['saliase']),
        );
    }

    //
    // add
    //
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add') {
        $tpl->assign('senderAliases', $groupRow['sender_aliases'] == 'yes');
        $tpl->assign('domainList', $domainList);
        $tpl->assign('pageContent', 'li/prefs.aliases.add.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // create
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'create' &&
        IsPOSTRequest()
    ) {
        if (count($thisUser->GetAliases()) < $groupRow['aliase']) {
            $typ = (int) $_REQUEST['typ'];

            //
            // internal alias
            //
            if ($typ == (ALIAS_SENDER | ALIAS_RECIPIENT)) {
                $emailAddress =
                    $_REQUEST['email_local'] . '@' . $_REQUEST['email_domain'];
                if (
                    in_array($_REQUEST['email_domain'], $domainList) &&
                    BMUser::AddressValid($emailAddress) &&
                    BMUser::AddressAvailable($emailAddress) &&
                    !BMUser::AddressLocked(trim($_REQUEST['email_local'])) &&
                    strlen(trim($_REQUEST['email_local'])) >=
                        $bm_prefs['minuserlength']
                ) {
                    $thisUser->AddAlias(
                        $emailAddress,
                        ALIAS_SENDER | ALIAS_RECIPIENT,
                    );
                    header(
                        'Location: prefs.php?action=aliases&sid=' .
                            session_id(),
                    );
                    exit();
                } else {
                    $tpl->assign('title', $lang_user['addalias']);
                    $tpl->assign('msg', $lang_user['addresstaken']);
                    $tpl->assign('pageContent', 'li/msg.tpl');
                }
            }

            //
            // external alias
            //
            elseif (
                $typ == ALIAS_SENDER &&
                $groupRow['sender_aliases'] == 'yes'
            ) {
                $emailAddress = EncodeSingleEMail(
                    trim($_REQUEST['typ_1_email']),
                );
                if (BMUser::AddressValid($emailAddress, false)) {
                    [, $emailDomain] = explode(
                        '@',
                        strtolower($_REQUEST['typ_1_email']),
                    );
                    $myDomain = in_array($emailDomain, MyDomains());

                    if ($myDomain && BMUser::GetID($emailAddress) == 0) {
                        $tpl->assign('title', $lang_user['addalias']);
                        $tpl->assign('msg', $lang_user['addressmustexist']);
                        $tpl->assign('pageContent', 'li/msg.tpl');
                    } else {
                        $thisUser->AddAlias($emailAddress, ALIAS_SENDER);
                        $tpl->assign('title', $lang_user['addalias']);
                        $tpl->assign(
                            'msg',
                            sprintf(
                                $lang_user['confirmalias'],
                                DecodeEMail($emailAddress),
                            ),
                        );
                        $tpl->assign(
                            'backLink',
                            'prefs.php?action=aliases&sid=' . session_id(),
                        );
                        $tpl->assign('pageContent', 'li/msg.tpl');
                    }
                } else {
                    $tpl->assign('title', $lang_user['addalias']);
                    $tpl->assign('msg', $lang_user['addressinvalid']);
                    $tpl->assign('pageContent', 'li/msg.tpl');
                }
            }
        } else {
            $tpl->assign('title', $lang_user['addalias']);
            $tpl->assign('msg', $lang_user['toomanyaliases']);
            $tpl->assign('pageContent', 'li/msg.tpl');
        }

        // display page
        $tpl->display('li/index.tpl');
    }

    //
    // list
    //
    else {
        // delete?
        if (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'delete' &&
            isset($_REQUEST['id'])
        ) {
            if (
                $userRow['defaultSender'] == 10 + $_REQUEST['id'] * 2 ||
                $userRow['defaultSender'] == 11 + $_REQUEST['id'] * 2
            ) {
                $thisUser->SetDefaultSender(1);
            }
            $thisUser->DeleteAlias((int) $_REQUEST['id']);
        }

        // mass delete?
        elseif (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'action' &&
            isset($_REQUEST['do2']) &&
            $_REQUEST['do2'] == 'delete'
        ) {
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 6) == 'alias_') {
                    $id = (int) substr($key, 6);
                    if (
                        $userRow['defaultSender'] == 10 + $id * 2 ||
                        $userRow['defaultSender'] == 11 + $id * 2
                    ) {
                        $thisUser->SetDefaultSender(1);
                    }
                    $thisUser->DeleteAlias($id);
                }
            }
        }

        $sortColumns = ['email', 'type'];

        // get sort info
        $sortColumn =
            isset($_REQUEST['sort']) &&
            in_array($_REQUEST['sort'], $sortColumns)
                ? $_REQUEST['sort']
                : 'email';
        $sortOrder =
            isset($_REQUEST['order']) &&
            in_array($_REQUEST['order'], ['asc', 'desc'])
                ? $_REQUEST['order']
                : 'asc';

        // alias list
        $aliasList = $thisUser->GetAliases($sortColumn, $sortOrder);

        // page output
        $tpl->assign('allowAdd', count($aliasList) < $groupRow['aliase']);
        $tpl->assign(
            'aliasUsage',
            sprintf(
                $lang_user['aliasusage'],
                count($aliasList),
                $groupRow['aliase'],
            ),
        );
        $tpl->assign('aliasList', $aliasList);
        $tpl->assign('sortColumn', $sortColumn);
        $tpl->assign('sortOrder', $sortOrder);
        $tpl->assign('sortOrderInv', $sortOrder == 'asc' ? 'desc' : 'asc');
        $tpl->assign('pageContent', 'li/prefs.aliases.tpl');
        $tpl->display('li/index.tpl');
    }
} /**
 * pop3 accounts
 */ elseif ($_REQUEST['action'] == 'extpop3') {
    //
    // add
    //
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add') {
        $tpl->assign(
            'dropdownFolderList',
            $mailbox->GetDropdownFolderList(-1, $null),
        );
        $tpl->assign('pageContent', 'li/prefs.extpop3.edit.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // create
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'createAccount' &&
        IsPOSTRequest()
    ) {
        if (count($thisUser->GetPOP3Accounts()) < $groupRow['ownpop3']) {
            $_REQUEST['p_host'] = str_replace(
                ['/', '\\'],
                '',
                $_REQUEST['p_host'],
            );
            $_REQUEST['p_user'] = str_replace(
                ["\n", "\r"],
                '',
                $_REQUEST['p_user'],
            );
            $_REQUEST['p_pass'] = str_replace(
                ["\n", "\r"],
                '',
                $_REQUEST['p_pass'],
            );

            // check if the user tries to add his own account (this would create a loop)
            if ($groupRow['selfpop3_check'] == 'yes') {
                $isOwnAccount =
                    trim(strtolower($_REQUEST['p_user'])) ==
                    strtolower($userRow['email']);
                if (!$isOwnAccount) {
                    $userAliases = $thisUser->GetAliases();
                    foreach ($userAliases as $aliasInfo) {
                        if (($aliasInfo['type'] & ALIAS_RECIPIENT) == 0) {
                            continue;
                        }

                        if (
                            trim(strtolower($_REQUEST['p_user'])) ==
                            strtolower($aliasInfo['email'])
                        ) {
                            $isOwnAccount = true;
                            break;
                        }
                    }
                }
            } else {
                $isOwnAccount = false;
            }

            if (!$isOwnAccount) {
                if (
                    CheckPOP3Login(
                        $_REQUEST['p_host'],
                        (int) $_REQUEST['p_port'],
                        $_REQUEST['p_user'],
                        $_REQUEST['p_pass'],
                        isset($_REQUEST['p_ssl']),
                    )
                ) {
                    $thisUser->AddPOP3Account(
                        $_REQUEST['p_host'],
                        $_REQUEST['p_user'],
                        $_REQUEST['p_pass'],
                        (int) $_REQUEST['p_target'],
                        (int) $_REQUEST['p_port'],
                        isset($_REQUEST['p_keep']),
                        isset($_REQUEST['p_ssl']),
                    );
                    header(
                        'Location: prefs.php?action=extpop3&sid=' .
                            session_id(),
                    );
                    exit();
                } else {
                    $tpl->assign('title', $lang_user['addpop3']);
                    $tpl->assign('msg', $lang_user['pop3loginerror']);
                    $tpl->assign('pageContent', 'li/msg.tpl');
                }
            } else {
                $tpl->assign('title', $lang_user['addpop3']);
                $tpl->assign('msg', $lang_user['pop3ownerror']);
                $tpl->assign('pageContent', 'li/msg.tpl');
            }
        } else {
            $tpl->assign('title', $lang_user['addpop3']);
            $tpl->assign('msg', $lang_user['toomanyaccounts']);
            $tpl->assign('pageContent', 'li/msg.tpl');
        }

        // display page
        $tpl->display('li/index.tpl');
    }

    //
    // edit
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'edit' &&
        isset($_REQUEST['id'])
    ) {
        $account = $thisUser->GetPOP3Account((int) $_REQUEST['id']);

        if ($account !== false) {
            // page output
            $tpl->assign('account', $account);
            $tpl->assign(
                'dropdownFolderList',
                $mailbox->GetDropdownFolderList(-1, $null),
            );
            $tpl->assign('pageContent', 'li/prefs.extpop3.edit.tpl');
            $tpl->display('li/index.tpl');
        }
    }

    //
    // save
    //
    elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'saveAccount' &&
        isset($_REQUEST['id']) &&
        IsPOSTRequest()
    ) {
        $_REQUEST['p_user'] = str_replace(
            ["\n", "\r"],
            '',
            $_REQUEST['p_user'],
        );
        $_REQUEST['p_pass'] = str_replace(
            ["\n", "\r"],
            '',
            $_REQUEST['p_pass'],
        );

        if (
            CheckPOP3Login(
                $_REQUEST['p_host'],
                (int) $_REQUEST['p_port'],
                $_REQUEST['p_user'],
                $_REQUEST['p_pass'],
                isset($_REQUEST['p_ssl']),
            )
        ) {
            $thisUser->UpdatePOP3Account(
                (int) $_REQUEST['id'],
                $_REQUEST['p_host'],
                $_REQUEST['p_user'],
                $_REQUEST['p_pass'],
                (int) $_REQUEST['p_target'],
                (int) $_REQUEST['p_port'],
                isset($_REQUEST['p_keep']),
                isset($_REQUEST['p_ssl']),
                isset($_REQUEST['paused']),
            );
            header('Location: prefs.php?action=extpop3&sid=' . session_id());
            exit();
        } else {
            $tpl->assign('title', $lang_user['editpop3']);
            $tpl->assign('msg', $lang_user['pop3loginerror']);
            $tpl->assign('pageContent', 'li/msg.tpl');
        }

        // display page
        $tpl->display('li/index.tpl');
    }

    //
    // list
    //
    else {
        // delete?
        if (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'delete' &&
            isset($_REQUEST['id'])
        ) {
            $thisUser->DeletePOP3Account((int) $_REQUEST['id']);
        }

        // mass delete?
        elseif (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'action' &&
            isset($_REQUEST['do2']) &&
            $_REQUEST['do2'] == 'delete'
        ) {
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 5) == 'pop3_') {
                    $id = (int) substr($key, 5);
                    $thisUser->DeletePOP3Account($id);
                }
            }
        }

        $sortColumns = ['p_host', 'p_user', 'last_fetch'];

        // get sort info
        $sortColumn =
            isset($_REQUEST['sort']) &&
            in_array($_REQUEST['sort'], $sortColumns)
                ? $_REQUEST['sort']
                : 'p_user';
        $sortOrder =
            isset($_REQUEST['order']) &&
            in_array($_REQUEST['order'], ['asc', 'desc'])
                ? $_REQUEST['order']
                : 'asc';

        // account list
        $accountList = $thisUser->GetPOP3Accounts($sortColumn, $sortOrder);

        // page output
        $tpl->assign('allowAdd', count($accountList) < $groupRow['ownpop3']);
        $tpl->assign(
            'accountUsage',
            sprintf(
                $lang_user['pop3usage'],
                count($accountList),
                $groupRow['ownpop3'],
            ),
        );
        $tpl->assign('accountList', $accountList);
        $tpl->assign('sortColumn', $sortColumn);
        $tpl->assign('sortOrder', $sortOrder);
        $tpl->assign('sortOrderInv', $sortOrder == 'asc' ? 'desc' : 'asc');
        $tpl->assign('pageContent', 'li/prefs.extpop3.tpl');
        $tpl->display('li/index.tpl');
    }
} /**
 * faq
 */ elseif ($_REQUEST['action'] == 'faq') {
    // get faq
    $faq = [];
    $res = $db->Query(
        'SELECT id,required,frage,antwort FROM {pre}faq WHERE (typ=? OR typ=?) AND (lang=? OR lang=?) ORDER BY frage ASC',
        'li',
        'both',
        ':all:',
        $currentLanguage,
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        if (
            !$row['required'] ||
            (isset($groupRow[$row['required']]) &&
                $groupRow[$row['required']] == 'yes')
        ) {
            $row['antwort'] = str_replace(
                '%%user%%',
                $userRow['email'],
                $row['antwort'],
            );
            $row['antwort'] = str_replace(
                '%%selfurl%%',
                $bm_prefs['selfurl'],
                $row['antwort'],
            );
            $row['antwort'] = str_replace(
                '%%hostname%%',
                $bm_prefs['b1gmta_host'],
                $row['antwort'],
            );
            $faq[] = $row;
        }
    }
    $res->Free();

    // page output
    $tpl->assign('faq', $faq);
    $tpl->assign('pageContent', 'li/prefs.faq.tpl');
    $tpl->display('li/index.tpl');
} /**
 * membership
 */ elseif ($_REQUEST['action'] == 'membership') {
    $tpl->assign('activeItem', 'membership');

    //
    // overview
    //
    if (!isset($_REQUEST['do']) || $_REQUEST['do'] == 'changePW') {
        if (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'changePW' &&
            IsPOSTRequest()
        ) {
            // password
            $suPass1 = CharsetDecode($_POST['pass1'], false, 'ISO-8859-15');
            $suPass2 = CharsetDecode($_POST['pass2'], false, 'ISO-8859-15');
            if (
                strlen($suPass1) < $bm_prefs['min_pass_length'] ||
                $suPass1 != $suPass2
            ) {
                $tpl->assign('errorStep', true);
                $tpl->assign(
                    'errorInfo',
                    sprintf(
                        $lang_user['pwerror'],
                        $bm_prefs['min_pass_length'],
                    ),
                );
            } else {
                $userRow['passwort'] = md5(
                    md5($suPass1) . $userRow['passwort_salt'],
                );
                $thisUser->UpdateContactData(
                    $userRow,
                    false,
                    true,
                    0,
                    $suPass1,
                );

                PrefsDone();
            }
        }

        // membership
        $tpl->assign('allowCancel', $bm_prefs['autocancel'] == 'yes');
        $tpl->assign('regDate', $thisUser->_row['reg_date']);

        // page output
        $tpl->assign('pageContent', 'li/prefs.membership.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // cancel account
    //
    elseif (
        $_REQUEST['do'] == 'cancelAccount' &&
        $bm_prefs['autocancel'] == 'yes'
    ) {
        // page output
        $_SESSION['allowCancel'] = time();
        $tpl->assign('pageContent', 'li/prefs.membership.cancelaccount.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // really cancel account
    //
    elseif (
        $_REQUEST['do'] == 'reallyCancelAccount' &&
        isset($_POST['really']) &&
        $_POST['really'] === 'true' &&
        $bm_prefs['autocancel'] == 'yes' &&
        isset($_SESSION['allowCancel']) &&
        $_SESSION['allowCancel'] + 30 <= time()
    ) {
        // log, cancel, logout
        PutLog(
            sprintf(
                'User <%s> (%d) cancelled his account from IP address <%s>',
                $userRow['email'],
                $userRow['id'],
                $_SERVER['REMOTE_ADDR'],
            ),
            PRIO_NOTE,
            __FILE__,
            __LINE__,
        );
        $thisUser->CancelAccount();
        BMUser::Logout();

        // page output
        $tpl->assign('ssl_url', $bm_prefs['ssl_url']);
        $tpl->assign(
            'ssl_login_enable',
            $bm_prefs['ssl_login_enable'] == 'yes',
        );
        $tpl->assign('domain_combobox', $bm_prefs['domain_combobox'] == 'yes');
        $tpl->assign('domainList', GetDomainList('login'));
        $tpl->assign('timezone', date('Z'));
        $tpl->assign('year', date('Y'));
        $tpl->assign('mobileURL', $bm_prefs['mobile_url']);
        $tpl->assign('title', $lang_user['cancelmembership']);
        $tpl->assign('msg', $lang_user['cancelledtext']);
        $tpl->assign('languageList', GetAvailableLanguages());
        $tpl->assign('page', 'nli/msg.tpl');
        $tpl->display('nli/index.tpl');
    }
} /**
 * keyring
 */ elseif ($_REQUEST['action'] == 'keyring' && $groupRow['smime'] == 'yes') {
    $tpl->assign('pkcs12Support', PKCS12_SUPPORT);

    //
    // key ring main page
    //
    if (
        !isset($_REQUEST['do']) ||
        in_array($_REQUEST['do'], ['action', 'delete'])
    ) {
        if (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'delete' &&
            isset($_REQUEST['hash'])
        ) {
            $thisUser->DeleteCertificateByHash(
                $_REQUEST['hash'],
                (int) $_REQUEST['type'],
            );
        } elseif (
            isset($_REQUEST['do']) &&
            $_REQUEST['do'] == 'action' &&
            isset($_REQUEST['cert']) &&
            is_array($_REQUEST['cert'])
        ) {
            $ownCerts = $thisUser->GetKeyRing(
                'cn',
                'asc',
                CERTIFICATE_TYPE_PRIVATE,
            );

            foreach ($_REQUEST['cert'] as $certHash) {
                $certType = CERTIFICATE_TYPE_PUBLIC;

                foreach ($ownCerts as $ownCert) {
                    if ($ownCert['hash'] == $certHash) {
                        $certType = CERTIFICATE_TYPE_PRIVATE;
                        break;
                    }
                }

                $thisUser->DeleteCertificateByHash($certHash, $certType);
            }
        }

        $sortColumns = ['cn', 'email', 'validto'];

        // get sort info
        $sortColumn =
            isset($_REQUEST['sort']) &&
            in_array($_REQUEST['sort'], $sortColumns)
                ? $_REQUEST['sort']
                : 'cn';
        $sortOrder =
            isset($_REQUEST['order']) &&
            in_array($_REQUEST['order'], ['asc', 'desc'])
                ? $_REQUEST['order']
                : 'asc';

        // get key ring
        $ownCerts = $thisUser->GetKeyRing(
            $sortColumn,
            $sortOrder,
            CERTIFICATE_TYPE_PRIVATE,
        );
        $publicCerts = $thisUser->GetKeyRing(
            $sortColumn,
            $sortOrder,
            CERTIFICATE_TYPE_PUBLIC,
        );

        // page output
        $tpl->assign('uploadCerts', $groupRow['upload_certificates'] == 'yes');
        $tpl->assign('issueCerts', $groupRow['issue_certificates'] == 'yes');
        $tpl->assign('sortColumn', $sortColumn);
        $tpl->assign('sortOrder', $sortOrder);
        $tpl->assign('sortOrderInv', $sortOrder == 'asc' ? 'desc' : 'asc');
        $tpl->assign('now', time());
        $tpl->assign('ownCerts', $ownCerts);
        $tpl->assign('publicCerts', $publicCerts);
        $tpl->assign('pageContent', 'li/prefs.keyring.tpl');
        $tpl->display('li/index.tpl');
    }

    //
    // import private certificate dialog
    //
    elseif (
        $_REQUEST['do'] == 'importPrivateCertificate' &&
        $groupRow['upload_certificates'] == 'yes'
    ) {
        // page output
        $tpl->display('li/prefs.keyring.importprivate.tpl');
    }

    //
    // upload private certificate
    //
    elseif ($_REQUEST['do'] == 'uploadPrivateCertificate' && IsPOSTRequest()) {
        // PKCS12 import
        if (PKCS12_SUPPORT) {
            $pkcs12TempID = RequestTempFile(
                $userRow['id'],
                time() + TIME_ONE_HOUR,
            );
            $pkcs12TempName = TempFileName($pkcs12TempID);
            $pkcs12UploadFile = getUploadedFile('pkcs12File', $pkcs12TempName);

            echo '<script>' . "\n";

            if (
                $pkcs12UploadFile &&
                $pkcs12UploadFile['size'] <= 1024 * 50 &&
                in_array(strtolower(substr($pkcs12UploadFile['name'], -4)), [
                    '.p12',
                    '.pfx',
                ])
            ) {
                $outCerts = false;
                $success = false;

                if (
                    openssl_pkcs12_read(
                        getFileContents($pkcs12TempName),
                        $outCerts,
                        $_REQUEST['pkeyPass'],
                    ) &&
                    is_array($outCerts)
                ) {
                    // crypt PK
                    $pKey = openssl_pkey_get_private($outCerts['pkey']);
                    if (
                        $pKey &&
                        openssl_pkey_export($pKey, $pKey, $_REQUEST['pkeyPass'])
                    ) {
                        // store
                        $success = $thisUser->StorePrivateCertificate(
                            $outCerts['cert'],
                            $pKey,
                            $_REQUEST['pkeyPass'],
                            is_array($outCerts['extracerts'])
                                ? $outCerts['extracerts']
                                : false,
                        );
                    }
                }

                if (!$success) {
                    echo 'alert(\'' .
                        addslashes($lang_user['privcertstoreerr']) .
                        '\');' .
                        "\n";
                } else {
                    echo 'parent.document.location.reload();' . "\n";
                }
            } else {
                echo 'alert(\'' .
                    addslashes($lang_user['invalidformat']) .
                    '\');' .
                    "\n";
            }

            ReleaseTempFile($userRow['id'], $pkcs12TempID);

            echo 'parent.hideOverlay();' . "\n";
            echo '</script>' . "\n";
        }

        // PEM import
        else {
            $certTempID = RequestTempFile(
                $userRow['id'],
                time() + TIME_ONE_HOUR,
            );
            $pkeyTempID = RequestTempFile(
                $userRow['id'],
                time() + TIME_ONE_HOUR,
            );
            $chainTempID = RequestTempFile(
                $userRow['id'],
                time() + TIME_ONE_HOUR,
            );
            $certTempName = TempFileName($certTempID);
            $pkeyTempName = TempFileName($pkeyTempID);
            $chainTempName = TempFileName($chainTempID);
            $certUploadFile = getUploadedFile('certFile', $certTempName);
            $pkeyUploadFile = getUploadedFile('pkeyFile', $pkeyTempName);
            $chainUploadFile = getUploadedFile('chainFile', $chainTempName);

            echo '<script>' . "\n";

            if (
                $certUploadFile &&
                $certUploadFile['size'] <= 1024 * 50 &&
                $pkeyUploadFile &&
                $pkeyUploadFile['size'] <= 1024 * 50 &&
                in_array(strtolower(substr($certUploadFile['name'], -4)), [
                    '.pem',
                    '.crt',
                ]) &&
                in_array(strtolower(substr($pkeyUploadFile['name'], -4)), [
                    '.pem',
                    '.key',
                ])
            ) {
                $certData = getFileContents($certTempName);
                $pkeyData = getFileContents($pkeyTempName);

                $chainCerts = false;
                if ($chainUploadFile) {
                    $chainData = getFileContents($chainTempName);
                    $chainCerts = explode(
                        '-----END CERTIFICATE-----',
                        $chainData,
                    );

                    foreach ($chainCerts as $key => $val) {
                        if (trim($val) == '') {
                            unset($chainCerts[$key]);
                        } else {
                            $chainCerts[$key] =
                                trim($val) . "\n" . '-----END CERTIFICATE-----';
                        }
                    }
                }

                if (
                    !$thisUser->StorePrivateCertificate(
                        $certData,
                        $pkeyData,
                        $_REQUEST['pkeyPass'],
                        $chainCerts,
                    )
                ) {
                    echo 'alert(\'' .
                        addslashes($lang_user['privcertstoreerr']) .
                        '\');' .
                        "\n";
                } else {
                    echo 'parent.document.location.href=\'prefs.php?action=keyring&sid=' .
                        session_id() .
                        '\';' .
                        "\n";
                }
            } else {
                echo 'alert(\'' .
                    addslashes($lang_user['invalidformat']) .
                    '\');' .
                    "\n";
            }

            ReleaseTempFile($userRow['id'], $certTempID);
            ReleaseTempFile($userRow['id'], $pkeyTempID);
            ReleaseTempFile($userRow['id'], $chainTempID);

            echo 'parent.hideOverlay();' . "\n";
            echo '</script>' . "\n";
        }
    }

    //
    // import certificate dialog
    //
    elseif ($_REQUEST['do'] == 'importPublicCertificate') {
        // page output
        $tpl->assign('title', $lang_user['addcert']);
        $tpl->assign('text', $lang_user['addcerttext']);
        $tpl->assign(
            'formAction',
            'prefs.php?action=keyring&do=uploadPublicCertificate&sid=' .
                session_id(),
        );
        $tpl->assign('fieldName', 'certFile');
        $tpl->display('li/dialog.openfile.tpl');
    }

    //
    // upload certificate
    //
    elseif ($_REQUEST['do'] == 'uploadPublicCertificate' && IsPOSTRequest()) {
        $tempID = RequestTempFile($userRow['id'], time() + TIME_ONE_HOUR);
        $tempName = TempFileName($tempID);
        $uploadFile = getUploadedFile('certFile', $tempName);

        echo '<script>' . "\n";

        if (
            $uploadFile &&
            $uploadFile['size'] <= 1024 * 50 &&
            in_array(strtolower(substr($uploadFile['name'], -4)), [
                '.pem',
                '.crt',
            ])
        ) {
            $pemData = getFileContents($tempName);

            if (!$thisUser->StoreCertificate($pemData)) {
                echo 'alert(\'' .
                    addslashes($lang_user['certstoreerr']) .
                    '\');' .
                    "\n";
            } else {
                echo 'parent.document.location.href=\'prefs.php?action=keyring&sid=' .
                    session_id() .
                    '\';' .
                    "\n";
            }
        } else {
            echo 'alert(\'' .
                addslashes($lang_user['invalidformat']) .
                '\');' .
                "\n";
        }

        ReleaseTempFile($userRow['id'], $tempID);

        echo 'parent.hideOverlay();' . "\n";
        echo '</script>' . "\n";
    }

    //
    // issue certificate
    //
    elseif (
        $_REQUEST['do'] == 'issuePrivateCertificate' &&
        $groupRow['issue_certificates'] == 'yes'
    ) {
        // get all addresses the user owns
        $availableAddresses = [strtolower($userRow['email'])];
        $aliases = $thisUser->GetAliases();
        foreach ($aliases as $aliasInfo) {
            if (
                ($aliasInfo['type'] & ALIAS_SENDER) != 0 &&
                ($aliasInfo['type'] & ALIAS_SENDER) != 0
            ) {
                $aliasEMail = strtolower($aliasInfo['email']);
                if (!in_array($aliasEMail, $availableAddresses)) {
                    $availableAddresses[] = $aliasEMail;
                }
            }
        }

        // remove addresses with active certificates
        $availableAddresses = $thisUser->GetRecipientsWithMissingCertificate(
            $availableAddresses,
            CERTIFICATE_TYPE_PRIVATE,
        );

        // addresses available?
        if (count($availableAddresses) == 0) {
            $tpl->assign('title', $lang_user['error']);
            $tpl->assign('msg', $lang_user['issuecert_noaddr']);
            $tpl->assign('pageContent', 'li/msg.tpl');
            $tpl->display('li/index.tpl');
        } else {
            if (!isset($_REQUEST['step'])) {
                $tpl->assign('availableAddresses', $availableAddresses);
                $tpl->assign(
                    'pageContent',
                    'li/prefs.keyring.issuecert.step1.tpl',
                );
                $tpl->display('li/index.tpl');
            } elseif (
                $_REQUEST['step'] == 2 &&
                isset($_REQUEST['address']) &&
                in_array($_REQUEST['address'], $availableAddresses)
            ) {
                $tpl->assign('userRow', $userRow);
                $tpl->assign('address', $_REQUEST['address']);
                $tpl->assign(
                    'pageContent',
                    'li/prefs.keyring.issuecert.step2.tpl',
                );
                $tpl->display('li/index.tpl');
            } elseif (
                $_REQUEST['step'] == 3 &&
                isset($_REQUEST['address']) &&
                in_array($_REQUEST['address'], $availableAddresses)
            ) {
                $password = CharsetDecode(
                    $_REQUEST['password'],
                    false,
                    'ISO-8859-15',
                );

                // password wrong
                if (
                    md5(md5($password) . $userRow['passwort_salt']) !==
                    $userRow['passwort']
                ) {
                    $tpl->assign('title', $lang_user['error']);
                    $tpl->assign('msg', $lang_user['issuecert_wrongpw']);
                    $tpl->assign('pageContent', 'li/msg.tpl');
                    $tpl->display('li/index.tpl');
                }

                // password OK
                else {
                    // DN array
                    $dn = [
                        'localityName' => $userRow['ort'],
                        'commonName' =>
                            $userRow['vorname'] . ' ' . $userRow['nachname'],
                        'emailAddress' => $_REQUEST['address'],
                    ];
                    if (empty($dn['localityName'])) {
                        unset($dn['localityName']);
                    }

                    // config args
                    $configArgs = [
                        'config' => B1GMAIL_DIR . 'serverlib/res/openssl.cnf',
                    ];

                    // generate private/public key pair
                    $privKey = openssl_pkey_new($configArgs);

                    // generate CSR
                    $csr = openssl_csr_new($dn, $privKey, $configArgs);

                    // get CA cert
                    $caCert = @openssl_x509_read($bm_prefs['ca_cert']);
                    $caPKKey = $bm_prefs['ca_cert_pk'];
                    $caPKPass = $bm_prefs['ca_cert_pk_pass'];
                    if ($caPKPass != '') {
                        $caPKPass = CryptPKPassPhrase(base64_decode($caPKPass));
                    }
                    $caPK = $caPKPass != '' ? [$caPKKey, $caPKPass] : $caPKKey;

                    // sign
                    $cert = openssl_csr_sign(
                        $csr,
                        $caCert,
                        $caPK,
                        CERTIFICATE_ISSUE_DAYS,
                        $configArgs,
                    );

                    // store
                    $certPEMData = $privKeyPEMData = '';
                    if (
                        $cert &&
                        openssl_x509_export($cert, $certPEMData) &&
                        openssl_pkey_export(
                            $privKey,
                            $privKeyPEMData,
                            $_REQUEST['password'],
                            $configArgs,
                        ) &&
                        $thisUser->StorePrivateCertificate(
                            $certPEMData,
                            $privKeyPEMData,
                            $_REQUEST['password'],
                        )
                    ) {
                        PutLog(
                            sprintf(
                                'Issued private certificate for "%s" <%s>',
                                $dn['commonName'],
                                $dn['emailAddress'],
                            ),
                            PRIO_NOTE,
                            __FILE__,
                            __LINE__,
                        );
                        header(
                            'Location: prefs.php?action=keyring&sid=' .
                                session_id(),
                        );
                        exit();
                    }

                    // error
                    else {
                        $tpl->assign('title', $lang_user['error']);
                        $tpl->assign('msg', $lang_user['issuecert_err']);
                        $tpl->assign('pageContent', 'li/msg.tpl');
                        $tpl->display('li/index.tpl');
                    }

                    // free
                    @openssl_x509_free($caCert);
                    @openssl_x509_free($cert);
                }
            }
        }
    }

    //
    // show certificate
    //
    elseif ($_REQUEST['do'] == 'showCertificate' && isset($_REQUEST['hash'])) {
        $hash = $_REQUEST['hash'];

        $certRow = $thisUser->GetCertificateByHash($hash);
        if ($certRow) {
            $certInfo = $publicKeyInfo = false;

            $cert = @openssl_x509_read($certRow['pemdata']);
            if ($cert) {
                $certInfo = openssl_x509_parse($cert);
                openssl_x509_free($cert);
            }

            $pkey = @openssl_pkey_get_public($certRow['pemdata']);
            if ($pkey) {
                $publicKeyInfo = openssl_pkey_get_details($pkey);

                if ($publicKeyInfo) {
                    if ($publicKeyInfo['type'] == OPENSSL_KEYTYPE_RSA) {
                        $publicKeyInfo['typeText'] = 'RSA';
                    } elseif ($publicKeyInfo['type'] == OPENSSL_KEYTYPE_DSA) {
                        $publicKeyInfo['typeText'] = 'DSA';
                    } elseif ($publicKeyInfo['type'] == OPENSSL_KEYTYPE_DH) {
                        $publicKeyInfo['typeText'] = 'DH';
                    } elseif ($publicKeyInfo['type'] == OPENSSL_KEYTYPE_EC) {
                        $publicKeyInfo['typeText'] = 'EC';
                    } else {
                        $publicKeyInfo['typeText'] = $lang_user['unknown'];
                    }
                }

                openssl_free_key($pkey);
            }

            $tpl->assign(
                'isValid',
                $certInfo['validFrom_time_t'] <= time() &&
                    $certInfo['validTo_time_t'] >= time(),
            );
            $tpl->assign('certInfo', postProcessCertInfo($certInfo));
            $tpl->assign('publicKeyInfo', $publicKeyInfo);
            $tpl->display('li/prefs.keyring.showcert.tpl');
        }
    }

    //
    // download certificate
    //
    elseif (
        $_REQUEST['do'] == 'downloadCertificate' &&
        isset($_REQUEST['hash'])
    ) {
        $hash = $_REQUEST['hash'];

        $certRow = $thisUser->GetCertificateByHash($hash);
        if ($certRow) {
            header('Pragma: public');
            header('Content-Type: application/x-pem-file');
            header('Content-Length: ' . strlen($certRow['pemdata']));
            header(
                sprintf(
                    'Content-Disposition: attachment; filename=cert-%s.pem',
                    $certRow['hash'],
                ),
            );
            echo $certRow['pemdata'];
            exit();
        }
    }

    //
    // export private certificate dialog
    //
    elseif (
        $_REQUEST['do'] == 'exportPrivateCertificate' &&
        isset($_REQUEST['hash']) &&
        PKCS12_SUPPORT
    ) {
        // page output
        $tpl->assign('hash', $_REQUEST['hash']);
        $tpl->display('li/prefs.keyring.exportprivate.tpl');
    }

    //
    // export private certificate
    //
    elseif (
        $_REQUEST['do'] == 'downloadPrivateCertificate' &&
        isset($_REQUEST['hash']) &&
        isset($_REQUEST['pw1']) &&
        isset($_REQUEST['pw2']) &&
        PKCS12_SUPPORT
    ) {
        echo '<script>' . "\n";

        $hash = $_REQUEST['hash'];
        $pw1 = $_REQUEST['pw1'];
        $pw2 = $_REQUEST['pw2'];

        // check pw
        if ($pw1 == $pw2 && strlen($pw1) >= 4) {
            $pkcs12Data = $thisUser->ExportPrivateCertificateAsPKCS12(
                $hash,
                $pw1,
            );

            if (!$pkcs12Data) {
                echo 'alert(\'' .
                    addslashes($lang_user['certexporterror']) .
                    '\');' .
                    "\n";
                echo 'parent.hideOverlay();' . "\n";
            } else {
                $tempFileID = RequestTempFile(
                    $userRow['id'],
                    time() + TIME_ONE_MINUTE,
                );
                $tempFileName = TempFileName($tempFileID);

                $fp = fopen($tempFileName, 'wb');
                fwrite($fp, $pkcs12Data);
                fclose($fp);

                echo 'parent.document.location.href=\'prefs.php?action=keyring&do=getPrivateCertificate&id=' .
                    $tempFileID .
                    '&name=' .
                    urlencode($hash) .
                    '&sid=' .
                    session_id() .
                    '\';' .
                    "\n";
                echo 'parent.hideOverlay();' . "\n";
            }
        } else {
            echo 'alert(\'' .
                addslashes($lang_user['certexportpwerror']) .
                '\');' .
                "\n";
            echo 'history.back(1);' . "\n";
        }

        echo '</script>' . "\n";
    }

    //
    // get exported private certificate
    //
    elseif (
        $_REQUEST['do'] == 'getPrivateCertificate' &&
        isset($_REQUEST['id']) &&
        PKCS12_SUPPORT
    ) {
        $tempFileID = (int) $_REQUEST['id'];

        if (ValidTempFile($userRow['id'], $tempFileID)) {
            header('Pragma: public');
            header(
                sprintf(
                    'Content-Disposition: attachment; filename="cert-%s.p12"',
                    preg_replace('/[^a-zA-Z0-9]/', '', $_REQUEST['name']),
                ),
            );
            header('Content-Type: application/x-pkcs12');
            header(
                sprintf(
                    'Content-Length: %d',
                    filesize(TempFileName($tempFileID)),
                ),
            );

            SendFile(TempFileName($tempFileID), -1);

            ReleaseTempFile($userRow['id'], $tempFileID);

            exit();
        }
    }
} /**
 * Try out modules
 */ else {
    ModuleFunction('UserPrefsPageHandler', [$_REQUEST['action']]);
}

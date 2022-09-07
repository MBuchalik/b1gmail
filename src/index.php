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

/**
 * languages
 */
$availableLanguages = GetAvailableLanguages();
$tpl->assign('languageList', $availableLanguages);

$tpl->assign('ssl_url', $bm_prefs['ssl_url']);
$tpl->assign('ssl_login_enable', $bm_prefs['ssl_login_enable'] == 'yes');
$tpl->assign('domain_combobox', $bm_prefs['domain_combobox'] == 'yes');
$tpl->assign('domainList', GetDomainList('login'));
$tpl->assign('timezone', date('Z'));
$tpl->assign('year', date('Y'));
$tpl->assign('mobileURL', $bm_prefs['mobile_url']);

/**
 * file handler for modules
 */
ModuleFunction('FileHandler', [
    substr(__FILE__, strlen(__DIR__) + 1),
    isset($_REQUEST['action']) ? $_REQUEST['action'] : '',
]);

/**
 * default action = login
 */
if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'login';
}

/**
 * mobile redirection?
 */
$nonMobileActions = [
    'codegen',
    'checkAddressAvailability',
    'resetPassword',
    'confirmAlias',
    'readCertMail',
    'completeAddressBookEntry',
    'showAddressSugestions',
    'initiateSession',
];
if (
    $bm_prefs['redirect_mobile'] == 'yes' &&
    IsMobileUserAgent() &&
    !isset($_COOKIE['noMobileRedirect']) &&
    !in_array($_REQUEST['action'], $nonMobileActions)
) {
    header('Location: ' . $bm_prefs['mobile_url']);
    exit();
}

if ($_REQUEST['action'] == 'tos') {
    $tpl->assign('pageTitle', $lang_user['tos']);
    $tpl->assign('tos', $lang_custom['tos']);
    $tpl->assign('page', 'nli/tos.tpl');
} elseif ($_REQUEST['action'] == 'privacy-policy') {
    $tpl->assign('pageTitle', $lang_user['privacy_policy']);
    $tpl->assign('privacyPolicy', $lang_custom['privacy_policy']);
    $tpl->assign('page', 'nli/privacy-policy.tpl');
} elseif ($_REQUEST['action'] == 'imprint') {
    $tpl->assign('pageTitle', $lang_user['contact']);
    $tpl->assign('imprint', $lang_custom['imprint']);
    $tpl->assign('page', 'nli/imprint.tpl');
} elseif ($_REQUEST['action'] == 'faq') {
    $faq = [];
    $res = $db->Query(
        'SELECT id,frage,antwort FROM {pre}faq WHERE (lang=? OR lang=?) AND (typ=? OR typ=?) ORDER BY frage ASC',
        ':all:',
        $currentLanguage,
        'both',
        'nli',
    );
    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
        $answer = $row['antwort'];
        $answer = str_replace('%%hostname%%', $_SERVER['HTTP_HOST'], $answer);
        $answer = str_replace('%%selfurl%%', $bm_prefs['selfurl'], $answer);

        array_push($faq, [
            'question' => $row['frage'],
            'answer' => $answer,
        ]);
    }
    $res->Free();

    $tpl->assign('pageTitle', $lang_user['faq']);
    $tpl->assign('faq', $faq);
    $tpl->assign('page', 'nli/faq.tpl');
} /**
 * address availability check (RPC)
 */ elseif ($_REQUEST['action'] == 'checkAddressAvailability') {
    if (!isset($_GET['address'])) {
        exit();
    }

    $address = EncodeEMail($_GET['address']);

    // check address availability
    $result = BMUser::AddressValid($address) ? 1 : 2;

    if ($result == 1) {
        [$localPart] = explode('@', $address);
        if (
            strlen(trim($localPart)) < $bm_prefs['minuserlength'] ||
            BMUser::AddressLocked($localPart)
        ) {
            $result = 0;
        }
    }

    if ($result == 1) {
        $result = BMUser::AddressAvailable($address) ? 1 : 0;
    }

    // respond
    $response = [
        'available' => $result,
    ];

    Array2XML($response);
    exit();
} /**
 * custom page
 */ elseif ($_REQUEST['action'] == 'page' && isset($_GET['page'])) {
    $page = preg_replace('/([^a-zA-Z0-9]*)/', '', $_GET['page']);
    $tpl->assign('page', 'custompages/' . $page . '.tpl');
} /**
 * forgot password
 */ elseif (
    $_REQUEST['action'] == 'lostPassword' &&
    ((isset($_REQUEST['email_local']) &&
        isset($_REQUEST['email_domain']) &&
        trim($_REQUEST['email_local']) != '') ||
        (isset($_REQUEST['email_full']) && trim($_REQUEST['email_full']) != ''))
) {
    $tpl->assign('pageTitle', $lang_user['lostpw']);

    $userMail = EncodeEMail(
        isset($_REQUEST['email_full'])
            ? trim($_REQUEST['email_full'])
            : trim($_REQUEST['email_local']) . '@' . $_REQUEST['email_domain'],
    );

    if (BMUser::LostPassword($userMail)) {
        // send PW link
        $tpl->assign('msg', $lang_user['pwresetsuccess']);
    } else {
        // unknown address
        $tpl->assign('msg', $lang_user['pwresetfailed']);
    }

    $tpl->assign('title', $lang_user['lostpw']);
    $tpl->assign('page', 'nli/msg.tpl');
} /**
 * reset password
 */ elseif (
    $_REQUEST['action'] == 'resetPassword' &&
    isset($_REQUEST['user']) &&
    isset($_REQUEST['key'])
) {
    header('Pragma: no-cache');
    header('Cache-Control: no-cache');
    header('X-Robots-Tag: noindex');
    $tpl->assign('robotsNoIndex', true);

    $tpl->assign('pageTitle', $lang_user['lostpw']);

    $userID = (int) $_REQUEST['user'];
    $resetKey = trim($_REQUEST['key']);

    if (BMUser::ResetPassword($userID, $resetKey)) {
        // ok
        $tpl->assign('msg', $lang_user['pwresetsuccess2']);
    } else {
        // invalid id/key
        $tpl->assign('msg', $lang_user['pwresetfailed2']);
    }

    $tpl->assign('title', $lang_user['lostpw']);
    $tpl->assign('page', 'nli/msg.tpl');
} /**
 * confirm alias
 */ elseif (
    $_REQUEST['action'] == 'confirmAlias' &&
    isset($_REQUEST['id']) &&
    isset($_REQUEST['code'])
) {
    header('Pragma: no-cache');
    header('Cache-Control: no-cache');
    header('X-Robots-Tag: noindex');
    $tpl->assign('robotsNoIndex', true);

    $tpl->assign('pageTitle', $lang_user['confirmaliastitle']);

    if (BMUser::ConfirmAlias((int) $_REQUEST['id'], $_REQUEST['code'])) {
        $tpl->assign('msg', $lang_user['confirmaliasok']);
    } else {
        $tpl->assign('msg', $lang_user['confirmaliaserr']);
    }

    $tpl->assign('title', $lang_user['confirmaliastitle']);
    $tpl->assign('page', 'nli/msg.tpl');
} /**
 * read cert mail
 */ elseif (
    $_REQUEST['action'] == 'readCertMail' &&
    isset($_REQUEST['id']) &&
    isset($_REQUEST['key'])
) {
    header('Pragma: no-cache');
    header('Cache-Control: no-cache');
    header('X-Robots-Tag: noindex');
    $tpl->assign('robotsNoIndex', true);

    $tpl->assign('pageTitle', $lang_user['certmail']);

    $id = (int) $_REQUEST['id'];
    $key = trim($_REQUEST['key']);

    if (!class_exists('BMMailbox')) {
        include './serverlib/mailbox.class.php';
    }

    $mail = BMMailbox::GetCertMail($id, $key);

    if ($mail) {
        // get text part
        $textParts = $mail->GetTextParts();
        if (isset($textParts['html'])) {
            $textMode = 'html';
            $text = $textParts['html'];
        } elseif (isset($textParts['text'])) {
            $textMode = 'text';
            $text = formatEMailText($textParts['text']);
        } else {
            $textMode = 'text';
            $text = '';
        }

        // get attachments
        $attachments = $mail->GetAttachments();

        // show text only?
        if (isset($_REQUEST['showText'])) {
            if ($textMode == 'html') {
                $text =
                    '<base target="_blank" /><font face="arial" size="2">' .
                    formatEMailHTMLText(
                        isset($textParts['html']) ? $textParts['html'] : '',
                        true,
                        $attachments,
                        (int) $_REQUEST['id'],
                    ) .
                    '</font>';
            } else {
                $text =
                    '<base target="_blank" /><font face="arial" size="2">' .
                    formatEMailText(
                        isset($textParts['text']) ? $textParts['text'] : '',
                    ) .
                    '</font>';
            }
            echo $text;
            exit();
        }

        // get attachment?
        if (isset($_REQUEST['downloadAttachment'])) {
            $parts = $mail->GetPartList();
            if (isset($parts[$_REQUEST['attachment']])) {
                $part = $parts[$_REQUEST['attachment']];

                header('Pragma: public');
                if (isset($part['charset']) && trim($part['charset']) != '') {
                    header(
                        'Content-Type: ' .
                            $part['content-type'] .
                            '; charset=' .
                            $part['charset'],
                    );
                } else {
                    header('Content-Type: ' . $part['content-type']);
                }
                header(
                    sprintf(
                        'Content-Disposition: %s; filename="%s"',
                        'attachment',
                        addslashes($part['filename']),
                    ),
                );

                $attData = &$part['body'];
                $attData->Init();
                while ($block = $attData->DecodeBlock(PART_CHUNK_SIZE)) {
                    echo $block;
                }
                $attData->Finish();

                exit();
            }
        }

        // assign
        $tpl->assign('mailID', $id);
        $tpl->assign('key', $key);
        $tpl->assign('subject', $mail->GetHeaderValue('subject'));
        $tpl->assign(
            'fromAddresses',
            ParseMailList($mail->GetHeaderValue('from')),
        );
        $tpl->assign('toAddresses', ParseMailList($mail->GetHeaderValue('to')));
        $tpl->assign('ccAddresses', ParseMailList($mail->GetHeaderValue('cc')));
        $tpl->assign(
            'replyToAddresses',
            ParseMailList($mail->GetHeaderValue('reply-to')),
        );
        $tpl->assign('flags', $mail->flags);
        $tpl->assign('date', $mail->date);
        $tpl->assign('priority', (int) $mail->priority);
        $tpl->assign('text', $text);
        $tpl->assign('textMode', $textMode);
        $tpl->assign('attachments', $attachments);
        $tpl->assign('page', 'nli/certmail.read.tpl');
    } else {
        $tpl->assign('msg', $lang_user['certmailerror']);
        $tpl->assign('title', $lang_user['certmail']);
        $tpl->assign('page', 'nli/msg.tpl');
    }
} /**
 * address book completion
 */ elseif (
    $_REQUEST['action'] == 'completeAddressBookEntry' &&
    isset($_REQUEST['contact']) &&
    isset($_REQUEST['key'])
) {
    header('Pragma: no-cache');
    header('Cache-Control: no-cache');
    header('X-Robots-Tag: noindex');
    $tpl->assign('robotsNoIndex', true);

    $tpl->assign('pageTitle', $lang_user['addrselfcomplete']);

    $contactID = (int) $_REQUEST['contact'];
    $key = trim($_REQUEST['key']);

    if (!class_exists('BMAddressbook')) {
        include './serverlib/addressbook.class.php';
    }

    $contactData = BMAddressbook::GetContactForSelfCompleteInvitation(
        $contactID,
        $key,
    );
    if ($contactData) {
        if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
            // save data
            $book = _new('BMAddressbook', [$contactData['user']]);
            $book->Change(
                $contactID,
                $_REQUEST['firma'],
                $contactData['vorname'],
                $contactData['nachname'],
                $_REQUEST['strassenr'],
                $_REQUEST['plz'],
                $_REQUEST['ort'],
                $_REQUEST['land'],
                $_REQUEST['tel'],
                $_REQUEST['fax'],
                $_REQUEST['handy'],
                $_REQUEST['email'],
                $_REQUEST['work_strassenr'],
                $_REQUEST['work_plz'],
                $_REQUEST['work_ort'],
                $_REQUEST['work_land'],
                $_REQUEST['work_tel'],
                $_REQUEST['work_fax'],
                $_REQUEST['work_handy'],
                $_REQUEST['work_email'],
                $_REQUEST['anrede'],
                $_REQUEST['position'],
                $_REQUEST['web'],
                $contactData['kommentar'],
                SmartyDateTime('geburtsdatum_'),
                $contactData['default_address'],
                false,
            );
            $book->InvalidateSelfCompleteInvitation($contactID, $key);

            // send mail
            $userData = BMUser::Fetch($contactData['user']);
            $vars = [
                'vorname' => $contactData['vorname'],
                'nachname' => $contactData['nachname'],
            ];
            SystemMail(
                $bm_prefs['passmail_abs'],
                $userData['email'],
                $lang_custom['selfcomp_n_sub'],
                'selfcomp_n_text',
                $vars,
            );

            // log
            PutLog(
                sprintf(
                    'Address book entry completed after accepting invitation (contact id: %d, key: %s, IP: %s)',
                    $contactID,
                    $key,
                    $_SERVER['REMOTE_ADDR'],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );

            $tpl->assign('msg', $lang_user['completeok']);
            $tpl->assign('title', $lang_user['addrselfcomplete']);
            $tpl->assign('page', 'nli/msg.tpl');
        } else {
            // show form
            $tpl->assign('contact', $contactData);
            $tpl->assign('page', 'nli/contact.complete.tpl');
        }
    } else {
        $tpl->assign('msg', $lang_user['completeerr']);
        $tpl->assign('title', $lang_user['addrselfcomplete']);
        $tpl->assign('page', 'nli/msg.tpl');
    }
} /**
 * switch language
 */ elseif (
    $_REQUEST['action'] == 'switchLanguage' &&
    isset($_REQUEST['lang'])
) {
    if (isset($availableLanguages[$_REQUEST['lang']])) {
        setcookie('bm_language', $_REQUEST['lang'], time() + TIME_ONE_YEAR);
    }
    header(
        'Location: index.php' .
            (isset($_REQUEST['target'])
                ? '?action=' . urlencode($_REQUEST['target'])
                : ''),
    );
    exit();
} /**
 * initiate web session from tool interface
 */ elseif (
    $_REQUEST['action'] == 'initiateSession' &&
    isset($_REQUEST['target']) &&
    isset($_REQUEST['sid'])
) {
    if (isset($_REQUEST['secret'])) {
        setcookie(
            'sessionSecret_' . substr($_REQUEST['sid'], 0, 16),
            $_REQUEST['secret'],
            0,
            '/',
        );
    }

    if ($_REQUEST['target'] == 'compose') {
        header('Location: email.compose.php?sid=' . $_REQUEST['sid']);
    } elseif ($_REQUEST['target'] == 'membership') {
        header(
            'Location: prefs.php?sid=' .
                $_REQUEST['sid'] .
                '&action=membership',
        );
    } elseif ($_REQUEST['target'] == 'inbox') {
        header('Location: email.php?sid=' . $_REQUEST['sid']);
    } elseif ($_REQUEST['target'] == 'webdisk') {
        header('Location: webdisk.php?sid=' . $_REQUEST['sid']);
    } else {
        header('Location: start.php?sid=' . $_REQUEST['sid']);
    }

    exit();
} /**
 * login
 */ else {
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'login') {
        // get login
        $password =
            isset($_REQUEST['password']) && !empty($_REQUEST['password'])
                ? AjaxCharsetDecode($_REQUEST['password'])
                : (isset($_REQUEST['passwordMD5'])
                    ? $_REQUEST['passwordMD5']
                    : '');
        $email = EncodeEMail(
            isset($_REQUEST['email_full'])
                ? AjaxCharsetDecode($_REQUEST['email_full'])
                : AjaxCharsetDecode(
                    $_REQUEST['email_local'] . '@' . $_REQUEST['email_domain'],
                ),
        );

        // login
        [$result, $param] = BMUser::Login($email, $password, true, true);

        // login ok?
        if ($result == USER_OK) {
            // stats
            Add2Stat('login');

            // register timezone
            $_SESSION['bm_timezone'] = isset($_REQUEST['timezone'])
                ? (int) $_REQUEST['timezone']
                : date('Z');

            // redirect to target page
            if (isset($_REQUEST['ajax'])) {
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/json');
                printf(
                    '{ "action": "redirect", "url" : "start.php?sid=%s" }',
                    $param,
                );
            } elseif (!isset($_REQUEST['target'])) {
                header('Location: start.php?sid=' . $param);
            } elseif ($_REQUEST['target'] == 'inbox') {
                header('Location: email.php?folder=0&sid=' . $param);
            } elseif ($_REQUEST['target'] == 'compose') {
                header(
                    'Location: email.compose.php?sid=' .
                        $param .
                        (isset($_REQUEST['draft']) && $_REQUEST['draft'] != ''
                            ? '&redirect=' . (int) $_REQUEST['draft']
                            : '') .
                        (isset($_REQUEST['to']) && $_REQUEST['to'] != ''
                            ? '&to=' . urlencode($_REQUEST['to'])
                            : '') .
                        (isset($_REQUEST['cc']) && $_REQUEST['cc'] != ''
                            ? '&subject=' . urlencode($_REQUEST['cc'])
                            : '') .
                        (isset($_REQUEST['subject']) &&
                        $_REQUEST['subject'] != ''
                            ? '&subject=' . urlencode($_REQUEST['subject'])
                            : '') .
                        (isset($_REQUEST['text']) && $_REQUEST['text'] != ''
                            ? '&text=' . urlencode($_REQUEST['text'])
                            : ''),
                );
            } elseif ($_REQUEST['target'] == 'membership') {
                header(
                    'Location: prefs.php?sid=' . $param . '&action=membership',
                );
            } elseif ($_REQUEST['target'] == 'webdisk') {
                header('Location: webdisk.php?sid=' . $param);
            }
            exit();
        } else {
            // tell user what happened
            $msg = '?';
            switch ($result) {
                case USER_BAD_PASSWORD:
                    $msg = sprintf($lang_user['badlogin'], $param);
                    break;
                case USER_DOES_NOT_EXIST:
                    $msg = $lang_user['baduser'];
                    break;
                case USER_LOCKED:
                    $msg = $lang_user['userlocked'];
                    break;
                case USER_LOGIN_BLOCK:
                    $msg = sprintf(
                        $lang_user['loginblocked'],
                        FormatDate($param),
                    );
                    break;
            }

            if (isset($_REQUEST['ajax'])) {
                header('Content-Type: application/json');
                printf('{ "action": "msg", "msg" : "%s" }', addslashes($msg));
                exit();
            } else {
                $tpl->assign('msg', $msg);
                $tpl->assign('page', 'nli/loginresult.tpl');
            }
        }
    } else {
        // lost password and no email entered?
        if (
            isset($_REQUEST['action']) &&
            $_REQUEST['action'] == 'lostPassword'
        ) {
            $tpl->assign('invalidFields', ['email_local_pw']);
        }
        $tpl->assign('page', 'nli/login.tpl');
    }
}

$tpl->display('nli/index.tpl');

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

/**
 * b1gMail base search provider plugin
 *
 */
class B1GMailSearchProvider extends BMPlugin {
    /**
     * constructor
     *
     * @return B1GMail_SearchProvider
     */
    function __construct() {
        global $lang_admin;

        // plugin info
        $this->type = BMPLUGIN_DEFAULT;
        $this->name = 'b1gMail Search Provider';
        $this->author = 'b1gMail Project';
        $this->version = '1.21';

        // admin pages
        $this->admin_pages = true;
        $this->admin_page_title = $lang_admin['searchprovider'];
        $this->admin_page_icon = 'search32.png';
    }

    /**
     * handle search mass action
     *
     * @param string $category Category name
     * @param string $action Action name
     * @param array $items Array with item IDs
     * @return bool Handled?
     */
    function HandleSearchMassAction($category, $action, $items) {
        global $thisUser, $userRow;

        if (substr($category, 0, 22) != 'B1GMailSearchProvider_') {
            return false;
        }

        if ($category == 'B1GMailSearchProvider_mails') {
            if (!class_exists('BMMailbox')) {
                include B1GMAIL_DIR . 'serverlib/mailbox.class.php';
            }
            $mailbox = _new('BMMailbox', [
                $userRow['id'],
                $userRow['email'],
                $thisUser,
            ]);

            if ($action == 'delete') {
                foreach ($items as $mailID) {
                    $mailbox->DeleteMail((int) $mailID);
                }
            } elseif ($action == 'markread') {
                foreach ($items as $mailID) {
                    $mailbox->FlagMail(FLAG_UNREAD, false, (int) $mailID);
                }
            } elseif ($action == 'markunread') {
                foreach ($items as $mailID) {
                    $mailbox->FlagMail(FLAG_UNREAD, true, (int) $mailID);
                }
            } elseif (substr($action, 0, 7) == 'moveto_') {
                $destFolderID = (int) substr($action, 7);
                $mailbox->MoveMail($items, $destFolderID);
            }
        } elseif ($category == 'B1GMailSearchProvider_calendar') {
            if (!class_exists('BMCalendar')) {
                include B1GMAIL_DIR . 'serverlib/calendar.class.php';
            }
            $calendar = _new('BMCalendar', [$userRow['id']]);

            if ($action == 'delete') {
                foreach ($items as $itemID) {
                    $calendar->DeleteDate((int) $itemID);
                }
            }
        } elseif ($category == 'B1GMailSearchProvider_addressbook') {
            if (!class_exists('BMAddressbook')) {
                include B1GMAIL_DIR . 'serverlib/addressbook.class.php';
            }
            $book = _new('BMAddressbook', [$userRow['id']]);

            if ($action == 'delete') {
                foreach ($items as $itemID) {
                    $book->Delete((int) $itemID);
                }
            } elseif ($action == 'compose') {
                $to = [];

                foreach ($items as $itemID) {
                    $contact = $book->GetContact($itemID);
                    $email =
                        $contact['default_address'] == ADDRESS_WORK
                            ? $contact['work_email']
                            : $contact['email'];

                    if (trim($email) != '') {
                        array_push(
                            $to,
                            sprintf(
                                '"%s, %s" <%s>',
                                $contact['nachname'],
                                $contact['vorname'],
                                $email,
                            ),
                        );
                    }
                }

                $toList = urlencode(implode(', ', $to));
                header(
                    'Location: email.compose.php?sid=' .
                        session_id() .
                        '&to=' .
                        $toList,
                );
                exit();
            }
        } elseif ($category == 'B1GMailSearchProvider_webdisk') {
            if (!class_exists('BMWebdisk')) {
                include B1GMAIL_DIR . 'serverlib/webdisk.class.php';
            }
            $webdisk = _new('BMWebdisk', [$userRow['id']]);

            if ($action == 'delete') {
                foreach ($items as $itemID) {
                    if (strpos($itemID, '_') === false) {
                        continue;
                    }
                    [$itemType, $id] = explode('_', $itemID);

                    if ($itemType == 'file') {
                        $webdisk->DeleteFile((int) $id);
                    } elseif ($itemType == 'folder') {
                        $webdisk->DeleteFolder((int) $id);
                    }
                }
            }
        }
    }

    /**
     * get implemented search categories
     *
     * @return array
     */
    function GetSearchCategories() {
        global $bm_prefs, $lang_user;

        // prefs
        $searchIn = @unserialize($bm_prefs['search_in']);
        if (!is_array($searchIn)) {
            $searchIn = [];
        }

        // build result
        $result = [];
        if (isset($searchIn['mails'])) {
            $result['B1GMailSearchProvider_mails'] = [
                'title' => $lang_user['mails'],
                'icon' => 'fa-envelope-o',
            ];
        }
        if (isset($searchIn['attachments'])) {
            $result['B1GMailSearchProvider_attachments'] = [
                'title' => $lang_user['attachments'],
                'icon' => 'fa-paperclip',
            ];
        }
        if (isset($searchIn['calendar'])) {
            $result['B1GMailSearchProvider_calendar'] = [
                'title' => $lang_user['dates2'],
                'icon' => 'fa-calendar',
            ];
        }
        if (isset($searchIn['addressbook'])) {
            $result['B1GMailSearchProvider_addressbook'] = [
                'title' => $lang_user['contacts'],
                'icon' => 'fa-address-book-o',
            ];
        }
        if (isset($searchIn['webdisk'])) {
            $result['B1GMailSearchProvider_webdisk'] = [
                'title' => $lang_user['webdisk'],
                'icon' => 'fa-folder-open-o',
            ];
        }
        if ($bm_prefs['search_engine'] != '') {
            $result['B1GMailSearchProvider_websearch'] = [
                'title' => $lang_user['websearch'],
                'icon' => 'fa-globe',
            ];
        }

        // return result
        return $result;
    }

    /**
     * perform search
     *
     * @param string $query Query
     * @return array Results
     */
    function OnSearch($query, $dateFrom = 0, $dateTo = 0) {
        global $bm_prefs, $userRow, $groupRow, $thisUser, $lang_user, $db;

        // prepare
        $results = [];
        $q = '\'%' . $db->Escape($query) . '%\'';

        // prefs
        $searchIn = @unserialize($bm_prefs['search_in']);
        if (!is_array($searchIn)) {
            $searchIn = [];
        }

        // date
        if ($dateTo == 0) {
            $dateTo = time() + TIME_ONE_MINUTE;
        }

        //
        // mails
        //
        if (
            isset($searchIn['mails']) &&
            ($groupRow['ftsearch'] == 'no' || !FTS_SUPPORT)
        ) {
            if (!class_exists('BMMailbox')) {
                include B1GMAIL_DIR . 'serverlib/mailbox.class.php';
            }
            $mailbox = _new('BMMailbox', [
                $userRow['id'],
                $userRow['email'],
                $thisUser,
            ]);

            $folderIcons = [
                FOLDER_INBOX => 'fa-inbox',
                FOLDER_OUTBOX => 'fa-outbox',
                FOLDER_DRAFTS => 'fa-envelope',
                FOLDER_SPAM => 'fa-ban',
                FOLDER_TRASH => 'fa-trash-o',
            ];

            $thisResults = [];
            $res = $db->Query(
                'SELECT id,betreff,fetched,size,folder,flags FROM {pre}mails WHERE fetched>=? AND fetched<=? AND userid=? AND (betreff LIKE ' .
                    $q .
                    ' OR von LIKE ' .
                    $q .
                    ' OR an LIKE ' .
                    $q .
                    ')',
                $dateFrom,
                $dateTo,
                $thisUser->_id,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $thisResults[] = [
                    'title' => $row['betreff'],
                    'link' => sprintf('email.read.php?id=%d&', $row['id']),
                    'date' => $row['fetched'],
                    'size' => $row['size'],
                    'id' => $row['id'],
                    'icon' => isset($folderIcons[$row['folder']])
                        ? $folderIcons[$row['folder']]
                        : 'fa-folder-o',
                    'bold' => ($row['flags'] & FLAG_UNREAD) != 0,
                    'strike' =>
                        ($row['flags'] & FLAG_DELETED) != 0 ||
                        $row['folder'] == FOLDER_TRASH,
                ];
            }
            $res->Free();

            $massActions = [
                $lang_user['actions'] => [
                    'markread' => $lang_user['markread'],
                    'markunread' => $lang_user['markunread'],
                    'delete' => $lang_user['delete'],
                ],
                $lang_user['move'] => [],
            ];
            $folders = $mailbox->GetFolderList(false);
            foreach ($folders as $folderID => $folder) {
                $massActions[$lang_user['move']]['moveto_' . $folderID] =
                    $lang_user['moveto'] .
                    ' &quot;' .
                    HTMLFormat($folder['title']) .
                    '&quot;';
            }

            if (count($thisResults) > 0) {
                $results[] = [
                    'icon' => 'fa-envelope-o',
                    'name' => 'B1GMailSearchProvider_mails',
                    'title' => $lang_user['mails'],
                    'results' => $thisResults,
                    'massActions' => $massActions,
                ];
            }
        } elseif (
            isset($searchIn['mails']) &&
            $groupRow['ftsearch'] == 'yes' &&
            FTS_SUPPORT
        ) {
            if (!class_exists('BMSearchIndex')) {
                include B1GMAIL_DIR . 'serverlib/searchindex.class.php';
            }
            if (!class_exists('BMMailbox')) {
                include B1GMAIL_DIR . 'serverlib/mailbox.class.php';
            }

            $mailbox = _new('BMMailbox', [
                $userRow['id'],
                $userRow['email'],
                $thisUser,
            ]);
            $idx = _new('BMSearchIndex', [$thisUser->_id]);

            $items = $idx->search($query);

            $mailIDs = [];
            foreach ($items as $key => $item) {
                $mailIDs[$item['itemID']] = $key;
            }

            $thisResults = [];
            if (count($mailIDs) > 0) {
                $res = $db->Query(
                    'SELECT `id`,`betreff`,`fetched`,`size`,`folder`,`flags` FROM {pre}mails WHERE `fetched`>=? AND `fetched`<=? AND `userid`=? AND `id` IN ?',
                    $dateFrom,
                    $dateTo,
                    $thisUser->_id,
                    array_keys($mailIDs),
                );
                while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                    $searchText = $items[$mailIDs[$row['id']]]['text'];
                    $excerpt = $idx->createExcerpt($query, $searchText);
                    $score = round(
                        $idx->computeScore($query, $searchText) * 100,
                        0,
                    );

                    $thisResults[] = [
                        'title' => $row['betreff'],
                        'link' => sprintf('email.read.php?id=%d&', $row['id']),
                        'date' => $row['fetched'],
                        'size' => $row['size'],
                        'id' => $row['id'],
                        'icon' => isset($folderIcons[$row['folder']])
                            ? $folderIcons[$row['folder']]
                            : 'fa-folder-o',
                        'bold' => ($row['flags'] & FLAG_UNREAD) != 0,
                        'strike' =>
                            ($row['flags'] & FLAG_DELETED) != 0 ||
                            $row['folder'] == FOLDER_TRASH,
                        'excerpt' => $excerpt,
                        'score' => $score,
                    ];
                }
                $res->Free();
            }

            if (count($thisResults) > 0) {
                $massActions = [
                    $lang_user['actions'] => [
                        'markread' => $lang_user['markread'],
                        'markunread' => $lang_user['markunread'],
                        'delete' => $lang_user['delete'],
                    ],
                    $lang_user['move'] => [],
                ];
                $folders = $mailbox->GetFolderList(false);
                foreach ($folders as $folderID => $folder) {
                    $massActions[$lang_user['move']]['moveto_' . $folderID] =
                        $lang_user['moveto'] .
                        ' &quot;' .
                        HTMLFormat($folder['title']) .
                        '&quot;';
                }

                $results[] = [
                    'icon' => 'fa-envelope-o',
                    'name' => 'B1GMailSearchProvider_mails',
                    'title' => $lang_user['mails'],
                    'results' => $thisResults,
                    'massActions' => $massActions,
                ];
            }
        }
        //
        // attachments
        //
        if (isset($searchIn['attachments'])) {
            $thisResults = [];
            $res = $db->Query(
                'SELECT `filename`,`size`,`mailid` FROM {pre}attachments WHERE `userid`=? AND (`filename` LIKE ' .
                    $q .
                    ') ORDER BY `filename` ASC',
                $thisUser->_id,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $res2 = $db->Query(
                    'SELECT fetched FROM {pre}mails WHERE id=? AND userid=?',
                    $row['mailid'],
                    $thisUser->_id,
                );
                if ($res2->RowCount() != 1) {
                    continue;
                }
                [$fetched] = $res2->FetchArray(MYSQLI_NUM);
                $res2->Free();

                if ($fetched < $dateFrom || $fetched > $dateTo) {
                    continue;
                }

                $thisResults[] = [
                    'title' => $row['filename'],
                    'link' => sprintf('email.read.php?id=%d&', $row['mailid']),
                    'date' => $fetched,
                    'size' => $row['size'],
                ];
            }
            $res->Free();

            if (count($thisResults) > 0) {
                $results[] = [
                    'icon' => 'fa-paperclip',
                    'name' => 'B1GMailSearchProvider_attachments',
                    'title' => $lang_user['attachments'],
                    'results' => $thisResults,
                ];
            }
        }

        //
        // calendar
        //
        if (isset($searchIn['calendar'])) {
            $thisResults = [];
            $res = $db->Query(
                'SELECT id,title,`startdate` FROM {pre}dates WHERE `startdate`>=? AND `startdate`<=? AND user=? AND (title LIKE ' .
                    $q .
                    ' OR text LIKE ' .
                    $q .
                    ') ORDER BY title ASC',
                $dateFrom,
                $dateTo,
                $thisUser->_id,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $thisResults[] = [
                    'title' => $row['title'],
                    'link' => sprintf(
                        'organizer.calendar.php?action=editDate&id=%d&',
                        $row['id'],
                    ),
                    'date' => $row['startdate'],
                    'id' => $row['id'],
                ];
            }
            $res->Free();

            if (count($thisResults) > 0) {
                $results[] = [
                    'icon' => 'fa-calendar',
                    'name' => 'B1GMailSearchProvider_calendar',
                    'title' => $lang_user['dates2'],
                    'results' => $thisResults,
                    'massActions' => [
                        'delete' => $lang_user['delete'],
                    ],
                ];
            }
        }

        //
        // addressbook
        //
        if (isset($searchIn['addressbook'])) {
            $thisResults = [];
            $res = $db->Query(
                'SELECT id,vorname,nachname,firma FROM {pre}adressen WHERE user=? AND (CONCAT(vorname,\' \',nachname,\' \',firma) LIKE ' .
                    $q .
                    ' OR CONCAT(nachname,\', \',vorname,\' \',firma) LIKE ' .
                    $q .
                    ') ORDER BY nachname,vorname ASC',
                $thisUser->_id,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $addrTitle = '';

                if (empty($row['vorname']) && empty($row['nachname'])) {
                    $addrTitle = $row['firma'];
                } else {
                    $addrTitle = $row['nachname'] . ', ' . $row['vorname'];
                }

                $thisResults[] = [
                    'title' => $addrTitle,
                    'link' => sprintf(
                        'organizer.addressbook.php?action=editContact&id=%d&',
                        $row['id'],
                    ),
                    'id' => $row['id'],
                ];
            }
            $res->Free();

            if (count($thisResults) > 0) {
                $results[] = [
                    'icon' => 'fa-address-book-o',
                    'name' => 'B1GMailSearchProvider_addressbook',
                    'title' => $lang_user['contacts'],
                    'results' => $thisResults,
                    'massActions' => [
                        'compose' => $lang_user['sendmail'],
                        'delete' => $lang_user['delete'],
                    ],
                ];
            }
        }

        //
        // webdisk
        //
        if (isset($searchIn['webdisk'])) {
            // files
            $thisResults = [];
            $res = $db->Query(
                'SELECT id,ordner,dateiname,modified,`size` FROM {pre}diskfiles WHERE modified>=? AND modified<=? AND user=? AND dateiname LIKE ' .
                    $q .
                    ' ORDER BY dateiname ASC',
                $dateFrom,
                $dateTo,
                $thisUser->_id,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $thisResults[] = [
                    'icon' => 'fa-file-o',
                    'title' => $row['dateiname'],
                    'link' => sprintf('webdisk.php?folder=%d&', $row['ordner']),
                    'date' => $row['modified'],
                    'size' => $row['size'],
                    'id' => 'file_' . $row['id'],
                ];
            }
            $res->Free();

            // folders
            $res = $db->Query(
                'SELECT id,titel,modified FROM {pre}diskfolders WHERE modified>=? AND modified<=? AND user=? AND titel LIKE ' .
                    $q .
                    ' ORDER BY titel ASC',
                $dateFrom,
                $dateTo,
                $thisUser->_id,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $thisResults[] = [
                    'icon' => 'fa-folder-open-o',
                    'title' => $row['titel'],
                    'link' => sprintf('webdisk.php?folder=%d&', $row['id']),
                    'date' => $row['modified'],
                    'id' => 'folder_' . $row['id'],
                ];
            }
            $res->Free();

            if (count($thisResults) > 0) {
                $results[] = [
                    'icon' => 'fa-folder-open-o',
                    'name' => 'B1GMailSearchProvider_webdisk',
                    'title' => $lang_user['webdisk'],
                    'results' => $thisResults,
                    'massActions' => [
                        'delete' => $lang_user['delete'],
                    ],
                ];
            }
        }

        //
        // web
        //
        if ($bm_prefs['search_engine'] != '') {
            $results[] = [
                'icon' => 'fa-globe',
                'name' => 'B1GMailSearchProvider_websearch',
                'title' => $lang_user['websearch'],
                'results' => [
                    [
                        'title' => $query,
                        'extLink' => sprintf(
                            $bm_prefs['search_engine'],
                            urlencode($query),
                        ),
                    ],
                ],
            ];
        }

        // return
        return $results;
    }

    /**
     * admin handler
     *
     */
    function AdminHandler() {
        global $tpl, $plugins, $lang_admin;

        if (!isset($_REQUEST['action'])) {
            $_REQUEST['action'] = 'prefs';
        }

        $tabs = [
            0 => [
                'title' => $lang_admin['prefs'],
                'icon' => '../plugins/templates/images/search32.png',
                'link' => $this->_adminLink() . '&',
                'active' => $_REQUEST['action'] == 'prefs',
            ],
        ];

        $tpl->assign('tabs', $tabs);

        if ($_REQUEST['action'] == 'prefs') {
            $this->_prefsPage();
        }
    }

    /**
     * admin prefs page
     *
     */
    function _prefsPage() {
        global $tpl, $db, $bm_prefs;

        // save?
        if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'save') {
            if (
                isset($_REQUEST['searchIn']) &&
                is_array($_REQUEST['searchIn'])
            ) {
                $searchIn = $_REQUEST['searchIn'];
            } else {
                $searchIn = [];
            }
            $db->Query(
                'UPDATE {pre}prefs SET search_in=?',
                serialize($searchIn),
            );
            ReadConfig();
        }

        // unserialize
        $searchIn = @unserialize($bm_prefs['search_in']);
        if (!is_array($searchIn)) {
            $searchIn = [];
        }

        // assign
        $tpl->assign('searchIn', $searchIn);
        $tpl->assign('pageURL', $this->_adminLink());
        $tpl->assign('page', $this->_templatePath('search.plugin.prefs.tpl'));
    }
}

/**
 * register plugin
 */
$plugins->registerPlugin('B1GMailSearchProvider');
?>

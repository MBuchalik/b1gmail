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
RequestPrivileges(PRIVILEGES_ADMIN);
AdminRequirePrivilege('groups');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'groups';
}

$tabs = [
    0 => [
        'title' => $lang_admin['groups'],
        'relIcon' => 'group32.png',
        'link' => 'groups.php?',
        'active' => $_REQUEST['action'] == 'groups',
    ],
    1 => [
        'title' => $lang_admin['create'],
        'relIcon' => 'group_add32.png',
        'link' => 'groups.php?action=create&',
        'active' => $_REQUEST['action'] == 'create',
    ],
];

/**
 * groups
 */
if ($_REQUEST['action'] == 'groups') {
    if (!isset($_REQUEST['do'])) {
        $_REQUEST['do'] = 'list';
    }

    //
    // list
    //
    if ($_REQUEST['do'] == 'list') {
        // mass action
        if (isset($_REQUEST['executeMassAction'])) {
            // get group IDs
            $groupIDs = [];
            foreach ($_POST as $key => $val) {
                if (
                    substr($key, 0, 6) == 'group_' &&
                    ($id = (int) substr($key, 6)) != $bm_prefs['std_gruppe']
                ) {
                    $groupIDs[] = $id;
                }
            }

            if (count($groupIDs) > 0) {
                if ($_REQUEST['massAction'] == 'delete') {
                    // fetch
                    $groups = [];
                    $res = $db->Query(
                        'SELECT id,titel FROM {pre}gruppen ORDER BY titel ASC',
                    );
                    while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                        $groups[$row['id']] = $row['titel'];
                    }
                    $res->Free();

                    // assign
                    $groupsToDelete = [];
                    foreach ($groupIDs as $id) {
                        $groupsToDelete[$id] = $groups[$id];
                        unset($groups[$id]);
                    }
                    $tpl->assign('groupsToDelete', $groupsToDelete);
                    $tpl->assign('groups', $groups);
                    $tpl->assign('page', 'groups.delete.tpl');
                    $stop = true;
                }
            }
        }

        if (!isset($stop)) {
            // groups
            $groups = [];
            $res = $db->Query(
                'SELECT id,titel FROM {pre}gruppen ORDER BY titel ASC',
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $res2 = $db->Query(
                    'SELECT COUNT(*) FROM {pre}users WHERE gruppe=?',
                    $row['id'],
                );
                [$memberCount] = $res2->FetchArray(MYSQLI_NUM);
                $res2->Free();

                $row['members'] = $memberCount;
                $row['default'] = $row['id'] == $bm_prefs['std_gruppe'];
                $groups[$row['id']] = $row;
            }
            $res->Free();

            // assign
            $tpl->assign('groups', $groups);
            $tpl->assign('page', 'groups.list.tpl');
        }
    }

    //
    // edit
    //
    elseif ($_REQUEST['do'] == 'edit' && isset($_REQUEST['id'])) {
        // save?
        if (isset($_REQUEST['save'])) {
            // prepare arrays
            $saliaseArray = explode("\n", $_REQUEST['saliase']);
            foreach ($saliaseArray as $key => $val) {
                if (($val = trim($val)) != '') {
                    $saliaseArray[$key] = EncodeDomain($val);
                } else {
                    unset($saliaseArray[$key]);
                }
            }
            $saliase = implode(':', $saliaseArray);

            // prepare sizes
            $_REQUEST['storage'] *= 1024 * 1024;
            $_REQUEST['maxsize'] *= 1024;
            $_REQUEST['anlagen'] *= 1024;

            $db->Query(
                'UPDATE {pre}gruppen SET titel=?, soforthtml=?, storage=?, maxsize=?, anlagen=?, send_limit_count=?, send_limit_time=?, aliase=?, wap=?, ads=?, pop3=?, smtp=?, responder=?, imap=?, forward=?, saliase=?, signatur=?, allow_newsletter_optout=?, max_recps=?, sender_aliases=?, ftsearch=?, auto_save_drafts=? WHERE id=?',
                $_REQUEST['titel'],
                isset($_REQUEST['soforthtml']) ? 'yes' : 'no',
                $_REQUEST['storage'],
                $_REQUEST['maxsize'],
                $_REQUEST['anlagen'],
                $_REQUEST['send_limit_count'],
                $_REQUEST['send_limit_time'],
                $_REQUEST['aliase'],
                isset($_REQUEST['wap']) ? 'yes' : 'no',
                isset($_REQUEST['ads']) ? 'yes' : 'no',
                isset($_REQUEST['pop3']) ? 'yes' : 'no',
                isset($_REQUEST['smtp']) ? 'yes' : 'no',
                isset($_REQUEST['responder']) ? 'yes' : 'no',
                isset($_REQUEST['imap']) ? 'yes' : 'no',
                isset($_REQUEST['forward']) ? 'yes' : 'no',
                $saliase,
                $_REQUEST['signatur'],
                isset($_REQUEST['allow_newsletter_optout']) ? 'yes' : 'no',
                (int) $_REQUEST['max_recps'],
                isset($_REQUEST['sender_aliases']) ? 'yes' : 'no',
                isset($_REQUEST['ftsearch']) ? 'yes' : 'no',
                isset($_REQUEST['auto_save_drafts']) ? 'yes' : 'no',
                $_REQUEST['id'],
            );
            $cacheManager->Delete('group:' . $_REQUEST['id']);

            // save group options
            $groupOptions = $plugins->GetGroupOptions();
            foreach ($groupOptions as $key => $val) {
                $db->Query(
                    'REPLACE INTO {pre}groupoptions(gruppe,module,`key`,value) VALUES(?,?,?,?)',
                    $_REQUEST['id'],
                    $val['module'],
                    $val['key'],
                    !isset($_REQUEST[$key]) ? 0 : $_REQUEST[$key],
                );
            }
        }

        // fetch from DB
        $res = $db->Query(
            'SELECT * FROM {pre}gruppen WHERE id=?',
            (int) $_REQUEST['id'],
        );
        $group = $res->FetchArray(MYSQLI_ASSOC);
        $res->Free();

        // assign
        $group['saliase'] = implode(
            "\n",
            array_map('DecodeDomain', explode(':', $group['saliase'])),
        );
        $tpl->assign('groupOptions', $plugins->GetGroupOptions($group['id']));
        $tpl->assign('group', $group);
        $tpl->assign('page', 'groups.edit.tpl');
    }

    //
    // delete
    //
    elseif ($_REQUEST['do'] == 'delete' && isset($_REQUEST['id'])) {
        // fetch
        $groups = [];
        $res = $db->Query(
            'SELECT id,titel FROM {pre}gruppen ORDER BY titel ASC',
        );
        while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
            $groups[$row['id']] = $row['titel'];
        }
        $res->Free();

        // assign
        $groupsToDelete = [$_REQUEST['id'] => $groups[$_REQUEST['id']]];
        unset($groups[$_REQUEST['id']]);
        $tpl->assign('groupsToDelete', $groupsToDelete);
        $tpl->assign('groups', $groups);
        $tpl->assign('page', 'groups.delete.tpl');
    }

    //
    // real delete
    //
    elseif (
        $_REQUEST['do'] == 'realDelete' &&
        isset($_REQUEST['groups']) &&
        is_array($_REQUEST['groups'])
    ) {
        foreach ($_REQUEST['groups'] as $groupID => $newGroupID) {
            $groupNames = [];
            $res = $db->Query(
                'SELECT `titel`,`id` FROM {pre}gruppen WHERE `id` IN(?,?)',
                $groupID,
                $newGroupID,
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $groupNames[$row['id']] = $row['titel'];
            }
            $res->Free();

            $db->Query(
                'UPDATE {pre}users SET gruppe=? WHERE gruppe=?',
                $newGroupID,
                $groupID,
            );
            $db->Query('DELETE FROM {pre}gruppen WHERE id=?', $groupID);
            $cacheManager->Delete('group:' . $groupID);

            PutLog(
                sprintf(
                    'Admin <%s> deleted group <%s>, moving its users to group <%s>',
                    $adminRow['username'],
                    $groupNames[$groupID],
                    $groupNames[$newGroupID],
                ),
                PRIO_NOTE,
                __FILE__,
                __LINE__,
            );
        }
        header('Location: groups.php?sid=' . session_id());
        exit();
    }
} /**
 * create group
 */ elseif ($_REQUEST['action'] == 'create') {
    // create group
    if (isset($_REQUEST['create'])) {
        // prepare arrays
        $saliaseArray = explode("\n", $_REQUEST['saliase']);
        foreach ($saliaseArray as $key => $val) {
            if (($val = trim($val)) != '') {
                $saliaseArray[$key] = EncodeDomain($val);
            } else {
                unset($saliaseArray[$key]);
            }
        }
        $saliase = implode(':', $saliaseArray);

        // prepare sizes
        $_REQUEST['storage'] *= 1024 * 1024;
        $_REQUEST['maxsize'] *= 1024;
        $_REQUEST['anlagen'] *= 1024;

        $db->Query(
            'INSERT INTO {pre}gruppen(titel,soforthtml,storage,maxsize,anlagen,send_limit_count,send_limit_time,aliase,wap,ads,pop3,smtp,responder,imap,forward,saliase,signatur,allow_newsletter_optout,sender_aliases,ftsearch,auto_save_drafts) VALUES ' .
                '(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
            $_REQUEST['titel'],
            isset($_REQUEST['soforthtml']) ? 'yes' : 'no',
            $_REQUEST['storage'],
            $_REQUEST['maxsize'],
            $_REQUEST['anlagen'],
            $_REQUEST['send_limit_count'],
            $_REQUEST['send_limit_time'],
            $_REQUEST['aliase'],
            isset($_REQUEST['wap']) ? 'yes' : 'no',
            isset($_REQUEST['ads']) ? 'yes' : 'no',
            isset($_REQUEST['pop3']) ? 'yes' : 'no',
            isset($_REQUEST['smtp']) ? 'yes' : 'no',
            isset($_REQUEST['responder']) ? 'yes' : 'no',
            isset($_REQUEST['imap']) ? 'yes' : 'no',
            isset($_REQUEST['forward']) ? 'yes' : 'no',
            $saliase,
            $_REQUEST['signatur'],
            isset($_REQUEST['allow_newsletter_optout']) ? 'yes' : 'no',
            isset($_REQUEST['sender_aliases']) ? 'yes' : 'no',
            isset($_REQUEST['ftsearch']) ? 'yes' : 'no',
            isset($_REQUEST['auto_save_drafts']) ? 'yes' : 'no',
        );
        $groupID = $db->InsertId();

        // save group options
        $groupOptions = $plugins->GetGroupOptions();
        foreach ($groupOptions as $key => $val) {
            $db->Query(
                'REPLACE INTO {pre}groupoptions(gruppe,module,`key`,value) VALUES(?,?,?,?)',
                $groupID,
                $val['module'],
                $val['key'],
                !isset($_REQUEST[$key]) ? 0 : $_REQUEST[$key],
            );
        }

        header('Location: groups.php?sid=' . session_id());
        exit();
    }

    // display form
    else {
        // inherit from default group -> fetch from DB
        $res = $db->Query(
            'SELECT * FROM {pre}gruppen WHERE id=?',
            $bm_prefs['std_gruppe'],
        );
        if ($res->RowCount() == 1) {
            $group = $res->FetchArray(MYSQLI_ASSOC);
            $group['titel'] = '';
            $res->Free();
        } else {
            $group = [];
        }

        // assign
        $group['saliase'] = implode(
            "\n",
            array_map('DecodeDomain', explode(':', $group['saliase'])),
        );
        $tpl->assign('groupOptions', $plugins->GetGroupOptions($group['id']));
        $tpl->assign('group', $group);
        $tpl->assign('create', true);
        $tpl->assign('page', 'groups.edit.tpl');
    }
}

$tpl->assign('ftsSupport', FTS_SUPPORT);
$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['usersgroups'] . ' &raquo; ' . $lang_admin['groups'],
);
$tpl->display('page.tpl');

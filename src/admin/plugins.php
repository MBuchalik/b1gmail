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
AdminRequirePrivilege('pluginsadmin');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'plugins';
}

$tabs = [
    0 => [
        'title' => $lang_admin['plugins'],
        'relIcon' => 'plugin32.png',
        'link' => 'plugins.php?',
        'active' => $_REQUEST['action'] == 'plugins',
    ],
    1 => [
        'title' => $lang_admin['widgets'],
        'relIcon' => 'wlayout_add32.png',
        'link' => 'plugins.php?action=widgets&',
        'active' => $_REQUEST['action'] == 'widgets',
    ],
];

/**
 * plugins/widgets
 */
if ($_REQUEST['action'] == 'plugins' || $_REQUEST['action'] == 'widgets') {
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'activatePlugin' &&
        isset($_REQUEST['plugin']) &&
        isset($plugins->_inactivePlugins[$_REQUEST['plugin']])
    ) {
        $plugins->activatePlugin($_REQUEST['plugin']);
        $tpl->assign('reloadMenu', true);
    } elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'deactivatePlugin' &&
        isset($_REQUEST['plugin']) &&
        isset($plugins->_plugins[$_REQUEST['plugin']])
    ) {
        $plugins->deactivatePlugin($_REQUEST['plugin']);
        $tpl->assign('reloadMenu', true);
    } elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'pausePlugin' &&
        isset($_REQUEST['plugin']) &&
        isset($plugins->_plugins[$_REQUEST['plugin']])
    ) {
        $plugins->pausePlugin($_REQUEST['plugin']);
        $tpl->assign('reloadMenu', true);
    } elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'unpausePlugin' &&
        isset($_REQUEST['plugin']) &&
        isset($plugins->_inactivePlugins[$_REQUEST['plugin']])
    ) {
        $plugins->unpausePlugin($_REQUEST['plugin']);
        $tpl->assign('reloadMenu', true);
    }

    $pluginList = [];

    // build plugin list
    foreach ($plugins->_plugins as $className => $pluginInfo) {
        if (
            ($_REQUEST['action'] == 'plugins' &&
                ($plugins->getParam('type', $className) == BMPLUGIN_DEFAULT ||
                    $plugins->getParam('type', $className) ==
                        BMPLUGIN_FILTER)) ||
            ($_REQUEST['action'] == 'widgets' &&
                $plugins->getParam('type', $className) == BMPLUGIN_WIDGET)
        ) {
            $pluginList[] = [
                'name' => $className,
                'title' => $plugins->getParam('name', $className),
                'version' => $plugins->getParam('version', $className),
                'author' => $plugins->getParam('author', $className),
                'type' =>
                    $pluginTypeTable[$plugins->getParam('type', $className)],
                'installed' => $plugins->getParam('installed', $className),
                'paused' => $plugins->getParam('paused', $className),
            ];
        }
    }
    foreach ($plugins->_inactivePlugins as $className => $pluginInfo) {
        if (
            ($_REQUEST['action'] == 'plugins' &&
                ($pluginInfo['type'] == BMPLUGIN_DEFAULT ||
                    $pluginInfo['type'] == BMPLUGIN_FILTER)) ||
            ($_REQUEST['action'] == 'widgets' &&
                $pluginInfo['type'] == BMPLUGIN_WIDGET)
        ) {
            $pluginList[] = [
                'name' => $className,
                'title' => $pluginInfo['name'],
                'version' => $pluginInfo['version'],
                'author' => $pluginInfo['author'],
                'type' => $pluginTypeTable[$pluginInfo['type']],
                'installed' => $pluginInfo['installed'],
                'paused' => $pluginInfo['paused'],
            ];
        }
    }

    function __PluginSort($a, $b) {
        return strcasecmp(
            ($a['installed'] ? '0' : '1') . $a['title'],
            ($b['installed'] ? '0' : '1') . $b['title'],
        );
    }

    uasort($pluginList, '__PluginSort');

    $tpl->assign('action', $_REQUEST['action']);
    $tpl->assign('plugins', $pluginList);
    $tpl->assign('page', 'plugins.list.tpl');
}

$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['plugins'] . ' &raquo; ' . $lang_admin['plugins'],
);
$tpl->display('page.tpl');
?>

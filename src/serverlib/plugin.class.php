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

define('BMPLUGIN_DEFAULT', 1);
define('BMPLUGIN_WIDGET', 2);
define('BMPLUGIN_FILTER', 3);

define('BMWIDGET_START', 1);
define('BMWIDGET_ORGANIZER', 2);

define('PLUGIN_USERID', -1);

/**
 * plugin base class
 *
 */
class BMPlugin {
    var $type = BMPLUGIN_DEFAULT;
    var $name = 'Plugin base class';
    var $version = '1.0';
    var $author = 'b1gMail Project';
    var $website = false;
    var $id = 0;
    var $tplFromModDir = true;
    var $installed = false;
    var $paused = false;
    var $admin_pages = false;
    var $admin_page_title = 'Plugin base';
    var $admin_page_icon = '';
    var $internal_name = 'BMPlugin';
    var $_groupOptions = [];
    var $order = 0;

    //
    // setup routines
    //

    /**
     * install handler
     * must return true on success
     *
     * @return bool
     */
    function Install() {
        return true;
    }

    /**
     * uninstall handler
     * must return true on success
     *
     * @return bool
     */
    function Uninstall() {
        return true;
    }

    //
    // handlers
    //
    /**
     * called when user changes his own account password
     *
     * @param int $userID UserID
     * @param string $oldPasswordMD5 Old MD5 password
     * @param string $newPasswordMD5 New MD5 password
     * @param string $newPasswordPlain New plaintext password
     */
    function OnUserPasswordChange(
        $userID,
        $oldPasswordMD5,
        $newPasswordMD5,
        $newPasswordPlain
    ) {
    }

    /**
     * search handler
     *
     * @param string $query Query
     * @return array Results
     */
    function OnSearch($query) {
        return [];
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
        return false;
    }

    /**
     * get implemented search categories
     *
     * @return array
     */
    function GetSearchCategories() {
        return [];
    }

    /**
     * authentication handler
     *
     * @param string $userName User name
     * @param string $userDomain User domain
     * @param string $passwordMD5 Password (MD5)
     * @return mixed bool or array
     */
    function OnAuthenticate(
        $userName,
        $userDomain,
        $passwordMD5,
        $passwordPlain = ''
    ) {
        return false;
    }

    /**
     * called when a mail is received
     *
     * @param BMMail Mail object
     * @param BMMailbox Mailbox object
     * @param BMUser User object
     * @return int
     */
    function OnReceiveMail(&$mail, &$mailbox, &$user) {
        return BM_OK;
    }

    /**
     * called before the page tabs are assigned to the template
     *
     * @param array $pageTabs Page tabs array
     */
    function BeforePageTabsAssign(&$pageTabs) {
    }

    /**
     * called before a template is actually displayed by the smarty base class
     *
     * @param string $resourceName Resource name (template path)
     * @param Template $tpl Template class instance
     */
    function BeforeDisplayTemplate($resourceName, &$tpl) {
    }

    /**
     * called after a mail is received
     *
     * @param BMMail Mail object
     * @param BMMailbox Mailbox object
     * @param BMUser User object
     */
    function AfterReceiveMail(&$mail, &$mailbox, &$user) {
    }

    /**
     * called after getting the domain list
     *
     * @param &$list Domain list reference
     */
    function OnGetDomainList(&$list) {
    }

    function AfterSuccessfulSignup($userid, $usermail) {
    }

    function OnGetMail($id, $user) {
    }

    function OnStartMailList($user, $draftList = false) {
    }

    function OnEndMailList($user, $draftList = false) {
    }

    function OnSignup($userid, $usermail) {
    }

    function OnCreateTemplate(&$tpl) {
    }

    function OnReadLang(
        &$lang_user,
        &$lang_client,
        &$lang_custom,
        &$lang_admin,
        $lang
    ) {
    }

    function OnSendMail(&$mail, $html) {
    }

    function OnCron() {
    }

    // added in 7.0.0-PL1
    function AfterStoreMail($mailID, &$mail, &$mailbox) {
    }

    // added in 7.0.0-PL1
    function AfterDeleteMail($mailID, &$mailbox) {
    }

    // added in 7.0.0-PL1
    function AfterMoveMails($mailIDs, $destFolderID, &$mailbox) {
    }

    // added in 7.0.0-PL1
    function AfterChangeMailFlags($mailID, $newFlags, &$mailbox) {
    }

    function AfterSendMail($userID, $from, $to, $outboxFP = false) {
    }

    function OnLogin($userID, $interface = 'web') {
    }

    function OnLoginFailed($userMail, $password, $reason) {
    }

    function OnLogout($userID) {
    }

    function OnDeleteUser($id) {
    }

    function AfterInit() {
    }

    function DataFilename($id, $fx) {
        return false;
    }

    /**
     * called after receiving a mail without any recipient
     *
     * @param BMMail $mail Mail object
     */
    function OnMailWithoutValidRecipient(&$mail) {
    }

    /**
     * user page handler
     *
     */
    function FileHandler($file, $action) {
    }

    /**
     * admin page handler
     *
     */
    function AdminHandler() {
    }

    function UserPrefsPageHandler($action) {
        return false;
    }

    /**
     * on load
     *
     */
    function OnLoad() {
    }

    function OnGetMessageFP($id, $allowOverride, &$mail) {
        return false;
    }

    /**
     * get notices for ACP
     *
     * @return array
     */
    function getNotices() {
        return [];
    }

    /**
     * get a class replacement
     *
     * @param string $class Class name
     * @return string
     */
    function getClassReplacement($class) {
        return false;
    }

    /**
     * get user pages
     *
     * @param bool $loggedin Logged in?
     * @return array
     */
    function getUserPages($loggedin) {
        return [];
    }

    /**
     * get items for 'new' menu
     * (added in b1gMail 7.3)
     *
     * @reurn array
     */
    function getNewMenu() {
        return [];
    }

    /**
     * filter factory
     *
     * @param BMMail $mail
     * @return BMMailFilter
     */
    function getFilterForMail($mail) {
        return false;
    }

    //
    // widget handlers
    //

    var $widgetTemplate = false;
    var $widgetTitle = 'Default title';
    var $widgetIcon = false;
    var $widgetPrefs = false;
    var $widgetPrefsWidth = 320;
    var $widgetPrefsHeight = 240;

    /**
     * return if widget is suitable for this page
     *
     * @param int $for Page (BMWIDGET_-constant)
     * @return bool
     */
    function isWidgetSuitable($for) {
        return false;
    }

    /**
     * render widget for user to template
     *
     * @return bool
     */
    function renderWidget() {
        return false;
    }

    /**
     * render widget preferences page
     *
     */
    function renderWidgetPrefs() {
    }

    /**
     * tool interface CheckLogin extender
     *
     * @param BMUser $user BMUser object of logged in user
     * @return array
     */
    function ToolInterfaceCheckLogin($user) {
        return false;
    }

    /**
     * tool interface unknown method handler
     *
     * @param string $method Method name
     * @param array $params Method params
     * @param array $result Result array
     * @param BMToolInterface $ti BMToolInterface instance
     */
    function ToolInterfaceHandler($method, $params, &$result, &$ti) {
    }

    //
    // internal functions
    //

    /**
     * register a group option
     *
     * @param string $key
     * @param int $type
     * @param string $desc
     * @param string $options
     * @param string $default
     */
    function RegisterGroupOption(
        $key,
        $type = FIELD_TEXT,
        $desc = '',
        $options = '',
        $default = ''
    ) {
        $this->_groupOptions[$key] = [
            'type' => $type,
            'options' => $options,
            'desc' => $desc,
            'default' => $default,
        ];
    }

    /**
     * get group option value
     *
     * @param string $key
     * @param int $group
     * @return string
     */
    function GetGroupOptionValue($key, $group = 0) {
        global $plugins, $groupRow;
        return $plugins->GetGroupOptionValue(
            $group == 0 ? $groupRow['id'] : $group,
            $this->internal_name,
            $key,
            $this->_groupOptions[$key]['default'],
        );
    }

    /**
     * get admin page link
     *
     * @return string
     */
    function _adminLink($withSID = false) {
        return 'plugin.page.php?plugin=' .
            $this->internal_name .
            ($withSID ? '&sid=' . session_id() : '');
    }

    /**
     * get resource path
     *
     * @param string $name Filename
     * @param string $type Type
     */
    function _resourcePath($template, $type) {
        global $plugins;

        return $plugins->pluginResourcePath(
            $template,
            $this->internal_name,
            $type,
        );
    }

    /**
     * get template path
     *
     * @param string $template Template
     */
    function _templatePath($template) {
        return $this->_resourcePath($template, 'template');
    }

    /**
     * close widget prefs
     *
     * @param $reload Reload dashboard?
     */
    function _closeWidgetPrefs($reload = true) {
        echo '<script>' . "\n";
        echo '<!--' . "\n";
        if ($reload) {
            echo '	parent.document.location.reload();' . "\n";
        } else {
            echo '	parent.hideOverlay();' . "\n";
        }
        echo '//-->' . "\n";
        echo '</script>' . "\n";

        exit();
    }

    /**
     * set preference
     *
     * @param string $key Key
     * @param string $value Value
     * @return bool
     */
    function _setPref($key, $value) {
        global $db;
        $db->Query(
            'REPLACE INTO {pre}userprefs(userID, `key`,`value`) VALUES(?, ?, ?)',
            PLUGIN_USERID,
            $this->internal_name . '::' . $key,
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
    function _getPref($key) {
        global $db;
        $res = $db->Query(
            'SELECT `value` FROM {pre}userprefs WHERE userID=? AND `key`=?',
            PLUGIN_USERID,
            $this->internal_name . '::' . $key,
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
}

/**
 * plugin interface
 *
 */
class BMPluginInterface {
    var $_plugins;
    var $_inactivePlugins;
    var $_dbPlugins;
    var $_groupOptions;

    /**
     * constructor
     *
     * @return BMPluginInterface
     */
    function __construct() {
        global $db, $cacheManager;

        // arrays
        $this->_plugins = [];
        $this->_inactivePlugins = [];

        // get db data
        if (!($this->_dbPlugins = $cacheManager->Get('dbPlugins_v2'))) {
            $res = $db->Query(
                'SELECT installed,paused,pos,modname FROM {pre}mods ORDER BY modname ASC',
            );
            while ($row = $res->FetchArray(MYSQLI_ASSOC)) {
                $this->_dbPlugins[$row['modname']] = $row;
            }
            $res->Free();

            $cacheManager->Set('dbPlugins_v2', $this->_dbPlugins);
        }
    }

    /**
     * activate a plugin
     *
     * @param string $plugin Plugin class name
     * @return boolean
     */
    function activatePlugin($plugin) {
        global $db, $cacheManager;

        if (!isset($this->_inactivePlugins[$plugin])) {
            return false;
        }

        $this->_plugins[$plugin] = $this->_inactivePlugins[$plugin];
        unset($this->_inactivePlugins[$plugin]);
        $this->_plugins[$plugin]['instance']->installed = true;
        $this->_plugins[$plugin]['installed'] = true;

        if ($this->_plugins[$plugin]['instance']->Install()) {
            $db->Query(
                'UPDATE {pre}mods SET installed=1 WHERE modname=?',
                $plugin,
            );
            if ($db->AffectedRows() == 0) {
                $db->Query(
                    'INSERT INTO {pre}mods(modname,installed) VALUES(?,1)',
                    $plugin,
                );
            }
            $cacheManager->Delete('dbPlugins_v2');
            return true;
        } else {
            $this->_inactivePlugins[$plugin] = $this->_plugins[$plugin];
            unset($this->_plugins[$plugin]);
            return false;
        }
    }

    /**
     * pause a plugin
     *
     * @param string $plugin Plugin class name
     * @return boolean
     */
    function pausePlugin($plugin) {
        global $db, $cacheManager;

        if (!isset($this->_plugins[$plugin])) {
            return false;
        }

        $db->Query('UPDATE {pre}mods SET paused=1 WHERE modname=?', $plugin);
        $cacheManager->Delete('dbPlugins_v2');

        $this->_inactivePlugins[$plugin] = $this->_plugins[$plugin];
        unset($this->_plugins[$plugin]);
        $this->_inactivePlugins[$plugin]['paused'] = true;
        $this->_inactivePlugins[$plugin]['instance']->paused = true;

        return true;
    }

    /**
     * unpause a plugin
     *
     * @param string $plugin Plugin class name
     * @return boolean
     */
    function unpausePlugin($plugin) {
        global $db, $cacheManager;

        if (!isset($this->_inactivePlugins[$plugin])) {
            return false;
        }

        $db->Query('UPDATE {pre}mods SET paused=0 WHERE modname=?', $plugin);
        $cacheManager->Delete('dbPlugins_v2');

        $this->_plugins[$plugin] = $this->_inactivePlugins[$plugin];
        unset($this->_inactivePlugins[$plugin]);
        $this->_plugins[$plugin]['paused'] = false;
        $this->_plugins[$plugin]['instance']->paused = false;

        return true;
    }

    /**
     * deactivate a plugin
     *
     * @param string $plugin Plugin class name
     * @return boolean
     */
    function deactivatePlugin($plugin) {
        global $db, $cacheManager;

        if (!isset($this->_plugins[$plugin])) {
            return false;
        }

        if ($this->_plugins[$plugin]['instance']->Uninstall()) {
            $this->_inactivePlugins[$plugin] = $this->_plugins[$plugin];
            unset($this->_plugins[$plugin]);
            $this->_inactivePlugins[$plugin]['instance']->installed = false;
            $this->_inactivePlugins[$plugin]['installed'] = false;

            $db->Query(
                'UPDATE {pre}mods SET installed=0 WHERE modname=?',
                $plugin,
            );
            $cacheManager->Delete('dbPlugins_v2');
            return true;
        } else {
            return false;
        }
    }

    /**
     * load plugins from "plugins" directory
     *
     */
    function loadPlugins() {
        global $plugins;

        $dir = B1GMAIL_DIR . 'plugins/';
        $dirHandle = @dir($dir);

        if (!is_object($dirHandle)) {
            DisplayError(
                0x10,
                'Plugin directory unavailable',
                'The plugin path cannot be opened.',
                sprintf("Path:\n%s", $dir),
                __FILE__,
                __LINE__,
            );
            die();
        }

        while ($entry = $dirHandle->read()) {
            if (
                strtolower(substr($entry, -4)) == '.php' &&
                is_file($dir . $entry)
            ) {
                if (!include $dir . $entry) {
                    DisplayError(
                        0x11,
                        'Plugin cannot be loaded',
                        'A plugin cannot be loaded.',
                        sprintf("Module:\n%s", $dir),
                        __FILE__,
                        __LINE__,
                    );
                    die();
                }
            }
        }

        $dirHandle->close();

        $this->_sortPlugins();
    }

    /**
     * sort plugins
     *
     */
    function _sortPlugins() {
        uasort($this->_plugins, ['BMPluginInterface', '_pluginSort']);
        uasort($this->_inactivePlugins, ['BMPluginInterface', '_pluginSort']);
    }

    /**
     * plugin sort handler
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    function _pluginSort($a, $b) {
        if ($a['order'] == $b['order']) {
            return strcasecmp($a['name'], $b['name']);
        }

        return $a['order'] - $b['order'];
    }

    /**
     * register new plugin
     *
     * @param string $pluginClass Plugin class name
     */
    function registerPlugin($pluginClass) {
        $installed = false;
        $paused = false;
        $pos = 0;

        // installed?
        if (isset($this->_dbPlugins[$pluginClass])) {
            if ($this->_dbPlugins[$pluginClass]['installed'] == 1) {
                $installed = true;
            }
            if ($this->_dbPlugins[$pluginClass]['paused'] == 1) {
                $paused = true;
            }
            $pos = $this->_dbPlugins[$pluginClass]['pos'];
        }

        // load
        $pluginInstance = _new($pluginClass);
        $pluginInstance->internal_name = $pluginClass;
        $pluginInstance->installed = $installed;
        if ($installed && !$paused) {
            $pluginInstance->OnLoad();
        }
        $pluginInfo = [
            'type' => $pluginInstance->type,
            'name' => $pluginInstance->name,
            'version' => $pluginInstance->version,
            'author' => $pluginInstance->author,
            'id' => $pluginInstance->id,
            'order' => $pluginInstance->order,
            'instance' => $pluginInstance,
            'installed' => $installed,
            'paused' => $paused,
        ];

        // install?
        if ($installed && !$paused) {
            $this->_plugins[$pluginClass] = $pluginInfo;
        } else {
            $this->_inactivePlugins[$pluginClass] = $pluginInfo;
        }
    }

    /**
     * return widget plugins suitable for certain dashboard type
     *
     * @param int $for Dashboard type (BMWIDGET_-constant)
     * @return array
     */
    function getWidgetsSuitableFor($for) {
        $result = [];

        foreach ($this->_plugins as $key => $val) {
            if (
                $this->_plugins[$key]['type'] == BMPLUGIN_WIDGET &&
                $this->_plugins[$key]['instance']->isWidgetSuitable($for)
            ) {
                $result[] = $key;
            }
        }

        return $result;
    }

    /**
     * call a plugin function
     *
     * @param string $function Function name
     * @param mixed $module "false" for all plugins or plugin name
     * @param boolean $arrayReturn Wether to return an array for multiple plugins
     * @return mixed Boolean result for $module===false && $arrayReturn==false, otherwise function return value
     */
    function callFunction(
        $function,
        $module = false,
        $arrayReturn = false,
        $args = false
    ) {
        if ($args === false || !is_array($args)) {
            $params = [];
        } else {
            $params = $args;
        }

        if ($module !== false && isset($this->_plugins[$module])) {
            if (
                method_exists($this->_plugins[$module]['instance'], $function)
            ) {
                return call_user_func_array(
                    [&$this->_plugins[$module]['instance'], $function],
                    $params,
                );
            }
        } else {
            $retArray = [];
            foreach ($this->_plugins as $key => $val) {
                if (
                    method_exists($this->_plugins[$key]['instance'], $function)
                ) {
                    $retArray[$key] = call_user_func_array(
                        [&$this->_plugins[$key]['instance'], $function],
                        $params,
                    );
                }
            }
            return $arrayReturn ? $retArray : true;
        }

        return false;
    }

    /**
     * get param of plugin
     *
     * @param string $param Param name
     * @param string $module Plugin name
     * @return mixed
     */
    function getParam($param, $module) {
        if (isset($this->_plugins[$module])) {
            return $this->_plugins[$module]['instance']->$param;
        }

        return false;
    }

    /**
     * get param of plugins
     *
     * @param string $param Param name
     * @return array
     */
    function getParams($param) {
        $result = [];

        foreach ($this->_plugins as $key => $val) {
            $result[$key] = $this->_plugins[$key]['instance']->$param;
        }

        return $result;
    }

    /**
     * get resource path for plugin resource
     *
     * @param string $template Template file name
     * @param string $module Plugin name
     * @param string $type Type (template/css/js)
     * @return string
     */
    function pluginResourcePath($template, $module, $type = 'template') {
        global $tpl;

        if (isset($this->_plugins[$module])) {
            if ($this->_plugins[$module]['instance']->tplFromModDir) {
                return B1GMAIL_DIR .
                    'plugins/' .
                    ($type == 'template' ? 'templates' : $type) .
                    '/' .
                    $template;
            } else {
                return $tpl->template_dir . $template;
            }
        }

        return false;
    }

    /**
     * get group option value
     *
     * @param string $group
     * @param string $module
     * @param string $key
     * @param string $default
     * @return string
     */
    function GetGroupOptionValue($group, $module, $key, $default) {
        global $db;

        $value = $default;
        $res = $db->Query(
            'SELECT value FROM {pre}groupoptions WHERE gruppe=? AND module=? AND `key`=?',
            $group,
            $module,
            $key,
        );
        while ($row = $res->FetchArray()) {
            $value = $row['value'];
        }
        $res->Free();

        return $value;
    }

    /**
     * get all group options
     *
     * @param int $forGroup For group?
     * @return array
     */
    function GetGroupOptions($forGroup = 0) {
        $result = [];

        $values = $this->getParams('_groupOptions');
        foreach ($values as $module => $value) {
            foreach ($value as $key => $info) {
                if ($forGroup != 0) {
                    $info['value'] = $this->GetGroupOptionValue(
                        $forGroup,
                        $module,
                        $key,
                        $info['default'],
                    );
                }
                $info['module'] = $module;
                $info['key'] = $key;
                $result[$module . '_' . $key] = $info;
            }
        }

        return $result;
    }
}

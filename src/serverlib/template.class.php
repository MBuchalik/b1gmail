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
 * smarty
 */
include B1GMAIL_DIR . 'serverlib/3rdparty/smarty/Smarty.class.php';

/**
 * template class (extends smarty)
 */
class Template extends Smarty {
    var $_cssFiles, $_jsFiles;
    var $tplDir;
    var $reassignFolderList = false;
    var $hookTable = [];

    /**
     * constructor
     *
     * @return Template
     */
    function __construct() {
        global $bm_prefs, $lang_user;

        parent::__construct();

        $this->_cssFiles = ['nli' => [], 'li' => [], 'admin' => []];
        $this->_jsFiles = ['nli' => [], 'li' => [], 'admin' => []];

        // template & cache directories
        if (ADMIN_MODE) {
            $this->setTemplateDir(B1GMAIL_DIR . 'admin/templates/');
            $this->setCompileDir(B1GMAIL_DIR . 'admin/templates/cache/');
            $this->assign('tpldir', $this->tplDir = './templates/');
        } else {
            $this->setTemplateDir(
                B1GMAIL_DIR . 'templates/' . $bm_prefs['template'] . '/',
            );
            $this->setCompileDir(
                B1GMAIL_DIR . 'templates/' . $bm_prefs['template'] . '/cache/',
            );
            $this->assign(
                'tpldir',
                $this->tplDir =
                    B1GMAIL_REL . 'templates/' . $bm_prefs['template'] . '/',
            );
        }

        // variables
        $this->assign('service_title', HTMLFormat($bm_prefs['titel']));
        // In older versions of b1gmail, various charsets were supported. On the long run, we want to allow only UTF-8.
        $this->assign('charset', 'utf-8');
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $this->assign(
                'selfurl',
                str_replace('http://', 'https://', $bm_prefs['selfurl']),
            );
        } else {
            $this->assign('selfurl', $bm_prefs['selfurl']);
        }
        $this->assign('_tpldir', 'templates/' . $bm_prefs['template'] . '/');
        $this->assign('_tplname', $bm_prefs['template']);
        $this->assign('serverTZ', date('Z'));

        // post vars?
        if (isset($_POST['transPostVars'])) {
            $_safePost = [];
            foreach ($_POST as $key => $val) {
                $_safePost[$key] = HTMLFormat($val);
            }
            $this->assign('_safePost', $_safePost);
        }

        // functions
        $this->registerPlugin('function', 'banner', 'TemplateBanner');
        $this->registerPlugin('function', 'lng', 'TemplateLang');
        $this->registerPlugin('function', 'comment', 'TemplateComment');
        $this->registerPlugin('function', 'date', 'TemplateDate');
        $this->registerPlugin('function', 'size', 'TemplateSize');
        $this->registerPlugin('function', 'text', 'TemplateText');
        $this->registerPlugin('function', 'domain', 'TemplateDomain');
        $this->registerPlugin('function', 'email', 'TemplateEMail');
        $this->registerPlugin('function', 'progressBar', 'TemplateProgressBar');
        $this->registerPlugin(
            'function',
            'fileSelector',
            'TemplateFileSelector',
        );
        $this->registerPlugin('function', 'pageNav', 'TemplatePageNav');
        $this->registerPlugin('function', 'addressList', 'TemplateAddressList');
        $this->registerPlugin('function', 'storeTime', 'TemplateStoreTime');
        $this->registerPlugin(
            'function',
            'halfHourToTime',
            'TemplateHalfHourToTime',
        );
        $this->registerPlugin('function', 'implode', 'TemplateImplode');
        $this->registerPlugin('function', 'mobileNr', 'TemplateMobileNr');
        $this->registerPlugin('function', 'hook', 'TemplateHook');
        $this->registerPlugin('function', 'fileDateSig', 'TemplateFileDateSig');
        $this->registerPlugin('function', 'number', 'TemplateNumber');
        $this->registerPlugin('function', 'fieldDate', 'TemplateFieldDate');

        // module handler
        ModuleFunction('OnCreateTemplate', [&$this]);
    }

    /**
     * register with a template hook
     *
     * @param string $id Hook ID
     * @param string $tpl File name of template to be included
     */
    function registerHook($id, $tpl) {
        if (!isset($this->hookTable[$id])) {
            $this->hookTable[$id] = [$tpl];
        } else {
            $this->hookTable[$id][] = $tpl;
        }
    }

    /**
     * adds a JS file to be included in the page
     *
     * @param string $area Area (nli/li/admin)
     * @param string $file Filename
     */
    function addJSFile($area, $file) {
        if (isset($this->_jsFiles[$area])) {
            if (!in_array($file, $this->_jsFiles[$area])) {
                if (file_Exists($file)) {
                    $file .= '?' . substr(md5(filemtime($file)), 0, 6);
                }
                $this->_jsFiles[$area][] = $file;
                return true;
            }
        }

        return false;
    }

    /**
     * adds a CSS file to be included in the page
     *
     * @param string $area Area (nli/li/admin)
     * @param string $file Filename
     */
    function addCSSFile($area, $file) {
        if (isset($this->_cssFiles[$area])) {
            if (!in_array($file, $this->_cssFiles[$area])) {
                $this->_cssFiles[$area][] = $file;
                return true;
            }
        }

        return false;
    }

    function createTemplate(
        $template,
        $cache_id = null,
        $compile_id = null,
        $parent = null,
        $do_clone = true
    ) {
        global $thisUser,
            $userRow,
            $groupRow,
            $lang_user,
            $plugins,
            $bm_prefs,
            $adminRow,
            $currentLanguage;

        $this->assign('templatePrefs', GetTemplatePrefs($bm_prefs['template']));

        // admin mode?
        if (ADMIN_MODE && isset($adminRow)) {
            $this->assign('adminRow', $adminRow);

            $bmVer = B1GMAIL_VERSION;

            $this->assign('bmver', $bmVer);

            $pluginMenuItems = [];
            foreach ($plugins->_plugins as $className => $pluginInfo) {
                if ($plugins->getParam('admin_pages', $className)) {
                    $pluginMenuItems[$className] = [
                        'title' => $plugins->getParam(
                            'admin_page_title',
                            $className,
                        ),
                        'icon' => $plugins->getParam(
                            'admin_page_icon',
                            $className,
                        ),
                    ];
                }
            }

            asort($pluginMenuItems);
            $this->assign('pluginMenuItems', $pluginMenuItems);

            $this->assign(
                'isGerman',
                strpos(strtolower($currentLanguage), 'deutsch') !== false,
            );
        }

        // tabs
        if (isset($userRow) && isset($groupRow)) {
            $pageTabs = [
                'email' => [
                    'icon' => 'email',
                    'faIcon' => 'fa-envelope-o',
                    'link' => 'email.php?sid=',
                    'text' => $lang_user['email'],
                ],
            ];

            $pageTabs = array_merge($pageTabs, [
                'organizer' => [
                    'icon' => 'organizer',
                    'faIcon' => 'fa-calendar',
                    'link' => 'organizer.php?sid=',
                    'text' => $lang_user['organizer'],
                ],
            ]);

            $moduleResult = $plugins->callFunction(
                'getUserPages',
                false,
                true,
                [true],
            );
            foreach ($moduleResult as $userPages) {
                $pageTabs = array_merge($pageTabs, $userPages);
            }

            $pageTabs = array_merge($pageTabs, [
                'prefs' => [
                    'icon' => 'prefs',
                    'faIcon' => 'fa-cog',
                    'link' => 'prefs.php?sid=',
                    'text' => $lang_user['prefs'],
                ],
            ]);

            ModuleFunction('BeforePageTabsAssign', [&$pageTabs]);

            $this->assign('pageTabs', $pageTabs);
            $this->assign('pageTabsCount', count($pageTabs));
            $this->assign('_userEmail', $userRow['email']);
            $this->assign(
                'searchDetailsDefault',
                $userRow['search_details_default'] == 'yes',
            );
            $this->assign(
                'ftsBGIndexing',
                $bm_prefs['fts_bg_indexing'] == 'yes' &&
                    $groupRow['ftsearch'] == 'yes' &&
                    FTS_SUPPORT,
            );
        }

        // pugin pages (not logged in)
        else {
            $menu = [];
            $moduleResult = $plugins->callFunction(
                'getUserPages',
                false,
                true,
                [false],
            );
            foreach ($moduleResult as $userPages) {
                $menu = array_merge($menu, $userPages);
            }
            $this->assign('pluginUserPages', $menu);
        }

        // folder list
        if ($this->reassignFolderList) {
            global $mailbox;

            if (isset($mailbox) && is_object($mailbox)) {
                [, $pageMenu] = $mailbox->GetPageFolderList();
                $this->assign('folderList', $pageMenu);
            }
        }

        ModuleFunction('BeforeDisplayTemplate', [$template, &$this]);

        $this->assign('_cssFiles', $this->_cssFiles);
        $this->assign('_jsFiles', $this->_jsFiles);

        StartPageOutput();
        return parent::createTemplate(
            $template,
            $cache_id,
            $compile_id,
            $parent,
            $do_clone,
        );
    }
}

/**
 * functions registered with smarty
 */
function TemplateFileDateSig($params, $smarty) {
    $fileName = $smarty->template_dir[0] . $params['file'];
    if (!file_exists($fileName)) {
        return '';
    }
    $time = filemtime($fileName);
    return substr(md5($time), 0, 6);
}
function TemplateBanner($params, $smarty) {
    global $db, $groupRow;

    if (isset($groupRow) && is_array($groupRow) && $groupRow['ads'] == 'no') {
        return '';
    }

    if (
        isset($params['category']) &&
        ($category = trim($params['category'])) != ''
    ) {
        $res = $db->Query(
            'SELECT id,code FROM {pre}ads WHERE paused=? AND category=? ORDER BY (views/weight) ASC LIMIT 1',
            'no',
            $category,
        );
    } else {
        $res = $db->Query(
            'SELECT id,code FROM {pre}ads WHERE paused=? ORDER BY (views/weight) ASC LIMIT 1',
            'no',
        );
    }
    if ($res->RowCount() == 1) {
        [$bannerID, $bannerCode] = $res->FetchArray(MYSQLI_NUM);
        $res->Free();

        $db->Query('UPDATE {pre}ads SET views=views+1 WHERE id=?', $bannerID);

        return $bannerCode;
    }

    return '';
}
function TemplateImplode($params, $smarty) {
    return implode($params['glue'], $params['pieces']);
}
function TemplateLang($params, $smarty) {
    global $lang_user, $lang_client, $lang_admin;

    $phrase = $params['p'];

    if (ADMIN_MODE && isset($lang_admin[$phrase])) {
        return $lang_admin[$phrase];
    }

    if (!ADMIN_MODE && isset($lang_user[$phrase])) {
        return $lang_user[$phrase];
    }

    return '#UNKNOWN_PHRASE(' . $phrase . ')#';
}
function TemplateHalfHourToTime($params, $smarty) {
    $value = $params['value'];

    if (isset($params['dateStart'])) {
        return mktime(
            $value % 2 == 0 ? $value / 2 : ($value - 1) / 2,
            $value % 2 == 0 ? 0 : 30,
            0,
            date('m', $params['dateStart']),
            date('d', $parmas['dateStart']),
            date('Y', $params['dateStart']),
        );
    }

    if ($value % 2 == 0) {
        return sprintf('%d:%02d', $value / 2, 0);
    } else {
        return sprintf('%d:%02d', ($value - 1) / 2, 30);
    }
}
function TemplateComment($params, $smarty) {
    if (!DEBUG) {
        return '';
    }
    return '<!-- ' . $params['text'] . ' -->';
}
function TemplateDate($params, $smarty) {
    global $userRow, $bm_prefs, $lang_user;

    if (isset($params['nozero']) && $params['timestamp'] == 0) {
        return '-';
    }

    if (isset($userRow)) {
        $format = $userRow['datumsformat'];
    } else {
        $format = $bm_prefs['datumsformat'];
    }
    $ts = $params['timestamp'];

    if ($ts == -1) {
        return $lang_user['unknown'];
    }

    $diff = time() - $ts;
    if (isset($params['elapsed'])) {
        if ($diff >= 0 && $diff < TIME_ONE_MINUTE) {
            $elapsed = sprintf(
                $diff == 1
                    ? $lang_user['elapsed_second']
                    : $lang_user['elapsed_seconds'],
                $diff,
            );
        } elseif ($diff >= TIME_ONE_MINUTE && $diff < TIME_ONE_HOUR) {
            $elapsed = sprintf(
                round($diff / TIME_ONE_MINUTE, 0) == 1
                    ? $lang_user['elapsed_minute']
                    : $lang_user['elapsed_minutes'],
                round($diff / TIME_ONE_MINUTE, 0),
            );
        } elseif ($diff >= TIME_ONE_HOUR && $diff < TIME_ONE_DAY) {
            $elapsed = sprintf(
                round($diff / TIME_ONE_HOUR, 0) == 1
                    ? $lang_user['elapsed_hour']
                    : $lang_user['elapsed_hours'],
                round($diff / TIME_ONE_HOUR, 0),
            );
        } elseif ($diff >= TIME_ONE_DAY) {
            $elapsed = sprintf(
                round($diff / TIME_ONE_DAY, 0) == 1
                    ? $lang_user['elapsed_day']
                    : $lang_user['elapsed_days'],
                round($diff / TIME_ONE_DAY, 0),
            );
        } else {
            $elapsed = '';
        }
    } else {
        $elapsed = '';
    }

    if (isset($params['dayonly'])) {
        return date('d.m.Y', $ts) . $elapsed;
    } elseif (isset($params['short'])) {
        if (date('d.m.Y', $ts) == date('d.m.Y')) {
            return date('H:i', $ts);
        } else {
            return date('d.m.y', $ts);
        }
    } elseif (!isset($params['nice'])) {
        return date($format, $ts) . $elapsed;
    } else {
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        if ($ts >= $today && $ts <= $today + TIME_ONE_DAY) {
            return sprintf('%s, %s', $lang_user['today'], date('H:i:s', $ts)) .
                $elapsed;
        } elseif ($ts >= $today - 86400 && $ts < $today) {
            return sprintf(
                '%s, %s',
                $lang_user['yesterday'],
                date('H:i:s', $ts),
            ) . $elapsed;
        } else {
            return date($format, $ts) . $elapsed;
        }
    }
}
function TemplateSize($params, $smarty) {
    global $lang_user;

    $size = $params['bytes'];

    if ($size == -1) {
        return '<i>' . $lang_user['unlimited'] . '</i>';
    }

    if ($size < 1024) {
        return (int) $size . ' B';
    } elseif ($size < 1024 * 1024) {
        return sprintf('%.2f KB', round($size / 1024, 2));
    } elseif ($size < 1024 * 1024 * 1024) {
        return sprintf('%.2f MB', round($size / 1024 / 1024, 2));
    } else {
        return sprintf('%.2f GB', round($size / 1024 / 1024 / 1024, 2));
    }
}
function cutHTML($str, $length, $add = '') {
    // no &#;-entities -> use substr
    if (
        !preg_match('/\&#([x0-9]*);/', $str) &&
        !preg_match('/\&([a-zA-Z]*);/', $str)
    ) {
        return _strlen($str) > $length
            ? _substr($str, 0, $length - _strlen($add)) . $add
            : $str;
    }

    // otherwise use the complicated way
    $tooLong = false;
    $result = [];
    for ($i = 0; $i < strlen($str); $i++) {
        $match = false;
        if (
            strlen($str) - $i > 3 &&
            (preg_match('/^\&#([x0-9]*);/', substr($str, $i), $match) ||
                preg_match('/^\&([a-zA-Z]*);/', substr($str, $i), $match))
        ) {
            $result[] = $match[0];
            $i += strlen($match[0]) - 1;
        } else {
            $result[] = $str[$i];
        }

        if (count($result) >= $length) {
            $tooLong = true;
            break;
        }
    }

    if ($tooLong) {
        return implode('', array_slice($result, 0, $length - strlen($add))) .
            $add;
    } else {
        return implode('', $result);
    }
}
function TemplateFieldDate($params, $smarty) {
    global $bm_prefs;

    $val = $params['value'];
    if (empty($val)) {
        return '-';
    }

    $parts = explode('-', $val);
    if (count($parts) != 3) {
        return '-';
    }

    [$y, $m, $d] = $parts;
    if ($y == 0 || $m == 0 || $d == 0) {
        return '-';
    }

    return sprintf('%02d.%02d.%04d', $d, $m, $y);
}
function TemplateNumber($params, $smarty) {
    $no = (int) $params['value'];
    if (isset($params['min'])) {
        $no = max($params['min'], $no);
    }
    if (isset($params['max'])) {
        $no = min($params['max'], $no);
    }
    return $no;
}
function TemplateDomain($params, $smarty) {
    $domain = $params['value'];
    return HTMLFormat(DecodeDomain($domain));
}
function TemplateEMail($params, $smarty) {
    $email = DecodeEMail($params['value']);
    if (isset($params['cut'])) {
        $email = cutHTML($email, $params['cut'], '...');
    }
    return HTMLFormat($email);
}
function TemplateText($params, $smarty) {
    $text = $params['value'];

    if (isset($params['ucFirst'])) {
        $text = ucfirst($text);
    }

    if (isset($params['escape'])) {
        $text = addslashes($text);
        if (isset($params['noentities'])) {
            $text = str_replace('/', '\/', $text);
        }
    }

    if (isset($params['cut'])) {
        $text = cutHTML($text, $params['cut'], '...');
    }

    if ($text == '' && !isset($params['allowEmpty'])) {
        return ' - ';
    }

    if (isset($params['stripTags'])) {
        $text = strip_tags($text);
    }

    if (isset($params['noentities'])) {
        return $text;
    } else {
        $text = HTMLFormat(
            $text,
            isset($params['allowDoubleEnc']) && $params['allowDoubleEnc'],
        );
        return $text;
    }
}
function TemplateAddressList($params, $smarty) {
    $list = '';
    $short = isset($params['short']);

    foreach ($params['list'] as $addressItem) {
        if ($short) {
            if (isset($params['simple'])) {
                $list .=
                    '; ' .
                    trim(
                        HTMLFormat($addressItem['name']) != ''
                            ? HTMLFormat($addressItem['name'])
                            : HTMLFormat(DecodeEMail($addressItem['mail'])),
                    );
            } else {
                $list .= sprintf(
                    ' <a class="mailAddressLink" href="javascript:void(0);" onclick="currentEMail=\'%s\';showAddressMenu(event);">%s</a>',
                    addslashes(DecodeEMail($addressItem['mail'])),
                    trim(
                        HTMLFormat($addressItem['name']) != ''
                            ? HTMLFormat($addressItem['name'])
                            : HTMLFormat(DecodeEMail($addressItem['mail'])),
                    ),
                );
            }
        } else {
            if (isset($params['simple'])) {
                $list .=
                    '; ' .
                    trim(
                        HTMLFormat($addressItem['name']) .
                            ' ' .
                            (trim($addressItem['name']) != ''
                                ? '&lt;' .
                                    HTMLFormat(
                                        DecodeEMail($addressItem['mail']),
                                    ) .
                                    '&gt;'
                                : HTMLFormat(
                                    DecodeEMail($addressItem['mail']),
                                )),
                    );
            } else {
                $list .= sprintf(
                    ' <a class="mailAddressLink" href="javascript:void(0);" onclick="currentEMail=\'%s\';showAddressMenu(event);">%s</a>',
                    DecodeEMail(addslashes($addressItem['mail'])),
                    trim(
                        HTMLFormat($addressItem['name']) .
                            ' ' .
                            (trim($addressItem['name']) != ''
                                ? '&lt;' .
                                    HTMLFormat(
                                        DecodeEMail($addressItem['mail']),
                                    ) .
                                    '&gt;'
                                : HTMLFormat(
                                    DecodeEMail($addressItem['mail']),
                                )),
                    ),
                );
            }
        }
    }

    if (isset($params['simple'])) {
        $list = substr($list, 2);
    }

    return trim($list);
}
function TemplateProgressBar($params, $smarty) {
    $value = $params['value'];
    $max = $params['max'];
    $width = $params['width'];
    $name = isset($params['name']) ? $params['name'] : mt_rand(0, 1000);

    if ($max == 0) {
        $valueWidth = 0;
    } else {
        $valueWidth = ($width / $max) * $value;
    }

    return sprintf(
        '<div class="progressBar" id="pb_%s" style="width:%dpx;"><div class="progressBarValue" id="pb_%s_value" style="width:%dpx;"></div></div>',
        $name,
        $width,
        $name,
        min($width - 2, $valueWidth),
    );
}
function TemplateFileSelector($params, $smarty) {
    global $lang_user, $groupRow;

    $name = $params['name'];
    $size = isset($params['size']) ? (int) $params['size'] : 30;

    return sprintf(
        '<table width="100%%" cellspacing="1" cellpadding="0">' .
            '<tr>' .
            '<td><div id="fileSelector_local_%s" style="display:;"><input type="file" id="localFile_%s" name="localFile_%s%s" size="%d" style="width: 100%%;"%s /></div>' .
            '</td>' .
            '</tr>' .
            '</table>',
        $name,
        $name,
        $name,
        isset($params['multiple']) ? '[]' : '',
        $size,
        isset($params['multiple']) ? ' multiple="multiple"' : '',
    );
}
function TemplatePageNav($params, $smarty) {
    $tpl_on = $params['on'];
    $tpl_off = $params['off'];
    $aktuelle_seite = $params['page'];
    $anzahl_seiten = $params['pages'];
    $ret = '';

    $seiten = [
        $aktuelle_seite - 3,
        $aktuelle_seite - 2,
        $aktuelle_seite - 1,
        $aktuelle_seite,
        $aktuelle_seite + 1,
        $aktuelle_seite + 2,
        $aktuelle_seite + 3,
    ];

    if ($aktuelle_seite > 1) {
        $ret .= str_replace(
            '.t',
            '&lt;&lt;',
            str_replace('.s', $aktuelle_seite - 1, $tpl_off),
        );
    }

    foreach ($seiten as $key => $val) {
        if ($val >= 1 && $val <= $anzahl_seiten) {
            if ($aktuelle_seite == $val) {
                $ret .= str_replace(['.s', '.t'], $val, $tpl_on);
            } else {
                $ret .= str_replace(['.s', '.t'], $val, $tpl_off);
            }
        }
    }

    if ($aktuelle_seite < $anzahl_seiten) {
        $ret .= str_replace(
            '.t',
            '&gt;&gt;',
            str_replace('.s', $aktuelle_seite + 1, $tpl_off),
        );
    }

    return $ret;
}
function TemplateStoreTime($params, $smarty) {
    global $lang_user;

    $time = $params['value'];

    if ($time == 86400) {
        return '1 ' . $lang_user['days'];
    } elseif ($time == 172800) {
        return '2 ' . $lang_user['days'];
    } elseif ($time == 432000) {
        return '5 ' . $lang_user['days'];
    } elseif ($time == 604800) {
        return '7 ' . $lang_user['days'];
    } elseif ($time == 1209600) {
        return '2 ' . $lang_user['weeks'];
    } elseif ($time == 2419200) {
        return '4 ' . $lang_user['weeks'];
    } elseif ($time == 4828400) {
        return '2 ' . $lang_user['months'];
    } else {
        return '-';
    }
}
function TemplateHook($params, $smarty) {
    $result = '';

    if (DEBUG && isset($_REQUEST['_showHooks'])) {
        $result .= '<div>#' . $params['id'] . '</div>';
    }

    if (DEBUG) {
        $result .= '<!-- hook(' . $params['id'] . ') -->';
    }

    if (
        isset($smarty->hookTable) &&
        is_array($smarty->hookTable) &&
        isset($smarty->hookTable[$params['id']])
    ) {
        foreach ($smarty->hookTable[$params['id']] as $file) {
            $result .= $smarty->fetch($file);
        }
    }

    if (DEBUG) {
        $result .= '<!-- /hook(' . $params['id'] . ') -->';
    }

    return $result;
}
function TemplateMobileNr($params, $smarty) {
    $value = isset($params['value']) ? $params['value'] : '';
    $name = $params['name'];
    $size = isset($params['size']) ? $params['size'] : '100%';

    return sprintf(
        '<input type="text" name="%s" id="%s" style="width:%s;" value="%s" />',
        $name,
        $name,
        $size,
        HTMLFormat($value),
    );
}

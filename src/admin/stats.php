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
include '../serverlib/barchart.class.php';
include '../serverlib/chart.class.php';
RequestPrivileges(PRIVILEGES_ADMIN);
AdminRequirePrivilege('stats');

if (!isset($_REQUEST['action'])) {
    $_REQUEST['action'] = 'common';
}

$tabs = [
    0 => [
        'title' => $lang_admin['commonstats'],
        'relIcon' => 'stats32.png',
        'link' => 'stats.php?',
        'active' => $_REQUEST['action'] == 'common',
    ],
    1 => [
        'title' => $lang_admin['emailstats'],
        'relIcon' => 'ico_email.png',
        'link' => 'stats.php?action=email&',
        'active' => $_REQUEST['action'] == 'email',
    ],
    2 => [
        'title' => $lang_admin['spaceusage'],
        'relIcon' => 'ico_data.png',
        'link' => 'stats.php?action=usage&',
        'active' => $_REQUEST['action'] == 'usage',
    ],
];

/**
 * common-/email-stats - basically the same, but seperated for the user
 */
if ($_REQUEST['action'] == 'common' || $_REQUEST['action'] == 'email') {
    // show chart?
    if (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'showChart' &&
        isset($_REQUEST['statType']) &&
        isset($_REQUEST['time'])
    ) {
        $statTypeItem = $_REQUEST['statType'];
        $time = (int) $_REQUEST['time'];
        $rawData = GetStatData($statTypeItem, $time);
        $data = [];

        foreach ($rawData as $key => $val) {
            $data[$key] = array_pop($val);
        }

        $chart = new BMChart(
            sprintf(
                '%s (%d/%d)',
                $lang_admin['stat_' . $statTypeItem],
                date('m', $time),
                date('Y', $time),
            ),
            520,
            280,
        );
        $chart->SetData($data);
        $chart->Display();
        exit();
    }

    // time?
    if (!isset($_REQUEST['timeMonth'])) {
        $time = mktime(0, 0, 0, date('m'), 1, date('Y'));
    } else {
        $time = mktime(
            0,
            0,
            0,
            $_REQUEST['timeMonth'],
            1,
            $_REQUEST['timeYear'],
        );
    }

    // common stats
    if ($_REQUEST['action'] == 'common') {
        $mode = 'common';
        $statTypes = ['login', 'signup'];
    }

    // email stats
    else {
        $mode = 'email';
        $statTypes = ['receive', 'send', 'sysmail'];
    }

    // stat type
    $statType = isset($_REQUEST['statType'])
        ? $_REQUEST['statType']
        : $statTypes[0];

    // special types
    $statsSpecial = [
        'login' => ['login', 'mobile_login'],
        'send' => ['send', 'send_intern', 'send_extern'],
        'receive' => ['receive', 'infected', 'spam'],
    ];
    if (isset($statsSpecial[$statType])) {
        $statTypeList = $statsSpecial[$statType];
    } else {
        $statTypeList = [$statType];
    }

    // build stats
    $stats = [];
    foreach ($statTypeList as $statTypeItem) {
        $statData = GetStatData($statTypeItem, $time);
        $maxVal = $sum = 0;

        foreach ($statData as $val) {
            if ($val[$statTypeItem] > $maxVal) {
                $maxVal = $val[$statTypeItem];
            }
            $sum += $val[$statTypeItem];
        }

        if ($maxVal % 10 != 0) {
            $maxVal += 10 - ($maxVal % 10);
        }

        $heights = [];
        foreach ($statData as $day => $val) {
            $theVal = $val[$statTypeItem];

            if ($maxVal <= 0) {
                $heights[$day] = 0;
            } else {
                $heights[$day] = round(($theVal / $maxVal) * 240, 0);
            }
        }

        $yScale = [];
        for ($i = 10; $i > 0; $i--) {
            $scale = $maxVal == 0 ? '' : round(($maxVal * $i) / 10, 1);
            $yScale[$i] = $scale;
        }

        $stats[] = [
            'title' => sprintf(
                '%s (%d/%d)',
                $lang_admin['stat_' . $statTypeItem],
                date('m', $time),
                date('Y', $time),
            ),
            'key' => $statTypeItem,
            'maxVal' => $maxVal,
            'yScale' => $yScale,
            'heights' => $heights,
            'data' => $statData,
            'count' => count($statData),
            'sum' => $sum,
        ];
    }

    // assign
    $tpl->assign('stats', $stats);
    $tpl->assign('time', $time);
    $tpl->assign('modeTitle', $lang_admin[$mode . 'stats']);
    $tpl->assign('statType', $statType);
    $tpl->assign('statTypes', $statTypes);
    $tpl->assign('mode', $mode);
    $tpl->assign('page', 'stats.view.tpl');
} /**
 * space usage
 */ elseif ($_REQUEST['action'] == 'usage') {
    // get data
    $byCategory = GetCategorizedSpaceUsage();
    $byGroup = GetGroupSpaceUsage();

    // get user count
    $res = $db->Query('SELECT COUNT(*) FROM {pre}users');
    [$userCount] = $res->FetchArray(MYSQLI_NUM);
    $res->Free();

    // image output?
    if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'showSpaceByCategory') {
        $data = [];
        foreach ($byCategory as $key => $val) {
            // There used to be more categories. Now, there is only email.
            if ($key === 'mails') {
                $data[$lang_admin['mails']] = $val;
            } else {
                exit('Unknown category');
            }
        }

        $chart = _new('BMBarChart', [$lang_admin['usagebycategory'], 500, 90]);
        $chart->SetData($data);
        $chart->Display();
        exit();
    } elseif (isset($_REQUEST['do']) && $_REQUEST['do'] == 'showSpaceByGroup') {
        $data = [];
        foreach ($byGroup as $val) {
            $data[$val['title']] = $val['size'];
        }

        $chart = _new('BMBarChart', [$lang_admin['usagebygroup'], 500, 90]);
        $chart->SetData($data);
        $chart->Display();
        exit();
    } elseif (
        isset($_REQUEST['do']) &&
        $_REQUEST['do'] == 'showSpaceAverageByGroup'
    ) {
        $data = [];
        foreach ($byGroup as $val) {
            $data[$val['title']] = (int) round(
                $val['size'] / max(1, $val['users']),
                0,
            );
        }
        $chart = _new('BMBarChart', [
            $lang_admin['usagebygroup'] .
            ' (' .
            $lang_admin['useraverage'] .
            ')',
            500,
            90,
        ]);
        $chart->SetData($data);
        $chart->Display();
        exit();
    }

    // assign
    $tpl->assign('userCount', $userCount);
    $tpl->assign('byCategory', $byCategory);
    $tpl->assign('byGroup', $byGroup);
    $tpl->assign('page', 'stats.usage.tpl');
}

$tpl->assign('tabs', $tabs);
$tpl->assign(
    'title',
    $lang_admin['tools'] . ' &raquo; ' . $lang_admin['stats'],
);
$tpl->display('page.tpl');
?>

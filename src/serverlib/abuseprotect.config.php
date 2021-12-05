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

$apTypes = [
    BMAP_SEND_RECP_LIMIT => [
        'title' => $lang_admin['ap_type1'],
        'defaultPoints' => 5,
        'prefs' => [],
    ],
    BMAP_SEND_FREQ_LIMIT => [
        'title' => $lang_admin['ap_type2'],
        'defaultPoints' => 25,
        'prefs' => [],
    ],
    BMAP_SEND_RECP_BLOCKED => [
        'title' => $lang_admin['ap_type3'],
        'defaultPoints' => 15,
        'prefs' => [],
    ],
    BMAP_SEND_RECP_LOCAL_INVALID => [
        'title' => $lang_admin['ap_type4'],
        'defaultPoints' => 10,
        'prefs' => [],
    ],
    BMAP_SEND_RECP_DOMAIN_INVALID => [
        'title' => $lang_admin['ap_type5'],
        'defaultPoints' => 10,
        'prefs' => [],
    ],
    BMAP_SEND_WITHOUT_RECEIVE => [
        'title' => $lang_admin['ap_type6'],
        'defaultPoints' => 20,
        'prefs' => [
            'interval' => [
                'title' => $lang_admin['limit_interval_m'] . ':',
                'type' => FIELD_TEXT,
                'default' => '60',
            ],
        ],
    ],
    BMAP_SEND_FAST => [
        'title' => $lang_admin['ap_type7'],
        'defaultPoints' => 20,
        'prefs' => [
            'interval' => [
                'title' => $lang_admin['min_resend_interval_s'] . ':',
                'type' => FIELD_TEXT,
                'default' => '5',
            ],
        ],
    ],
    BMAP_RECV_FREQ_LIMIT => [
        'title' => $lang_admin['ap_type21'],
        'defaultPoints' => 5,
        'prefs' => [
            'amount' => [
                'title' => $lang_admin['limit_amount_count'] . ':',
                'type' => FIELD_TEXT,
                'default' => '50',
            ],
            'interval' => [
                'title' => $lang_admin['limit_interval_m'] . ':',
                'type' => FIELD_TEXT,
                'default' => '5',
            ],
        ],
    ],
    BMAP_RECV_TRAFFIC_LIMIT => [
        'title' => $lang_admin['ap_type22'],
        'defaultPoints' => 5,
        'prefs' => [
            'amount' => [
                'title' => $lang_admin['limit_amount_mb'] . ':',
                'type' => FIELD_TEXT,
                'default' => '100',
            ],
            'interval' => [
                'title' => $lang_admin['limit_interval_m'] . ':',
                'type' => FIELD_TEXT,
                'default' => '5',
            ],
        ],
    ],
];

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

$templateInfo = [
    'title' => 'b1gMail ' . $lang_admin['default'],
    'author' => 'b1gMail Project',
    'website' => '',
    'for_b1gmail' => B1GMAIL_VERSION,

    'prefs' => [
        'prefsLayout' => [
            'title' => $lang_admin['prefslayout'] . ':',
            'type' => FIELD_DROPDOWN,
            'options' => [
                'onecolumn' => $lang_admin['onecolumn'],
                'twocolumns' => $lang_admin['twocolumns'],
            ],
            'default' => 'onecolumn',
        ],
        'showUserEmail' => [
            'title' => $lang_admin['showuseremail'] . '?',
            'type' => FIELD_CHECKBOX,
            'default' => false,
        ],
        'showCheckboxes' => [
            'title' => $lang_admin['showcheckboxes'] . '?',
            'type' => FIELD_CHECKBOX,
            'default' => false,
        ],
    ],
];

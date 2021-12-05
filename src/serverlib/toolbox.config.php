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
 * b1gMail Toolbox configuration descriptor
 *
 * It is strongly discouraged to edit this file as it needs to stay in sync with the
 * b1gMail Project Toolbox Generator web service.
 *
 * Changes in this file will most likely break Toolbox generation.
 */

$tbxConfig['1.0'] = [
    // common
    'common' => [
        'title' => $lang_admin['common'],
        'options' => [
            'serviceURL' => [
                'title' => $lang_admin['serviceurl'] . ':',
                'type' => FIELD_TEXT,
                'default' => $bm_prefs['selfurl'],
            ],
        ],
    ],

    // branding
    'branding' => [
        'title' => $lang_admin['branding'],
        'icon' => 'branding32',
        'options' => [
            'appTitle' => [
                'title' => $lang_admin['apptitle'] . ':',
                'type' => FIELD_TEXT,
                'default' => $bm_prefs['titel'] . ' Toolbox',
            ],
            'serviceTitle' => [
                'title' => $lang_admin['servicetitle'] . ':',
                'type' => FIELD_TEXT,
                'default' => $bm_prefs['titel'],
            ],
            'appLogo' => [
                'title' => $lang_admin['applogo'] . ':',
                'type' => FIELD_IMAGE,
                'imgSize' => '32x32',
                'default' => 'res/toolbox/applogo.png',
            ],
            'wizardLeft' => [
                'title' => $lang_admin['wizardleft'] . ':',
                'type' => FIELD_IMAGE,
                'imgSize' => '164x314',
                'default' => 'res/toolbox/wizard-left.png',
            ],
            'wizardHead' => [
                'title' => $lang_admin['wizardhead'] . ':',
                'type' => FIELD_IMAGE,
                'imgSize' => '150x57',
                'default' => 'res/toolbox/wizard-head.png',
            ],
            'tbBranding' => [
                'title' => $lang_admin['tbbranding'] . '?',
                'type' => FIELD_CHECKBOX,
                'default' => true,
            ],
        ],
    ],

    // style
    'style' => [
        'title' => $lang_admin['style'],
        'icon' => 'template32',
        'options' => [
            'style' => [
                'title' => $lang_admin['style'] . ':',
                'type' => FIELD_DROPDOWN,
                'options' => [
                    'auto' => $lang_admin['native'],
                    'plastique' => 'Plastique',
                    'cleanlooks' => 'Cleanlooks',
                ],
                'default' => 'auto',
            ],
            'css' => [
                'title' => $lang_admin['stylesheet'] . ':',
                'type' => FIELD_TEXTAREA,
                'default' => '',
            ],
        ],
    ],

    // names
    'names' => [
        'title' => $lang_admin['names'],
        'icon' => 'phrases32',
        'options' => [
            'nameWebdisk' => [
                'title' => 'Webdisk:',
                'type' => FIELD_TEXT,
                'default' => 'Webdisk',
            ],
            'nameSMSManager' => [
                'title' => 'SMS-Manager:',
                'type' => FIELD_TEXT,
                'default' => 'SMS-Manager',
            ],
        ],
    ],
];

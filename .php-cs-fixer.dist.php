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

$fileHeader = <<<END
b1gMail
Copyright (c) 2021 Patrick Schlangen et al

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

END;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src/')
    ->exclude('clientlib/')
    ->exclude('data/')
    ->exclude('serverlib/3rdparty/')
    ->exclude('temp/')
    ->exclude('templates/')

    ->notPath('serverlib/config.default.inc.php')
    ->notPath('serverlib/config.inc.php')
    ->notPath('serverlib/version.inc.php')
;

$config = new PhpCsFixer\Config();

return $config
    ->setFinder($finder)
    ->setIndent("\t")
    ->setRules([
        'header_comment' => [
            'header' => $fileHeader,
            'separate' => 'bottom'
        ]
    ])
;

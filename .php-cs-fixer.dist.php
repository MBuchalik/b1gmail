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

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Based on https://github.com/prettier/plugin-php/blob/f0e6add17f95c91d2d4dba993d0ca1d3c7667989/docs/recipes/php-cs-fixer/PrettierPHPFixer.php
 */
final class PrettierPHPFixer implements FixerInterface {
    public function getPriority(): int {
        return -999;
    }

    public function getDefinition(): FixerDefinitionInterface {
        return new FixerDefinition(
            'The file must be formatted according to Prettier.',
        );
    }

    public function isCandidate(Tokens $tokens): bool {
        return true;
    }

    public function isRisky(): bool {
        return false;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void {
        if (
            0 < $tokens->count() &&
            $this->isCandidate($tokens) &&
            $this->supports($file)
        ) {
            $this->applyFix($file, $tokens);
        }
    }

    public function getName(): string {
        return 'Prettier/php';
    }

    public function supports(SplFileInfo $file): bool {
        return true;
    }

    private function applyFix(SplFileInfo $file, Tokens $tokens): void {
        exec("npx prettier $file", $prettierOutput, $prettierResultCode);

        if ($prettierResultCode !== 0) {
            /*
                It is very important to throw an Exception if something goes wrong while running Prettier.
                Otherwise, the error will just be ignored.
                Which is particularly bad when running an automated code fixer, because it will then just delete the content of the affected file.
            */

            throw new Exception('Something went wrong while running Prettier.');
        }

        // For some reason, the output would not contain an empty newline at the end.
        array_push($prettierOutput, '');

        $code = implode("\n", $prettierOutput);
        $tokens->setCode($code);
    }
}

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src/')

    ->exclude('clientlib/')
    ->exclude('data/')
    ->exclude('serverlib/3rdparty/')
    ->exclude('temp/')
    ->exclude('templates/')

    ->notPath('serverlib/config.default.inc.php')
    ->notPath('serverlib/config.inc.php')
    ->notPath('serverlib/version.inc.php');

$config = new PhpCsFixer\Config();

return $config
    ->setFinder($finder)
    ->registerCustomFixers([new PrettierPHPFixer()])
    ->setRules([
        'Prettier/php' => true,

        'header_comment' => [
            'header' => $fileHeader,
            'separate' => 'bottom',
        ],
    ]);

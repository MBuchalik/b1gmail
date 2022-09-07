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

abstract class SingleMigrationStep {
    abstract function applyMigration($dbConnection): bool;
}

class MigrationRunner {
    private bool $forSetup;

    function __construct($forSetup) {
        $this->forSetup = $forSetup;
    }

    function performMigrations($dbConnection): bool {
        $allMigrationLevels = $this->getAllMigrationLevels();
        $currentMigrationLevel = $this->getCurrentMigrationLevel($dbConnection);

        $migrationLevelsHigherThanCurrentLevel = [];
        foreach ($allMigrationLevels as $migrationLevel) {
            $comparisonResult = $this->compareMigrationLevels(
                $migrationLevel,
                $currentMigrationLevel,
            );
            if ($comparisonResult !== 1) {
                continue;
            }

            $migrationLevelsHigherThanCurrentLevel[] = $migrationLevel;
        }

        usort($migrationLevelsHigherThanCurrentLevel, function ($a, $b) {
            return $this->compareMigrationLevels($a, $b);
        });

        foreach ($migrationLevelsHigherThanCurrentLevel as $migration) {
            $success = $this->applySingleMigration($migration, $dbConnection);

            if (!$success) {
                return false;
            }
        }

        return true;
    }

    function getLatestAvailableMigrationLevelAsString(): string {
        $allMigrationLevels = $this->getAllMigrationLevels();
        usort($allMigrationLevels, function ($a, $b) {
            return $this->compareMigrationLevels($a, $b);
        });

        $latest = $allMigrationLevels[count($allMigrationLevels) - 1];

        return $this->convertMigrationLevelToString($latest);
    }

    /**
     * Get the Migration Level the system is currently on (i.e. read the value of column "migration_level" from "bm60_prefs").
     * If the column does not exist, return the default Migration Level 0-0-0--0.
     *
     * (This method returns the already parsed Migration Level.)
     */
    private function getCurrentMigrationLevel($dbConnection): array {
        $DEFAULT_MIGRATION_LEVEL = $this->parseMigrationName('0-0-0--0');

        if ($this->forSetup) {
            return $DEFAULT_MIGRATION_LEVEL;
        }

        $hasMigrationLevelColumn = false;

        $hasColumnQuery = mysqli_query(
            $dbConnection,
            'SHOW COLUMNS FROM bm60_prefs LIKE \'migration_level\'',
        );
        if ($hasColumnQuery->num_rows === 1) {
            $hasMigrationLevelColumn = true;
        }
        mysqli_free_result($hasColumnQuery);

        if (!$hasMigrationLevelColumn) {
            return $DEFAULT_MIGRATION_LEVEL;
        }

        $res = mysqli_query(
            $dbConnection,
            'SELECT migration_level FROM bm60_prefs',
        );
        $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
        $migrationLevelName = $row['migration_level'];
        mysqli_free_result($res);

        $result = $this->parseMigrationName($migrationLevelName);
        return $result;
    }

    private function getAllMigrationLevels(): array {
        $allMigrations = [];

        foreach (glob('./migrations/*.php') as $filePath) {
            $migrationName = basename($filePath, '.php');
            $parsedMigrationName = $this->parseMigrationName($migrationName);

            $allMigrations[] = $parsedMigrationName;
        }

        return $allMigrations;
    }

    private function parseMigrationName($name): ?array {
        $matches = [];
        preg_match('/^([0-9]+)-([0-9]+)-([0-9]+)--([0-9]+)$/', $name, $matches);

        if (count($matches) !== 5) {
            return null;
        }

        return [
            'versionMajor' => (int) $matches[1],
            'versionMinor' => (int) $matches[2],
            'versionPatch' => (int) $matches[3],
            'migrationNumber' => (int) $matches[4],
        ];
    }

    private function convertMigrationLevelToString(
        array $migrationLevel
    ): string {
        return "{$migrationLevel['versionMajor']}-{$migrationLevel['versionMinor']}-{$migrationLevel['versionPatch']}--{$migrationLevel['migrationNumber']}";
    }

    /**
     * Compare the two provided Migration Levels.
     * Return -1 if Level A is less than Level B.
     * Return 0 is Levels A and B are equal.
     * Return 1 is Level A is greater than Level A.
     */
    private function compareMigrationLevels($levelA, $levelB): int {
        $fieldsToCompare = [
            'versionMajor',
            'versionMinor',
            'versionPatch',
            'migrationNumber',
        ];

        foreach ($fieldsToCompare as $field) {
            if ($levelA[$field] < $levelB[$field]) {
                return -1;
            }
            if ($levelA[$field] > $levelB[$field]) {
                return 1;
            }
        }

        return 0;
    }

    private function applySingleMigration(
        $migrationLevel,
        $dbConnection
    ): bool {
        $fileName = $this->convertMigrationLevelToString($migrationLevel);

        require "./migrations/{$fileName}.php";

        $migrationClassName = "Migration_{$migrationLevel['versionMajor']}_{$migrationLevel['versionMinor']}_{$migrationLevel['versionPatch']}__{$migrationLevel['migrationNumber']}";
        $migrationInstance = new $migrationClassName();

        $migrationSuccess = $migrationInstance->applyMigration($dbConnection);
        if (!$migrationSuccess) {
            return false;
        }

        if ($this->forSetup) {
            return true;
        }

        if (
            !mysqli_query(
                $dbConnection,
                "UPDATE bm60_prefs SET migration_level = '$fileName'",
            )
        ) {
            return false;
        }
        return true;
    }
}

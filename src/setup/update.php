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

// init
require './common.inc.php';
require '../config/config.php';
require '../serverlib/version.inc.php';

error_reporting(E_ALL);

// steps
define('STEP_SELECT_LANGUAGE', 0);
define('STEP_WELCOME', 1);
define('STEP_SYSTEMCHECK', 2);
define('STEP_UPDATE', 3);
define('STEP_UPDATE_STEP', 4);

// connect to mysql db
if (
    !($connection = CheckMySQLLogin(
        $mysql['host'],
        $mysql['user'],
        $mysql['pass'],
        $mysql['db'],
    ))
) {
    die('ERROR:MySQL connection failed');
}

// read prefs
$result = mysqli_query($connection, 'SELECT * FROM bm60_prefs LIMIT 1');
$bm_prefs = mysqli_fetch_array($result, MYSQLI_ASSOC);
mysqli_free_result($result);

// step?
if (!isset($_REQUEST['step'])) {
    $step = STEP_WELCOME;
} else {
    $step = (int) $_REQUEST['step'];
}

// read language file
if (!isset($_GET['lng'])) {
    $_GET['lng'] =
        strpos($bm_prefs['language'], 'deutsch') !== false
            ? 'deutsch'
            : 'english';
}
ReadLanguage();

// header
if ($step != STEP_UPDATE_STEP) {
    pageHeader(true);
}

/**
 * welcome
 */
if ($step == STEP_WELCOME) {
    $nextStep = STEP_SYSTEMCHECK; ?>
        <h1><?php echo $lang_setup['welcome']; ?></h1>

		<?php echo $lang_setup['update_welcome_text']; ?>
		<?php
} /**
 * system check
 */ elseif ($step == STEP_SYSTEMCHECK) {
    $nextStep = STEP_UPDATE; ?>
	<h1><?php echo $lang_setup['syscheck']; ?></h1>

	<?php echo $lang_setup['syscheck_text']; ?>

	<br /><br />
	<table class="list">
		<tr>
			<th width="180">&nbsp;</th>
			<th><?php echo $lang_setup['required']; ?></th>
			<th><?php echo $lang_setup['available']; ?></th>
			<th width="60">&nbsp;</th>
		</tr>
		<tr>
			<th><?php echo $lang_setup['phpversion']; ?></th>
			<td>5.3.0</td>
			<td><?php echo phpversion(); ?></td>
			<td><img src="../admin/templates/images/<?php if (
       (int) str_replace('.', '', phpversion()) >= 530
   ) {
       echo 'ok';
   } else {
       echo 'error';
       $nextStep = STEP_SYSTEMCHECK;
   } ?>.png" border="0" alt="" width="16" height="16" /></td>
		</tr>
		<tr>
			<th><?php echo $lang_setup['mysqlext']; ?></th>
			<td><?php echo $lang_setup['yes']; ?></td>
			<td><?php echo function_exists('mysqli_connect')
       ? $lang_setup['yes']
       : $lang_setup['no']; ?></td>
			<td><img src="../admin/templates/images/<?php if (
       function_exists('mysqli_connect')
   ) {
       echo 'ok';
   } else {
       echo 'error';
       $nextStep = STEP_SYSTEMCHECK;
   } ?>.png" border="0" alt="" width="16" height="16" /></td>
		</tr>
		<?php foreach ($writeableFiles as $file) { ?>
		<tr>
			<th><?php echo $file; ?></th>
			<td><?php echo $lang_setup['writeable']; ?></td>
			<td><?php echo is_writeable('../' . $file)
       ? $lang_setup['writeable']
       : $lang_setup['notwriteable']; ?></td>
			<td><img src="../admin/templates/images/<?php if (is_writeable('../' . $file)) {
       echo 'ok';
   } else {
       echo 'error';
       $nextStep = STEP_SYSTEMCHECK;
   } ?>.png" border="0" alt="" width="16" height="16" /></td>
		</tr>
			<?php } ?>
	</table>

	<br />
	<?php echo $nextStep == STEP_UPDATE
     ? $lang_setup['checkok_text']
     : $lang_setup['checkfail_text']; ?>
	<?php
} /**
 * update
 */ elseif ($step == STEP_UPDATE) { ?>
	<h1><?php echo $lang_setup['updating']; ?></h1>

	<?php echo $lang_setup['updating_text']; ?>

	<br /><br />
	<table class="list">
		<tr>
			<th width="40"></th>
			<th><?php echo $lang_setup['step']; ?></th>
			<th width="180"><?php echo $lang_setup['progress']; ?></th>
		</tr>
		<tr>
			<td id="step_prepare_status">&nbsp;</td>
			<th id="step_prepare_text" style="font-weight:normal;">1. <?php echo $lang_setup[
       'update_prepare'
   ]; ?></th>
			<td id="step_prepare_progress">&nbsp;</td>
		</tr>
		<tr>
			<td id="step_struct2_status">&nbsp;</td>
			<th id="step_struct2_text" style="font-weight:normal;">1. <?php echo $lang_setup[
       'update_struct2'
   ]; ?></th>
			<td id="step_struct2_progress">&nbsp;</td>
		</tr>
		<tr>
			<td id="step_config_status">&nbsp;</td>
			<th id="step_config_text" style="font-weight:normal;">2. <?php echo $lang_setup[
       'update_config'
   ]; ?></th>
			<td id="step_config_progress">&nbsp;</td>
		</tr>
		<tr>
			<td id="step_struct3_status">&nbsp;</td>
			<th id="step_struct3_text" style="font-weight:normal;">3. <?php echo $lang_setup[
       'update_struct3'
   ]; ?></th>
			<td id="step_struct3_progress">&nbsp;</td>
		</tr>
		<tr>
			<td id="step_resetcache_status">&nbsp;</td>
			<th id="step_resetcache_text" style="font-weight:normal;">4. <?php echo $lang_setup[
       'update_resetcache'
   ]; ?></th>
			<td id="step_resetcache_progress">&nbsp;</td>
		</tr>
		<tr>
			<td id="step_optimize_status">&nbsp;</td>
			<th id="step_optimize_text" style="font-weight:normal;">5. <?php echo $lang_setup[
       'update_optimize'
   ]; ?></th>
			<td id="step_optimize_progress">&nbsp;</td>
		</tr>
		<tr>
			<td id="step_complete_status">&nbsp;</td>
			<th id="step_complete_text" style="font-weight:normal;">6. <?php echo $lang_setup[
       'update_complete'
   ]; ?></th>
			<td id="step_complete_progress">&nbsp;</td>
		</tr>
	</table>

	<br />
	<?php echo $lang_setup['updating_text2']; ?>

	<textarea readonly="readonly" class="installLog" id="log" style="display:none;height:150px;"></textarea>
	<br /><br />

	<div align="center" id="done" style="display:none;">
		<b><?php echo $lang_setup['updatedonefinal']; ?></b>
	</div>

	<script src="./res/update.js"></script>
	<script>
		window.onload = beginUpdate;
	</script>

	<?php } /**
 * update step
 */ elseif ($step == STEP_UPDATE_STEP) {
    $do = $_REQUEST['do'];
    $pos = isset($_REQUEST['pos']) ? (int) $_REQUEST['pos'] : 0;

    //
    // preparation
    //
    if ($do == 'prepare') {
        mysqli_query($connection, 'UPDATE bm60_prefs SET wartung=\'yes\'');
        echo 'OK:DONE';
    }

    //
    // db structure sync
    //
    elseif ($do == 'struct2') {
        include './migration.php';

        $migrationRunner = new MigrationRunner(false);

        $migrationSuccess = $migrationRunner->performMigrations($connection);
        if ($migrationSuccess) {
            echo 'OK:DONE';
        } else {
            echo 'ERROR:Failed to perform database migrations';
        }
    }

    //
    // config
    //
    elseif ($do == 'config') {
        // add new root certificates
        if (!isset($rootCertsData)) {
            include './data/rootcerts.data.php';
        }
        foreach ($rootCertsData as $hash => $query) {
            $res = mysqli_query(
                $connection,
                sprintf(
                    'SELECT COUNT(*) FROM bm60_certificates WHERE `type`=0 AND `userid`=0 AND `hash`=\'%s\'',
                    SQLEscape($hash, $connection),
                ),
            );
            [$hashCount] = mysqli_fetch_array($res, MYSQLI_NUM);
            mysqli_free_result($res);

            if ((int) $hashCount == 0) {
                mysqli_query($connection, $query);
            }
        }

        // remove outdated root certificates
        mysqli_query(
            $connection,
            'DELETE FROM bm60_certificates WHERE `type`=0 AND `userid`=0 AND `validto`<' .
                time(),
        );

        echo 'OK:DONE';
    }

    //
    // optimize and clean up
    //
    elseif ($do == 'struct3') {
        // Currently a NOOP.
        echo 'OK:DONE';
    }

    //
    // reset cache
    //
    elseif ($do == 'resetcache') {
        $deleteIDs = [];

        $res = mysqli_query(
            $connection,
            'SELECT size,`key` FROM bm60_file_cache',
        );
        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
            $fileName = '../temp/cache/' . $row['key'] . '.cache';
            if (file_exists($fileName)) {
                @unlink($fileName);
            }
            $fileName = '../temp/' . $row['key'] . '.cache';
            if (file_exists($fileName)) {
                @unlink($fileName);
            }
            $deleteIDs[] = $row['key'];
        }
        mysqli_free_result($res);

        if (count($deleteIDs) > 0) {
            mysqli_query(
                $connection,
                'DELETE FROM bm60_file_cache WHERE `key` IN(\'' .
                    implode('\',\'', $deleteIDs) .
                    '\')',
            );
        }

        echo 'OK:DONE';
    }

    //
    // optimize tables
    //
    elseif ($do == 'optimize') {
        // get tables
        $tables = [];
        $res = mysqli_query($connection, 'SHOW TABLES');
        while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
            if (substr($row[0], 0, 5) == 'bm60_') {
                $tables[] = $row[0];
            }
        }
        mysqli_free_result($res);
        $count = count($tables);

        // done?
        if ($pos >= $count) {
            echo 'OK:DONE';
        } else {
            $table = $tables[$pos++];
            mysqli_query($connection, 'OPTIMIZE TABLE ' . $table);

            if ($pos >= $count) {
                echo 'OK:DONE';
            } else {
                echo 'OK:' . $pos . '/' . $count;
            }
        }
    }

    //
    // complete
    //
    elseif ($do == 'complete') {
        mysqli_query(
            $connection,
            'UPDATE bm60_prefs SET wartung=\'no\',patchlevel=0',
        );

        echo 'OK:DONE';
    }

    //
    // unknown action
    //
    else {
        echo 'ERROR:Unknown action.';
    }

    mysqli_close($connection);

    exit();
}

// footer
pageFooter(true);

// disconnect
mysqli_close($connection);
?>

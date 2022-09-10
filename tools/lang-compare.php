<?php
/*
  Detect language variables that are exit in the German, but not in the English language file. And vice versa.
  This script should be run from the CLI.
*/

define('SRC_DIR', dirname(dirname(__FILE__)) . '/src/');

$langArrayNames = ['lang_user', 'lang_admin', 'lang_client', 'lang_custom'];

include SRC_DIR . '/serverlib/languages/deutsch.lang.php';
$lang_user_de = $lang_user;
$lang_admin_de = $lang_admin;
$lang_client_de = $lang_client;
$lang_custom_de = $lang_custom;

// Reset the language arrays.
foreach ($langArrayNames as $langArrayName) {
    $$langArrayName = [];
}
$lang_user = $lang_admin = $lang_client = $lang_custom = [];
include SRC_DIR . '/serverlib/languages/english.lang.php';
$lang_user_en = $lang_user;
$lang_admin_en = $lang_admin;
$lang_client_en = $lang_client;
$lang_custom_en = $lang_custom;

foreach ($langArrayNames as $langArrayName) {
    $deVarName = $langArrayName . '_de';
    $enVarName = $langArrayName . '_en';

    foreach ($$deVarName as $langKey => $langValue) {
        if (isset($$enVarName[$langKey])) {
            continue;
        }
        echo "Missing en variable: \${$lang_key}\n";
    }

    foreach ($$enVarName as $langKey => $langValue) {
        if (isset($$deVarName[$langKey])) {
            continue;
        }
        echo "Missing de variable: \${$langArrayName}['{$langKey}']\n";
    }
}

<?php
/**
 * lang_iso.php
 * Simple tool to convert language files to ISO-8859-15 for distribution.
 */

if(count($_SERVER['argv']) != 2)
{
	echo 'Usage: ' . $_SERVER['argv'][0] . ' filename' . "\n";
	exit(1);
}

$file = $_SERVER['argv'][1];

if(!file_exists($file))
{
	echo 'File does not exist: ' . $file . "\n";
	exit(1);
}

$cnt = file_get_contents($file);
$cnt = mb_convert_encoding($cnt, 'iso-8859-15', 'utf-8');
$cnt = str_replace('::UTF-8::', '::iso-8859-1::', $cnt);
$cnt = str_replace('::de_DE.UTF-8|de_DE|de_DE@euro|de|ge|deu|deu_deu|ger',
	'::de_DE.ISO8859-15|de_DE.ISO8859-1|de_DE|de_DE@euro|de|ge|deu|deu_deu|ger',
	$cnt);
$cnt = str_replace('::en_US.UTF-8|en_US|en_GB.UTF-8|en_GB|english|en|us',
	'::en_US.US-ASCII|en_US|en_GB.US-ASCII|en_GB|english|en|us',
	$cnt);

$fp = fopen($file, 'wb');
fwrite($fp, $cnt);
fclose($fp);

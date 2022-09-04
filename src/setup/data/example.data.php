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

$exampleData = [];
$exampleData[] =
    'INSERT INTO bm60_faq(`id`,`typ`,`required`,`frage`,`antwort`,`lang`) VALUES(\'1\',\'nli\',\'\',\'Was hat es mit dem Captcha-Code auf sich?\',\'Mit dem Captcha-Code stellen wir sicher, dass keine Massen-Registrierungen durchgeführt werden, z.B. durch speziell dazu entwickelten Programmen. Die jeweiligen Porgramme können den Captcha-Code, der durch Bilder angezeigt wird, nicht einlesen und somit keine Registrierungen durchführen. So schützen wir unseren Dienst vor Spam-Anmeldungen.\',\'deutsch\')';
$exampleData[] =
    'INSERT INTO bm60_faq(`id`,`typ`,`required`,`frage`,`antwort`,`lang`) VALUES(\'2\',\'nli\',\'\',\'Das Land in dem ich lebe ist nicht in der Liste aufgef&uuml;hrt. Was soll ich tun?\',\'F&uuml;r diesen Zweck haben wir einen Extra-Eintrag hinzugef&uuml;gt: \"Anderes Land\". W&auml;hlen Sie bitte diesen Eintrag, wenn ihr Land nicht aufgef&uuml;hrt sein sollte.\',\':all:\')';
$exampleData[] =
    'INSERT INTO bm60_faq(`id`,`typ`,`required`,`frage`,`antwort`,`lang`) VALUES(\'3\',\'nli\',\'\',\'Ich habe mein Passwort vergessen, was soll ich tun?\',\'Klicken Sie dazu bitte im Men&uuml; auf Passwort. Geben Sie dort bitte die E-Mail - Adresse ein, die Sie bei und registriert haben. Nach einem Klick auf \"Okay\" wird Ihnen das Passwort an die E-Mail - Adresse gesandt, die Sie als Alternativ - Adresse angegeben haben. Sollten Sie keine Alternativ - Adresse angegeben haben, k&ouml;nnen wir Ihnen leider das Passwort nicht automatisch zusenden lassen. Kontaktieren Sie uns bitte direkt, wir helfen Ihnen dann gerne weiter.\',\':all:\')';
$exampleData[] =
    'INSERT INTO bm60_faq(`id`,`typ`,`required`,`frage`,`antwort`,`lang`) VALUES(\'4\',\'nli\',\'\',\'Wo finde ich Kontaktinformationen zu Ihnen?\',\'Unter dem Men&uuml;punkt \"Impressum\" finden Sie Kontaktdaten des Verantwortlichen f&uuml;r diesen Dienst.\',\':all:\')';
$exampleData[] =
    'INSERT INTO bm60_faq(`id`,`typ`,`required`,`frage`,`antwort`,`lang`) VALUES(\'5\',\'nli\',\'\',\'Ich stimme Ihren AGB nicht zu, kann ich mich trotzdem anmelden?\',\'Eine Registrierung bei uns mit der Zustimmung und Einhaltung unserer Allgemeinen Gesch&auml;ftsbedingungen (AGB) verbunden. Ein Verstoß gegen die Bedingungen kann eine sofortige, fristlose L&ouml;schung Ihres Accounts zur Folge haben. Wenn Sie unseren AGB nicht zustimmen, k&ouml;nnen und d&uuml;rfen Sie sich bei uns leider nicht registrieren.\',\':all:\')';
$exampleData[] =
    'INSERT INTO bm60_faq(`id`,`typ`,`required`,`frage`,`antwort`,`lang`) VALUES(\'6\',\'nli\',\'\',\'Was bedeutet die Option \"Merken\" beim Login?\',\'Wenn Sie \"Merken\" aktivieren, werden Ihre Login-Daten gespeichert und beim n&auml;chsten Besuch unseres Dienstes automatisch eingef&uuml;gt. Somit k&ouml;nnen Sie sich nur durch einen Klick auf \"Login\" einloggen, andere Daten werden wie gesagt automatisch eingef&uuml;gt.\',\':all:\')';

$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'B1GMailSearchProvider\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Notes\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_WebdiskDND\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Mailspace\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Quicklinks\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Calendar\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Tasks\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Welcome\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_EMail\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Webdiskspace\',\'1\',\'0\',\'\',\'\',\'\')';
$exampleData[] =
    'INSERT INTO bm60_mods(`modname`,`installed`,`pos`,`packageName`,`signature`,`files`) VALUES(\'BMPlugin_Widget_Websearch\',\'1\',\'0\',\'\',\'\',\'\')';

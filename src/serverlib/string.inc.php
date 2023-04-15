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
 * Strip 4-byte UTF-8 characters from a string, but only if current charset
 * is UTF-8.
 *
 * @param string $in String
 * @return string
 */
function Strip4ByteChars($in) {
    global $currentCharset;

    if (in_array(strtolower($currentCharset), ['utf8', 'utf-8'])) {
        return preg_replace('/[\x{10000}-\x{10FFFF}]/u', ' ', $in);
    }

    return $in;
}

/**
 * multi byte compliant strlen() implementation
 * (return number of characters)
 *
 * @param string $str Input
 * @return int
 */
function _strlen($str) {
    global $currentCharset;

    if (function_exists('mb_strlen')) {
        return mb_strlen($str, $currentCharset);
    }
    if (function_exists('iconv_strlen')) {
        return iconv_strlen($str, $currentCharset);
    }

    return strlen($str);
}

/**
 * multi byte compliant substr() implementation
 *
 * @param string $str Input
 * @param int $start Start position
 * @param int $length Length
 * @return string
 */
function _substr($str, $start, $length = false) {
    global $currentCharset;

    if ($length === false) {
        $length = _strlen($str) - $start;
    }

    if (function_exists('mb_substr')) {
        return mb_substr($str, $start, $length, $currentCharset);
    }
    if (function_exists('iconv_substr')) {
        return iconv_substr($str, $start, $length, $currentCharset);
    }

    return substr($str, $start, $length);
}

/**
 * check if $char is a whitespace char
 *
 * @param string $char Char
 * @return bool
 */
function _is_whitespace($char) {
    return in_array($char, [' ', "\n", "\r", "\t"]);
}

/**
 * multi byte compliant wordwrap() implementation
 *
 * @param string $str Input
 * @param int $width Line width
 * @param string $break Break string
 * @param bool $cut Cut words?
 * @return string
 */
function _wordwrap($str, $width = 75, $break = null, $cut = null) {
    global $currentCharset;

    $currentLineLength = 0;
    $result = '';
    $shouldBreak = false;
    $strLen = _strlen($str);
    $pcreUTF8 =
        function_exists('preg_split') &&
        ((SERVER_WINDOWS && PHPNumVersion() >= 423) ||
            (!SERVER_WINDOWS && PHPNumVersion() >= 410));

    // it is _way_ faster to use preg_split to split the string
    // into characters than to use _substr / mb_substr
    if (in_array(strtolower($currentCharset), ['utf8', 'utf-8']) && $pcreUTF8) {
        $processed = 0;
        while ($processed < $strLen) {
            // prevent splitting a too long string at once by processing it
            // in chunks
            $chunk = _substr($str, $processed, 2048);
            $processed += _strlen($chunk);
            $chars = preg_split('//u', $chunk, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($chars as $c) {
                $result .= $c;

                if ($c == "\n") {
                    $currentLineLength = 0;
                } else {
                    $currentLineLength++;
                }

                if ($currentLineLength >= $width) {
                    if ($cut || ($shouldBreak && _is_whitespace($c))) {
                        $result = rtrim($result) . $break;
                        $currentLineLength = 0;
                        $shouldBreak = false;
                    } else {
                        $shouldBreak = true;
                    }
                }
            }
        }
    }

    // fall back to slower method if PCRE does not support UTF-8
    else {
        for ($i = 0; $i < $strLen; $i++) {
            $c = _substr($str, $i, 1);
            $result .= $c;

            if ($c == "\n") {
                $currentLineLength = 0;
            } else {
                $currentLineLength++;
            }

            if ($currentLineLength >= $width) {
                if ($cut || ($shouldBreak && _is_whitespace($c))) {
                    $result = rtrim($result) . $break;
                    $currentLineLength = 0;
                    $shouldBreak = false;
                } else {
                    $shouldBreak = true;
                }
            }
        }
    }

    return $result;
}

/**
 * decode hex-encoded char
 *
 * @param string $hex Hex string
 * @return string
 */
function _hex2utf($matches) {
    $num = hexdec($matches[1]);
    if ($num < 128) {
        return chr($num);
    } elseif ($num < 1924) {
        return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
    } elseif ($num < 32768) {
        return chr(($num >> 12) + 224) .
            chr((($num >> 6) & 63) + 128) .
            chr(($num & 63) + 128);
    } elseif ($num < 2097152) {
        return chr(($num >> 18) + 240) .
            chr((($num >> 12) & 63) + 128) .
            chr((($num >> 6) & 63) + 128) .
            chr(($num & 63) + 128);
    }
    return '';
}

/**
 * decode javascript escaped string
 *
 * @param string $str Input
 * @return string
 */
function _unescape($str) {
    global $currentCharset;

    return preg_replace_callback(
        '/%u([0-9ABCDEF]{3,4})/i',
        '_hex2utf',
        CharsetDecode($str, 'ISO-8859-1'),
    );
}

function _urlencode($str) {
    global $currentCharset;

    if (
        strtolower($currentCharset) == 'utf-8' ||
        strtolower($currentCharset) == 'utf8'
    ) {
        return urlencode(utf8_decode($str));
    }
    return urlencode($str);
}

function _urldecode($str) {
    global $currentCharset;

    if (
        strtolower($currentCharset) == 'utf-8' ||
        strtolower($currentCharset) == 'utf8'
    ) {
        return utf8_encode(urldecode($str));
    }
    return urldecode($str);
}

/**
 * Decode from UTF-8 to current charset provided that data has been passed over XMLHttpRequest
 * or with charset=utf-8 content type.
 *
 * @param string $in
 * @return string
 */
function AjaxCharsetDecode($in) {
    global $currentCharset;

    // AJAX data is always utf-8 encoded, so there's nothing to do when we're in utf-8 mode
    if (in_array(strtolower($currentCharset), ['utf8', 'utf-8'])) {
        return $in;
    }

    // Otherwise, check if this is AJAX-submitted
    if (
        strpos(strtolower($_SERVER['CONTENT_TYPE']), 'charset=utf-8') !==
            false ||
        (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'charset') === false &&
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    ) {
        return CharsetDecode($in, 'utf8');
    }

    return $in;
}

/**
 * convert string charset
 *
 * @param string $text Input
 * @param string $charset Input charset (false => $currentCharset)
 * @param string $destCharset Output charset (false => $currentCharset)
 * @return string
 */
function CharsetDecode($text, $charset = false, $destCharset = false) {
    global $currentCharset;

    if ($charset !== false && $charset != '') {
        $charset = trim(strtolower($charset));
    } else {
        $charset = trim(strtolower($currentCharset));
    }

    $_charset = $charset;

    if ($destCharset !== false) {
        $myCharset = trim(strtolower($destCharset));
    } else {
        $myCharset = trim(strtolower($currentCharset));
    }

    $_myCharset = $myCharset;

    if (substr($charset, 0, 10) == 'iso-8859-1') {
        $charset = 'iso-8859-1';
    }
    if (substr($myCharset, 0, 10) == 'iso-8859-1') {
        $myCharset = 'iso-8859-1';
    }

    if ($charset == $myCharset) {
        return $text;
    }

    if (function_exists('mb_convert_encoding')) {
        if ($newText = convertOrFalse($text, $_myCharset, $_charset)) {
            $text = $newText;
        } elseif ($newText = convertOrFalse($text, $myCharset, $charset)) {
            $text = $newText;
        }
    } elseif (function_exists('iconv')) {
        if ($newText = @iconv($_charset, $_myCharset, $text)) {
            $text = $newText;
        } elseif ($newText = @iconv($charset, $myCharset, $text)) {
            $text = $newText;
        }
    } else {
        switch ($charset) {
            case 'iso-8859-1':
                if ($myCharset == 'utf8' || $myCharset == 'utf-8') {
                    $text = utf8_encode($text);
                }
                break;

            case 'utf8':
            case 'utf-8':
                if ($myCharset == 'iso-8859-1') {
                    $text = utf8_decode($text);
                }
                break;
        }
    }

    return $text;
}

/**
 * This function is a wrapper around `mb_convert_encoding`:
 * It tries to return the result from a call to `mb_convert_encoding`.
 * If a `ValueError` occurs, this function returns `false`.
 */
function convertOrFalse($string, $to_encoding, $from_encoding) {
    try {
        return @mb_convert_encoding($string, $to_encoding, $from_encoding);
    } catch (ValueError $e) {
        return false;
    }
}

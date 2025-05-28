<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.38.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

function truncateText($text, $numOfWords = 10, $add = '')
{
    $text = strip_tags($text);

    if ($numOfWords) {
        $text = str_replace(array("\r", "\n"), '', $text);

        $lenBefore = strlen($text);
        if ($numOfWords) {
            if (preg_match("/\s*(\S+\s*){0,$numOfWords}/", $text, $match)) {
                $text = trim($match[0]);
            }
            if (strlen($text) != $lenBefore) {
                $text .= '... ' . $add;
            }
        }
    }

    return $text;
}

function utf8_substr($str, $from, $len)
{
    $str = strip_tags($str);
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' .
        '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s', '$1', $str);
}

function utf8_strlen($s)
{
    return preg_match_all('/./u', $s, $tmp);
}

function utf8_ucfirst($string, $e = 'utf-8')
{
    if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
        $string = mb_strtolower($string, $e);
        $upper = mb_strtoupper($string, $e);
        preg_match('#(.)#us', $upper, $matches);
        $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
    } else {
        $string = ucfirst($string);
    }
    return $string;
}

function utf8_strtolower($string, $e = 'utf-8')
{
    if (function_exists('mb_strtolower')) {
        $string = mb_strtolower($string, $e);
    } else {
        $string = strtolower($string);
    }
    return $string;
}

function translit($str, $separator = 'dash', $lowercase = TRUE, $removespace = TRUE)
{
    return URLify::filter($str);
}


/**
 * Strip a string from the end of a string
 *
 * @param string $str the input string
 * @param string $remove OPTIONAL string to remove
 *
 * @return string the modified string
 */
function rstrtrim($str, $remove = null)
{
    $str = (string)$str;
    $remove = (string)$remove;

    if (empty($remove)) {
        return rtrim($str);
    }

    $len = strlen($remove);
    $offset = strlen($str) - $len;
    while ($offset > 0 && $offset == strpos($str, $remove, $offset)) {
        $str = substr($str, 0, $offset);
        $offset = strlen($str) - $len;
    }

    return rtrim($str);
}

//End of function rstrtrim($str, $remove=null)

function cleanPostData($data)
{
    $data = trim($data);
    $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data = mb_strtolower($data, 'UTF-8');
    $data = preg_replace('~[^a-z0-9 \x80-\xFF]~i', "", $data);
    return $data;
}

function purify($text)
{
    $purifier = new CHtmlPurifier;
    $purifier->options = [
        'AutoFormat.AutoParagraph' => true,
        //'HTML.Allowed'=>'p,ul,li,b,i,a[href],pre',
        'HTML.Nofollow' => true,
        'Core.EscapeInvalidTags' => true,
    ];
//    if(!param('convertYoutubeLink', 1)){
//        $purifier->options['AutoFormat.Linkify'] = true;
//    }

    return $purifier->purify($text);
}

function purifyForDemo($text)
{
    $purifier = new CHtmlPurifier;
    $purifier->options = array(
        'HTML.Allowed' => 'p,ul[style],ol,li,strong,b,em,span',
        'HTML.Nofollow' => true,
        'Core.EscapeInvalidTags' => true,
    );

    return $purifier->purify($text);
}

function getRefValByID($ID)
{
    $sql = "SELECT title_" . Yii::app()->language . " FROM {{apartment_reference_values}} WHERE id=:id";
    return CHtml::encode(Yii::app()->db->createCommand($sql)->queryScalar(array('id' => $ID)));
}

function fieldTextToArray($text, $separator = "\n")
{
    $text = explode($separator, $text);
    $text = array_map('trim', $text);
    return $text;
}

function isIssetHtml($string)
{
    if($string){
        return preg_match("/<[^<]+>/", $string, $m) != 0;
    }
    return false;
}

function firstLettes($string)
{
    $words = explode(" ", $string);
    $letters = "";
    foreach ($words as $value) {
        $letters .= mb_substr($value, 0, 1);
    }
    return $letters;
}

function convertYoutube($string) {
    return preg_replace(
        "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
        "<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$2\" allowfullscreen></iframe>",
        $string
    );
}


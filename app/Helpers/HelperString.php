<?php


namespace App\Helpers;


class HelperString
{
    public static function randomString($length)
    {
        $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
        $charsLength = strlen($characters) - 1;
        $string = "";
        for ($i = 0; $i < $length; $i++) {
            $randNum = mt_rand(0, $charsLength);
            $string .= $characters[$randNum];
        }
        return $string;
    }

    public static function generateURL($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function randomDigit($length)
    {
        return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}

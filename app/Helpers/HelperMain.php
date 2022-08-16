<?php

namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Morilog\Jalali\Jalalian;

class HelperMain
{

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

    public static function cleanUrl($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public static function paginate($array)
    {
        $page = request()->has('page') ? request('page') : 1;
        $perPage = request()->has('per_page') ? request('per_page') : 15;
        $offset = ($page * $perPage) - $perPage;

        $newCollection = collect($array);
        $results = new LengthAwarePaginator(
            $newCollection->slice($offset, $perPage)->values()->all(),
            $newCollection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return $results;
    }

    public static function diffForHumanInPersian($string)
    {
        return Jalalian::fromCarbon(Carbon::parse($string))->ago();
    }

    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}

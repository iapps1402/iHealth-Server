<?php

namespace App\Helpers;


class AndroidDeepLinkBuilder
{
    public static $SCHEMA = 'dopamine';
    private $protocol, $host, $path, $package, $scheme;
    private $parameters = '';
    public static $ANDROID_PACKAGE_NAME = 'ir.dopaminefit.dopamine';
    public static $Android_PROTOCOL = 'dopamine';

    public function __construct(string $protocol, string $host, string $path, string $package, $scheme)
    {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->path = $path;
        $this->package = $package;
        $this->scheme = $scheme;
    }

    public function addParameter($key, $value)
    {
        $this->parameters .= ($this->parameters != '' ? '&' : '') . $key . '=' . $value;
    }

    public function generate(): string
    {
        return '<script> document.location="intent://' . $this->host . '/' . (empty($this->path) ? '' : $this->path) . ($this->parameters != '' ? '?' . $this->parameters : '') . '#Intent;scheme=' . $this->scheme . ';package=' . $this->package . ';end";</script>';
    }

}

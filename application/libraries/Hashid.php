<?php

namespace App\Libraries;

use Hashids\Hashids;

class Hashid
{
    protected static $hash;

    protected static function init()
    {
        if (!self::$hash) {
            self::$hash = new Hashids($_ENV["APP_SALT"]);
        }
    }

    public static function encode($par): string
    {
        self::init();
        return self::$hash->encode($par);
    }

    public static function singleDecode($par = ""): string
    {
        self::init();
        return self::$hash->decode($par)[0];
    }
}

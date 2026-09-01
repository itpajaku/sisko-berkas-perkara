<?php

namespace App\Libraries;

use Hashids\Hashids;

class Hashid
{
	protected static $hash;

	protected static function init()
	{
		if (!self::$hash) {
			self::$hash = new Hashids($_ENV["APP_SALT"], 6);
		}
	}

	public static function encode($par): string
	{
		self::init();
		return self::$hash->encode($par);
	}

	public static function singleDecode($par = ""): ?string
	{
		self::init();
		$decoded = self::$hash->decode($par);
		return isset($decoded[0]) ? (string)$decoded[0] : null;
	}

	public static function singleEncode($par)
	{
		self::init();
		return self::$hash->encode($par);
	}
}

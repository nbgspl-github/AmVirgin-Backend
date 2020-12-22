<?php

namespace App\Models;

class Settings extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'settings';
	protected $fillable = ['key', 'value'];
	protected $primaryKey = 'key';
	public $incrementing = false;
	public $timestamps = false;

	public static function get ($key, $default = null)
	{
		$setting = self::find($key);
		if ($setting != null)
			return $setting->value;
		else
			return $default;
	}

	public static function getInt ($key, $default = 0)
	{
		return intval(self::get($key, $default));
	}

	public static function getBool ($key, $default = false)
	{
		return boolval(self::get($key, $default));
	}

	public static function set ($key, $value)
	{
		$setting = self::find($key);
		if ($setting != null) {
			$setting->value = $value;
			$setting->save();
		} else {
			$setting = new Settings();
			$setting->key = $key;
			$setting->value = $value;
			$setting->save();
		}
	}

	public static function remove ($key)
	{
		$setting = Settings::find($key);
		if ($setting != null) {
			$setting->delete();
			return true;
		}
		return false;
	}
}
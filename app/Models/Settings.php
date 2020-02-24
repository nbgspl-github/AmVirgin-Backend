<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model {
	protected string $table = 'settings';
	protected array $fillable = ['key', 'value'];
	protected string $primaryKey = 'key';
	public bool $incrementing = false;
	public bool $timestamps = false;

	public static function get($key, $default = null) {
		$setting = Settings::find($key);
		if ($setting != null)
			return $setting->value;
		else
			return $default;
	}

	public static function getInt($key, $default = 0) {
		return intval(self::get($key, $default));
	}

	public static function getBool($key, $default = false) {
		return boolval(self::get($key, $default));
	}

	public static function set($key, $value) {
		$setting = Settings::find($key);
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

	public static function remove($key) {
		$setting = Settings::find($key);
		if ($setting != null) {
			$setting->delete();
			return true;
		}
		return false;
	}
}

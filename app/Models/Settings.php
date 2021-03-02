<?php

namespace App\Models;

use Exception;

class Settings extends \App\Library\Database\Eloquent\Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    public $timestamps = false;

    public const FAQ = 'faq';
    public const PRIVACY_POLICY = 'privacy_policy';
    public const RETURN_POLICY = 'return_policy';
    public const CANCELLATION_POLICY = 'cancellation_policy';
    public const SHIPPING_POLICY = 'shipping_policy';
    public const ABOUT_US = 'about_us';
    public const TERMS_CONDITIONS = 'terms_conditions';

    public static function get ($key, $default = null)
    {
        $setting = self::query()->find($key);
        if ($setting != null)
            return $setting->value;
        else
            return $default;
    }

    public static function getInt ($key, $default = 0): int
    {
        return intval(self::get($key, $default));
    }

    public static function getBool ($key, $default = false): bool
    {
        return boolval(self::get($key, $default));
    }

    public static function set ($key, $value)
    {
        $setting = self::query()->find($key);
        if ($setting != null) {
            $setting->value = $value;
            $setting->save();
        } else {
            Settings::query()->create([
                'key' => $key,
                'value' => $value
            ]);
        }
    }

    /**
     * @param $key
     * @return bool
     * @throws Exception
     */
    public static function remove ($key): bool
    {
        $setting = Settings::query()->find($key);
        if ($setting != null) {
            $setting->delete();
            return true;
        }
        return false;
    }
}
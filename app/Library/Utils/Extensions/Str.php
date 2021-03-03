<?php

namespace App\Library\Utils\Extensions;

class Str extends \Illuminate\Support\Str
{
    const Empty = '';
    const NotAvailable = '<N/A>';
    const Null = null;
    const NewLine = PHP_EOL;
    const WhiteSpace = ' ';

    public static function equals (string $first = null, string $second = null): bool
    {
        return strcmp($first, $second) == 0;
    }

    public static function split ($delimiter, $string, $trim = false): array
    {
        if (!$trim)
            return explode($delimiter, $string);
        else
            return preg_split("@{$delimiter}@", $string, NULL, PREG_SPLIT_NO_EMPTY);
    }

    public static function join ($separator, array $pieces): string
    {
        return implode($separator, $pieces);
    }

    public static function trimExtraWhiteSpaces ($string)
    {
        return preg_replace('/\s+/', ' ', $string);
    }

    public static function ellipsis (?string $value, $threshold = 50): string
    {
        if (empty($value))
            return Str::Empty;
        return strlen($value) > $threshold ? substr($value, 0, $threshold) . "..." : $value;
    }

    public static function trans (string $key, $default = Str::Empty)
    {
        $value = __($key);
        return Str::equals($value, $key) ? $default : $value;
    }

    public static function makeUuid (): string
    {
        $segmentX = Str::random();
        $segmentY = Str::uuid()->toString();
        $segmentY = str_replace('-', self::Empty, $segmentY);
        $segmentX = self::upper($segmentX);
        $segmentY = self::upper($segmentY);
        $segmentZ = date('s');
        return $segmentX . $segmentZ . $segmentY;
    }

    public static function stringifyBoolean ($value, $true = 'Yes', $false = 'No'): string
    {
        $value = boolval($value);
        return $value == true ? $true : $false;
    }
}
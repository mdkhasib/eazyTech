<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected static ?array $memo = null;

    public static function all_(): array
    {
        return static::$memo ??= static::query()->pluck('value', 'key')->all();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::all_();

        return ($all[$key] ?? null) !== null && $all[$key] !== '' ? $all[$key] : $default;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$memo = null;
    }
}

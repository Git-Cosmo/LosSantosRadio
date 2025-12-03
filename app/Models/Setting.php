<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'key';

    /**
     * The "type" of the primary key.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Setting types.
     */
    public const TYPE_STRING = 'string';

    public const TYPE_INTEGER = 'integer';

    public const TYPE_BOOLEAN = 'boolean';

    public const TYPE_JSON = 'json';

    /**
     * Get a setting value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "settings.{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = static::find($key);

            if (! $setting) {
                return $default;
            }

            return $setting->castValue();
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value, ?string $type = null): void
    {
        $type = $type ?? static::detectType($value);

        $stringValue = match ($type) {
            self::TYPE_BOOLEAN => $value ? '1' : '0',
            self::TYPE_JSON => json_encode($value),
            default => (string) $value,
        };

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $stringValue, 'type' => $type]
        );

        Cache::forget("settings.{$key}");
    }

    /**
     * Cast the value based on type.
     */
    public function castValue(): mixed
    {
        return match ($this->type) {
            self::TYPE_INTEGER => (int) $this->value,
            self::TYPE_BOOLEAN => (bool) $this->value && $this->value !== '0',
            self::TYPE_JSON => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Detect the type from a value.
     */
    protected static function detectType(mixed $value): string
    {
        if (is_bool($value)) {
            return self::TYPE_BOOLEAN;
        }
        if (is_int($value)) {
            return self::TYPE_INTEGER;
        }
        if (is_array($value)) {
            return self::TYPE_JSON;
        }

        return self::TYPE_STRING;
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("settings.{$setting->key}");
        }
    }

    /**
     * Get all settings as an array.
     */
    public static function allAsArray(): array
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::all()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->castValue()];
            })->toArray();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'key'; // Use 'key' as primary key
    public $incrementing = false; // Non-incrementing primary key
    protected $keyType = 'string'; // Key type is string

    protected $fillable = [
        'key',
        'value',
        'group', // Optional: Group settings (e.g., 'general', 'localization', 'tax')
    ];

    // Optional: Cast specific settings
    protected $casts = [
        // 'value' => 'json', // If storing complex data like arrays
    ];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        // Consider caching this for performance
        $setting = self::find($key);
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $group
     * @return Setting
     */
    public static function setValue(string $key, $value, ?string $group = null): Setting
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}

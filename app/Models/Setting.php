<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\SettingObserver;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'key'; // Use 'key' as primary key
    public $incrementing = false; // Non-incrementing primary key
    protected $keyType = 'string'; // Key type is string

    // protected $with = [
    //     'groups', // Optional: Eager load related group settings
    // ];

    protected $fillable = [
        'key',
        'value',
        'group', // Optional: Group settings (e.g., 'general', 'localization', 'tax')
    ];

    // Optional: Cast specific settings
    protected $casts = [
        // 'value' => 'json', // If storing complex data like arrays
    ];

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    /**
     * Get the setting group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Setting::class, 'group', 'key');
    }

    public function groups()
    {
        return $this->hasMany(Setting::class, 'group', 'key');
    }


    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // static::observe(SettingObserver::class);
    }

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
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
        $attributes = ['value' => $value];
        if ($group !== null) {
            $attributes['group'] = $group;
        }

        return self::updateOrCreate(
            ['key' => $key],
            $attributes
        );
    }
}

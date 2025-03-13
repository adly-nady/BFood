<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'key_name',
        'live_values',
        'test_values',
        'settings_type',
        'mode',
        'is_active',
        'additional_data',
    ];
    protected $casts = [
        'live_values' => 'array',
        'test_values' => 'array',
        'additional_data' => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'code',
        'name',
        'iso_3166_2',
        'iso_3166_3',
        'numeric_code',
        'tld',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'region',
        'subregion',
        'emoji',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}

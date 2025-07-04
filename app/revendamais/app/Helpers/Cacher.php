<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class Cacher
{
    public function __construct(public string $store = 'file')
    {
    }

    public function setCached($key, $value)
    {
        Cache::store($this->store)->put($key, $value);
    }

    public function getCached($key)
    {
        $cachedData = Cache::store($this->store)->get($key);
        return $cachedData ? \json_decode($cachedData) : null;
    }

    public function removeCached($key)
    {
        Cache::store($this->store)->forget($key);
    }
}

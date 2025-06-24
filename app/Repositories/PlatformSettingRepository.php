<?php

namespace App\Repositories;

use App\Interfaces\PlatformSettingRepositoryInterface;
use App\Models\PlatformSetting;

class PlatformSettingRepository implements PlatformSettingRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return PlatformSetting::get();
    }

    public function getByKey($key)
    {
        return PlatformSetting::where('key',$key)->get();
    }

    public function save(array $data)
    {
        return PlatformSetting::create($data);
    }
}

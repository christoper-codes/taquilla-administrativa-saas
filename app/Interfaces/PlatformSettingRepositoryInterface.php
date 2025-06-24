<?php

namespace App\Interfaces;

interface PlatformSettingRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll();
    public function getByKey($key);
    public function save(array $data);

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
}

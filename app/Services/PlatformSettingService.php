<?php

namespace App\Services;

use App\Interfaces\PlatformSettingRepositoryInterface;

class PlatformSettingService
{
    /*
    * |--------------------------------------------------------------------------
    * | PlatformSettingService the repository services for global module by Christoper Patiño
    */

    protected $platform_setting_repository_interface;

    public function __construct(PlatformSettingRepositoryInterface $platform_setting_repository_interface)
    {
        $this->platform_setting_repository_interface = $platform_setting_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all platform_setting catalogues
    */
    public function getAll()
    {
        return $this->platform_setting_repository_interface->getAll();
    }


    /*
    * |--------------------------------------------------------------------------
    * | Get cash register by key
    */
    public function getByKey($key)
    {
        try {

            return $this->platform_setting_repository_interface->getByKey($key);

        } catch (\Exception $e) {

            throw $e;

        }
    }


    /*
    * |--------------------------------------------------------------------------
    * | Save new platform_setting catalogue
    */
    public function save(array $data)
    {
        try {

            return $this->platform_setting_repository_interface->save($data);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}

<?php

namespace App\Services;

use App\Interfaces\UtilRepositoryInterface;

class UtilService
{
    /*
    * |--------------------------------------------------------------------------
    * | UtilsService the repository services for global module by Christoper Patiño
    */

    protected $utils_repository_interface;

    public function __construct(UtilRepositoryInterface $utils_repository_interface)
    {
        $this->utils_repository_interface = $utils_repository_interface;
    }

    public function migrate()
    {
        return  $this->utils_repository_interface->migrate();
    }

    public function cleanAll()
    {
        return  $this->utils_repository_interface->cleanAll();
    }

    public function refreshCaches()
    {
        return  $this->utils_repository_interface->refreshCaches();
    }

    public function storageCopy()
    {
        return  $this->utils_repository_interface->storageCopy();
    }

    public function getCP(string $cp)
    {
        return  $this->utils_repository_interface->getCP($cp);
    }
}

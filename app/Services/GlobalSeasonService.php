<?php

namespace App\Services;

use App\Interfaces\GlobalSeasonRepositoryInterface;

class GlobalSeasonService
{
    /*
    * |--------------------------------------------------------------------------
    * | GlobalSeasonService the repository services for global module by Christoper Patiño
    */

    protected $global_season_repository_interface;

    public function __construct(GlobalSeasonRepositoryInterface $global_season_repository_interface)
    {
        $this->global_season_repository_interface = $global_season_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all global_season catalogues
    */
    public function getAll()
    {
        $global_seasons = $this->global_season_repository_interface->getAll();

        return $global_seasons;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new global_season catalogue
    */
    public function save(array $data)
    {
        try {

            $global_season = $this->global_season_repository_interface->save($data);

            return $global_season;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}

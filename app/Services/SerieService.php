<?php

namespace App\Services;

use App\Interfaces\SerieRepositoryInterface;

class SerieService
{
    /*
    * |--------------------------------------------------------------------------
    * | SerieService the repository services for global module by Christoper Patiño
    */

    protected $serie_repository_interface;

    public function __construct(SerieRepositoryInterface $serie_repository_interface)
    {
        $this->serie_repository_interface = $serie_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all serie catalogues
    */
    public function getAll()
    {
        $series = $this->serie_repository_interface->getAll();

        return $series;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new serie catalogue
    */
    public function save(array $data)
    {
        try {

            $serie = $this->serie_repository_interface->save($data);

            return $serie;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update serie catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $serie = $this->serie_repository_interface->update($id, $data);

            return $serie;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | delete serie catalogue
    */
    public function delete(int $id)
    {
        try {

            $serie = $this->serie_repository_interface->delete($id);

            return $serie;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}

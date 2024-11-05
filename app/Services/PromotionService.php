<?php

namespace App\Services;

use App\Interfaces\PromotionRepositoryInterface;

class PromotionService
{
    /*
    * |--------------------------------------------------------------------------
    * | PromotionService the repository services for global module by Christoper Patiño
    */

    protected $promotion_repository_interface;

    public function __construct(PromotionRepositoryInterface $promotion_repository_interface)
    {
        $this->promotion_repository_interface = $promotion_repository_interface;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all promotion catalogues
    */
    public function getAll()
    {
        $promotions = $this->promotion_repository_interface->getAll();

        return $promotions;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all promotion catalogues
    */
    public function getAllByStadium($id)
    {
        $promotions = $this->promotion_repository_interface->getAllByStadium($id);

        return $promotions;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Save new promotion catalogue
    */
    public function save(array $data)
    {
        try {

            $promotion = $this->promotion_repository_interface->save($data);

            return $promotion;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | update promotion catalogue
    */
    public function update(int $id, array $data)
    {
        try {

            $promotion = $this->promotion_repository_interface->update($id, $data);

            return $promotion;

        } catch (\Exception $e) {

            throw $e;
        }
    }

    /*
    * |--------------------------------------------------------------------------
    * | delete promotion catalogue
    */
    public function delete(int $id)
    {
        try {

            $promotion = $this->promotion_repository_interface->delete($id);

            return $promotion;

        } catch (\Exception $e) {

            throw $e;
        }
    }
}

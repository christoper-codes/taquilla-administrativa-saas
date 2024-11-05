<?php

namespace App\Repositories;

use App\Interfaces\PromotionRepositoryInterface;
use App\Models\Promotion;

class PromotionRepository implements PromotionRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return Promotion::with('promotionType')->get();
    }

    public function getAllByStadium($id)
    {
        return Promotion::where('stadium_id',$id)->with('promotionType')->get();
    }

    public function getById($id)
    {
        return Promotion::findOrfail($id);
    }

    public function save(array $data)
    {
        return Promotion::create($data);
    }

    public function update($id, array $data)
    {
        return Promotion::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Promotion::destroy($id);
    }
}

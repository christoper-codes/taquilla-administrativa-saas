<?php

namespace App\Repositories;

use App\Interfaces\ReasonAgreementRepositoryInterface;
use App\Models\ReasonAgreement;

class ReasonAgreementRepository implements ReasonAgreementRepositoryInterface
{
    /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return ReasonAgreement::all();
    }

    public function getById($id)
    {
        return ReasonAgreement::findOrfail($id);
    }

    public function save(array $data)
    {
        return ReasonAgreement::create($data);
    }

    public function update($id, array $data)
    {
        return ReasonAgreement::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return ReasonAgreement::destroy($id);
    }

     /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function getAllByStadium($id)
    {
        return ReasonAgreement::where('stadium_id', $id)->get();
    }

}

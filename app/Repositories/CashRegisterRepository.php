<?php

namespace App\Repositories;

use App\Interfaces\CashRegisterRepositoryInterface;
use App\Models\CashRegister;
use Illuminate\Support\Collection;

class CashRegisterRepository implements CashRegisterRepositoryInterface
{
     /*
    * |--------------------------------------------------------------------------
    * | Primaries methods for the repository interface
    */
    public function getAll()
    {
        return CashRegister::all();
    }

    public function getById($id)
    {
        return CashRegister::findOrFail($id);
    }

    public function save(array $data)
    {
        $new_cash_register = new CashRegister();
        $new_cash_register->ticket_office_id = $data['ticket_office_id'];
        $new_cash_register->cash_register_type_id = $data['cash_register_type_id'];
        $new_cash_register->seller_user_opening_id = $data['seller_user_opening_id'];
        $new_cash_register->seller_user_closing_id = null;
        $new_cash_register->description = 'apertura de caja registradora por user_id: ' . $data['seller_user_opening_id'];
        $new_cash_register->is_open = true;
        $new_cash_register->confirmed_closure = false;
        $new_cash_register->batch_cash_register = $data['batch_cash_register'];
        $new_cash_register->batch_code = $data['batch_code'] ?? uniqid();
        $new_cash_register->opening_balance = $data['opening_balance'];
        $new_cash_register->current_balance = $data['opening_balance'];
        $new_cash_register->closing_balance = null;
        $new_cash_register->opening_time = now();
        $new_cash_register->save();

        return $new_cash_register;
    }

    public function update($id, array $data)
    {
        return CashRegister::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return CashRegister::destroy($id);
    }

    /*
    * |--------------------------------------------------------------------------
    * | Custom methods for the repository interface
    */
    public function close(array $data, CashRegister $cash_register)
    {
            $cash_register->is_open = false;
            $cash_register->confirmed_closure = true;
            $cash_register->seller_user_closing_id = $data['seller_user_closing_id'];
            $cash_register->closing_balance = $cash_register->current_balance;
            $cash_register->closing_time = now();
            $cash_register->save();

            return $cash_register;
    }

    public function closeAll(array $data, Collection $cash_registers)
    {
        $cash_registers->each(function ($cash_register) use ($data) {
            $cash_register->is_open = false;
            $cash_register->confirmed_closure = true;
            $cash_register->seller_user_closing_id = $data['seller_user_closing_id'];
            $cash_register->closing_balance = $cash_register->current_balance;
            $cash_register->closing_time = now();
            $cash_register->save();
        });

        return $cash_registers;
    }
}

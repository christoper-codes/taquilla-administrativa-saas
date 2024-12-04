<?php

namespace App\Services;

use App\Models\CashRegisterMovement;
use App\Models\CashRegisterMovementType;
use App\Models\GlobalPaymentType;
use App\Models\SaleTicket;
use App\Models\SaleTicketStatus;
use App\Models\SeatCatalogueStatus;
use App\Repositories\SaleTicketRepository;
use Illuminate\Support\Facades\DB;
use PhpOption\Some;

class SaleTicketService 
{
     /*
    * |--------------------------------------------------------------------------
    * | SaleTicketService the repository services for global module by Christoper Patiño
    */
    protected $sale_ticket_repository;

    public function __construct(SaleTicketRepository $sale_ticket_repository)
    {
        $this->sale_ticket_repository = $sale_ticket_repository;
    }

    /*
    * |--------------------------------------------------------------------------
    * | Get all SaleTickets
    */
    public function getAll()
    {
        try {

            $sale_tickets = $this->sale_ticket_repository->getAll();
            return $sale_tickets;

        } catch (\Exception $e) {

            throw $e;
        }
    }

     /*
    * |--------------------------------------------------------------------------
    * | Cacellation of tickets 
    */
    public function cancellationSaleTickets(array $data)
    {
        try {

            /* 
            * Update sale ticket status
            */
            $sale_ticket = SaleTicket::find($data['sale_ticket_id']);
            
            /* 
            * Get cash register
            */
            $cash_register = $sale_ticket->cashRegister;
            $balance_to_returned = 0;
            $cash_register_movement_type_id = null;

            if($data['is_partial_cancel']){
                $sale_ticket->sale_ticket_status_id = SaleTicketStatus::where('name', 'parcialmente cancelado')->first()->id;
                $cash_register_movement_type_id = CashRegisterMovementType::where('name', 'cancelacion parcial de venta')->first()->id;
            } else {
                $sale_ticket->sale_ticket_status_id = SaleTicketStatus::where('name', 'cancelado')->first()->id;
                $cash_register_movement_type_id = CashRegisterMovementType::where('name', 'cancelacion total de venta')->first()->id;
            }
            $sale_ticket->save();

            /* 
            * Update pivot beetwen sale tickets and event seat catalog
            */
            $seat_catalogue_status_id = SeatCatalogueStatus::where('name', 'disponible')->first()->id;
            foreach($sale_ticket->eventSeatCatalogs as $event_seat_catalog) {
                if($data['is_partial_cancel']){
                
                    if (in_array($event_seat_catalog->seatCatalogue->code, $data['cancel_seat_codes'])) {
                        $balance_to_returned += $event_seat_catalog->price;
                       $this->updateEventSeatCatalog($event_seat_catalog, $seat_catalogue_status_id, $sale_ticket, $data['payment_types_selected_keys']);
                    }
                    
                } else {
                    $balance_to_returned += $event_seat_catalog->price;
                    $this->updateEventSeatCatalog($event_seat_catalog, $seat_catalogue_status_id, $sale_ticket, $data['payment_types_selected_keys']);

                }
                
            }

            /* 
            * Intance a new cash register movement
            */
            $cash_register_movement = new CashRegisterMovement();
            $cash_register_movement->cash_register_id = $cash_register->id;
            $cash_register_movement->cash_register_movement_type_id = $cash_register_movement_type_id;
            $cash_register_movement->sale_ticket_id = $sale_ticket->id;
            $cash_register_movement->previous_balance = $cash_register->current_balance;
            $cash_register_movement->movement_amount = $balance_to_returned;
            $cash_register_movement->new_balance = ($cash_register->current_balance - $balance_to_returned);
            $cash_register_movement->is_active = true;
            $cash_register_movement->save();

            /* 
            * Update cash register
            */
            $cash_register->current_balance = ($cash_register->current_balance - $balance_to_returned);
            $cash_register->save();

            return true;

        } catch(\Exception $e){
            throw $e;
        }
    }

    private function updateEventSeatCatalog($event_seat_catalog, $seat_catalogue_status_id, $sale_ticket, $payment_types_selected_keys)
    {
        /* 
        * Update sale ticket
        */
        $sale_ticket->total_amount = ($sale_ticket->total_amount - $event_seat_catalog->price);
        $sale_ticket->total_returned = ($sale_ticket->total_returned + $event_seat_catalog->price);
        $sale_ticket->save();

        /* 
        * Update pivot beetwen sale ticket and global payment types
        */
        $remainingAmount = $event_seat_catalog->price;
        foreach ($payment_types_selected_keys as $payment_type_key) {
            $global_payment_type = GlobalPaymentType::where('name', $payment_type_key)->first();
        
            if ($global_payment_type) {
                $currentAmount = $sale_ticket->globalPaymentTypes()->where('global_payment_type_id', $global_payment_type->id)->first()->pivot->amount;
        
                if ($remainingAmount > 0) {
                    if ($currentAmount >= $remainingAmount) {
                        $sale_ticket->globalPaymentTypes()->updateExistingPivot($global_payment_type->id, [
                            'amount' => $currentAmount - $remainingAmount
                        ]);
                        $remainingAmount = 0;
                    } else {
                        $sale_ticket->globalPaymentTypes()->updateExistingPivot($global_payment_type->id, [
                            'amount' => 0
                        ]);
                        $remainingAmount -= $currentAmount;
                    }
                } else {
                    break;
                }
            }
        }

        /* 
        * Update event seat catalog
        */
        $event_seat_catalog->user_id = null;
        $event_seat_catalog->seat_catalogue_status_id = $seat_catalogue_status_id;
        $event_seat_catalog->sale_ticket_id = null;
        $event_seat_catalog->qr = null;
        $event_seat_catalog->price = 0.00;
        $event_seat_catalog->is_verified = false;
        $event_seat_catalog->save();

        $sale_ticket->eventSeatCatalogs()->updateExistingPivot($event_seat_catalog->id, [
            'is_active' => false
        ]);
    }

    
}
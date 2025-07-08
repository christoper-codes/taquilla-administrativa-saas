<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Str;

class SalesTicketDebtorExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $sale_tickets;

    /**
     * @param \Illuminate\Support\Collection $sale_tickets
     */
    public function __construct($sale_tickets)
    {
        $this->sale_tickets = $sale_tickets;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->sale_tickets;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Folio',
            'Deudor',
            'Numero de teléfono',
            'Correo electrónico',
            'Estatus',
            'Fecha de venta',
            'Asientos',
            'Monto total',
            'Monto Pagado',
            'Adeudo',
            'Pagos realizados',
        ];
    }

    /**
     * @return array
     */
    public function map($saleTicket): array
    {
        $debtorFullName = Str::title("{$saleTicket->saleDebtor->first_name} {$saleTicket->saleDebtor->last_name}");
        $totalPaid = $saleTicket->installmentPaymentHistories->sum(fn($p) => (float) $p->total_amount);
        $seatCodes = $saleTicket->eventSeatCatalogues->map(fn($esc) => $esc->seatCatalogue->code)->implode(', ');

        $payment_types = $saleTicket->installmentPaymentHistories
        ->flatMap(fn($history) => $history->globalPaymentTypes)
        ->groupBy(fn($type) => ucfirst($type->name))
        ->map(fn($group) => $group->sum(fn($type) => (float) $type->pivot->amount))
        ->map(fn($amount, $name) => sprintf('%s: $%s', $name, number_format($amount, 2, '.', ',')))
        ->values()->implode(', ');

        return [
            $saleTicket->id,
            $debtorFullName,
            $saleTicket->saleDebtor->phone_number,
            $saleTicket->saleDebtor->email,
            Str::title($saleTicket->saleTicketStatus->name),
            $saleTicket->created_at,
            $seatCodes,
            number_format($saleTicket->total_amount, 2, '.', ','),
            number_format($totalPaid, 2, '.', ','),
            number_format($saleTicket->remaining_amount, 2, '.', ','),
            $payment_types
        ];
    }
}

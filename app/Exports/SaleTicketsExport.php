<?php
// filepath: app/Exports/SaleTicketsExport.php

namespace App\Exports;

use App\Services\SaleTicketService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SaleTicketsExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    use Exportable;

    protected $event_id;
    protected $sale_ticket_service;

    public function __construct($event_id, SaleTicketService $sale_ticket_service)
    {
        $this->event_id = $event_id;
        $this->sale_ticket_service = $sale_ticket_service;
    }

    public function array(): array
    {
        try {
                $history_per_event = $this->sale_ticket_service->getHistoryPerEvent(['event_id' => $this->event_id]);
                $new_data = $history_per_event['new_data'] ?? null;

                if ($new_data instanceof \Illuminate\Database\Eloquent\Model) {
                    $new_data = $new_data->toArray();
                }
                $sale_tickets = $new_data['sale_tickets'] ?? [];
                if (empty($sale_tickets)) {
                    return [];
                }
                $data = [];
                $sorted_sale_tickets = collect($sale_tickets)->sortByDesc('created_at');

                foreach ($sorted_sale_tickets as $index => $sale_ticket) {
                    try {
                        $payment_types = '';
                        if (isset($sale_ticket['global_payment_types']) && is_array($sale_ticket['global_payment_types'])) {
                            $payment_types = collect($sale_ticket['global_payment_types'])->map(function($payment_type) {
                                $payment_info = ucfirst($payment_type['name']) . ': ' . $payment_type['pivot']['amount'];
                                if ($payment_type['name'] === 'tarjeta' && isset($payment_type['pivot']['card_type_name'])) {
                                    $payment_info .= ' (' . $payment_type['pivot']['card_type_name'] . ')';
                                }
                                return $payment_info;
                            })->implode(', ');
                        }

                        $seat_catalogues = '';
                        if (isset($sale_ticket['event_seat_catalogs']) && is_array($sale_ticket['event_seat_catalogs'])) {
                            $seat_catalogues = collect($sale_ticket['event_seat_catalogs'])->map(function($seat_catalogue) {
                                return $seat_catalogue['seat_catalogue']['code'] ?? 'N/A';
                            })->implode(', ');
                        }

                        $adeudo = 0;
                        if (isset($sale_ticket['sale_ticket_status']['name']) && $sale_ticket['sale_ticket_status']['name'] === 'pendiente') {
                            $installment_total = 0;
                            if (isset($sale_ticket['installment_payment_histories']) && is_array($sale_ticket['installment_payment_histories'])) {
                                $installment_total = collect($sale_ticket['installment_payment_histories'])->sum('total_amount');
                            }
                            $adeudo = $sale_ticket['total_amount'] - $installment_total;
                        }

                        $original_date = new \DateTime($sale_ticket['created_at']);
                        $original_date->modify('-6 hours');
                        $adjusted_date = $original_date->format('Y-m-d H:i:s');

                        $total_amount = number_format($sale_ticket['total_amount'] ?? 0, 2);
                        $amount_received = $sale_ticket['amount_received'] ?? 0;
                        $total_returned = $sale_ticket['total_returned'] ?? 0;
                        $amount_paid = number_format($amount_received - $total_returned, 2);
                        $adeudo_formatted = number_format($adeudo, 2);

                        $data[] = [
                            $sale_ticket['id'], // Folio
                            ucfirst($sale_ticket['sale_ticket_status']['name'] ?? 'N/A'), // Estatus
                            ($sale_ticket['is_online'] ?? false) ? 'En linea' : 'Taquilla', // Tipo de venta
                            count($sale_ticket['event_seat_catalogs'] ?? []), // Total asientos vendidos
                            $seat_catalogues, // Asientos
                            $total_amount, // Monto total de venta
                            $amount_paid, // Monto Pagado
                            $adeudo_formatted, // Adeudo
                            $payment_types, // Tipos de pago
                            ($sale_ticket['promotion_id'] ?? null) ? ($sale_ticket['promotion']['name'] ?? 'N/A') : 'N/A', // Promoción
                            $sale_ticket['payment_in_installments'] ?? 'N/A', // Venta a meses
                            ($sale_ticket['sale_debtor_id'] ?? null) ?
                                ($sale_ticket['sale_debtor']['first_name'] ?? '') . ' ' . ($sale_ticket['sale_debtor']['last_name'] ?? '') : 'N/A', // Deudor
                            $adjusted_date, // Fecha
                        ];

                    } catch (\Exception $e) {
                        continue;
                    }
                }

            return $data;

        } catch (\Exception $e) {
            return [];
        }
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Estatus',
            'Tipo de venta',
            'Total asientos vendidos',
            'Asientos',
            'Monto total de venta',
            'Monto Pagado',
            'Adeudo',
            'Tipos de pago',
            'Promoción',
            'Venta a meses',
            'Deudor',
            'Fecha de venta',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'e2e8f0']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, 'B' => 15, 'C' => 15, 'D' => 20, 'E' => 30,
            'F' => 20, 'G' => 15, 'H' => 15, 'I' => 40, 'J' => 20,
            'K' => 15, 'L' => 25, 'M' => 20,
        ];
    }
}

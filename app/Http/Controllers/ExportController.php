<?php

namespace App\Http\Controllers;

use App\Exports\SaleTicketsExport;
use App\Services\EventService;
use App\Services\SaleTicketService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $sale_ticket_service;
    protected $event_service;

    public function __construct(SaleTicketService $sale_ticket_service, EventService $event_service)
    {
        $this->sale_ticket_service = $sale_ticket_service;
        $this->event_service = $event_service;
    }

    public function exportSummaryByTickets(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
        ]);

        $event_id = $request->input('event_id');
        $export = new SaleTicketsExport($event_id, $this->sale_ticket_service);
        $event = $this->event_service->getOnlyEvent($event_id);
        $file_name = 'ventas_' . str_replace(' ', '_', $event->name) . '_' . date('Y-m-d') . '.xlsx';

        return $export->download($file_name);
    }
}

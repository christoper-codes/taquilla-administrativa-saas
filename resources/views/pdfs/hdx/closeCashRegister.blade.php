<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="ticket">
        <h1>HALCONES DE XALAPA</h1>
        <p class="info" style="margin-top: 20px;">
            Cultura Veracruzana, Zona Universitaria,<br>
            Campus Cad, Xalapa Enriquez, 91094
        </p>
        <h2 style="margin-top: 40px;">Resumen de caja #{{ $pdf_data['cash_register']['cash_register_type_id'] }}</h2>

        <table class="w-full" style="margin-top: 40px;">
            <tr>
                <td class="w-half-left">
                    <p>Taquila: {{ Str::ucfirst($pdf_data['ticket_office']['name']) }}</p>
                    <p>Vendedor: {{ trim(implode(' ', array_filter([ $pdf_data['cash_register']['sellerUserOpening']['first_name'], $pdf_data['cash_register']['sellerUserOpening']['middle_name'], $pdf_data['cash_register']['sellerUserOpening']['last_name'] ]))) }}</p>
                    <p>Fecha de apertura: {{ date('d/m/Y H:i', strtotime('-6 hours', strtotime($pdf_data['cash_register']['opening_time']))) }}</p>
                    <p>Fecha de cierre: {{ date('d/m/Y H:i', strtotime('-6 hours', strtotime($pdf_data['cash_register']['closing_time']))) }}</p>
                </td>
                <td class="w-half-right">
                    <p>Lote: {{ $pdf_data['cash_register']['batch_cash_register'] }}</p>
                    <p>Caja registradora: {{ $pdf_data['cash_register']['cash_register_type_id'] }}</p>
                </td>
            </tr>
        </table>
        <div class="line"></div>
        <h3 style="">Total en venta: ${{ number_format(($pdf_data['cash_register']['closing_balance'] - $pdf_data['cash_register']['opening_balance']), 2, '.', ',') }}</h3>

        <p class="info" style="margin-top: 20px;">
            El total de la venta inluye <br>
            todos los tipos de pago (efectivo, tarjeta, cashless...)
        </p>

        <h2 style="margin-top: 40px;">Fondo inicial: ${{ number_format($pdf_data['cash_register']['opening_balance'], 2, '.', ',') }}</h2>

        <h4 style="margin-top: 40px;">Dinero Total por Metodo de Pagos</h4>
        <table class="w-full" style="margin-top: 40px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left">Tipo</th>
                    <th class="text-right">Pagado</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['type_payments_sales'] as $payment_type => $value)
                    <tr>
                        <td class="text-left">{{ Str::ucfirst($payment_type) }}</td>
                            <td class="text-right">${{ number_format($value['initial_amount'] ?? 0, 2, '.', ',') }} MXN</td>
                            <td class="text-right">${{ number_format($value['amount'] ?? 0, 2, '.', ',') }} MXN</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="margin-top: 40px;">Dinero por Metodos de Pago</h4>
        <table class="w-full" style="margin-top: 40px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left">Tipo</th>
                    <th class="text-right">Pagado</th>
                    <th class="text-right">Total</th>
                    {{-- <th class="text-right">Deuda</th> --}}
                    {{-- <th class="text-right">Transacc.</th>
                    <th class="text-right">Asientos</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['type_payments'] as $payment_type => $value)
                    <tr>
                        <td class="text-left">{{ Str::ucfirst($payment_type )}}</td>
                        @if (isset($value['amount']))
                            <td class="text-right">${{ number_format($value['initial_amount'] ?? 0, 2, '.', ',') }} MXN</td>
                            <td class="text-right">${{ number_format($value['amount'] ?? 0, 2, '.', ',') }} MXN</td>
                            {{-- <td class="text-right">${{ number_format($value['remaining_amount'] ?? 0, 2, '.', ',') }} MXN</td> --}}
                        @elseif (isset($value['amountList']) && is_array($value['amountList']))
                            <td class="text-right">
                                @foreach ($value['initial_amount_list'] as $method => $amount_data)
                                    @if (isset($amount_data['amount']))
                                        ${{ number_format($amount_data['amount'], 2, '.', ',') }} MXN ({{ ucfirst($method) }})
                                        @if (!$loop->last) <br> @endif
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-right">
                                @foreach ($value['amountList'] as $method => $amount_data)
                                    @if (isset($amount_data['amount']))
                                        ${{ number_format($amount_data['amount'], 2, '.', ',') }} MXN ({{ ucfirst($method) }})
                                        @if (!$loop->last) <br> @endif
                                    @endif
                                @endforeach
                            </td>
                            <td class="text-right">
                                @foreach ($value['remaining_amount_list'] as $method => $amount_data)
                                    @if (isset($amount_data['amount']))
                                        ${{ number_format($amount_data['amount'], 2, '.', ',') }} MXN ({{ ucfirst($method) }})
                                        @if (!$loop->last) <br> @endif
                                    @endif
                                @endforeach
                            </td>
                        @endif

                        {{-- <td class="text-right">{{ $value['transactions'] }}</td>
                        <td class="text-right">{{ $value['seats'] }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="margin-top: 40px;">Dinero por cobrar</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left"></th>
                    <th class="text-right">Monto</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="text-left">Total</td>
                        <td class="text-right">${{ number_format($pdf_data['remaining_amount'], 2, '.', ',') }} MXN</td>
                    </tr>
            </tbody>
        </table>
        <p style="font-size: 12px; color: #555;">
            <strong>Nota:</strong> Para los tickets que fueron apartados a plazos, los registros de cobro se encuentran distribuidos en distintas cajas, según el historial de pagos realizados.
        </p>

        <div class="line"></div>

        <h4 style="margin-top: 40px;">Venta Normal</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left">Tipo de venta</th>
                    <th class="text-right">Ventas</th>
                    <th class="text-right">Asientos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['type_sales'] as $type_sale => $value)
                    <tr>
                        <td class="text-left">{{ Str::ucfirst($type_sale) }}</td>
                        <td class="text-right">{{ $value['transaction'] }}</td>
                        <td class="text-right">{{ $value['sales'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="margin-top: 20px;">Venta Parcial</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left">Tipo de venta</th>
                    <th class="text-right">Ventas</th>
                    <th class="text-right">Asientos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['partially_canceled'] as $type_sale => $value)
                    <tr>
                        <td class="text-left">{{ Str::ucfirst($type_sale) }}</td>
                        <td class="text-right">{{ $value['transaction'] }}</td>
                        <td class="text-right">{{ $value['sales'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="margin-top: 20px;">Ventas a plazos</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left">Tipo de venta</th>
                    <th class="text-right">Ventas</th>
                    <th class="text-right">Asientos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['installment_sale'] as $type_sale => $value)
                    <tr>
                        <td class="text-left">{{ Str::ucfirst($type_sale) }}</td>
                        <td class="text-right">{{ $value['transaction'] }}</td>
                        <td class="text-right">{{ $value['sales'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h4 style="margin-top: 20px;">Ventas Canceladas</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left"></th>
                    <th class="text-right">Cancelaciones</th>
                    <th class="text-right">Asientos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['canceled'] as $type_sale => $value)
                    <tr>
                        <td class="text-left">{{ Str::ucfirst($type_sale) }}</td>
                        <td class="text-right">{{ $value['transaction'] }}</td>
                        <td class="text-right">{{ $value['sales'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h4 style="margin-top: 20px;">Abonos a Deuda</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left"></th>
                    <th class="text-right">Abonos a Deuda</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td class="text-left">Total</td>
                        <td class="text-right">{{ $pdf_data['installment_payment_sale'] }}</td>
                    </tr>
            </tbody>
        </table>

        <h4 style="margin-top: 20px;">Ventas</h4>
        <table class="w-full" style="margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-left">Folio</th>
                    <th class="text-left">Estatus</th>
                    <th class="text-left">Asientos</th>

                    <th class="text-left">Pagado</th>
                    <th class="text-left">Total</th>
                    <th class="text-left">Adeudo</th>
                    <th class="text-left">Pagos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdf_data['sale_tickets'] as $ticket)
                    <tr>
                        <td class="text-left">{{ $ticket['id'] }}</td>
                        <td class="text-left">{{ Str::ucfirst($ticket['saleTicketStatus']['name'])}}</td>
                        <td class="text-left">{{ collect($ticket['eventSeatCatalogues'])->pluck('seatCatalogue.code')->implode(', ') }}</td>
                        <td class="text-left">{{ number_format(floatval($ticket['amount_received']) - floatval($ticket['total_returned']), 2) }}</td>
                        <td class="text-left">{{ number_format($ticket['total_amount'], 2) }}</td>
                        <td class="text-left">{{ number_format($ticket['remaining_amount'], 2) }}</td>
                        <td class="text-left">{{ collect($ticket['globalPaymentTypes'])->map(fn($paymentType) => Str::ucfirst("{$paymentType['name']}: {$paymentType['pivot']['amount']}") )->implode(', ') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>



        <div class="footer">
            <p>
                Este documento es un resumen del cierre de caja generado automáticamente. <br>
                Para información más detallada y opciones avanzadas consulte el panel de administración<br>
            </p>
            <h5>
                Halcones de Xalapa 2025
            </h5>
        </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .ticket {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ticket h1, .ticket h2, .ticket h3 {
            margin: 0;
            padding: 5px 0;
            text-align: center;
        }
        .ticket h2, .ticket h3 {
            font-size: 30px;
        }
        .ticket h1{
            font-size: 40px;
            background: #000000;
            color: white;
            border-radius: 20px;
            padding: 35px 0px
        }
        .ticket h3{
            font-size: 60px;
            border-top: 4px dashed #000;
            padding-top: 40px;
        }
        .ticket p {
            margin: 5px 0;
            font-size: 20px;
        }
        .ticket .info {
            text-align: center;
            margin-bottom: 10px;
        }
        .ticket .products {
            margin: 10px 0;
        }
        .ticket .products p {
            display: flex;
            justify-content: space-between;
        }
        .ticket .footer p, .ticket .footer h5 {
            padding: 20px;
            background: #f1f1f1;
            margin-top: 40px;
            text-align: center;
            border-radius: 20px;
            font-size: 17px;
        }
        .ticket .qr {
            text-align: center;
            padding-top: 40px;
            border-top: 4px dashed #000;
        }
        .line {
            margin-top: 40px;
            border-top: 4px dashed #000;
            margin-bottom: 3px;
        }
        .ticket .qr img {
            width: 400px;
            height: 400px;
        }
        .w-full {
            width: 100%;
        }
        .w-half-left {
            width: 50%;
            text-align: left;
        }
        .w-half-right {
            width: 50%;
            text-align: right;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        td{
            font-size: 15px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>
</body>
</html>

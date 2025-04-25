<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - #123</title>
</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 40%;">
                <h3>HALCONES DE XALAPA</h3>
                <pre>
                Día de compra: {{ $data['sale_date'] }}
                Identificador: HDX-{{ $data['folio'] }}
                Estatus: {{ $data['status'] }}
                Folio: #{{ $data['folio'] }}
                Vendedor: {{ trim(implode(' ', array_filter([ $data['seller_user']['first_name'], $data['seller_user']['middle_name'], $data['seller_user']['last_name'] ]))) }}
                </pre>


            </td>
            <td align="center">
                <img src="{{ public_path('img/halcones-img-logo.png') }}" width="125" alt="QR Code">
            </td>
            <td align="right" style="width: 40%;">
                <h3> {{ $data['event_name'] }} <br> temporada 2025 (FASE 1)</h3>
                <pre>
                    https://halconesdexalapa.com.mx
                    revisar términos y condiciones
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

 <div class="invoice">
    <h3 style="font-size: 20px;">Especificaciones del pedido #{{ $data['folio'] }}</h3>
    <p><strong>Nombre completo del titular:</strong> {{ $data['owner']['seasonTicket']['holder_name']  . ' ' .  $data['owner']['seasonTicket']['holder_last_name'] . ' ' . $data['owner']['seasonTicket']['holder_middle_name'] }}</p>
    <table width="100%">
        <thead>
            <tr>
                <th class="text-right">CÓDIGO POSTAL</th>
                <th class="text-right">CELULAR</th>
                <th class="text-right">EMAIL</th>
                <th class="text-right">TITULAR</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-right">{{ $data['owner']['seasonTicket']['holder_zip_code'] }}</td>
                <td class="text-right">{{ $data['owner']['seasonTicket']['holder_phone'] }}</td>
                <td class="text-right">{{ $data['owner']['seasonTicket']['holder_email'] }}</td>
            </tr>
        </tbody>
    </table>


    <div class="ticket">
        <div class="line"></div>
        <h3 style="">Asientos 2025 | Precios aplicados</h3>
        @if ($data['promotion_ticket'])
            <p><strong>Promoción aplicada:</strong> {{ $data['promotion_ticket']['name'].' - '. Str::ucfirst(str_replace('_', ' ', $data['promotion_ticket']['promotionType']['name'].(  $data['promotion_ticket']['percent_allow'] ? ' ('.$data['promotion_ticket']['percent_allow'].'%)': '')))}}</p>
        @endif
        <table width="100%">
            <thead>
                <tr>
                    <th class="text-left">USUARIO</th>
                    <th class="text-right">GENERO</th>
                    <th class="text-right">TALLA</th>
                    <th class="text-left">ZONA</th>
                    <th class="text-left">FILA</th>
                    <th class="text-right">ASIENTO</th>
                    <th class="text-right">TIPO</th>
                    <th class="text-right">CASHBACK</th>
                    @if ($data['promotion_ticket'])
                        <th class="text-right">PRECIO   ORIGINAL</th>
                    @endif
                    <th class="text-right">PRECIO ABONO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['seats'] as $seat)
                    <tr>
                        <td class="text-right">{{ $seat['seasonTicket']['holder_name']  . ' ' .  $seat['seasonTicket']['holder_last_name'] }}</td>
                        <td class="text-right">{{ $seat['seasonTicket']['holder_jersey_type'] }}</td>
                        <td class="text-right">{{ $seat['seasonTicket']['holder_jersey_size'] }}</td>
                        <td class="text-right">{{ $seat['seatCatalogue']['zone']  }}</td>
                        <td class="text-right">{{ $seat['seatCatalogue']['row']  }}</td>
                        <td class="text-right">{{ $seat['seatCatalogue']['seat']  }}</td>
                        <td class="text-right">{{ $seat['seatCatalogue']['seatType']['name']  }}</td>
                        <td class="text-right">{{ $seat['seatCatalogue']['seatType']['percentage_cashback']  }}%</td>
                        @if ($data['promotion_ticket'])
                            <td class="text-right">${{ number_format($seat['seatCatalogue']['price_types'][0]['pivot']['price'], 2, '.', '')?? ''}}</td>
                        @endif
                        <td class="text-right">${{ number_format($seat['price'], 2, '.', '') }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: left;">
            <p><strong>Tipo de compra :</strong> Pago a plazos </p>
        </div>

        <table width="100%" cellspacing="0" cellpadding="6">
            <thead>
                <tr>
                    <th class="text-left">VENDEDOR</th>
                    <th class="text-left">FECHA DE PAGO</th>
                    <th class="text-left">MONTO PAGADO</th>
                    <th class="text-left">PAGOS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['installment_payment_histories'] as $payment)
                <tr>
                    <td class="text-right">{{ trim(implode(' ', array_filter([ $payment['cashRegister']['sellerUserOpening']['first_name'] ?? '', $payment['cashRegister']['sellerUserOpening']['middle_name'] ?? '', $payment['cashRegister']['sellerUserOpening']['last_name'] ?? '' ]))) }}</td>
                    <td class="text-right">{{ date('d/m/Y H:i', strtotime('-6 hours', strtotime($payment['created_at'])))}}</td>
                    <td class="text-right"> $ {{ number_format($payment['total_amount'], 2) }} MXN</td>
                    <td class="text-right">{{ collect($payment['globalPaymentTypes'])->map(fn($payment) => ucfirst($payment['name']) . ': $' . number_format($payment['pivot']['amount'], 2) . ' MXN')->implode(', ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right">
            @if ($data['payment_in_installments'])
                <p><strong>Pago MSI:</strong> {{ $data['payment_in_installments']}} meses</p>
            @endif
            <p><strong>Total de Compra:</strong> $ {{ number_format($data['total_amount'], 2, '.', '') }} MXN</p>
            <p><strong>Total Pagado:</strong> $ {{ number_format($data['total_amount_installment_payment_histories'], 2, '.', '') }} MXN</p>
            <p><strong>Total Restante:</strong> $ {{ number_format($data['remaining_total'], 2, '.', '') }} MXN</p>
        </div>

        <div style="font-size: 12px;  margin-top: 30px; background: #f2f2f2; padding-top:10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; border-radius: 15px;">
            <p><strong>Observaciones adicionales con nombres y asientos:</strong></p>
            <p>{{ $data['owner']['seasonTicket']['description'] }}</p>
        </div>
    </div>
</div>

<div class="information" style="position: absolute; bottom: 0; width: 100%;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%; font-size: 10px;">
                &copy; {{ date('Y') }} Importe de cashback, solo aplica para las zonas courtside, dorada y purpura: Se aplica por asiento.
                El importe es en funcion a la fase de venta y por zona, revisar terminos y condiciones vigentes. No aplica cancelacion, devolucion o cambio de lugar.
            </td>
            <td align="right" style="width: 50%;">
                Halcones de Xalapa
                <br>
                Temporada 2025
            </td>
        </tr>

    </table>
</div>
</body>
</html>

<style type="text/css">
    @page {
        margin: 0px;
    }
    body {
        margin: 0px;
    }
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    a {
        color: #fff;
        text-decoration: none;
    }
    h3, h4 {
        font-size: 15px;
        font-weight: bolder;
    }
    table {
        font-size: x-small;
    }

    tfoot tr td {
        font-weight: bold;
        font-size: x-small;
    }
    .invoice {
        padding: 0px 30px;
        margin-left: auto;
        margin-right: auto;
    }

    .information {
        background-color: rgb(61, 61, 61);
        color: #FFF;
    }
    .information .logo {
        margin: 5px;
    }
    .information table {
        padding: 10px;
    }
    pre {
        font-size: 15px;
    }
    .invoice th, td {
        padding: 10px;
    }
    .invoice th {
        background-color: #f2f2f2;
    }
    .invoice .text-left {
        text-align: left;
    }
    .invoice .text-right {
        text-align: right;
    }
    .ticket h1, .ticket h2, .ticket h3 {
        margin: 0;
        padding: 5px 0;
        text-align: center;
    }
    .ticket .line {
            margin-top: 40px;
            border-top: 2px dashed #757575;
            margin-bottom: 3px;
        }
    .ticket h3{
        font-size: 25px;
        border-top: 2px dashed #757575;
        margin-bottom: 20px;
        padding-top: 20px;
    }
    .ticket img {
        width: 50px;
        height: 50px;
    }

</style>

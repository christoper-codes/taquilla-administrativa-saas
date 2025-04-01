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
Dia de compra: {{ $generic_data['sale_date'] }}
Identificador: HDX-{{ $generic_data['folio'] }}
Status: En proceso
Folio: #{{ $generic_data['folio'] }}
Vendedor: {{ trim(implode(' ', array_filter([ $generic_data['seller_user']['first_name'], $generic_data['seller_user']['middle_name'], $generic_data['seller_user']['last_name'] ]))) }}
</pre>


            </td>
            <td align="center">
                <img src="{{ public_path('img/halcones-img-logo.png') }}" width="125" alt="QR Code">
            </td>
            <td align="right" style="width: 40%;">

                <h3>Registro de abono varonil <br> temporada 2025 (FASE 1)</h3>
                <pre>
                    https://halconesdexalapa.com
                    revisar terminos y condiciones
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3 style="font-size: 20px;">Especificaciones del pedido #{{ $generic_data['folio'] }}</h3>
    @foreach($pdf_data as $data)
        @if ($data['is_owner'])
            <p><strong>Nombre completo del titular:</strong> {{ $data['holder_name']  . ' ' .  $data['holder_last_name'] . ' ' . $data['holder_middle_name'] }}</p>
        @endif
    @endforeach
    <table width="100%">
        <thead>
            <tr>
                <th class="text-right">CODIGO POSTAL</th>
                <th class="text-right">CELULAR</th>
                <th class="text-right">EMAIL</th>
                <th class="text-right">TITULAR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pdf_data as $data)
                @if ($data['is_owner'])
                <tr>
                    <td class="text-right">{{ $data['holder_zip_code'] }}</td>
                    <td class="text-right">{{ $data['holder_phone'] }}</td>
                    <td class="text-right">{{ $data['holder_email'] }}</td>
                    <td class="text-right">{{ $data['is_owner'] }}</td>
                </tr>
                @endif

            @endforeach

        </tbody>
    </table>

    <div class="ticket">
        <div class="line"></div>
        <h3 style="">Asientos 2025 | Precios aplicados</h3>
        @if ($generic_data['promotion_ticket'])
            <p><strong>Promoción aplicada:</strong> {{ $generic_data['promotion_ticket']['name'].' - '. Str::ucfirst(str_replace('_', ' ', $generic_data['promotion_ticket']['promotionType']['name'].(  $generic_data['promotion_ticket']['percent_allow'] ? ' ('.$generic_data['promotion_ticket']['percent_allow'].'%)': '')))}}</p>
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
                    @if ($generic_data['promotion_ticket'])
                        <th class="text-right">PRECIO   ORIGINAL</th>
                    @endif
                    <th class="text-right">PRECIO ABONO</th>

                    {{-- <th class="text-right">QR</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($pdf_data as $data)
                    <tr>
                        <td class="text-right">{{ $data['holder_name']  . ' ' .  $data['holder_last_name'] }}</td>
                        <td class="text-right">{{ $data['holder_jersey_type'] }}</td>
                        <td class="text-right">{{ $data['holder_jersey_size'] }}</td>
                        <td class="text-right">{{ $data['zone_type']  }}</td>
                        <td class="text-right">{{ $data['row']  }}</td>
                        <td class="text-right">{{ $data['seat']  }}</td>
                        <td class="text-right">{{ $data['seat_type']  }}</td>
                        <td class="text-right">{{ $data['percentage_cashback']  }}%</td>
                        @if ($generic_data['promotion_ticket'])
                            <td class="text-right">${{ number_format($data['original_price'][0]['pivot']['price'], 2, '.', '')?? ''}}</td>
                        @endif
                        <td class="text-right">${{ $data['final_price'] }}</td>
                        {{-- <td class="text-right"><img src="{{ $data['qr_img'] }}" alt="QR Code"></td> --}}
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div style="margin-top: 30px; text-align: right">

            <p><strong>Tipo de compra :</strong> {{ $generic_data['sale_debtor'] ? "Pago a plazos" : "Pago al contado" }}</p>
            @if ($generic_data['sale_debtor'])
                <p><strong>Pago inicial:</strong> {{ '$'.number_format($generic_data['installment_payment_histories'][0]['total_amount'], 2, '.', '') ?? null }}</p>
            @endif

            @foreach($generic_data['global_payment_types'] as $data)
                <p><strong>Tipo de pago:</strong> {{ $data['name']}} {{  count($generic_data['global_payment_types']) > 1 ? '$'.number_format($data['pivot']['amount'], 2, '.', ''):'' }}</p>
            @endforeach
            @if ($generic_data['payment_in_installments'])
                <p><strong>Pago MSI:</strong> {{ $generic_data['payment_in_installments']}} meses</p>
            @endif
            <p><strong>Importe del abono:</strong> ${{ number_format($generic_data['total_amount'], 2, '.', '') }}</p>

            @if ($generic_data['sale_debtor'])
                <p><strong>Total restante:</strong> {{ '$'.number_format((float) ($generic_data['total_amount'] ?? 0) - (float) ($generic_data['installment_payment_histories'][0]['total_amount'] ?? 0), 2, '.', '') }}</p>
            @endif
        </div>

        <div style="font-size: 12px;  margin-top: 30px; background: #f2f2f2; padding-top:10px; padding-bottom: 10px; padding-left: 20px; padding-right: 20px; border-radius: 15px;">
            <p><strong>Observaciones adicionales con nombres y asientos:</strong></p>
            @foreach($pdf_data as $data)
                @if ($data['is_owner'])
                    <p>{{ $data['description'] }}</p>
                @endif
            @endforeach
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
                Temporada 2025/2026
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

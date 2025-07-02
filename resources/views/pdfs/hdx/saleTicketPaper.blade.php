@php
    use Carbon\Carbon;
    Carbon::setLocale('es');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    @foreach($pdf_data as $data)
        @if ($loop->first)
            <div class="alert">
                <p>EL BOLETO NO ESTÁ SUJETO A REEMBOLSO, CAMBIO O REPOSICIÓN. EL BOLETO TE DA DERECHO A UN ACCESO AL INMUEBLE. El boleto te da derecho a un lugar específico dentro del inmueble. No está permitido el reingreso. Este boleto es válido solo para el evento y asiento descrito en pantalla. Queda prohibido mostrar capturas de pantalla del boleto en la entrada. El poseedor del boleto asume cualquier riesgo o peligro accidental proveniente del evento. La admisión<br>está sujeta a que el espectador permita que se practique la revisión correspondiente para evitar el acceso a alimentos y bebidas alcohólicas, drogas, armas, mochilas, maletas, productos de tabaco, vapeadores, grabadoras, cámaras de cualquier tipo o cualquier otro artículo o sustancia no autorizada. El titular del inmueble del evento<br> o sus representantes se reservan el derecho de admisión o, en su caso, se retirará del inmueble a cualquier persona cuya conducta se considere ofensiva, que induzca al desorden, y en general aquellas conductas que pudieran constituir una infracción o delito, no estando obligado a reembolsar cantidad alguna. El espectador se obliga a cumplir con las reglas del inmueble.</p>
            </div>
        @endif
        <div class="ticket">
            <h1>HALCONES DE XALAPA</h1>
            <p class="info" style="margin-top: 20px; margin-left: 160px">
                Cultura Veracruzana, Zona Universitaria,<br>
                Campus Cad, Xalapa Enriquez, 91094
            </p>
            <h2 style="margin-top: 40px; margin-left: 160px">TICKET DE VENTA</h2>
            <p style="margin-top: 20px; margin-left:30%">Taquilla: Taquilla halcones</p>
            <p style="margin-top: 20px; margin-left: 30%">Vendedor:{{
                trim(implode(' ', array_filter([
                    $data['seller_user']['first_name'],
                    $data['seller_user']['middle_name'],
                    $data['seller_user']['last_name']
                ])))
                }}
            </p>
            <p style="margin-top: 20px; margin-left: 30%">Fecha de compra:{{ Carbon::parse($data['ticket_created_at'])->translatedFormat('d F, Y h:i A') }}</p>
            @if ($data['promotion_ticket'])
                <p style="margin-top: 20px; margin-left: 30%">Promoción aplicada: {{ $data['promotion_ticket']['name'].' - '. Str::ucfirst(str_replace('_', ' ', $data['promotion_ticket']['promotionType']['name'].(  $data['promotion_ticket']['percent_allow'] ? ' ('.$generic_data['promotion_ticket']['percent_allow'].'%)': '')))}}</p>
            @endif

            <p style="margin-top: 20px; margin-left: 30%">Folio de Venta:  {{ $data['ticket_id'] }}</p>

            <p style="margin-top: 20px; margin-left: 30%">Caja registradora: {{ $data['cash_register_type'] }}</p>
            <!--<table style="margin-top: 40px; margin-left: 170px">
                <tr>
                    <th>
                        <p>Taquilla:<br>Taquilla halcones</p>
                        <p>Vendedor:<br>{{
                            trim(implode(' ', array_filter([
                                $data['seller_user']['first_name'],
                                $data['seller_user']['middle_name'],
                                $data['seller_user']['last_name']
                            ])))
                            }}
                        </p>
                        <p>Fecha de compra:<br>{{ Carbon::parse($data['ticket_created_at'])->translatedFormat('d F, Y h:i A') }}</p>
                        @if ($data['promotion_ticket'])
                            <p>Promoción aplicada:<br>{{ $data['promotion_ticket']['name'].' - '. Str::ucfirst(str_replace('_', ' ', $data['promotion_ticket']['promotionType']['name'].(  $data['promotion_ticket']['percent_allow'] ? ' ('.$generic_data['promotion_ticket']['percent_allow'].'%)': '')))}}</p>
                        @endif
                    </th>
                    <td>
                        <p>Folio de Venta:  {{ $data['ticket_id'] }}</p>
                        <p></p>
                        <p>Caja registradora: {{ $data['cash_register_type'] }}</p>
                    </td>
                </tr>
            </table>-->
            <h3 style="margin-top: 40px;">{{ $data['seat_code'] }}</h3>
            <p class="info" style="margin-top: 20px;  margin-left: 160px">
                El ingreso al inmueble es exclusivo <br>
                para el asiento y zona especificada
            </p>
            <h2 style="margin-top: 40px;">{{ $data['event_name'] }}</h2>
            <p style="margin-top: 20px; margin-left: 30%">Lugar:USBI "Nido de los Halcones"</p>
            <p style="margin-top: 20px; margin-left: 30%">Fecha del evento:{{  Carbon::parse($data['event_start_date'])->translatedFormat('d F, Y h:i A') }}</p>
            <p style="margin-top: 20px; margin-left: 30%">Boletos: 1</p>
            <p style="margin-top: 20px; margin-left: 30%">Total: ${{  number_format($data['final_price'], 2, '.', '') }}</p>
            <!--<table style="margin-top: 40px; margin-left: 170px">
                <tr>
                    <th>
                        <p>Lugar:<br>USBI "Nido de los Halcones"</p>
                        <p>Fecha del evento:<br>{{  Carbon::parse($data['event_start_date'])->translatedFormat('d F, Y h:i A') }}</p>
                    </th>
                    <td>
                        <p>Boletos: 1</p>
                        <p>Total: ${{  number_format($data['final_price'], 2, '.', '') }}</p>
                    </td>
                </tr>
            </table>-->
            <div class="line"></div>
            <div class="qr">
                <img src="{{ $data['qr_img'] }}" alt="QR Code">
            </div>
            <div class="footer">
                <p>
                    Este código será verificado al ingresar al inmueble,<br>
                    solo podrá ser utilizado una vez. <br>
                </p>
                <h5>¡Gracias por su compra!</h5>
            </div>

            <div class="line" style="margin-bottom: 40px;"></div>

        </div>
    @endforeach

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .alert {
            margin-left: 30%;
            page-break-before: always;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .alert p {
            font-size: 23px;
        }
        .ticket {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ticket h1, .ticket h2, .ticket h3 {
            margin: 0;
            margin-left: 25%;
            padding: 5px 0;
            text-align: center;
        }
        .ticket h2, .ticket h3 {
            font-size: 35px;
        }
        .ticket h1{
            font-size: 40px;
            background: #000000;
            color: white;
            border-radius: 0px;
            padding: 35px 0px
        }
        .ticket h3{
            font-size: 70px;
            border-top: 4px dashed #000;
            padding-top: 40px;
        }
        .ticket p {
            margin-top: 40px;
            margin: 5px 0;
            font-size: 25px;
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
            margin-left: 30%;
            padding: 20px;
            background: #f1f1f1;
            margin-top: 40px;
            text-align: center;
            border-radius: 20px;
            font-size: 25px;
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
            margin-left: 160px;
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
    </style>
</body>
</html>

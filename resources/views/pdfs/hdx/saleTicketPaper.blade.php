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
        <div class="ticket">
            <h1>HALCONES DE XALAPA</h1>
            <p class="info" style="margin-top: 20px;">
                Cultura Veracruzana, Zona Universitaria,<br>
                Campus Cad, Xalapa Enriquez, 91094
            </p>
            <h2 style="margin-top: 40px;">TICKET DE VENTA</h2>
            <table class="w-full" style="margin-top: 40px;">
                <tr>
                    <td class="w-half-left">
                        <p>Taquilla: Taquilla halcones</p>
                        <p>Vendedor: {{
                            trim(implode(' ', array_filter([
                                $data['seller_user']['first_name'],
                                $data['seller_user']['middle_name'],
                                $data['seller_user']['last_name']
                            ])))
                            }}
                        </p>
                        <p>Fecha de compra: {{ Carbon::parse($data['ticket_created_at'])->translatedFormat('d F, Y h:i A') }}</p>
                    </td>
                    <td class="w-half-right">
                        <p>Folio de Venta: {{ $data['ticket_id'] }}</p>
                        <p></p>
                        <p>Caja registradora: {{ $data['cash_register_type'] }}</p>
                    </td>
                </tr>
            </table>
            <h3 style="margin-top: 40px;">{{ $data['seat_code'] }}</h3>
            <p class="info" style="margin-top: 20px;">
                La adquisición al inmueble es exclusiva <br>
                para el asiento y zona especificada
            </p>
            <h2 style="margin-top: 40px;">{{ $data['event_name'] }}</h2>
            <table class="w-full" style="margin-top: 40px;">
                <tr>
                    <td class="w-half-left">
                        <p>Lugar: USBI "Nido de los Halcones"</p>
                        <p>Fecha del evento: {{  Carbon::parse($data['event_start_date'])->translatedFormat('d F, Y h:i A') }}</p>
                    </td>
                    <td class="w-half-right">
                        <p>Boletos: 1</p>
                        <p>Total: ${{  number_format($data['final_price'], 2, '.', '') }}</p>
                    </td>
                </tr>
            </table>
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
            font-size: 70px;
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
    </style>
</body>
</html>

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
        <h1>LOS HALCONES DE XALAPA</h1>
        <p class="info" style="margin-top: 20px;">
            Cultura Veracruzana, Zona Universitaria,<br>
            Campus Cad, Xalapa Enriquez, 91094
        </p>
        <h2 style="margin-top: 40px;">TICKET DE VENTA</h2>

        <table class="w-full" style="margin-top: 40px;">
            <tr>
                <td class="w-half-left">
                    <p>Taquila: TIENDA TEST</p>
                    <p>Fecha de compra: 2024-10-28 04:13:54</p>
                </td>
                <td class="w-half-right">
                    <p>Folio de Venta: 20845</p>
                    <p>Caja registradora: 1</p>
                </td>
            </tr>
        </table>

        <h3 style="margin-top: 40px;">AA13</h3>

        <p class="info" style="margin-top: 20px;">
            La adquisición al inmueble es exclusiva <br>
            para el asiento y zona especificada
        </p>

        <h2 style="margin-top: 40px;">Halcones de Xalapa vs Monterrey</h2>

        <table class="w-full" style="margin-top: 40px;">
            <tr>
                <td class="w-half-left">
                    <p>Lugar: USBI "Nido de los Halcones"</p>
                    <p>Fecha del evento: 2024-10-28 04:13:54</p>
                </td>
                <td class="w-half-right">
                    <p>Boletos: 1</p>
                    <p>Total: $100</p>
                </td>
            </tr>
        </table>

        <div class="line"></div>
        <div class="qr">
            <img src="{{ $img }}" alt="QR Code">
        </div>

        <div class="footer">
            <p>
                Este condigo sera verificado al ingresar al inmueble,<br>
                solo podra ser utilizado una vez. <br>
            </p>
            <h5>¡Gracias por su compra!</h5>
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

<html>
<head>
    <title>{{ $title }} - {{$quote[0]->client_name}}</title>
    <style>
        .logo {
            position: absolute;
            top: 10px; /* Ajusta según sea necesario */
            right: 10px; /* Ajusta según sea necesario */
            width: 250px; /* Controla el tamaño de la imagen */
        }

        .page-break {
            page-break-before: always; /* Asegura un salto de página */
        }

        .hide-on-next-pages {
            display: block;
        }

        /* Ocultar elementos en páginas siguientes */
        @media print {
            .hide-on-next-pages {
                display: none;
            }
        }
        body {
            font-family: Arial, sans-serif;
            padding-top: 40px; /* Espaciado para evitar que el texto toque la imagen */
            position: relative;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #333;
        }
        tr{
            padding: 15px; border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
<img src="{{ $logo }}" class="logo hide-on-next-pages" alt="">
<h1>{{ $title }}</h1>
@foreach($quote as $registro)
    <p>Fecha de expiración: {{$registro->quote_expiration_date}}</p>
    <h2>Cliente: </h2>
    <p>NIT/CC: {{$registro->client_identification}}</p>
    <p>Nombre: {{$registro->client_name}}</p>
    <p>Número de teléfono: {{$registro->client_ph}}</p>
    <p>Dirección: {{$registro->client_address}}</p>
    <p>Email: {{$registro->client_email}}</p>
    <h2>Detalle: </h2>
    <p>Horas de trabajo: {{$registro->quote_estimated_time}}</p>
@endforeach
<table style="width: 100%;  border-collapse: collapse; text-align: center; padding-top: 5% ">
    <thead style="background-color: #f7f7f7; font-weight: bold; color: #333; ">
    <tr style="padding: 15px; border-bottom: 1px solid #ddd;">
        <th>Item</th>
        <th>Descripción</th>
        <th>Imagen</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Valor Parcial</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    {{$subtotal = 0}}
    {{$contador = 1}}
    @foreach($detail as $detalle)
        {{$subtotal += $detalle->quantity * $detalle-> unit_price}}
        <tr style="padding: 15px; border-bottom: 1px solid #ddd;">
            <td>{{$contador}}</td>
            <td>{{$detalle -> prod_name}}</td>
            <td><img style="max-width: 85px;" src="{{$detalle -> prod_image}}" alt=""></td>
            <td>{{$detalle -> quantity}}</td>
            <td>$ {{ number_format($detalle -> unit_price)}}</td>
            <td>$ {{number_format($detalle->quantity * $detalle-> unit_price)}}</td>
            <td>$ {{ number_format($subtotal) }}</td>
        </tr>
        {{$contador += 1}}
    @endforeach
    <tr>
        {{$subtotal += ($quote[0]->quote_helper_payday/8)*$quote[0]->quote_estimated_time}}
        <td>{{$contador}}</td>
        <td>Auxiliares</td>
        <td>N/A</td>
        <td>{{$quote[0]->quote_helpers}}</td>
        <td>$ {{number_format($quote[0]->quote_helper_payday) }}</td>
        <td>$ {{number_format(($quote[0]->quote_helper_payday/8)*$quote[0]->quote_estimated_time)}}</td>
        <td>$ {{number_format($subtotal)}}</td>
        {{$contador += 1}}
    </tr>
    <tr>
        {{$subtotal += ($quote[0]->quote_supervisor_payday/8)*$quote[0]->quote_estimated_time}}
        <td>{{$contador}}</td>
        <td>Supervisor de obra</td>
        <td>N/A</td>
        <td>1</td>
        <td>$ {{number_format($quote[0]->quote_supervisor_payday) }}</td>
        <td>$ {{number_format(($quote[0]->quote_supervisor_payday/8)*$quote[0]->quote_estimated_time)}}</td>
        <td>$ {{number_format($subtotal)}}</td>
        {{$contador += 1}}
        <td></td>
    </tr>

    <tr>
        {{$subtotal += $quote[0]->quote_other_costs_total}}
        <td>{{$contador}}</td>
        <td>Otros Costos</td>
        <td>N/A</td>
        <td>1</td>
        <td>$ {{number_format($quote[0]->quote_other_costs_total) }}</td>
        <td>$ {{number_format($quote[0]->quote_other_costs_total)}}</td>
        <td>$ {{number_format($subtotal)}}</td>
        <td></td>
        {{$contador += 1}}
    </tr>

    <tr>
        <td>{{$contador}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Subtotal:</td>
        <td>$ {{number_format($subtotal)}}</td>
        {{$contador += 1}}
    </tr>
    <tr>
        <td>{{$contador}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>IVA:</td>
        <td>$ {{number_format($subtotal * 0.19)}}</td>
        {{$contador += 1}}
    </tr>
    <tr>
        <td>{{$contador}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total:</td>
        <td>$ {{number_format(($subtotal * 0.19)+$subtotal)}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>

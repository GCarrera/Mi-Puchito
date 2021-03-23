<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mi Puchito C.A</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="../public/css/bootstrap.css">
        <link rel="stylesheet" href="../public/css/index.css">

    </head>
    <body>


                    <!--HEADER DE LA FACTURA-->
                    <div class="" style="height: 120px; position: absolute; left: 10px; top: 0px;" >
                        <img src="../public/img/pinchitos.png" width="150" alt="logo-mi-puchito" >
                    </div>

                        <div class="text-center mt-3" style="width: 100%;">
                            <span class="font-weight-bold">Comercial Mi puchito</span>
                            <br>
                            <span class="font-weight-bold">RIF J-30674696-0</span>
                            <br>
                            <span>Este @if ($despacho->type == 1)
                              despacho
                            @else
                              retiro
                            @endif fue emitido para </span> {{ $despacho->piso_venta->nombre }}</span>
                            <br>
                            <span><span class="font-weight-bold">Estado:</span> <span style="font-size: 1.2em;">
                              @switch ($despacho->confirmado)
                            @case('1')
                              <span class="text-success">Confirmado</span>
                              @break
                            @case('0')
                              <span class="text-danger">Negado</span>
                              @break
                            @case(null)
                              <span class="text-default">Pendiente</span>
                              @break
                            @endswitch</span> {{ $despacho->created_at }}</span>
                            <br>
                        </div>


                    <!--TABLA DE PRODUCTOS-->

                    <table class="table table-striped table-bordered table-sm mt-3">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($despacho->productos as $producto)
                            <tr>
                                <td>{{$producto->product_name}}</td>
                                <td>{{$producto->pivot->cantidad}}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <!--<div class="text-center" style="width: 70%; position: absolute; bottom: 0px; left: 100px;">
                        <p class="font-weight-bold text-center" style="font-size: 1.5em;">Gracias por su compra siganos en instragram @Mipuchito.Ca</p>
                    </div>-->

    </body>
</html>

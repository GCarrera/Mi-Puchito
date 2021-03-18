<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

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
                            <span>Calle sabana larga cruce entre mariño y rondon.</span>
                            <br>
                            <span><span class="font-weight-bold">Teléfonos empresarial:</span> 0424-3622054</span>
                            <br>
                        </div>
                        <div class="mt-3">
                            <span class="float-left"><span class="font-weight-bold">Esta factura fue emitida:</span> {{$pedido->created_at}}</span>

                            <span class="float-right"><span class="text-danger">N°Factura:</span> <span style="font-size: 1.2em;">FC-000{{$pedido->id}}</span></span>
                        </div>
                            <br>
                            <span><span class="font-weight-bold">Cliente:</span> {{$pedido->user->people->name}}</span><br>
                            <span><span class="font-weight-bold">Cedula:</span> {{$pedido->user->people->dni}}</span><br>
                            @if ($pedido->rate->address_user_delivery != null)
                              <span><span class="font-weight-bold">Telefono:</span> {{$pedido->rate->address_user_delivery->phone_contact}}</span><br>
                            @else
                              <span><span class="font-weight-bold">Telefono:</span> {{$pedido->dir->phone_contact}}</span><br>
                            @endif
                            @if ($pedido->rate->address_user_delivery != null)
                              @if ($pedido->rate->address_user_delivery->travel_rate_id != null)
                                <span class="">{{$pedido->dir->sector->sector}} {{$pedido->rate->address_user_delivery->details}}</span>
                              @else
                                <span class="">{{$pedido->rate->address_user_delivery->details}}</span>
                              @endif
                            @else
                              <span class="">{{$pedido->sector->sector}} {{$pedido->dir->details}}</span>
                            @endif

                    <!--TABLA DE PRODUCTOS-->

                    <table class="table table-striped table-bordered mt-3">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <!--<th>iva unitario</th>-->
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->details as $producto)
                            <tr>
                                <td>{{$producto->product->inventory->product_name}}</td>

                                @if($producto->type == "al-mayor")
                                <td>{{$producto->quantity . " " .$producto->product->inventory->unit_type}}</td>
                                <td>{{number_format($producto->sub_total / $producto->quantity, 2, ',', '.')}}</td>
                                {{--<td>{{number_format($producto->iva / $producto->quantity, 2, ',', '.') }}</td>--}}
                                <td>{{number_format($producto->amount, 2, ',', '.') }}</td>

                                @else
                                <td>{{$producto->quantity . " " .$producto->product->inventory->unit_type_menor}}</td>
                                <td>{{number_format($producto->sub_total / $producto->quantity, 2, ',', '.')}}</td>
                                {{--<td>{{number_format($producto->iva / $producto->quantity, 2, ',', '.') }}</td>--}}
                                <td>{{number_format(($producto->amount), 2, ',', '.') }}</td>
                                @endif

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="text-center" style="width: 70%; position: absolute; bottom: 0px; left: 100px;">
                        <p class="font-weight-bold text-center" style="font-size: 1.5em;">Gracias por su compra siganos en instragram @Mipuchito.Ca</p>
                    </div>
                    <div class="text-right">
                        {{--<span class=""><span class="font-weight-bold">Subtotal:</span><span>{{number_format($pedido->sub_total*$pedido->dolar->price, 2, ',', '.') }} Bs.<span></span>--}}
                        {{--<p class=""><span class="font-weight-bold">IVA:</span><span>{{number_format($pedido->iva, 2, ',', '.') }} Bs.<span></p>--}}
                        <span class=""><span class="font-weight-bold">Total: </span><span>{{number_format($pedido->amount*$pedido->dolar->price, 2, ',', '.')}} Bs.<span></span>
                        <p class="" style="font-size: 0.9em"><span class="text-success">Total dolares:</span><span> {{number_format($pedido->amount, 2, ',', '.')}}$.<span></p>
                    </div>


    </body>
</html>

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

                        <div class="text-center" style="width: 100%;">
                            <span class="font-weight-bold">Comercial Mi puchito</span>
                            <br>
                            <span class="font-weight-bold">RIF J-30674696-0</span>
                            <br>
                            <span>Calle sabana larga cruce entre mariño y rondon.</span>
                            <br>
                            <span><span class="font-weight-bold">Teléfonos empresarial:</span> 0424-3622054</span>
                            <br>
                            
                        </div> 


                    <!--TABLA DE PRODUCTOS-->
    
                    <table class="table table-striped table-bordered mt-5">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>Cantidad</th>
                                <th>Descripcion</th>
                                <th>Precio unitario</th>
                                <th>Precio</th>
                                <th>iva</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->details as $producto)
                            <tr>
                                <td>{{$producto->quantity}}</td>
                                <td>{{$producto->product->inventory->product_name}}</td>
                                <td>{{$producto->product->retail_total_price - $producto->product->retail_iva_amount}}</td>
                                <td>{{number_format((($producto->product->retail_total_price - $producto->product->retail_iva_amount) * $producto->quantity), 2, ',', '.') }}</td>
                                <td>{{number_format($producto->product->retail_iva_amount, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="float-left text-center" style="width: 70%;">
                        
                        <span class="font-weight-bold" style="font-size: 1.5em;">Gracias por su compra siganos en instragram @Mipuchito.Ca</span>

                    </div>
                    <p class="text-right"><span class="mr-5 font-weight-bold">Subtotal:</span><span>{{number_format($subtotal, 2, ',', '.') }} Bs.<span></p>
                    <p class="text-right"><span class="mr-5 font-weight-bold">IVA:</span><span>{{number_format($iva, 2, ',', '.') }} Bs.<span></p>
                    <p class="text-right"><span class="mr-5 font-weight-bold">Total a pagar:</span><span>{{number_format($pedido->amount, 2, ',', '.')}} Bs.<span></p>
                    <p class="text-right"><span class="mr-5 font-weight-bold">Total dolares:</span><span> {{number_format($pedido->dolar->price, 2, ',', '.')}}$.<span></p>
              
                    <span><span class="font-weight-bold">Fecha:</span> {{$pedido->created_at}}</span>
                    <span><span class="font-weight-bold">N°Factura:</span> {{$pedido->code}}</span>
                    <br>
                    <span><span class="font-weight-bold">Cliente:</span> {{$pedido->user->people->name}}</span>
                    <span><span class="font-weight-bold">Cedula:</span> {{$pedido->user->people->dni}}</span>
      
    </body>
</html>

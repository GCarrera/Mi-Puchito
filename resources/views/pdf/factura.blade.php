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
                        <div class="float-left" style="max-width: 40%;">
                            <span class="font-weight-bold">Mi puchitos</span>
                            <br>
                            <span class="font-weight-bold">Dirección:</span> Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.
                            <br>
                            <span class="font-weight-bold">Teléfonos:</span> 0414 265-2565
                            <br>
                            <span class="font-weight-bold">Pagina web:</span> https://www.mipuchito.com/
                            <br>
                        </div>
                    
                      
                        <img src="../public/img/pinchitos.png" width="150" alt="logo-mi-puchito" style="margin-left:15px;">
                        
                        
                        <table class="table-striped float-right" style="width: 25%;">

                            <tr>
                                <th>Pedido numero</th>
                            </tr>
                            <tr>
                                <td>{{$pedido->code}}</td>
                            </tr>

                            <tr>
                                <th>Fecha</th>
                            </tr>
                            <tr>
                                <td> {{$pedido->created_at}}</td>
                            </tr>
                                
                        </table>          
                    <br>
                    <!--DATOS DEL CLIENTE-->
                    <div class="" style="margin-top: 50px;">
                        <p class="font-weight-bold text-center">Datos del cliente:</p>

                        <p><span class="font-weight-bold">Nombre:</span> {{$pedido->user->people->name}}</p>
                        @if(isset($pedido->user->people->lastname))
                        <p><span class="font-weight-bold">Apellido:</span> {{$pedido->user->people->lastname}}</p>
                        @endif
                        <p><span class="font-weight-bold">Cedula:</span> {{$pedido->user->people->dni}}</p>
                        @if(isset($pedido->user->people->phone_number))
                        <p><span class="font-weight-bold">Telefono:</span> {{$pedido->user->people->phone_number}}</p>
                        @endif
                    </div>

                    <!--TABLA DE PRODUCTOS-->
                    <p class="font-weight-bold text-center">Factura:</p>
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Precio Bs</th>
                                <th>iva</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->details as $producto)
                            <tr>
                                <td>{{$producto->quantity}}</td>
                                <td>{{$producto->product->inventory->product_name}}</td>
                                <td>{{number_format((($producto->product->retail_total_price - $producto->product->retail_iva_amount) * $producto->quantity), 2, ',', '.') }}</td>
                                <td>{{number_format($producto->product->retail_iva_amount, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot class="bordered">
                            <tr>
                                <td colspan="3" class="text-right">Subtotal:</td>
                                <td>{{number_format($subtotal, 2, ',', '.') }} Bs.</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">IVA:</td>
                                <td>{{number_format($iva, 2, ',', '.') }} Bs.</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Total:</td>
                                <td>{{$pedido->amount}} Bs.</td>
                            </tr>
                        </tfoot>
                    </table>
              
            
      
    </body>
</html>

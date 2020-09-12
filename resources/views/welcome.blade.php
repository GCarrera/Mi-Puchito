<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">



        <title>Laravel</title>

        <!-- Fonts -->
      

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
            
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>

            <div class="container">
            <div class="card">
                <div class="card-body">
                    <!--HEADER DE LA FACTURA-->
                    <div class="row">
                        <div class="col-4">
                            <p class="font-weight-bold">Mi puchitos</p>
                            <p>Dirección:</p>
                            <p>Teléfonos:</p>
                            <p>Pagina web:</p>
                        </div>
                        <div class="col-4">
                            <!--<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; position: center;" alt="Mi puchitos logo">-->
                        </div>
                        <div class="col-4">
                            <table class="table table-striped table-bordered">

                                <tr>
                                    <th>Pedido numero</th>
                                </tr>
                                <tr>
                                    <td>3123123123</td>
                                </tr>

                                <tr>
                                    <th>Fecha</th>
                                </tr>
                                <tr>
                                    <td> 7/8/2019</td>
                                </tr>
                            
                            </table>
                        </div>

                    </div>
                    <!--DATOS DEL CLIENTE-->
                    <div>
                        <p class="font-weight-bold">Datos del cliente:</p>

                        <div class="row">
                            <div class="col-6">
                                <p><span class="font-weight-bold">Nombre:</span> luis briceño</p>
                            </div>
                            <div class="col-6">
                                <p><span class="font-weight-bold">Telefono:</span> 2564546846</p>
                            </div>
                            <div class="col-6">
                                <p><span class="font-weight-bold">Direccion:</span> fdfnsafhsdfnaslfkbnasdfklnasklfnklasfnaslñfks</p>
                            </div>
                            <div class="col-6">
                                <p><span class="font-weight-bold">Ciudad:</span> Maracay</p>
                            </div>
                        </div>
                    </div>

                    <!--TABLA DE PRODUCTOS-->

                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>iva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>231</td>
                                <td>salchichas</td>
                                <td>200000</td>
                                <td>20000</td>
                            </tr>
                            <tr>
                                <td>231</td>
                                <td>salchichas</td>
                                <td>200000</td>
                                <td>20000</td>
                            </tr>
                            <tr>
                                <td>231</td>
                                <td>salchichas</td>
                                <td>200000</td>
                                <td>20000</td>
                            </tr>
                        </tbody>
                        <tfoot class="bordered">
                            <tr>
                                <td colspan="3" class="text-right">Subtotal:</td>
                                <td>fdsf</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">IVA:</td>
                                <td>fdsf</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right">Total:</td>
                                <td>fdsf</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/toastr.js') }}"></script>-
        <script src="{{ asset('slick/slick.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script>
        <!-- <script src="{{ asset('bootstrap-star-rating/js/star-rating.js') }}"></script> -->
        <!-- <script src="{{ asset('bootstrap-star-rating/themes/krajee-fas/theme.min.js') }}" ></script> -->
        <!--<script src="{{ asset('js/datatables.min.js') }}" ></script>-->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <script src="{{ asset('js/daterangepicker.js') }}"></script>
        <script src="{{ asset('js/jquery.lazy.min.js') }}"></script>
        <script type="text/javascript">
            
            Echo.channel('home').listen('NewMessage', (e) => {
                console.log(e.message)
            })

        </script>
    </body>
</html>

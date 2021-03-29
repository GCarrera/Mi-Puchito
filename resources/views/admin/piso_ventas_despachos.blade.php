@extends('layouts.adminVue')

@section('content')
	<div style="margin-top: 100px;"></div>
	@foreach ($piso_venta as $value)

	@endforeach
	<piso-ventas-despachos dataDespachos="{{$value->id}}" nombrePiso="{{$value->nombre}}" />
@endsection

@extends('layouts.admin')

@section('content')
	<div style="margin-top: 100px;"></div>
	@foreach ($piso_venta as $value)

	@endforeach
	<piso-ventas-ventas dataVentas="{{$value->id}}" nombrePiso="{{$value->nombre}}" />
@endsection

@extends('layouts.admin')

@section('content')
	<div style="margin-top: 100px;"></div>
	@foreach ($piso_venta as $value)

	@endforeach
	<piso-ventas-retiros dataRetiros="{{$value->id}}" nombrePiso="{{$value->nombre}}" />
@endsection

@extends('layouts.app', ['bandera' => 'true'])
@section('title') Dashboard @endsection
@section('content')
    @if(isset($partido))
        <div class="row" style="background-color: white; margin: 8px">
            <div class="col-md-3">
                <h2> {{ $partido }} </h2>
            </div>
            <div class="col-md-5" >
                <h2>Eventos</h2>
                <hr>
                <div id="eventosGastoCategorias"></div>
                <div id="eventoConteoEstados"></div>
                <div id="eventosGastoEstados"></div>
                <div id="eventosSubcategoriaAnimacion"></div>
                <div id="eventosSubcategoriaEspectacular"></div>
                <div id="eventosSubcategoriaEstructura"></div>
            </div>
            <div class="col-md-4">
                <h2>Tierra</h2>
                <hr>
                <?=  print_r($prueba)  ?>
            </div>
        </div>
    @else

    @endif

    {!! \Lava::render('ColumnChart', 'Gasto por Categoría', 'eventosGastoCategorias') !!}
    {!! \Lava::render('DonutChart', 'Cantidad por estado', 'eventoConteoEstados') !!}
    {!! \Lava::render('DonutChart', 'Gasto por estado', 'eventosGastoEstados') !!}
    {!! \Lava::render('DonutChart', 'Gasto en animación', 'eventosSubcategoriaAnimacion') !!}
    {!! \Lava::render('DonutChart', 'Gasto en espectacular', 'eventosSubcategoriaEspectacular') !!}
    {!! \Lava::render('DonutChart', 'Gasto en estructura', 'eventosSubcategoriaEstructura') !!}
@endsection
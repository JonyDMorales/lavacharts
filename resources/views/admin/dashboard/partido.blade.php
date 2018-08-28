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
                <div id="eventosSubcategoriaProduccion"></div>
                <div id="eventosSubcategoriaTransporte"></div>
                <div id="eventosSubcategoriaUtilitario"></div>
            </div>
            <div class="col-md-4">
                <h2>Tierra</h2>
                <hr>
                <div id="tierraGastoCategorias"></div>
                <div id="tierraConteoEstados"></div>
                <div id="tierraGastoEstados"></div>
                <div id="tierraSubcategoriaMovil"></div>
                <div id="tierraSubcategoriaFija"></div>
                <!--<?=  print_r($prueba)  ?>-->
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
    {!! \Lava::render('DonutChart', 'Gasto en producción', 'eventosSubcategoriaProduccion') !!}
    {!! \Lava::render('DonutChart', 'Gasto en transporte', 'eventosSubcategoriaTransporte') !!}
    {!! \Lava::render('DonutChart', 'Gasto en utilitario', 'eventosSubcategoriaUtilitario') !!}

    {!! \Lava::render('ColumnChart', 'Gasto de tierra por Categoría', 'tierraGastoCategorias') !!}
    {!! \Lava::render('DonutChart', 'Tierra cantidad por estado', 'tierraConteoEstados') !!}
    {!! \Lava::render('DonutChart', 'Tierra gasto por estado', 'tierraGastoEstados') !!}
    {!! \Lava::render('DonutChart', 'Gasto en fija', 'tierraSubcategoriaFija') !!}
    {!! \Lava::render('DonutChart', 'Gasto en movil', 'tierraSubcategoriaMovil') !!}
@endsection
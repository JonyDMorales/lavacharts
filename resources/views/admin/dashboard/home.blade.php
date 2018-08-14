@extends('layouts.app')
@section('title') Dashboard @endsection
@section('content')
    <div class="row" style="background-color: white; margin: 8px">
        <div class="col-md-3"></div>
        <div class="col-md-5" >
            <h2>Eventos</h2>
            <hr>
            <div id="gasto"></div>
            <div id="conteo"></div>
        </div>
        <div class="col-md-4">
            <h2>Tierra</h2>
            <hr>

        </div>
    </div>


    {!! \Lava::render('DonutChart', 'Gasto de Eventos', 'gasto') !!}
    {!! \Lava::render('BarChart', 'Conteo de Eventos', 'conteo') !!}
@endsection

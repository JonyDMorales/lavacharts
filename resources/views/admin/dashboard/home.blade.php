@extends('layouts.app')
@section('title') Dashboard @endsection
@section('content')
    <div class="row" style="background-color: white; margin: 8px">
        <div class="col-md-3"></div>
        <div class="col-md-5" >
            <h2>Eventos</h2>
            <hr>
            <div id="eventosGasto"></div>
            <div id="eventosConteo"></div>
        </div>
        <div class="col-md-4">
            <h2>Tierra</h2>
            <hr>
            <div id="tierraGasto"></div>
            <div id="tierraCategorias"></div>
            <?= print_r('') ?>
        </div>
    </div>


    {!! \Lava::render('DonutChart', 'Gasto de eventos', 'eventosGasto') !!}
    {!! \Lava::render('BarChart', 'Total de eventos', 'eventosConteo') !!}
    {!! \Lava::render('ColumnChart', 'Gasto de tierra', 'tierraGasto') !!}
    {!! \Lava::render('ColumnChart', 'Gasto de categorías', 'tierraCategorias') !!}
@endsection

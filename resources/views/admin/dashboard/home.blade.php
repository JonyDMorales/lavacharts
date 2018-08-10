@extends('layouts.app')
@section('title') Dashboard @endsection
@section('content')
    <div class="row" style="background-color: white; margin: 8px">
        <div class="col-md-3"></div>
        <div class="col-md-5" >
            <h2>Eventos</h2>
            <hr>
            <div id="chart-div"></div>
        </div>
        <div class="col-md-4">
            <h2>Tierra</h2>
            <hr>
        </div>
    </div>

    <?= $lava->render('DonutChart', 'Eventos', 'chart-div') ?>
@endsection

@extends('layouts.app')
@section('title') Dashboard @endsection
@section('content')

    <div id="chart-div"></div>
    <?= $lava->render('DonutChart', 'IMDB', 'chart-div') ?>
@endsection

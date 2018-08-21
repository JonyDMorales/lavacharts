@extends('layouts.app', ['bandera' => 'true'])
@section('title') Dashboard @endsection
@section('content')
    <div style="background-color: white">
        @if(isset($partido)) <h2> {{$partido}} </h2> @endif
    </div>
@endsection
@extends('errors::layout')

@section('title', 'Service Unavailable')

@section('message')
    {!! trans('views.maintenance_mode') !!}
@stop

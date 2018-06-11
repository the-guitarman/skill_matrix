@extends('layouts.app')

@section('content')
    <div class="text-center">
        {!! trans('views.errors.401') !!}
    </div>
    @if(env('APP_DEBUG'))
        <div class="text-center">
            {{ $exception->getMessage() }}
        </div>
        <div class="text-left">
            {!! str_replace("\n", '<br />', $exception->getTraceAsString()) !!}
        </div>
    @endif
@endsection
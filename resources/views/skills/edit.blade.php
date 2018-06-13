@extends('layouts.app')

@section('top_navbar_navigation_button')
    <a href="{{ route('skill-groups.index') }}" class="top-nav-button btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Übersicht</a>
@endsection

@section('content')
    <h3><i class="fa fa-object-group"></i> Skill Group ändern</h3>

    {{ Form::open(['url' => route('skill-groups.update', ['id' => $skillGroup->id]), 'method' => 'PUT']) }}
        @include('skill_groups/_form', ['skillGroup' => $skillGroup])
    {{ Form::close() }}
@endsection
@extends('layouts.app')

@section('top_navbar_navigation_button')
<!--
    <a href="{{-- route('skill-groups.index', ['id' => $skillGroup->id]) --}}" class="top-nav-button btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Übersicht</a>
-->
@endsection

@section('content')
    <h3><i class="fa fa-object-group"></i> Skill ändern</h3>

    {{ Form::open(['url' => route('skills.my.update', ['skill_id' => $userSkill->skill_id]), 'method' => 'PUT']) }}
        @include('my_skills/_form', ['userSkill' => $userSkill])
    {{ Form::close() }}

    @if (Route::currentRouteName() === 'skills.my.edit')
        @include('common/_delete_button', ['route' => 'skills.my.destroy', 'route_parameters' => ['skill_id' => $userSkill->skill_id], 'text' => 'Meinen Skill löschen'])
    @endif
@endsection
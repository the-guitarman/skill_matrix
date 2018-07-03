@extends('layouts.app')

@section('top_navbar_navigation_button')
<!--
    <a href="{{-- route('skill-groups.show', ['id' => $skillGroup->id]) --}}" class="top-nav-button btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Übersicht</a>
-->
@endsection

@section('content')
    <h3><i class="fa fa-object-group"></i> Skill eintragen</h3>

    {{ Form::open(['url' => route('skills.my.store', ['skill_id' => $userSkill->skill_id])]) }}
        @include('my_skills/_form', ['userSkill' => $userSkill])
    {{ Form::close() }}
@endsection
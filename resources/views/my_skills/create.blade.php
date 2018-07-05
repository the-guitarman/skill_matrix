@extends('layouts.app')

@section('top_navbar_navigation_button')
<!--
    <a href="{{-- route('skill-groups.show', ['id' => $skillGroup->id]) --}}" class="top-nav-button btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Übersicht</a>
-->
@endsection

@section('content')
    @include('my_skills/_create', ['userSkill' => $userSkill])
@endsection
@extends('layouts.app')

@section('top_navbar_navigation_button')
    <a href="{{ route('skill-groups.show', ['id' => $skillGroup->id]) }}" class="top-nav-button btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Übersicht</a>
@endsection

@section('content')
    <h3><i class="fa fa-object-group"></i> Skill anlegen</h3>

    {{ Form::open(['url' => route('skill-groups.skills.store', ['skill_group_id' => $skillGroup->id])]) }}
        @include('skills/_form', ['skill' => $skill, 'skillGroup' => $skillGroup, 'allSkillGroups' => $allSkillGroups])
    {{ Form::close() }}
@endsection
@extends('layouts.app')

@section('top_navbar_navigation_button')
    <a href="{{ route('skill-groups.index', ['id' => $skillGroup->id]) }}" class="top-nav-button btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i> Übersicht</a>
@endsection

@section('content')
    <h3><i class="fa fa-object-group"></i> Skill ändern</h3>

    {{ Form::open(['url' => route('skill-groups.update', ['skill_group_id' => $skillGroup->id, 'id' => $skill->id]), 'method' => 'PUT']) }}
        @include('skills/_form', ['skill' => $skill, 'skillGroup' => $skillGroup, 'allSkillGroups' => $allSkillGroups])
    {{ Form::close() }}
@endsection
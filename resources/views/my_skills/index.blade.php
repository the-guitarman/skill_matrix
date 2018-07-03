@extends('layouts.app')

@section('content')
    <h1>Übersicht</h1>
    <h2>Meine Skills</h2>

    @include('common/_skill_groups_list', ['skillGroups' => $skillGroups, 'skillCount' => $skillCount, 'users' => $users])

@endsection
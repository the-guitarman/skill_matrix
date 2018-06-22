@extends('layouts.app')

@section('content')
    <h1>Übersicht</h1>
    <h2>Alle Skills</h2>

    @if($skillGroups->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    @foreach($skillGroups as $skillGroup)
                        <th colspan="{{ $skillGroup->skills_count }}">{{ $skillGroup->name }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach($skillGroups as $skillGroup)
                        @foreach($skillGroup->skills as $skill)
                            <th class="text-vertical">{{ $skill->name }}</th>
                        @endforeach
                    @endforeach
                </tr>
                
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    @foreach($skillGroups as $skillGroup)
                        @foreach($skillGroup->skills as $skill)
                            <td>{{ $skill->name }}</td>
                        @endforeach
                    @endforeach
                </tr>
            </tbody>
        </table>
    @else
        <p class="alert alert-warning">
            Es wurde noch keine Skill-Group angelegt.
        </p>
    @endif

@endsection
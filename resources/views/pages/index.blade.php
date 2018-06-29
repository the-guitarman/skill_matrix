@extends('layouts.app')

@section('content')
    <h1>Übersicht</h1>
    <h2>Alle Skills</h2>

    @if($skillGroups->count() > 0)
        <table class="table table-striped table-bordered rotated">
            <thead>
                <tr>
                    <td>&nbsp;</td>
                    @foreach($skillGroups as $skillGroup)
                        <th class="rotation-0 text-center" colspan="{{ $skillGroup->skills_count }}">
                            {{ $skillGroup->name }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach($skillGroups as $skillGroup)
                        @foreach($skillGroup->skills as $skill)
                            <th class="rotation-90 text-center">
                                <div><span>{{ $skill->name }}</span></div>
                            </th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @if($users->count() > 0)
                    @foreach($users as $user)
                        <tr>
                            <th class="row-header">{{ $user->name }}</th>
                            @foreach($skillGroups as $skillGroup)
                                @foreach($skillGroup->skills as $skill)
                                    @php
                                        $currentGrade = 0;
                                        $userSkill = $user->getUserSkill($skill->id);
                                        if (!empty($userSkill)) {
                                            $currentGrade = $userSkill->grade;
                                        }
                                    @endphp
                                    <td class="value text-center" style="background-color:rgba({{ implode(',', $grade_rgb_colors[$currentGrade]).',1' }})" class="text-center">
                                        <a href="#" class="btn btn-link">
                                            {{ $currentGrade > 0  ? $currentGrade : '?' }}
                                        </a>
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ $skillCount + 1 }}">
                            Es sind noch keine Benutzer-Skills eingetragen.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @else
        <p class="alert alert-warning">
            Es wurde noch keine Skill-Group angelegt.
        </p>
    @endif

@endsection
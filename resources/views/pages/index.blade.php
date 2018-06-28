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
                        <th class="text-center" colspan="{{ $skillGroup->skills_count }}">{{ $skillGroup->name }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    @foreach($skillGroups as $skillGroup)
                        @foreach($skillGroup->skills as $skill)
                            <th class="rotation-90"><div>{{ $skill->name }}</div></th>
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
                                        $userSkill = $user->getUserSkill($skill->id);
                                        //dd($userSkill);
                                        $currentGrade = 5;//$userSkill->grade;
                                    @endphp
                                    <td class="value" style="background-color:rgba({{ implode(',', $grade_rgb_colors[$currentGrade]).',1' }})" class="text-center">
                                        <a href="#">{{ $currentGrade }}</a>
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
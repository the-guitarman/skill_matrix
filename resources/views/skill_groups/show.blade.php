@extends('layouts.app')


@section('top_navbar_navigation_button')
    <a href="{{ route('skill-groups.index') }}" class="nav-item btn btn-primary"><i class="fa fa-long-arrow-left"></i> Skill-Groups <span class="sr-only">(current)</span></a>
@endsection
@section('top_navbar_create_button')
    <span class="navbar-text">&nbsp;</span>
    <a href="{{ route('skill-groups.skills.create', ['skill_group_id' => $skillGroup->id]) }}" class="nav-item btn btn-success"><i class="fa fa-plus"></i> Skill</a>
@endsection

@section('content')

    <h3><i class="fa fa-object-group"></i> {{ $skillGroup->name }}</h3>
    
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td>@include('common/_order_by_column_header', ['text' => 'Skill-Name'])</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach($skills as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
                <td class="text-right">
                    @include('common/_delete_button', ['route' => 'skill-groups.skills.destroy', 'route_parameters' => ['skill_group_id' => $skill->skill_group_id, 'id' => $skill->id], 'text' => '<i class="fa fa-trash"></i>'])
                    <a href="{{ route('skill-groups.skills.edit', ['skill_group_id' => $skillGroup->id, 'id' => $skill->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $skills->links() }}
    </div>
@endsection
@extends('layouts.app')

@section('top_navbar_create_button')
    <a href="{{ route('skill-groups.create') }}" class="top-nav-button btn btn-sm btn-primary"><i class="fa fa-plus"></i> Hinzufügen</a>
@endsection

@section('content')

    <h3>
        <i class="fa fa-object-group"></i> {{ $skillGroup->name }}
        <a href="{{ route('skill-groups.edit', $skillGroup->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
    </h3>
    
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <td></td>
                <td class="text-right">
                    <a href="{{ route('skill-groups.skills.create', ['skill_group_id' => $skillGroup->id]) }}" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                </td>
            </tr>

        </thead>
        <tbody>
            @foreach($skills as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
                <td class="text-right">
                    <a href="{{ route('skill-groups.skills.edit', ['skill_group_id' => $skillGroup->id, 'id' => $skill->id]) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="text-right">
                    <a href="{{ route('skill-groups.skills.create', ['skill_group_id' => $skillGroup->id]) }}" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="text-center">
        {{ $skills->links() }}
    </div>
@endsection
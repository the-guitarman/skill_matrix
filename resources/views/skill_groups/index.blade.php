@extends('layouts.app')

@section('top_navbar_create_button')
    <a href="{{ route('skill-groups.create') }}" class="nav-item btn btn-success"><i class="fa fa-plus"></i> Skill-Group <span class="sr-only">(current)</span></a>
@endsection

@section('content')
    <h3><i class="fa fa-object-group"></i> Skill-Groups</h3>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>
                <a href="{{ Request::fullUrlWithQuery(Helper::sort('name')) }}">
                    @include('common/_order_by_column_header', ['text' => 'Group-Name'])
                </a>
            </th>
            <th>
                <a href="{{ Request::fullUrlWithQuery(Helper::sort('skills_count')) }}">
                    @include('common/_order_by_column_header', ['text' => 'Anzahl Skills'])
                </a>
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach($skillGroups as $skillGroup)
            <tr>
                <td>{{ $skillGroup->name }}</td>
                <td>{{ $skillGroup->skills_count }}</td>
                <td class="text-right">
                    @if ($skillGroup->skills_count === 0)
                        @include('common/_delete_button', ['route' => 'skill-groups.destroy', 'route_parameters' => ['id' => $skillGroup->id], 'text' => '<i class="fa fa-trash"></i>'])
                    @endif

                    <a href="{{ route('skill-groups.show', $skillGroup->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-search"></i></a>
                    <a href="{{ route('skill-groups.edit', $skillGroup->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $skillGroups->links() }}
    </div>
@endsection
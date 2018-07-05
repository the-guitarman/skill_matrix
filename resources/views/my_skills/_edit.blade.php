    <h3><i class="fa fa-object-group"></i> Skill ändern</h3>

    {{ Form::open(['url' => route('skills.my.update', ['skill_id' => $userSkill->skill_id]), 'method' => 'PUT']) }}
        @include('my_skills/_form', ['userSkill' => $userSkill])
    {{ Form::close() }}

    @if (Route::currentRouteName() === 'skills.my.edit')
        @include('common/_delete_button', ['route' => 'skills.my.destroy', 'route_parameters' => ['skill_id' => $userSkill->skill_id], 'text' => 'Meinen Skill löschen'])
    @endif
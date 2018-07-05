<h3><i class="fa fa-object-group"></i> Skill eintragen</h3>

{{ Form::open(['url' => route('skills.my.store', ['skill_id' => $userSkill->skill_id])]) }}
    @include('my_skills/_form', ['userSkill' => $userSkill])
{{ Form::close() }}
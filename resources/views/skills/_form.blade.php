@include('common/_input_form_group', [
    'object' => $skill, 'scope' => true, 'name' => 'name',
    'options' => ['autofocus' => true, 'placeholder' => 'Name',],
])

<div class="form-group {{ $errors->has('skill.skill_group_id') || (empty($skillGroups) || count($skillGroups) === 0) ? 'is-invalid' : '' }} ">
    
    @if(!empty($allSkillGroups) && count($allSkillGroups) > 0)
        <select class="form-control select2" name="skill[skill_group_id]" id="skill_skill_group_id">
            <option></option>
            @php
                if (!empty(old('skill.skill_group_id')) && old('skill.skill_group_id')) {
                    $current_skill_group_id = old('skill.skill_group_id');
                } else {
                    $current_skill_group_id = $skillGroup->id;
                }
            @endphp
            @foreach($allSkillGroups as $aSkillGroup)
                <option value="{{ $aSkillGroup->id }}" {{ $current_skill_group_id == $aSkillGroup->id ? ' selected' : '' }}>{{ $aSkillGroup->name }}</option>
            @endforeach
        </select>
    @else
        <p class="alert alert-danger" role="alert">
            Es sind keine Skill-Groups auswählbar.<br />
            Ohne eine Skill-Group kann kein Skill angelegt werden.
        </p>
    @endif

    @include('common/_input_error', ['validation_key' => 'skill.skill_group_id'])
</div>

<div class="form-group">
    {{ Form::submit('Speichern', ['class' => 'btn btn-primary']) }}
</div>
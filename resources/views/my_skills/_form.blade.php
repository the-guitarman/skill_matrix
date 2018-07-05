@include('common/_input_form_group', [
    'object' => $userSkill->skill, 'scope' => true, 'name' => 'name',
    'options' => ['placeholder' => 'Skill', 'disabled' => true,],
])

@php
    $grades = array_keys($grade_rgb_colors);
    $grades = array_filter(
        $grades,
        function($grade) {
            return $grade > 0;
        }
    );
    $minGrade = min($grades);
    $maxGrade = max($grades);
@endphp
@include('common/_input_form_group', [
    'object' => $userSkill, 'scope' => true, 'name' => 'grade',
    'options' => ['autofocus' => true, 'placeholder' => 'Schulnote', 'min' => $minGrade, 'max' => $maxGrade, 'step' => 1, 'required' => true],
    'type' => 'number' //, 'numeric' => true, 'decimals' => 0,
])

<div class="form-group">
    {{ Form::submit('Speichern', ['class' => 'btn btn-primary']) }}
</div>
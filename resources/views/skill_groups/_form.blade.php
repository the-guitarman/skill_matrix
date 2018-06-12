@include('common/_input_form_group', [
    'object' => $skillGroup, 'scope' => true, 'name' => 'name',
    'options' => ['autofocus' => true, 'placeholder' => 'Name',],
])

<div class="ln_solid"></div>

<div class="form-group">
    {{ Form::submit('Speichern', ['class' => 'btn btn-primary']) }}
</div>
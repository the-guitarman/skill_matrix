@php
    $field_key = $name;
    $field_id = $name;
    $field_name = $name;

    if (!empty($object)) {
        $objectScope = strtolower(snake_case(class_basename($object)));
        if (isset($scope) && is_bool($scope) && $scope === true) {
            $scope = $objectScope;
        }
    }

    if (!empty($scope)) {
        $field_key = $scope.'.'.$name;
        $field_id = $scope.'_'.$name;
        $field_name = $scope.'['.$name.']';
    }

    $css_classes = ['form-control'];
    if ($errors->has($field_key)) {
        $css_classes[] = 'is-invalid';
    }
    $default_options = ['id' => $field_id, 'class' => implode(' ', $css_classes)];

    if (empty($options) || !is_array($options)) {
        $options = [];
    }
    $options = array_merge($default_options, $options);

    $type = empty($type) ? 'text' : strtolower($type);

    if (!empty($numeric) && $numeric === true) {
        $decimals = empty($decimals) ? 2 : $decimals;
        $object->$name = number_format($object->$name, $decimals, ',', '.');
    }
@endphp
    

<div class="form-group {{ $errors->has($field_key) ? ' is-invalid' : '' }}">
    @if (!empty($label)) 
        {{ Form::label($field_id, $label) }}
    @endif

    @if (!empty($input_group)) 
        <div class="input-group">

        @if (!empty($input_group['prepend_text'])) 
            <div class="input-group-prepend"><span class="input-group-text">{{ $input_group['prepend_text'] }}</span></div>
        @elseif (!empty($input_group['prepend_class']))
            <div class="input-group-prepend"><span class="input-group-text {{ $input_group['prepend_class'] }}"></span></div>
        @endif
    @endif

    @if ($type == 'textarea')
        {{ Form::textarea($field_name, old($field_key) ? old($field_key) : $object->$name, $options) }}
    @else
        @php
            $hidden = '';
            if ($type == 'checkbox' || $type == 'radio') {
                if (empty($options['disabled']) || $options['disabled'] !== true) {
                    $hidden = Form::hidden($field_name, '0');
                }
                if (!isset($options['checked'])) {
                    $options['checked'] = old($field_key) ? true : false;
                }
                $value = isset($options['value']) ? $options['checked'] : 1;
            } else {
                $value = '';
                if (!empty($object)) {
                    $value = $object->$name;
                }
                $value = old($field_key) || $errors->has($field_key) ? old($field_key) : $value;
            }
            if ($type === 'date') {
                $value = Helper::localize($value, 'Y-m-d');
            } elseif ($type === 'datetime') {
                $value = Helper::localize($value, 'Y-m-d h:i:s');
            }
            $input = Form::input($type, $field_name, $value, $options);
        @endphp

        {{ $hidden }}
        {{ $input }}
    @endif

    @if (!empty($input_group)) 
        @if (!empty($input_group['append_text'])) 
            <div class="input-group-append"><span class="input-group-text">{{ $input_group['append_text'] }}</span></div>
        @elseif (!empty($input_group['append_class']))
            <div class="input-group-append"><span class="input-group-text {{ $input_group['append_class'] }}"></span></div>
        @endif

        @if (!empty($input_group))
            @include('common/_input_error', ['validation_key' => $field_key])
        @endif

        </div>
    @endif

    @if (empty($input_group))
        @include('common/_input_error', ['validation_key' => $field_key])
    @endif

</div>
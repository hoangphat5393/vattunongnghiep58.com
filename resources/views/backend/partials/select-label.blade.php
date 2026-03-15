<div class="{!! isset($required) && $required ? 'required' : '' !!}
    field {!! isset($formGroupClass) ? implode(' ', $formGroupClass) : '' !!}">
    <label for="{{ $name }}">{{ $label }}</label>
    {{--<div class="ui sub header">{{ $label }}</div>--}}
    <!--Or user form-control class in select-->
    <select class="form-control {{ $class ?? '' }} {{ $type ?? '' }}"
            name="{{ $name }}" id="{{ $name }}" data-type="{{ $type ?? '' }}" data-child="{{ $child ?? '' }}">
        @if(isset($hasDefaultOption) && $hasDefaultOption)
            <option value="{{ isset($defaultValue) ? $defaultValue : '' }}">
                {{ isset($defaultLabel) ? $defaultLabel : __('Chọn') }}
            </option>
        @endif
        @if($options!='')
        @foreach($options as $index => $option)
            <option value="{{ $option->id }}"
                {!! old($name, isset($item) && $item ? $item : null) == $option->id ? 'selected' : '' !!}>
                {{ $option->name }}
            </option>
        @endforeach
        @endif
    </select>
</div>

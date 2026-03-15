@php
    $province_wmenu = \App\Models\Province::orderby('id')->get();
@endphp
@if (count($province_wmenu) > 0)
    @foreach ($province_wmenu as $province)
        <label for="province_{{ $province->id }}" class="form-group">
            <input type="checkbox" class="category_item_input" value="{{ $province->id }}" id="province_{{ $province->id }}">
            {{ $province->name }}
            <input type="hidden" class="item-name-{{ $province->id }}" value="{{ $province->name }}">
            <input type="hidden" class="item-url-{{ $province->id }}" value="{{ $province->slug }}">
        </label>
    @endforeach
@endif

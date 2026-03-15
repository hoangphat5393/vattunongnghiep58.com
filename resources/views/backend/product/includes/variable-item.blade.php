
@if(count($data)>0)
        <ul id="muti_menu_post" class="muti_menu_right_category">
    @foreach($data as $index => $item)
        @if($type=='checkbox')
            <li class="active category_menu_list  d-inline w-50 float-start">
                <label for="checkbox_{{ $item->id }}">
                    <input type="checkbox" class="category_item_input" name="variable[{{ $item->id }}][id]" value="{{ $item->id }}" id="checkbox_{{ $item->id }}" {{ in_array($item->id, $id_selected) ? 'checked' : '' }}>
                    {{ $item->name }}
                </label>
            </li>
        @else
                @php
                if(isset($variables_join) && $variables_join !='')
                    $desired_object = $variables_join->filter(function($join) use($item) {
                        return $join->variable_id == $item->id;
                    })->first();
                @endphp
                <li class="active category_menu_list">
                    <div class="row">
                        <label for="input{{ $index }}" class="col-sm-3 col-form-label">{{ $item->name }}</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="variable[{{ $item->id }}][id]" value="{{ $item->id }}">
                            <input type="text" class="form-control" id="input{{ $index }}" name="variable[{{ $item->id }}][description]" autocomplete="off" value="{{ $desired_object->description ?? '' }}">
                        </div>
                    </div>
                </li>
            
        @endif
    @endforeach
        </ul>   
@endif

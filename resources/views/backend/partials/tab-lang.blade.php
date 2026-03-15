@php
    $lang = $lang ?? '';
    $desc = $desc ?? '';
    if(isset($data) && $data != ''){
        $title_lang = 'name_'. $lang;
        $title_lang = $data->$title_lang;

        $title_sub_lang = 'titlesub_'. $lang;
        $title_sub_lang = $data->$title_sub_lang;

        $description = 'description_'. $lang;
        $description = $data->$description;
        
        $content = 'content_'. $lang;
        $content = $data->$content;
    }
@endphp
<div class="tab-pane fade" id="{{ $lang }}" role="tabpanel" aria-labelledby="{{ $lang }}-tab">
    <input type="hidden" name="tab_lang[]" value="en">
    <div class="form-group">
        <label for="name_{{ $lang }}">Title ({{ $lang }})</label>
        <input type="text" class="form-control" id="name_{{ $lang }}" name="name_{{ $lang }}" placeholder="Tiêu đề - {{ $lang }}" value="{{ $title_lang ?? '' }}">
    </div>

    @if(isset($sub))
    <div class="form-group">
        <label for="title_sub_{{ $lang }}">Tiêu đề phụ ({{ $lang }})</label>
        <input type="text" class="form-control title_slugify" id="title_sub_{{ $lang }}" name="titlesub_{{ $lang }}" placeholder="Tiêu đề" value="{{ $title_sub_lang ?? ''}}">
    </div>
    @endif

    @if($desc)
    <div class="form-group">
        <label for="post_description_{{ $lang }}">Description ({{ $lang }})</label>
        <textarea id="description_{{ $lang }}" name="description_{{ $lang }}">{!! $description ?? '' !!}</textarea>
    </div>
    @endif
    
    <div class="form-group">
        <label for="post_content_{{ $lang }}">Content ({{ $lang }})</label>
        <textarea id="content_{{ $lang }}" name="content_{{ $lang }}">{!! $content ?? '' !!}</textarea>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    editor('content_{{ $lang }}');
    editorQuote('description_{{ $lang }}');
</script>
@endpush

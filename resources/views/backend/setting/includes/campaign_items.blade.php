@php
    $campaign = \App\Models\Campaign::where('status', 1)->orderByDesc('sort')->get();
@endphp

@if (count($campaign) > 0)
    @foreach ($campaign as $item)
        <div class="form-group">
            <label for="category_{{ $item->id }}" class="">
                <input type="checkbox" class="category_item_input" value="{{ $item->id }}" id="category_{{ $item->id }}">
                {{ $item->name }}
                <input type="hidden" class="item-name-{{ $item->id }}" value="{{ $item->name }}">
                <input type="hidden" class="item-slug-{{ $item->id }}" value="{{ $item->slug }}">
                <input type="hidden" class="item-url-{{ $item->id }}" value="{{ route('campaign.detail', [$item->slug, $item->id]) }}">
                <input type="hidden" class="item-id-{{ $item->id }}" value="{{ $item->id }}">
                <input type="hidden" class="item-type-{{ $item->id }}" value="campaign">
            </label>
        </div>
    @endforeach
@endif

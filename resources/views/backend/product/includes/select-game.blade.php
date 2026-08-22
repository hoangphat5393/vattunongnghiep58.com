@php

    $game_id = $game_id ?? 0;

    $game = \App\Models\Game::where('status', 1)->get();

    $categories = '';

    $current_game_id = 0;

    $current_category_id = 0;

    $data_checks = App\Models\ShopProductCategory::where('product_id', $id)->first();

    if (!empty($data_checks)) {
        $current_game_id = $data_checks->game_id;

        $current_category_id = $data_checks->category_id;

        if (!empty($data_checks['game_id'])) {
            $db = App\Models\Game::select('*');

            $data_game = $db->where('id', $data_checks->game_id)->first();

            $categories = $data_game->shopcategory;
        }
    }

@endphp





<div class="form-group">

    <label for="game_id" class="title_txt col-form-label text-lg-right">Sản phẩm thuộc game</label>



    <select class="custom-select" id="game_id" name="game_id">

        <option value="">== Không có ==</option>

        @if ($game->count() > 0)

            @foreach ($game as $item)
                <option value="{{ $item->id }}" {{ $item->id == $current_game_id ? 'selected' : '' }}>
                    {{ $item->name }}</option>
            @endforeach

        @endif

    </select>

</div>



<div class="form-group">

    <label for="category_id" class="title_txt col-form-label text-lg-right">Chuyên mục</label>

    @if (!empty($categories))

        <select class="custom-select" id="category_id" name="category_id">

            <option value="">== Không có ==</option>

            @if ($categories->count() > 0)

                @foreach ($categories as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $current_category_id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach

            @endif

        </select>
    @else
        <select class="custom-select" id="category_id" name="category_id">

            <option value="">== Không có ==</option>

        </select>

    @endif

</div>

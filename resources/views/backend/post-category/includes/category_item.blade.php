@if (count($categories) > 0)
    @foreach ($categories as $item)
        <tr class="tr-item item-level-{{ isset($level) ? $level : 0 }}">
            <td class="text-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="post-cat-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                    <label for="post-cat-{{ $item->id }}"></label>
                </div>
            </td>

            <td class="text-center">
                <input type="number" id="sort-{{ $item->id }}" class="text-center form-control form-control-sm quick_change_value mx-auto" style="max-width: 65px;" data-id="{{ $item->id }}" data-column="sort" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
            </td>

            <td class="title">
                <div class="d-flex align-items-center">
                    @if ($level > 0)
                        <span class="text-muted font-monospace me-2" style="user-select: none;">{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level - 1) !!}└──</span>
                    @endif
                    <div>
                        <a class="text-decoration-none fw-semibold {{ $level == 0 ? 'text-primary' : 'text-dark' }}" href="{{ route('admin.post-category.edit', [$item->id]) }}">
                            <i class="{{ $level == 0 ? 'fa-solid fa-folder text-warning' : 'fa-regular fa-folder text-secondary' }} me-1"></i>
                            {{ $item->name }}
                        </a>
                        @if ($item->slug)
                            <small class="text-muted d-block ps-4" style="font-size: 0.78rem;">/{{ $item->slug }}</small>
                        @endif
                    </div>
                </div>
            </td>

            <td class="text-center">
                <div class="cat-thumb-box" style="width: 90px; height: 60px; max-width: 90px; max-height: 60px; overflow: hidden; display: inline-block;">
                    @if (!empty($item->image))
                        <img src="{{ get_image($item->image) }}" class="cat-thumb-img" alt="{{ $item->name }}" loading="lazy" style="width: 90px; height: 60px; object-fit: cover; display: block;">
                    @else
                        <div class="cat-thumb-placeholder" style="width: 90px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-regular fa-image fs-5"></i>
                        </div>
                    @endif
                </div>
            </td>

            <td class="text-center">
                <div class="form-check form-switch d-inline-flex align-items-center justify-content-center m-0">
                    <input class="form-check-input quick_change_value switch-status" type="checkbox" role="switch"
                        id="hot-{{ $item->id }}" data-column="hot" data-id="{{ $item->id }}"
                        data-model="{{ get_class($item) }}" value="1" value-off="0"
                        @checked($item->hot == 1) title="{{ $item->hot ? 'Đang nổi bật (Click để bỏ nổi bật)' : 'Không nổi bật (Click để đặt nổi bật)' }}"
                        style="cursor: pointer; width: 2.3rem; height: 1.25rem;">
                </div>
            </td>

            <td class="text-center">
                <div class="form-check form-switch d-inline-flex align-items-center justify-content-center m-0">
                    <input class="form-check-input quick_change_value switch-status" type="checkbox" role="switch"
                        id="status-{{ $item->id }}" data-column="status" data-id="{{ $item->id }}"
                        data-model="{{ get_class($item) }}" value="1" value-off="0"
                        @checked($item->status == 1) title="{{ $item->status ? 'Đang công khai (Click để chuyển bản nháp)' : 'Đang là bản nháp (Click để công khai)' }}"
                        style="cursor: pointer; width: 2.3rem; height: 1.25rem;">
                </div>
            </td>

            <td class="text-center text-muted" style="font-size: 0.84rem;">
                @php
                    $rawDate = $item->created_at ?: $item->updated_at;
                @endphp
                <div><i class="fa-regular fa-calendar-check me-1 text-secondary"></i>{{ $rawDate ? \Carbon\Carbon::parse($rawDate)->format('d/m/Y') : '-' }}</div>
                <small class="text-secondary">{{ $rawDate ? \Carbon\Carbon::parse($rawDate)->format('H:i:s') : '' }}</small>
            </td>

            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('admin.post-category.edit', [$item->id]) }}" class="btn btn-outline-primary" title="@lang('admin.Edit')">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('post-category', {{ $item->id }})" title="@lang('admin.Delete')">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </td>
        </tr>

        @php
            $visited = $visited ?? [];
            $children = (isset($childrenMap) && $item->id > 0 && !in_array($item->id, $visited, true))
                ? $childrenMap->get($item->id, collect())
                : collect();
            $nextVisited = array_merge($visited, [$item->id]);
        @endphp
        @if ($children->isNotEmpty())
            @include('backend.post-category.includes.category_item', [
                'categories' => $children,
                'level' => $level + 1,
                'childrenMap' => $childrenMap ?? collect(),
                'visited' => $nextVisited,
            ])
        @endif
    @endforeach
@endif

@if (count($pages) > 0)
    @foreach ($pages as $item)
        <tr>
            <td class="text-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="page-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                    <label for="page-{{ $item->id }}"></label>
                </div>
            </td>

            <td class="text-center">
                <input type="number" id="sort-{{ $item->id }}" class="form-control form-control-sm quick_change_value text-center mx-auto" style="max-width: 65px;" data-id="{{ $item->id }}" data-column="sort" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
            </td>

            <td>
                <a class="text-decoration-none fw-bold text-dark fs-6" href="{{ route('admin.page.edit', [$item->id]) }}">
                    <i class="fa-regular fa-file-lines text-primary me-1"></i>
                    {{ $item->name }}
                </a>
                @if ($item->slug)
                    <div class="mt-1">
                        <a class="text-decoration-none text-muted small" href="{{ route('page', ['slug' => $item->slug]) }}" target="_blank" title="Xem trên website">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i>/{{ $item->slug }}
                        </a>
                    </div>
                @endif
            </td>
            <td class="text-center" style="width: 110px; min-width: 110px; max-width: 110px;">
                <div class="cat-thumb-box" style="width: 90px; height: 60px; max-width: 90px; max-height: 60px; overflow: hidden; display: inline-block;">
                    @if (!empty($item->image))
                        <img src="{{ get_image($item->image) }}" class="cat-thumb-img" alt="{{ $item->name }}" loading="lazy" style="width: 90px; height: 60px; max-width: 90px; max-height: 60px; object-fit: cover; display: block;">
                    @else
                        <div class="cat-thumb-placeholder" style="width: 90px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-regular fa-file-lines fs-5"></i>
                        </div>
                    @endif
                </div>
            </td>
            <td class="text-center">
                @if ($item->user)
                    <span class="badge bg-light text-dark border">
                        <i class="fa-regular fa-user me-1 text-secondary"></i>{{ $item->user->name ?? $item->user->username ?? $item->user->fullname }}
                    </span>
                @else
                    <span class="badge bg-light text-secondary border">
                        <i class="fa-solid fa-user-shield me-1"></i>Hệ thống
                    </span>
                @endif
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
                <div><i class="fa-regular fa-calendar-check me-1 text-secondary"></i>{{ $item->created_at ? $item->created_at->format('d/m/Y') : ($item->updated_at ? $item->updated_at->format('d/m/Y') : '-') }}</div>
                <small class="text-secondary">{{ $item->created_at ? $item->created_at->format('H:i:s') : ($item->updated_at ? $item->updated_at->format('H:i:s') : '') }}</small>
            </td>
            <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('page', ['slug' => $item->slug]) }}" target="_blank" class="btn btn-outline-secondary" title="Xem trên website">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                    <a href="{{ route('admin.page.edit', [$item->id]) }}" class="btn btn-outline-primary" title="@lang('admin.Edit')">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('page', {{ $item->id }})" title="@lang('admin.Delete')">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
@endif

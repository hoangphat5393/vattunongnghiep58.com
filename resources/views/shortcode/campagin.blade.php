{{-- @php extract($data) @endphp --}}
@php
    // dd($projects);
    use Carbon\Carbon;
    Carbon::setLocale(app()->getlocale());

    // $project = \App\Campaign::limit($items)->paginate(6);

@endphp
@empty(!$projects)
    <div class="container">
        <div class="row">
            @foreach ($projects as $item)
                @php
                    $cdt = new Carbon($item->created_at);
                @endphp

                {{-- <div class="datetime text-end my-3">
                    <span>{{ $cdt->format('d-m-Y') }}</span>
                </div> --}}

                <div class="project__item mb-3">
                    <div class="row wrapper">
                        <div class="col-lg-4 mb-3 mb-lg-0">
                            <div class="voucher-item__value">
                                <img src="{{ get_image($item->image) }}" class="img-fluid w-100 project__img" alt="{{ $item->name }}">
                            </div>
                        </div>
                        <div class="col-lg-8 d-flex flex-column">
                            <div class="project-item__desc">
                                <h4 class="fw-bold">{{ $item->name }}</h4>
                                {!! htmlspecialchars_decode($item->description) !!}
                            </div>
                            <div class="project__item__button">
                                <a class="btn btn-custom me-3" href="{{ route('campaign.detail', [$item->slug, $item->id]) }}" role="button">
                                    Đọc thêm
                                </a>

                                <a class="btn btn-custom" href="{{ route('page', 'donate') }}">
                                    Tài trợ dự án này
                                </a>
                                {{-- <button type="button" class="btn btn-custom">
                                    Tài trợ dự án này
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="nav-pagination mt-4 mb-5">
                {{ $projects->links($templateFile . '.pagination.custom') }}
            </div>
        </div>
    </div>
@endempty

@php
    $download_files_en = $download_file_en ?? '';

    if ($download_files_en && $download_files_en != '""') {
        $download_files_en = json_decode($download_files_en, true);
        end($download_files_en); // move the internal pointer to the end of the array
        $key_index_en = key($download_files_en);
    }

    $download_files = $download_file ?? '';
    if ($download_files && $download_files != '""') {
        $download_files = json_decode($download_files, true);
        end($download_files); // move the internal pointer to the end of the array
        $key_index = key($download_files);
    }
@endphp

<div class="download_file">

    {{-- Tab pill --}}
    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="en-tab" data-toggle="pill" data-target="#pills-download-en" type="button" role="tab" aria-controls="pills-home" aria-selected="true">English</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vi-tab" data-toggle="pill" data-target="#pills-download-vi" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Vietnamese</button>
        </li>
    </ul>

    {{-- Tab content --}}
    <div class="tab-content" id="pills-tabContent">

        {{-- EN --}}
        <div class="tab-pane fade show active" id="pills-download-en" local="en" role="tabpanel" aria-labelledby="vi-tab">
            <div class="d-flex mb-2">
                <div class="col-5 border-bottom pb-1">File Name</div>
                <div class="col-5 border-bottom pb-1">File link</div>
                <div class="col-2 border-bottom pb-1"></div>
            </div>

            <div class="spec-short-clone" data="{{ $key_index_en ?? 0 }}" style="display: none;">
                <div class="form-group row group-item">
                    <div class="col-lg-4">
                        <input type="text" class="form-control spec-short-name" name="">
                    </div>
                    {{-- group_item_images --}}
                    <div class="col-lg-6">
                        <div class="group_item_images">
                            <div class="inside d-flex">
                                <div class="image-view mr-2">
                                    <img class="gallery-view" src="{{ asset('assets/images/icon/icon-uploader.jpg') }}" style="height: 38px;">
                                </div>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="gallery[]" id="gallery-input" value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="gallery-item">Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-danger w-100 text-center spec-remove">Xóa</button>
                    </div>
                </div>
            </div>

            <div class="spec-short-group">
                @if ($download_files_en != '' && is_array($download_files_en))

                    @foreach ($download_files_en as $index => $item)
                        <div class="form-group row group-item">
                            <div class="col-lg-4">
                                <input type="text" class="form-control spec-short-name" name="download_file_en[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}">
                            </div>

                            {{-- group_item_images --}}
                            <div class="col-lg-6">
                                <div class="group_item_images">
                                    <div class="inside d-flex">
                                        <div class="image-view mr-2">
                                            <img class="gallery-view-{{ $index }}" src="{{ asset('assets/images/icon/icon-uploader.jpg') }}" style="height: 38px;">
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="download_file_en[{{ $index }}][image]" id="gallery-input-{{ $index }}" value="{{ $item['image'] ?? '' }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="gallery-item-{{ $index }}" data="gallery-input-{{ $index }}" data-show="gallery-view-{{ $index }}">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-danger w-100 text-center spec-remove">Xóa</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="spec-btn text-right">
                <button type="button" class="btn btn-primary download_file-add">Thêm file</button>
            </div>
        </div>

        {{-- VN --}}
        <div class="tab-pane fade" id="pills-download-vi" role="tabpanel" local="" aria-labelledby="vi-tab">
            <div class="d-flex mb-2">
                <div class="col-5 border-bottom pb-1">Tên file</div>
                <div class="col-5 border-bottom pb-1">Đường dẫn</div>
                <div class="col-2 border-bottom pb-1"></div>
            </div>
            <div class="spec-short-clone" data="{{ $key_index ?? 0 }}" style="display: none;">
                <div class="form-group row group-item">
                    <div class="col-lg-4">
                        <input type="text" class="form-control spec-short-name" name="">
                    </div>

                    {{-- group_item_images --}}
                    <div class="col-lg-6">
                        <div class="group_item_images">
                            <div class="inside d-flex">
                                <div class="image-view mr-2">
                                    <img class="gallery-view" src="{{ asset('assets/images/icon/icon-uploader.jpg') }}" style="height: 38px;">
                                </div>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="gallery[]" id="gallery-input" value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="gallery-item">Upload</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="button" class="btn btn-danger w-100 text-center spec-remove">Xóa</button>
                    </div>
                </div>
            </div>

            <div class="spec-short-group">
                @if ($download_files != '' && is_array($download_files))

                    @foreach ($download_files as $index => $item)
                        <div class="form-group row group-item">
                            <div class="col-lg-4">
                                <input type="text" class="form-control spec-short-name" name="download_file[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}">
                            </div>

                            {{-- group_item_images --}}
                            <div class="col-lg-6">
                                {{-- <input type="text" class="form-control spec-short-desc" name="download_file[{{ $index }}][desc]" value="{!! $item['desc'] ?? '' !!}"> --}}
                                <div class="group_item_images">
                                    <div class="inside d-flex">
                                        <div class="image-view mr-2">
                                            <img class="gallery-view-{{ $index }}" src="{{ asset('assets/images/icon/icon-uploader.jpg') }}" style="height: 38px;">
                                        </div>
                                        <div class="input-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="download_file[{{ $index }}][image]" id="gallery-input-{{ $index }}" value="{{ $item['image'] ?? '' }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary ckfinder-popup" type="button" id="gallery-item-{{ $index }}" data="gallery-input-{{ $index }}" data-show="gallery-view-{{ $index }}">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-danger w-100 text-center spec-remove">Xóa</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="spec-btn text-right">
                <button type="button" class="btn btn-primary download_file-add">Thêm file</button>
            </div>
        </div>
    </div>

</div>


@push('scripts')
    <script>
        jQuery(document).ready(function($) {
            var download_file = $('.download_file');
            if (download_file.length > 0) {
                $(document).on('click', '.download_file .ckfinder-popup', function() {
                    var id = $(this).attr('id'),
                        input = $(this).attr('data'),
                        view_img = $(this).data('show');
                    selectFileWithCKFinder(input, view_img);
                })
                $('.download_file-add').click(function() {
                    var ele = $(this).parents('.tab-pane');
                    var local = ele.attr('local') ? '_' + ele.attr('local') : '';
                    var id = ele.find('.spec-short-clone').attr('data');

                    id = parseInt(id) + 1;
                    ele.find('.spec-short-clone').attr('data', id);

                    var html = ele.find('.spec-short-clone').find('.group-item').clone();

                    html.find('input.spec-short-name').attr('name', 'download_file' + local + '[' + id + '][name]');

                    html.find('#gallery-item').attr('id', 'gallery-item-' + id).attr('data', "gallery-input-" + id).attr('data-show', "gallery-view-" + id);
                    html.find('.gallery-view').attr('class', 'gallery-view-' + id);
                    html.find('#gallery-input').attr('id', 'gallery-input-' + id);
                    html.find('input[name="gallery[]"]').attr('name', 'download_file' + local + '[' + id + '][image]');
                    ele.find('.spec-short-group').append(html);
                });

                $(document).on('click', '.download_file .spec-remove', function() {
                    $(this).closest('.form-group').remove();
                });
            }
        });
    </script>
@endpush

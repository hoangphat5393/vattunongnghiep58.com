<div class="mb-4 card card-warning card-outline">

    <div class="card-header">

        <h3 class="card-title">Gallery</h3>

        <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">

                <i class="fas fa-minus"></i>

            </button>

        </div>

    </div> <!-- /.card-header -->

    <div class="card-body gallery_body">

        <!--Post Gallery-->

        <div class="gallery_box grabbable-parent" id="gallery_sort">

            @if (!empty($gallery_images))

                @foreach ($gallery_images as $index => $image)
                    <div class="gallery_item">

                        <div class="gallery_content">

                            <span class="remove"><i class="fa fa-times-circle" aria-hidden="true"></i></span>

                            <input type="hidden" name="gallery[]" value="{{ $image }}">

                            <img class="gallery-view{{ $index }}" src="{{ $image }}">

                        </div>

                    </div>
                @endforeach

            @endif

        </div>



        <div class="text-center">

            <button class="btn btn-outline-secondary btn-sm ckfinder-gallery" type="button">Chọn ảnh từ thư viện</button>

        </div>

    </div>

</div>

<!--End Post Gallery-->



@push('scripts-footer')
    <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {

            new Sortable(gallery_sort, {

                swapClass: 'highlight', // The class applied to the hovered swap item

                ghostClass: 'blue-background-class',

                animation: 150

            });

            $(document).on('click', '.gallery_item .remove', function() {

                $(this).closest('.gallery_item').remove();

            })

        });
    </script>
@endpush

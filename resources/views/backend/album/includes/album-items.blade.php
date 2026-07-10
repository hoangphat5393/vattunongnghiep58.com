{{-- Album table --}}

<form action="/" id="album_items" method="post">

    @if ($album_items)

        <div id="slider_list" class="col-lg-12 slider-list">

            @foreach ($album_items as $slider)
                <div data-id="{{ $slider->id }}" class="slider-item slider-{{ $slider->id }} row mb-2 pb-2 border-bottom align-items-center">

                    <input type="hidden" name="slider[]" value="{{ $slider->id }}">

                    <div class="col-lg-3 d-flex align-items-center">

                        <i class="fas fa-arrows-alt handle"></i>&emsp;

                        <div>

                            <img src="{{ get_image($slider->image) }}" class="img-fluid d-block mx-auto" style="min-height: 80px">

                        </div>

                    </div>

                    <div class="col-lg-3">

                        {{ $slider->name }}

                        {{-- |

                        {{ $slider->name_en }} --}}

                    </div>



                    <div class="col-lg-3">

                        {{ $slider->link_name }}

                        <br>

                        <a href="{{ $slider->link }}">{{ $slider->link }}</a>

                    </div>

                    <div class="col-md-3 d-flex justify-content-center">

                        <button type="button" class="btn btn-sm btn-warning edit-slider" data="{{ $slider->id }}" data-parent="{{ $slider->album_id }}"><i class="fa-regular fa-pen-to-square"></i></button>
                        <button type="button" class="btn btn-sm btn-danger delete-slider ms-2" data="{{ $slider->id }}"><i class="fa-regular fa-trash"></i></button>

                    </div>

                </div>
            @endforeach

        </div>

    @endif

</form>





@push('scripts')
    <script>
        // UPDATE SORT

        var sliderListEl = document.getElementById('slider_list');
        if (sliderListEl) {
            var sortable = new Sortable(sliderListEl, {
                // handle: '.handle', // handle's class

                // swap: true, // Enable swap plugin

                // swapClass: 'highlight', // The class applied to the hovered swap item

                animation: 150,

                onEnd: function(evt) {

                    var items = sortable.toArray(); // Lấy thứ tự các mục sau khi sắp xếp

                    updateOrder(items);

                }

            });
        }


        function updateOrder(items) {

            // Sử dụng Axios để gửi yêu cầu cập nhật thứ tự

            axios.post(`{{ route('admin.albumItem.update_sort') }}`, {
                    sort: items

                })

                .then(function(response) {

                    console.log('Order updated:', response.data);

                })

                .catch(function(error) {

                    console.error('Error updating order:', error);

                });

        }



        // DELETE ALBUM ITEM

        $(document).on("click", ".delete-slider", function() {

            var id = $(this).attr("data");



            if (id) {

                axios.delete(`{{ url('/admin/album_item') }}/${id}`)
                    .then((res) => {

                        $("#album_items").html(res.data.view);

                        // if (res.data.view != "") $(".slider-list").html(res.data.view);

                    })

                    .catch((e) => console.log(e));

            }

        });
    </script>
@endpush

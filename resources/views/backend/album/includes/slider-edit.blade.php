<!-- Modal -->
@if (isset($slider))
    <div class="modal fade" id="editSlider" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Thêm Slider</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-editSlider" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="slider_id" value="{{ $slider->slider_id }}">
                        <input type="hidden" name="id" value="{{ $slider->id }}">
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $slider->name }}">
                        </div>
                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" name="link" value="{{ $slider->link }}">
                        </div>
                        <div class="form-group">
                            <label for="order">STT</label>
                            <input id="order" type="text" name="order" class="form-control" value="{{ $slider->order }}">
                        </div>
                        <div class="form-group">
                            <div class="inserIMG">
                                <input type="hidden" name="src" id="src-img" value="{{ $slider->src ?? '' }}">
                                @if ($slider->src != '')
                                    <img src="{{ asset($slider->src) }}" id="show-img" class="show-img src-img ckfinder-popup" data-show="show-img" data="src-img" width="200" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';">
                                @else
                                    <img src="{{ asset('assets/images/placeholder.png') }}" id="show-img" class="src-img ckfinder-popup" data-show="show-img" data="src-img" width="200">
                                @endif
                                <span class="remove-icon" data-img="{{ asset('assets/images/placeholder.png') }}">X</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="description" id="description" class="form-control">{!! $slider->description !!}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="js-validation-messages-modal col-lg-12 small" role="alert"></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary insert-slider" data="edit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    $(document).ready(function($) {
        $('.ckfinder-popup').each(function(index, el) {
            var id = $(this).attr('id'),
                input = $(this).attr('data'),
                view_img = $(this).data('show');
            var button1 = document.getElementById(id);
            button1.onclick = function() {
                selectFileWithCKFinder(input, view_img);
            };
        });

        editorQuote('description');
    });
</script>

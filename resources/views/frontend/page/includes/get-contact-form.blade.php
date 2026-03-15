@php
    extract($data);
@endphp

<div class="modal login fade" id="getContactModal" tabindex="-1" role="dialog" aria-labelledby="getContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="getContactModalLabel">Thông tin liên hệ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-content-loading">
                    <div class="half-circle-spinner">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-2"></div>
                    </div>
                </div>
                <form id="customer-register" class="form-contact" role="form" method="POST" action="{{route('contact.submit')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="contact[url]" value="{{ $url_current ?? '' }}">
                    <input type="hidden" name="contact[product_title]" value="{{ $product_title ?? '' }}">
                    <div class="row mt-2 mb-5 align-items-center">
                        <div class="mb-3 col-sm-12">
                            <label for="name" class="control-label">Họ tên<span class="required">*</span></label>
                            <input id="name" type="text" class="form-control" placeholder="Họ tên" name="contact[name]" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                        </div>
                        <div class="mb-3 col-sm-12">
                            <label for="phone" class="control-label">Số điện thoại<span class="required">*</span></label>
                            <input id="phone" type="text" class="form-control" placeholder="Số điện thoại" name="contact[phone]" value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                        </div>
                        <div class="mb-3 col-sm-12">
                            <label for="email" class="control-label">Email<span class="required">*</span></label>
                            <input id="email" type="email" class="form-control" placeholder="Email" name="contact[email]" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                        </div>
                        <div class="mb-3 col-sm-12">
                            <label for="password" class="control-label">Lời nhắn<span class="required">*</span></label>
                            <textarea name="" class="form-control" rows="5"></textarea>
                        </div>
                        
                        <div class="mb-3 col-sm-12">
                            <div class="error-message"></div>
                        </div>
                        <div class="col-sm-12 text-center d-grid">
                            <button type="submit" class="btn btn-primary btn-register">Gửi yêu cầu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
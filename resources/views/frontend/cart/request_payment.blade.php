@extends($templatePath .'.layouts.index')

@section('seo')
@include($templatePath .'.layouts.seo', $seo??[] )
@endsection

@section('content')

    <div class="container py-lg-5 py-3">
        <h3 class="text-center mb-3">Xác nhận đã thanh toán đơn hàng</h3>
        <div class="row justify-content-center">
            <div class="col-lg-6 payment-page-info position-relative">
                @if($cart->cart_payment == 2)
                <form method="POST" action="{{ route('request_payment_success.post') }}" id="requestPaymentForm" class="needs-validation" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="request_payment" value="1">
                    <input type="hidden" name="cart_code" value="{{ $cart->cart_code }}">
                    <div class="list-content-loading">
                         <div class="half-circle-spinner">
                             <div class="circle circle-1"></div>
                             <div class="circle circle-2"></div>
                         </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            Vui lòng nhập đầy đủ thông tin yêu cầu đã thanh toán cho đơn hàng <a href="{{ route('cart.view', $cart->cart_code) }}">{{ $cart->cart_code }}</a>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="name" class="form-label">{{ __('Your name') }}</label>
                            <input required name="request_name" type="text" class="form-control" id="name" placeholder="{{ __('Your name') }}">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="email" class="form-label">{{ __('Email') }} (Không bắt buộc)</label>
                            <input name="request_email" type="email" class="form-control" id="email" placeholder="{{ __('Email') }}">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input required name="request_phone" type="phone" class="form-control" id="phone" placeholder="{{ __('Phone') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="messase" class="form-label">{{ __('Message') }}</label>
                        <textarea name="request_message" class="form-control" id="message" rows="3" placeholder="Nội dung xác nhận giao dịch
- Mã giao dịch chuyển khoản"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="messase" class="form-label">Hình ảnh giao dịch (không bắt buộc)</label>
                        <!-- Drag and drop file upload -->
                        <div class="file-drop-area">
                          <div class="file-drop-icon ci-cloud-upload"></div>
                          <span class="file-drop-message">Kéo thả hình ảnh vào đây</span>
                          <input type="file" name="request_file" class="file-drop-input">
                          <button type="button" class="file-drop-btn btn btn-primary btn-active btn-radius btn-sm">Hoặc bấm chọn file</button>
                        </div>
                    </div>

                    <div class="error-message" style="display: none;"></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-request-payment">Gửi xác nhận</button>
                    </div>
                </form>

                @else
                <div class="content_group_offer_view mt-3 pb-3 text-center">
                    <p><img src="{{ asset($templateFile .'/images/circle-icon.png') }}" width="120" alt=""></p>
                    <p>Đơn hàng của bạn đã được thanh toán</p>
                    <p><span>Quay về </span>
                        <a href="{{url('/')}}" style="color: rgb(255 153 51);">Trang chủ</a>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('after-footer')
<script>
    jQuery(document).ready(function($){
        var payment_form = $('#requestPaymentForm');
        payment_form.validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                'request_name': "required",
                'request_phone': "required",
            },
            messages: {
                'request_name': "Nhập tên của bạn",
                'request_phone': "Nhập số điện thoại của bạn",
            },
            errorElement : 'div',
            errorLabelContainer: '.errorTxt',
            invalidHandler: function(event, validator) {
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });

        $('.btn-request-payment').click(function(event) {
          if(payment_form.valid()){
              var form = document.getElementById('requestPaymentForm');
              var fdnew = new FormData(form);
              payment_form.find('.list-content-loading').show();
              axios({
                  method: 'POST',
                  url: "/send-request-payment-success",
                  data: fdnew,

              }).then(res => {
                 // console.log(res.data);
                  payment_form.find('.error-message').hide();
                  if (res.data.error == 0) {
                      $('.payment-page-info').html(res.data.view);
                  } 
                  else{
                      payment_form.find('.list-content-loading').hide();
                      payment_form.find('.error-message').show().html(res.data.msg);
                  }
              }).catch(e => console.log(e));
          }
        });
    })
</script>
@endpush
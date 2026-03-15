@extends($templatePath .'.layouts.index')

@section('content')
<div id="page-content" class="page-template page-register-content">
    
    <!--Page Title-->
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Create an Account</h1></div>
          </div>
    </div>
    <!--End Page Title-->
    
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
                <div class="mb-4">
                   <form method="post" action="{{route('postRegisterCustomer')}}" id="page-customer-register" accept-charset="UTF-8" class="contact-form">	
                        @csrf()
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="FirstName">First Name</label>
                                    <input type="text" name="firstname" placeholder="" id="FirstName" autofocus="">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="LastName">Last Name</label>
                                    <input type="text" name="lastname" placeholder="" id="LastName">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" placeholder="" id="phone">
                                </div>
                            </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="mb-3">
                                <label for="CustomerEmail">Email</label>
                                <input type="email" name="email" placeholder="" id="CustomerEmail" class="" autocorrect="off" autocapitalize="none" autofocus="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="mb-3">
                                <label for="CustomerPassword">Password</label>
                                <input type="password" value="" name="password" placeholder="" id="CustomerPassword" class="">                        	
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="mb-3 col-sm-12">
                            <div class="error-message"></div>
                        </div>
                        <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="button" class="btn mb-3 btn-register">@lang('Create')</button>
                        </div>
                     </div>
                 </form>
                </div>
               </div>
        </div>
    </div>
    
</div>

@endsection

@push('after-footer')
<script>
    jQuery(document).ready(function($) {
        $("#page-customer-register").validate({
          onfocusout: false,
          onkeyup: false,
          onclick: false,
          rules: {
              last_name: "required",
              phone: "required",
              email: "required",
              password: "required"
          },
          messages: {
                last_name: "@lang('Enter Last name')",
              phone: "Nhập số điện thoại",
              email: "Nhập địa chỉ E-mail",
              password : "Nhập mật khẩu"
          },
          errorElement : 'div',
          errorLabelContainer: '.errorTxt',
          invalidHandler: function(event, validator) {
              $('html, body').animate({
                  scrollTop: 0
              }, 500);
          }
        });
    $('.btn-register').click(function(event) {
        form_id = $('#page-customer-register');
        if(form_id.valid()){
        
            form_id.find('.list-content-loading').show();
            var form = document.getElementById('page-customer-register');
        
            var fdnew = new FormData(form);
         
            axios({
                   method: 'POST',
                   url: form_id.prop("action"),
               data: fdnew,

            }).then(res => {
                var url_back = '';

                if (res.data.error == 0) {
                    url_back = res.data.redirect_back;
                    $('.page-register-content').html(res.data.view);
                }
                else{
                    form_id.find('.error-message').html(res.data.msg);
                    form_id.find('.list-content-loading').hide();
                }
                
            }).catch(e => console.log(e));
        }
    });

    });
</script>
@endpush
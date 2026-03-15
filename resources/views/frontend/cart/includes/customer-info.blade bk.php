@php
    $states = \App\Models\Province::orderBy('name', 'ASC')->get();
    $fullname = $fullname ?? ($cart_info['ship_name'] ?? '');
    $firstname = $firstname ?? ($cart_info['firstname'] ?? '');
    $lastname = $lastname ?? ($cart_info['lastname'] ?? '');
    $email = $email ?? ($cart_info['ship_email'] ?? '');
    $phone = $phone ?? ($cart_info['ship_phone'] ?? '');
    $address = $address ?? ($cart_info['address_line1'] ?? '');
    $province = $province ?? ($cart_info['state_province'] ?? '');
    $cart_note = $cart_note ?? ($cart_info['cart_note'] ?? '');
    $user = auth()->user();
@endphp

<div id="customer-form">
    @if (!$user)
        {{-- CUSTOMER INFORMATION --}}
        <div class="block-content bdr">
            <div class="title-block-p2">CUSTOMER INFORMATION</div>
            <div class="body py-4 position-relative">
                <div class="signin_tab" style="display: none;">
                    <form method="post" action="{{ route('loginCustomerAction') }}" id="form-login-page">
                        @csrf
                        <input type="hidden" name="url_back" value="{{ url()->current() }}">
                        <div class="list-content-loading">
                            <div class="half-circle-spinner">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-2"></div>
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating form-floating-custom icon">
                                    <img class="form-icon" src="{{ asset($templateFile . '/images/email-icon.png') }}" alt="Email">
                                    <input type="text" class="form-control form-control-custom" id="cart_mail" name="email" placeholder="Your Email" value="{{ $email ?? '' }}">
                                    <label for="cart_mail" class="float-label-custom">Email</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating form-floating-custom icon">
                                    <img class="form-icon" src="{{ asset($templateFile . '/images/lock-icon.png') }}" alt="Phone">
                                    <input type="password" class="form-control form-control-custom" id="cart_password" name="password" placeholder="Password">
                                    <label for="cart_password" class="float-label-custom">Password</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <p class="mb-2">Don’t have account? <a href="#signup-modal" data-bs-toggle="modal">Sign Up</a></p>
                                <p class="mb-3">Don’t you remember password? <a href="{{ route('forgetPassword') }}" target="_blank">Forget password</a></p>
                                <button class="btn btn-custom btn-login-page" type="button">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="guest_tab">
                    <form method="post" action="{{ route('loginCustomerAction') }}" id="form-guest">
                        <input type="hidden" name="url_back" value="{{ url()->current() }}">

                        <div class="row">
                            {{-- <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating form-floating-custom icon">
                                            <img class="form-icon" src="{{ asset($templateFile . '/images/user-icon.png') }}" alt="Email">
                                            <input type="text" class="form-control form-control-custom" id="firstname" name="firstname" placeholder="First Name" value="{{ $firstname ?? '' }}">
                                            <label for="firstname" class="float-label-custom">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating form-floating-custom icon">
                                            <img class="form-icon" src="{{ asset($templateFile . '/images/user-icon.png') }}" alt="Email">
                                            <input type="text" class="form-control form-control-custom" id="lastname" name="lastname" placeholder="Last Name" value="{{ $lastname ?? '' }}">
                                            <label for="lastname" class="float-label-custom">Last Name</label>
                                        </div>
                                    </div>

                                </div>
                            </div> --}}

                            <div class="col-sm-10 mb-3">
                                <label for="ship_mail" class="float-label-custom">Email</label>
                                <input type="text" class="form-control form-control-custom" id="ship_email" name="email" placeholder="Your Email" value="{{ $cart_info['ship_email'] ?? '' }}">
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <select name="country" id="country" class="form-control country select-custom2">
                                            <option value=""> --- Choose Country--- </option>
                                            @isset($countries)
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->code }}" {{ !empty($cart_info['country']) && $cart_info['country'] == $country->code ? 'selected' : '' }}>{{ $country->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        {{-- <select name="state_province" id="state_province" class="form-control state_province select-custom2">
                                            <option value=""> --- Choose Province / City --- </option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->name }}" {{ !empty($cart_info['state_province']) && $cart_info['state_province'] == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-floating form-floating-custom icon">
                                            <input type="text" class="form-control form-control-custom" id="phone" name="phone" placeholder="Your Phone" value="{{ $cart_info['ship_phone'] ?? '' }}">
                                            <label for="phone" class="float-label-custom">Phone</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- <div class="row align-items-center mb-3">
                            <div class="col-sm-9">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="register_auto" id="register_auto">
                                    <label class="form-check-label" for="register_auto">
                                        Register instantly to become a Member, and enjoy 1% - 5% Discount (About VIP)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="send_password" id="send_password">
                                    <label class="form-check-label" for="send_password">
                                        Just send me the password in email
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="check_agree" id="check_agree">
                                            <label class="form-check-label" for="check_agree">
                                                By continuing you agree to <a href="{{ url('/terms-of-service') }}" target="_blank">CNL’s Terms of Service</a> and <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy Policy</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-custom cont-check" type="button">Cont Checkout</button>
                                    </div>
                                </div>
                                <div class="error-message error" style="display: none;"></div>
                            </div>
                        </div> --}}
                    </form>
                </div>

            </div>
        </div>
    @else
        <div class="block-content bdr">
            <div class="title-block-p2">CUSTOMER INFORMATION</div>
            <div class="body py-4">
                <p class="block-content__welcome mb-3">
                    Hello {{ $user->fullname }}, well come back!
                    <br><br>
                    You can continue to check out since we have trust from each other
                </p>

                <!-- <div class="d-flex justify-content-between align-items-center"> -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="check_agree" id="check_agree">
                    <label class="form-check-label" for="check_agree">
                        By continuing you agree to <a href="{{ url('/terms-of-service') }}" target="_blank">CNL’s Terms of Service</a> and <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy Policy</a>
                    </label>
                </div>
                <!-- <button class="btn btn-checkout">Cont Checkout</button> -->
                <!-- </div> -->
            </div>
        </div>
    @endif
</div>

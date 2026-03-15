<div class="block-content bdr">
    <div class="title-block-p2">CUSTOMER INFORMATION</div>
    <div class="body py-4">
        <p class="block-content__welcome mb-3">
            Hello {{ $user->fullname }}, well come back!
            <br><br>
            You can continue to check out since we have trust from each other
        </p>

        <div class="d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked3">
                <label class="form-check-label" for="flexCheckChecked3">
                    By continuing you agree to CNL’s <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>
            <!-- <button class="btn btn-checkout">Cont Checkout</button> -->
        </div>
    </div>
</div>
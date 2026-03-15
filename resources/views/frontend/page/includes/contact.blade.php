<form id="form-tuvan" method="post" action="">
    <input type="hidden" name="type" value="tuvan">
    <div class="list-content-loading">
        <div class="half-circle-spinner">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div>Họ tên (*)<br />
                <p class="form-control-wrap name">
                    <input type="text" name="name" value="" size="40" class="form-control text validates-as-required" />
                </p>
            </div>
        </div>
        <div class="col-sm-6">
            <div>Email (*)<br />
                <p class="form-control-wrap your-email">
                    <input type="email" name="email" value="" size="40" class="form-control text email validates-as-required validates-as-email" />
                </p>
            </div>
        </div>
        <div class="col-sm-12">
            <div>Số điện thoại (*)<br />
                <p class="form-control-wrap phone">
                    <input type="text" name="phone" value="" size="40" class="form-control text validates-as-required validates-as-phone" />
                </p>
            </div>
        </div>
        <div class="col-sm-12">
            <div>Vấn đề da của bạn<br />
                <p class="form-control-wrap your-message">  
                    <textarea name="your_message" cols="40" rows="4" class="form-control textarea"></textarea>
                </p>
            </div>
        </div>
    </div>
    <input type="button" value="@lang('Tư vấn ngay')" class="form-control btn-info submit tuvan-submit" />
</form>
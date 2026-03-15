<ul class="list-group mb-3">
    <li class="list-group-item">
        <div class="custom-control custom-radio">
            <input type="radio" id="delivery_ship" name="delivery" value="shipping" class="custom-control-input" checked>
            <label class="custom-control-label" for="delivery_ship">Giao hàng nhanh</label>
            <div class="ship-content" style="display: none;">
                - Giá ship thay đổi tùy thời điểm, nhân viên sẽ liên hệ với quý khách để xác nhận.
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="custom-control custom-radio">
            <input type="radio" id="delivery_pickup" name="delivery" value="pick_up" class="custom-control-input">
            <label class="custom-control-label" for="delivery_pickup">@lang('Pick up')</label>
        </div>
    </li>
</ul>

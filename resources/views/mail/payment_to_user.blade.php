<p>Hi {{$firstname}},</p>

<p><b>Bạn đã thanh toán thành công đơn hàng: {{ $cart_id }}</b></p>

<p>Order ID: {{ $cart_id }}</p>
@if($firstname)
<p>Name: {{$firstname }} {{$lastname ?? '' }} </p>
@endif
<p>Số điện thoại: {{ $cart_phone }}</p>
<p>E-mail: {{ $cart_email }}</p>
<p>Ngày thanh toán: {{ $updated_at }}</p>
<p>Tổng thanh toán: {{ render_price($cart_total) }}</p>

<p>Nếu đơn hàng của bạn vẫn chưa được xử lý</p>
<p>vui lòng liên hệ:</p>
<div>
	{!! htmlspecialchars_decode(setting_option('pickup_address')) !!}
</div>

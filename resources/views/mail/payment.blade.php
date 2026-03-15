Hi {{ setting_option('email_admin)') }},
<br>
<b>Thông tin khách hàng thanh toán đơn hàng: {{ $cart_id }}</b>

<p>Order ID: {{ $cart_id }}</p>
@if($firstname)
<p>Name: {{$firstname }} {{$lastname ?? '' }} </p>
@endif
<p>Số điện thoại: {{ $cart_phone }}</p>
<p>E-mail: {{ $cart_email }}</p>
<p>Ngày thanh toán: {{ $updated_at }}</p>


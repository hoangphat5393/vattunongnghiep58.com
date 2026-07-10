<div class="content_element customer-menu">
    <div class="support-icon">
        <a href="{{ route('customer.dashboard') }}" class="{{ request()->segment(1) == 'account' && request()->segment(2) == null ? 'active' : '' }}">
            <i class="fa fa-map-marker"></i>
            Tài khoản
        </a>
    </div>
    <div class="support-icon">
        <a href="{{ route('customer.profile') }}" class="{{ request()->segment(2) == 'profile' ? 'active' : '' }}">
            <i class="fa fa-phone"></i>
            Thông tin tài khoàn
        </a>
    </div>
    <div class="support-icon">
        <a href="{{ route('customer.orders.index') }}" class="{{ request()->segment(2) == 'orders' ? 'active' : '' }}">
            <i class="fa fa-envelope-o"></i>
            Đơn hàng của tôi
        </a>
    </div>
    <div class="support-icon">
        <a href="{{ route('post.notice') }}" class="{{ request()->segment(1) == 'thong-bao' ? 'active' : '' }}">
            <i class="fa fa-bell"></i>
            Thông báo
        </a>
    </div>
    {{-- <div class="support-icon">
		<a href="{{route('customer.changePassword')}}">
			<i class="fa fa-bell"></i>
			Đổi mật khẩu
		</a>
	</div> --}}
    <div class="support-icon">
        <form method="post" action="{{ route('customer.logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="border-0 bg-transparent p-0 text-start">
                <i class="fa fa-reply"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</div>

{{--
	<ul class="menu-cus">
		<li><a href="{{route('index')}}/customer">Tài khoản</a></li>
		<li><a href="{{route('customer.profile')}}">Thông tin tài khoàn</a></li>
		<!-- <li><a href="{{route('customer.wishlist')}}">Address Book</a></li> -->
		<li><a href="{{route('customer.my-orders')}}">Đơn hàng của tôi</a></li>
		<li><a href="{{route('customer.wishlist')}}">Danh sách yêu thích</a></li>
		<!-- <li><a href="{{route('customer.reviews')}}">Danh sách đánh giá</a></li> -->
		<li><a href="{{route('customer.changePassword')}}">Đổi mật khẩu</a></li>
		<li><a href="{{route('customer.logout')}}">Đăng xuất</a></li>
	</ul>
--}}

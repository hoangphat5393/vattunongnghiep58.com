<div class="flex flex-col items-center text-center gap-4">
    <div class="w-16 h-16 rounded-full bg-leaf-100 text-leaf-600 flex items-center justify-center">
        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">
        Đăng ký thành công
    </h1>

    @auth
        <p class="text-gray-700 font-semibold">
            Chào mừng {{ Auth::user()->fullname ?? Auth::user()->email }}!
        </p>
        <p class="text-gray-600 leading-relaxed max-w-2xl">
            Tài khoản của bạn đã được kích hoạt. Chúng tôi đã gửi email xác nhận đến
            <span class="font-semibold text-gray-800">{{ Auth::user()->email }}</span>.
            Bạn có thể tiếp tục mua sắm hoặc quản lý đơn hàng trong khu vực tài khoản.
        </p>
    @else
        <p class="text-gray-600 leading-relaxed max-w-2xl">
            Tài khoản của bạn đã được tạo thành công. Vui lòng đăng nhập để tiếp tục.
        </p>
    @endauth
</div>

<div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
    <a href="{{ route('index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold bg-leaf-600 text-white hover:bg-leaf-700 transition">
        Về trang chủ
    </a>
    @auth
        <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold border-2 border-gray-200 text-gray-800 hover:border-leaf-300 hover:bg-leaf-50 transition">
            Vào tài khoản
        </a>
    @else
        <a href="{{ route('customer.login') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold border-2 border-gray-200 text-gray-800 hover:border-leaf-300 hover:bg-leaf-50 transition">
            Đăng nhập
        </a>
    @endauth
</div>

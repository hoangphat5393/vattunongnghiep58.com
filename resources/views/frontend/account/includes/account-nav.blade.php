@php
    $currentRoute = request()->route()?->getName();
@endphp

<nav class="rounded-2xl border border-leaf-100 bg-white p-4 shadow-sm" aria-label="Tài khoản">
    <p class="mb-3 px-2 text-xs font-bold uppercase tracking-wide text-gray-400">Tài khoản</p>
    <ul class="space-y-1 text-sm font-semibold">
        <li>
            <a href="{{ route('customer.profile') }}" class="block rounded-xl px-3 py-2 no-underline transition {{ $currentRoute === 'customer.profile' ? 'bg-leaf-50 text-leaf-700' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-700' }}">
                Thông tin cá nhân
            </a>
        </li>
        <li>
            <a href="{{ route('customer.orders.index') }}" class="block rounded-xl px-3 py-2 no-underline transition {{ str_starts_with((string) $currentRoute, 'customer.orders') ? 'bg-leaf-50 text-leaf-700' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-700' }}">
                Đơn hàng của tôi
            </a>
        </li>
        <li>
            <a href="{{ route('customer.password.edit') }}" class="block rounded-xl px-3 py-2 no-underline transition {{ in_array($currentRoute, ['customer.password.edit', 'customer.password.update'], true) ? 'bg-leaf-50 text-leaf-700' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-700' }}">
                Đổi mật khẩu
            </a>
        </li>
        <li class="pt-2 border-t border-gray-100">
            <form method="post" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl px-3 py-2 text-left text-gray-700 transition hover:bg-red-50 hover:text-red-600 border-0 bg-transparent font-semibold text-sm cursor-pointer">
                    Đăng xuất
                </button>
            </form>
        </li>
    </ul>
</nav>

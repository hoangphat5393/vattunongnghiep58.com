@php
    $headerMenu = \App\Models\Frontend\Menu::byName('Menu-main');
    $currentUrl = url()->current();
@endphp

<header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-leaf-100">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <button type="button" id="mobile-menu-btn" class="md:hidden text-leaf-700 focus:outline-none" aria-controls="mobile-menu" aria-label="Mở menu">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
            </button>

            <a href="{{ route('index') }}" class="flex shrink-0 items-center gap-2 group">
                <img src="{{ get_image(setting_option('logo') ?: 'upload/images/logo/logo.png') }}" alt="Vật Tư 58" class="h-20 w-auto object-contain" />
                <div class="flex flex-col">
                    <span class="text-2xl font-extrabold text-leaf-700 leading-none group-hover:text-leaf-500 transition">
                        Vật Tư 58
                    </span>
                    <span class="text-xs font-semibold text-leaf-500 tracking-widest uppercase">
                        Nông Nghiệp Sạch
                    </span>
                </div>
            </a>

            <div class="hidden md:flex flex-1 max-w-lg mx-8 relative">
                <form action="{{ route('search') }}" method="get" class="w-full">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm kiếm hạt giống, phân bón..." class="w-full pl-10 pr-4 py-2 rounded-full border-2 border-leaf-100 focus:border-leaf-500 focus:outline-none bg-leaf-50/50" />
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <button type="submit" class="hidden" aria-hidden="true"></button>
                </form>
            </div>

            <div class="flex shrink-0 items-center gap-4">
                @if (\Illuminate\Support\Facades\Route::has('login'))
                    <a href="{{ route('login') }}" class="hidden md:block font-bold text-leaf-700 hover:text-leaf-500">
                        Đăng nhập
                    </a>
                @endif
                <a href="{{ route('cart') }}" class="relative bg-leaf-100 p-2 rounded-full text-leaf-700 hover:bg-leaf-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="absolute top-0 right-0 w-3 h-3 bg-orange-500 rounded-full border-2 border-white" id="CartCountDot">
                    </span>
                </a>
            </div>
        </div>

        <nav class="hidden md:flex justify-center mt-4 gap-8 font-bold text-gray-600">
            @if ($headerMenu)
                @foreach ($headerMenu->items as $item)
                    @php
                        $itemUrl = $item->link;
                        $isActive = $currentUrl === $itemUrl;
                    @endphp
                    <a href="{{ $itemUrl }}" class="{{ $isActive ? 'text-leaf-600 border-b-2 border-leaf-500' : 'hover:text-leaf-600 transition' }}">
                        {{ $item->label }}
                    </a>
                @endforeach
            @endif
        </nav>
    </div>
</header>

{{-- Fixed layers must NOT live inside <header> with backdrop-blur: Chrome/WebKit composites children incorrectly (transparent panel, stray black overlay). --}}
<div id="mobile-menu-overlay" class="fixed inset-0 hidden bg-black/50 transition-opacity duration-300" aria-hidden="true"></div>

<div id="mobile-menu" class="fixed top-0 left-0 flex h-full w-64 -translate-x-full transform bg-white shadow-2xl transition-transform duration-300 ease-in-out md:hidden" aria-modal="true" role="dialog">
    <div class="flex h-full w-full flex-col p-5">
        <div class="mb-8 flex items-center justify-between border-b border-gray-200 pb-4">
            <span class="text-xl font-bold text-leaf-700">Menu</span>
            <button type="button" id="close-menu-btn" class="rounded-md border-0 bg-transparent p-1 text-gray-500 transition hover:text-red-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="flex flex-col space-y-4 font-bold text-gray-600">
            @if ($headerMenu)
                @foreach ($headerMenu->items as $item)
                    @php
                        $itemUrl = $item->link;
                        $isActive = $currentUrl === $itemUrl;
                    @endphp
                    <a href="{{ $itemUrl }}" class="block rounded-md px-2 py-2 font-bold no-underline transition {{ $isActive ? 'bg-leaf-50 text-leaf-600' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-600' }}">
                        {{ $item->label }}
                    </a>
                @endforeach
            @endif
        </div>

        <div class="mt-auto border-t border-gray-200 pt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ setting_option('webtitle') }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('mobile-menu-btn');
            var menu = document.getElementById('mobile-menu');
            var overlay = document.getElementById('mobile-menu-overlay');
            var closeBtn = document.getElementById('close-menu-btn');

            if (!btn || !menu || !overlay || !closeBtn) {
                return;
            }

            var openMenu = function() {
                overlay.classList.remove('hidden');
                setTimeout(function() {
                    menu.classList.remove('-translate-x-full');
                }, 10);
            };

            var closeMenu = function() {
                menu.classList.add('-translate-x-full');
                setTimeout(function() {
                    overlay.classList.add('hidden');
                }, 300);
            };

            btn.addEventListener('click', openMenu);
            closeBtn.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);
        });
    </script>
@endpush

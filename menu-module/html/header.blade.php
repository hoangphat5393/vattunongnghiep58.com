@php
    $headerMenu = \App\Models\Frontend\Menu::byName('Menu-main');
    $currentUrl = url()->current();
@endphp

<header class="overflow-visible bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-leaf-100">
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
                @if (\Illuminate\Support\Facades\Route::has('customer.login'))
                    <a href="{{ route('customer.login') }}" class="hidden md:block font-bold text-leaf-700 hover:text-leaf-500">
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

        <nav class="hidden justify-center gap-6 pt-1 md:flex md:flex-wrap" aria-label="Menu chính">
            @if ($headerMenu)
                @foreach ($headerMenu->items as $item)
                    @php
                        $itemUrl = $item->link;
                        $hasChildren = $item->child->isNotEmpty();
                        $isChildActive = $hasChildren && $item->child->contains(fn($child) => $currentUrl === $child->link);
                        $isActive = $currentUrl === $itemUrl || $isChildActive;
                    @endphp
                    @if ($hasChildren)
                        <div class="group relative font-bold text-gray-600" data-nav-dropdown>
                            <div class="flex items-center gap-0.5">
                                <a href="{{ $itemUrl }}" class="inline-flex items-center border-b-2 border-transparent pb-1 no-underline transition {{ $isActive ? 'border-leaf-500 text-leaf-600' : 'text-gray-600 hover:text-leaf-600' }}">
                                    {{ $item->label }}
                                </a>
                                <button type="button" class="nav-desktop-dropdown-toggle rounded p-1 text-leaf-700 transition hover:bg-leaf-50 hover:text-leaf-600 focus:outline-none" data-nav-dropdown-toggle aria-expanded="false" aria-haspopup="true" aria-controls="nav-sub-{{ $item->id }}" aria-label="Mở menu con {{ $item->label }}">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div id="nav-sub-{{ $item->id }}" role="menu"
                                class="invisible absolute left-0 top-full z-[70] mt-0.5 min-w-48 origin-top scale-95 rounded-lg border border-leaf-100 bg-white py-2 opacity-0 shadow-lg transition duration-150 ease-out group-hover:visible group-hover:scale-100 group-hover:opacity-100 group-[.is-open]:visible group-[.is-open]:scale-100 group-[.is-open]:opacity-100">
                                @foreach ($item->child as $child)
                                    @php
                                        $childUrl = $child->link;
                                        $childActive = $currentUrl === $childUrl;
                                    @endphp
                                    <a href="{{ $childUrl }}" role="menuitem" class="block px-4 py-2 text-sm font-bold no-underline transition {{ $childActive ? 'bg-leaf-50 text-leaf-600' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-600' }}">
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $itemUrl }}" class="inline-flex items-center border-b-2 border-transparent pb-1 font-bold no-underline transition {{ $isActive ? 'border-leaf-500 text-leaf-600' : 'text-gray-600 hover:text-leaf-600' }}">
                            {{ $item->label }}
                        </a>
                    @endif
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
        <div class="flex flex-col space-y-2 font-bold text-gray-600">
            @if ($headerMenu)
                @foreach ($headerMenu->items as $item)
                    @php
                        $itemUrl = $item->link;
                        $hasChildren = $item->child->isNotEmpty();
                        $isChildActive = $hasChildren && $item->child->contains(fn($child) => $currentUrl === $child->link);
                        $isActive = $currentUrl === $itemUrl || $isChildActive;
                    @endphp
                    @if ($hasChildren)
                        <details class="nav-mobile-details rounded-md border border-gray-100 bg-white">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-2 py-2 font-bold text-gray-800 marker:hidden [&::-webkit-details-marker]:hidden">
                                <span class="{{ $isActive ? 'text-leaf-600' : '' }}">{{ $item->label }}</span>
                                <svg class="nav-mobile-details-chevron h-4 w-4 shrink-0 text-leaf-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>
                            <div class="space-y-1 border-t border-gray-100 px-2 pb-2 pt-1">
                                <a href="{{ $itemUrl }}" class="block rounded-md px-2 py-1.5 text-sm font-bold no-underline text-leaf-700 transition hover:bg-leaf-50">
                                    Tất cả {{ $item->label }}
                                </a>
                                @foreach ($item->child as $child)
                                    @php
                                        $childUrl = $child->link;
                                        $childActive = $currentUrl === $childUrl;
                                    @endphp
                                    <a href="{{ $childUrl }}" class="block rounded-md px-2 py-1.5 text-sm font-bold no-underline transition {{ $childActive ? 'bg-leaf-50 text-leaf-600' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-600' }}">
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </details>
                    @else
                        <a href="{{ $itemUrl }}" class="block rounded-md px-2 py-2 font-bold no-underline transition {{ $isActive ? 'bg-leaf-50 text-leaf-600' : 'text-gray-700 hover:bg-leaf-50 hover:text-leaf-600' }}">
                            {{ $item->label }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>

        <div class="mt-auto border-t border-gray-200 pt-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} {{ setting_option('webtitle') }}
        </div>
    </div>
</div>

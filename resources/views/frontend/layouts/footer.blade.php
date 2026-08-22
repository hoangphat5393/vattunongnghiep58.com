@php
    $footerMenu = \App\Models\Frontend\Menu::where('name', 'Menu-footer')->first();
@endphp

<!-- Footer -->
<footer class="bg-leaf-900 text-white pt-16 pb-8 rounded-t-[3rem] mt-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <img src="{{ get_image(setting_option('logo')) }}" alt="Logo" class="h-10 w-auto bg-white rounded-lg p-1 object-contain" />
                    <span class="text-2xl font-bold">Vật Tư 58</span>
                </div>
                <p class="text-leaf-200 mb-6">
                    Đồng hành cùng nhà nông Việt trên mọi nẻo đường. Chất lượng tạo nên uy tín vững bền.
                </p>
                <h3 class="font-bold text-lg mb-3">@lang('admin.social_networks')</h3>
                <div class="flex gap-4">
                    @if(setting_option('facebook'))
                        <a href="{{ setting_option('facebook') }}" rel="nofollow" target="_blank" class="mr-2">
                            <img src="{{ asset('assets/images/social/facebook.png') }}" alt="Facebook" style="width: 30px; height: 30px; object-fit: contain;">
                        </a>
                    @endif
                    @if(setting_option('zalo') || setting_option('phone'))
                        <a href="https://zalo.me/{{ str_replace([' ', '.'], '', setting_option('zalo', setting_option('phone'))) }}" title="{{ str_replace(' ', '', setting_option('phone', '0938.133.830')) }}" target="_blank">
                            <img src="{{ asset('assets/images/social/zalo-icon.png') }}" alt="Zalo" class="img-fluid" style="width: 30px; height: 30px; object-fit: contain;">
                        </a>
                    @endif
                    @if(setting_option('youtube'))
                        <a href="{{ setting_option('youtube') }}" rel="nofollow" target="_blank">
                            <img src="{{ asset('assets/images/social/youtube.png') }}" alt="Youtube" style="width: 30px; height: 30px; object-fit: contain;">
                        </a>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">Thông tin liên hệ</h3>
                <ul class="space-y-4 text-leaf-200">
                    <li class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-leaf-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>58A Tô Ngọc Vân, Phường Thạnh Xuân, Quận 12. TP Hồ Chí Minh</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-leaf-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>0932 009 180</span>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">Đăng ký nhận tin</h3>
                <form class="space-y-3">
                    <input type="email" placeholder="Email của bạn" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-leaf-700 focus:outline-none focus:bg-white/20 text-white placeholder-leaf-400" />
                    <button class="w-full py-3 bg-leaf-500 text-white font-bold rounded-xl hover:bg-leaf-600 transition">
                        Đăng ký ngay
                    </button>
                </form>
            </div>
        </div>
        <div class="border-t border-leaf-800 pt-8 text-center text-leaf-400 text-sm">
            <p>&copy; 2021 CỬA HÀNG VẬT TƯ NÔNG NGHIỆP 58. All rights reserved. Design by GetAtZ</p>
        </div>
    </div>
</footer>

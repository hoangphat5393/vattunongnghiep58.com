@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    @php
        $user = $user ?? auth()->user();
        $avatarFallback = asset('assets/images/default-avatar.svg');
        $avatarUrl = $user->avatar ? asset($user->avatar) : $avatarFallback;
    @endphp

    <div class="container mx-auto px-4 py-6 md:py-10">
        <div class="mb-6 text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('index') }}" class="hover:text-leaf-600 no-underline">Trang chủ</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Thông tin tài khoản</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <aside class="lg:col-span-3">
                @include($templatePath . '.account.includes.account-nav')
            </aside>

            <div class="lg:col-span-9">
                <div class="rounded-2xl border border-leaf-100 bg-white p-5 md:p-8 shadow-sm">
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6">Thông tin cá nhân</h1>

                    @if (session('success'))
                        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="post" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div class="flex items-center gap-6 pb-2">
                            <div class="relative group cursor-pointer shrink-0" onclick="document.getElementById('avatar_upload').click()" title="Nhấn để đổi ảnh đại diện">
                                <img id="avatar_preview" src="{{ $avatarUrl }}" alt="Ảnh đại diện tài khoản {{ $user->fullname }}" class="h-24 w-24 rounded-full object-cover border-2 border-leaf-200 shadow-md group-hover:opacity-90 transition" onerror="this.src='{{ $avatarFallback }}'">
                            </div>

                            <div>
                                <label class="block text-base font-bold text-gray-900 mb-1.5">Ảnh đại diện</label>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <button type="button" onclick="document.getElementById('avatar_upload').click()" class="cursor-pointer inline-flex items-center gap-2 rounded-xl bg-leaf-50 px-4 py-2.5 text-sm font-semibold text-leaf-700 hover:bg-leaf-100 transition border border-leaf-200 shadow-sm">
                                        <svg class="w-4 h-4 text-leaf-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Chọn ảnh mới
                                    </button>
                                    <span id="file_name_display" class="text-xs text-gray-500 italic">Chưa chọn ảnh mới</span>
                                </div>
                                <input type="file" name="avatar_upload" id="avatar_upload" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                                <p class="text-xs text-gray-400 mt-1.5">Hỗ trợ các định dạng JPG, PNG, GIF, WEBP</p>
                            </div>
                        </div>

                        <div>
                            <label for="fullname" class="block text-sm font-semibold text-gray-700 mb-1.5">Họ tên <span class="text-red-500">*</span></label>
                            <input type="text" name="fullname" id="fullname" value="{{ old('fullname', $user->fullname) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                            <input type="email" value="{{ $user->email }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-500" readonly disabled>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Địa chỉ</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition">
                        </div>

                        <button type="submit" class="cursor-pointer rounded-xl bg-leaf-600 px-6 py-3 font-bold text-white hover:bg-leaf-700 transition">
                            Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar_preview');
                    if (preview) {
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);

                const fileNameDisplay = document.getElementById('file_name_display');
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.classList.remove('text-gray-500', 'italic');
                    fileNameDisplay.classList.add('text-leaf-700', 'font-bold');
                }
            }
        }
    </script>
@endpush

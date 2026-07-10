@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    @php
        $user = $user ?? auth()->user();
        $avatarUrl = $user->avatar ? asset($user->avatar) : asset('assets/images/no-image.jpg');
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

                        <div class="flex items-center gap-4">
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="h-16 w-16 rounded-full object-cover border border-gray-200">
                            <div>
                                <label for="avatar_upload" class="block text-sm font-semibold text-gray-700 mb-1">Ảnh đại diện</label>
                                <input type="file" name="avatar_upload" id="avatar_upload" accept="image/*" class="text-sm text-gray-600">
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

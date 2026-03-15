@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@php
@endphp

@section('content')
    <main id="about">
        @include('frontend.includes.menu')

        <div class="bg-leaf-50 font-sans text-gray-800 relative overflow-hidden">
            <div class="absolute bg-green-200 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2 opacity-30 blur-[40px] -z-10">
            </div>

            <div class="container mx-auto px-4 py-4">
                <div class="text-sm text-gray-500 flex items-center gap-2">
                    <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                    <span>/</span>
                    <span class="text-leaf-700 font-bold">Giới thiệu</span>
                </div>
            </div>

            <section class="py-12 md:py-20">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row items-center gap-12">
                        <div class="md:w-1/2">
                            <span class="text-leaf-600 font-bold uppercase tracking-widest text-sm mb-2 block">Về chúng
                                tôi</span>
                            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                                Đồng hành cùng sự phát triển
                                <span class="text-leaf-600">bền vững</span> của nông nghiệp
                            </h1>
                            <p class="text-lg text-gray-600 mb-6 leading-relaxed">Chuyên cung cấp các sản phẩm chất lượng cho
                                sự
                                phát triển bền vững của ngành nông nghiệp. Với hơn 20 năm kinh nghiệm trong lĩnh vực kinh
                                doanh
                                vật tư nông nghiệp, Vật Tư 58 tự hào là một trong những địa chỉ uy tín hàng đầu, phục vụ nhu
                                cầu
                                của quý khách.</p>
                            <div class="flex gap-4">
                                <div class="flex flex-col">
                                    <span class="text-3xl font-extrabold text-leaf-600">20+</span>
                                    <span class="text-sm text-gray-500 font-bold">Năm kinh nghiệm</span>
                                </div>
                                <div class="w-px bg-gray-300 h-12"></div>
                                <div class="flex flex-col">
                                    <span class="text-3xl font-extrabold text-leaf-600">1000+</span>
                                    <span class="text-sm text-gray-500 font-bold">Sản phẩm</span>
                                </div>
                                <div class="w-px bg-gray-300 h-12"></div>
                                <div class="flex flex-col">
                                    <span class="text-3xl font-extrabold text-leaf-600">Top 1</span>
                                    <span class="text-sm text-gray-500 font-bold">Uy tín hàng đầu</span>
                                </div>
                            </div>
                        </div>
                        <div class="md:w-1/2 relative">
                            <div class="absolute inset-0 bg-leaf-600 rounded-[2rem] transform rotate-3 opacity-10"></div>
                            <img src="{{ asset('upload/images/bang_hieu.jpg') }}" alt="Về chúng tôi" class="relative rounded-[2rem] shadow-2xl w-full h-auto object-cover border-4 border-white" />
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- DB Content Section -->
        {{-- <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="prose max-w-4xl mx-auto text-gray-700">
                    {!! htmlspecialchars_decode($page->content) !!}
                </div>
            </div>
        </section> --}}

        <section class="py-16 bg-white relative">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row-reverse items-center gap-12">
                    <div class="md:w-1/2">
                        <span class="text-leaf-600 font-bold uppercase tracking-widest text-sm mb-2 block">Định hướng phát triển</span>
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Tầm nhìn & Sứ mệnh</h2>

                        <div class="mb-6">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-leaf-100 flex items-center justify-center flex-shrink-0 text-leaf-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tầm nhìn</h3>
                                    <p class="text-gray-600">
                                        Trở thành đơn vị cung cấp vật tư nông nghiệp uy tín hàng đầu khu vực, là người bạn đồng hành
                                        tin cậy của mọi nhà nông.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-leaf-100 flex items-center justify-center flex-shrink-0 text-leaf-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Sứ mệnh</h3>
                                    <p class="text-gray-600">
                                        Mang đến những sản phẩm chất lượng cao, an toàn và hiệu quả, góp phần xây dựng nền nông nghiệp xanh,
                                        sạch và bền vững.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-1/2 relative">
                        <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-orange-100 rounded-full z-0"></div>
                        <div class="absolute -top-6 -right-6 w-32 h-32 bg-leaf-100 rounded-full z-0"></div>

                        <img src="{{ asset('upload/images/Slider/1673630411_750900.jpg') }}" alt="Tầm nhìn sứ mệnh" class="relative z-10 rounded-2xl shadow-xl w-full h-auto object-cover aspect-video transform hover:scale-[1.02] transition duration-500">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-white rounded-t-[3rem] relative z-10">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Sản Phẩm & Dịch Vụ</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Chúng tôi chuyên cung cấp đa dạng các loại sản phẩm phục vụ cho sản xuất nông nghiệp với chất lượng được
                        kiểm định nghiêm ngặt.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-leaf-50 rounded-2xl p-8 hover:shadow-lg transition duration-300 border border-leaf-100 group">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center text-leaf-600 mb-6 shadow-sm group-hover:scale-110 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Phân bón</h3>
                        <p class="text-gray-600">
                            Các loại phân bón hữu cơ và vô cơ, giúp cung cấp dinh dưỡng cần thiết cho cây trồng, nâng cao năng suất
                            và chất lượng nông sản.
                        </p>
                    </div>

                    <div class="bg-leaf-50 rounded-2xl p-8 hover:shadow-lg transition duration-300 border border-leaf-100 group">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center text-leaf-600 mb-6 shadow-sm group-hover:scale-110 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Tro trấu & Giá thể</h3>
                        <p class="text-gray-600">
                            Các loại tro trấu, sơ dừa, tro trộn, phân bò, vỏ trấu hỗ trợ tối đa cho sự phát triển của bộ rễ cây
                            trồng.
                        </p>
                    </div>

                    <div class="bg-leaf-50 rounded-2xl p-8 hover:shadow-lg transition duration-300 border border-leaf-100 group">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center text-leaf-600 mb-6 shadow-sm group-hover:scale-110 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Đất sạch</h3>
                        <p class="text-gray-600">
                            Đại lý cấp 1 của các công ty chuyên cung cấp đất sạch uy tín trên thị trường như Tribat, Đồng Nai,
                            Sfarm,...
                        </p>
                    </div>

                    <div class="bg-leaf-50 rounded-2xl p-8 hover:shadow-lg transition duration-300 border border-leaf-100 group">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center text-leaf-600 mb-6 shadow-sm group-hover:scale-110 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Thuốc bảo vệ thực vật</h3>
                        <p class="text-gray-600">
                            Các sản phẩm được lựa chọn kỹ lưỡng, an toàn và hiệu quả, giúp bảo vệ mùa màng khỏi sâu bệnh hại.
                        </p>
                    </div>

                    <div class="bg-leaf-50 rounded-2xl p-8 hover:shadow-lg transition duration-300 border border-leaf-100 group">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center text-leaf-600 mb-6 shadow-sm group-hover:scale-110 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Hạt giống</h3>
                        <p class="text-gray-600">
                            Cung cấp hạt giống của nhiều loại cây trồng khác nhau, đảm bảo nguồn gốc rõ ràng và đạt tiêu chuẩn chất
                            lượng cao.
                        </p>
                    </div>

                    <div class="bg-leaf-50 rounded-2xl p-8 hover:shadow-lg transition duration-300 border border-leaf-100 group">
                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center text-leaf-600 mb-6 shadow-sm group-hover:scale-110 transition">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Chậu & Dụng cụ</h3>
                        <p class="text-gray-600">
                            Đầy đủ các loại chậu nhựa và dụng cụ trồng cây, hỗ trợ quý khách trong việc chăm sóc và phát triển cây
                            trồng tại nhà.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-leaf-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <span class="text-leaf-600 font-bold uppercase tracking-widest text-sm mb-2 block">Giá trị cốt lõi</span>
                    <h2 class="text-3xl font-extrabold text-gray-900">Điều gì làm nên sự khác biệt?</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition text-center border-b-4 border-leaf-500">
                        <div class="w-16 h-16 bg-leaf-100 rounded-full flex items-center justify-center mx-auto mb-6 text-leaf-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Chất lượng cam kết</h3>
                        <p class="text-gray-600">
                            100% sản phẩm chính hãng, nguồn gốc rõ ràng, được kiểm định chất lượng nghiêm ngặt trước khi đến tay
                            khách hàng.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition text-center border-b-4 border-orange-500">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6 text-orange-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Tận tâm phục vụ</h3>
                        <p class="text-gray-600">
                            Đội ngũ tư vấn viên am hiểu kỹ thuật, sẵn sàng hỗ trợ bà con nông dân với thái độ nhiệt tình, chu đáo.
                        </p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition text-center border-b-4 border-leaf-500">
                        <div class="w-16 h-16 bg-leaf-100 rounded-full flex items-center justify-center mx-auto mb-6 text-leaf-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Đồng hành phát triển</h3>
                        <p class="text-gray-600">
                            Không chỉ bán hàng, chúng tôi chia sẻ kiến thức, kỹ thuật canh tác mới nhất giúp bà con nâng cao năng
                            suất.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 relative bg-leaf-900 text-white overflow-hidden mt-12">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
            </div>
            <div class="container mx-auto px-4 relative z-10 text-center">
                <svg class="w-16 h-16 text-leaf-500 mx-auto mb-6 opacity-80" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16H7.19934C6.64698 16 6.19934 16.4477 6.19934 17C6.19934 17.5523 6.64698 18 7.19934 18H12.017C12.017 18 12.017 18 12.017 18V21H14.017ZM20.017 6H4.017C2.91243 6 2.017 6.89543 2.017 8V19C2.017 20.1046 2.91243 21 4.017 21H6.017V17H12.017V21H20.017C21.1216 21 22.017 20.1046 22.017 19V8C22.017 6.89543 21.1216 6 20.017 6ZM20.017 19H14.017V16H20.017V19ZM20.017 14H14.017V8H20.017V14ZM12.017 14H4.017V8H12.017V14Z">
                    </path>
                </svg>
                <h2 class="text-3xl md:text-5xl font-extrabold mb-6 italic">"Chất lượng tạo niềm tin"</h2>
                <p class="text-leaf-200 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                    Cửa hàng cam kết mang đến cho khách hàng những sản phẩm tốt nhất cùng với dịch vụ tận tình và chuyên nghiệp.
                    Sự hài lòng của bạn là thành công của chúng tôi.
                </p>
            </div>
        </section>
    </main>
@endsection


@push('scripts')
@endpush

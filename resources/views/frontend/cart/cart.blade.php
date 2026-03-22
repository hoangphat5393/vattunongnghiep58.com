@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@inject('ProductModel', 'App\Models\Frontend\Product')

@section('content')
    @include('frontend.includes.menu')

    <div class="container mx-auto px-4 py-4">
        <div class="text-sm text-gray-500 flex items-center gap-2">
            <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Giỏ hàng</span>
        </div>
    </div>

    <main class="bg-leaf-50 flex-grow">
        <div class="container mx-auto px-4 py-8">
            @if ($carts->count())
                <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Giỏ hàng của bạn</h1>

                <div class="flex flex-col lg:flex-row gap-4 carts-content">
                    <div class="lg:w-3/4">
                        <div class="bg-white rounded-2xl shadow-sm border border-leaf-100 overflow-hidden">
                            <div class="overflow-x-auto cart-table-include">
                                @include('frontend.cart.cart-table')
                            </div>
                        </div>
                    </div>

                    @include('frontend.cart.includes.cart-sidebar')
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mt-4 text-center">
                    <p class="text-gray-600 text-lg">@lang('Cart is empty!')</p>
                </div>
            @endif
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(event) {
                var removeBtn = event.target.closest('.cart__remove');
                if (removeBtn) {
                    event.preventDefault();

                    var rowId = removeBtn.getAttribute('data-rowid') || removeBtn.getAttribute('data');
                    if (!rowId) {
                        return;
                    }

                    axios({
                        method: 'post',
                        url: '/cart/ajax/remove',
                        data: {
                            rowId: rowId
                        }
                    }).then(function(res) {
                        if (res.data.error === 0) {
                            if (res.data.view) {
                                var tableContainer = document.querySelector('.carts-content .cart-table-include');
                                if (tableContainer) {
                                    tableContainer.innerHTML = res.data.view;
                                }
                            }
                            if (res.data.view_sidebar) {
                                var sidebarEl = document.querySelector('.carts-content .cart-sidebar-include');
                                if (sidebarEl) {
                                    sidebarEl.outerHTML = res.data.view_sidebar;
                                }
                            }

                            setTimeout(function() {
                                var cartCount = document.getElementById('CartCount');
                                if (cartCount) {
                                    cartCount.innerHTML = res.data.count_cart;
                                }
                                var headerTotal = document.querySelector('#header-cart .total .money');
                                if (headerTotal) {
                                    headerTotal.innerHTML = res.data.total;
                                }
                            }, 500);

                            alertJs('success', res.data.msg);
                        } else {
                            alertJs('error', res.data.msg);
                        }
                    }).catch(function() {
                        alertJs('error', 'Đã xảy ra lỗi, vui lòng thử lại!');
                    });
                }

                var decreaseBtn = event.target.closest('.quantity-decrease');
                var increaseBtn = event.target.closest('.quantity-increase');

                if (decreaseBtn || increaseBtn) {
                    event.preventDefault();
                    var rowId = (decreaseBtn || increaseBtn).getAttribute('data-rowid');
                    var input = document.querySelector(
                        '.quantity1[data-rowid="' + rowId + '"]');
                    if (!input) {
                        return;
                    }
                    var current = parseInt(input.value || '1', 10);
                    if (isNaN(current) || current < 1) {
                        current = 1;
                    }
                    if (decreaseBtn) {
                        current = Math.max(1, current - 1);
                    }
                    if (increaseBtn) {
                        current = current + 1;
                    }
                    input.value = current;

                    updateCartQuantity(rowId, current);
                }
            });

            document.addEventListener('change', function(event) {
                var qtyInput = event.target.closest('.quantity1');
                if (!qtyInput) {
                    return;
                }

                var qty = parseInt(qtyInput.value || '0', 10);
                var rowId = qtyInput.getAttribute('data-rowid');

                if (!rowId || qty <= 0 || isNaN(qty)) {
                    return;
                }

                updateCartQuantity(rowId, qty);
            });

            function updateCartQuantity(rowId, qty) {
                axios({
                    method: 'post',
                    url: '{{ route('carts.update') }}',
                    data: {
                        rowId: rowId,
                        qty: qty
                    }
                }).then(function(res) {
                    if (res.data.error === 0) {
                        if (res.data.view) {
                            var tableContainer = document.querySelector('.carts-content .cart-table-include');
                            if (tableContainer) {
                                tableContainer.innerHTML = res.data.view;
                            }
                        }
                        if (res.data.view_sidebar) {
                            var sidebarEl = document.querySelector('.carts-content .cart-sidebar-include');
                            if (sidebarEl) {
                                sidebarEl.outerHTML = res.data.view_sidebar;
                            }
                        }

                        setTimeout(function() {
                            var cartCount = document.getElementById('CartCount');
                            if (cartCount) {
                                cartCount.innerHTML = res.data.count_cart;
                            }

                            var siteCart = document.querySelector('.site-cart');
                            if (siteCart && res.data.view_cart_mini) {
                                var existingHeaderCart = siteCart.querySelector('#header-cart');
                                if (existingHeaderCart) {
                                    existingHeaderCart.remove();
                                }
                                siteCart.insertAdjacentHTML('beforeend', res.data.view_cart_mini);
                                var headerCart = siteCart.querySelector('#header-cart');
                                if (headerCart) {
                                    headerCart.classList.add('d-block');
                                    setTimeout(function() {
                                        headerCart.classList.remove('d-block');
                                    }, 1000);
                                }
                            }
                        }, 500);

                        alertJs('success', res.data.msg);
                    } else {
                        alertJs('error', res.data.msg);
                    }
                }).catch(function() {
                    alertJs('error', 'Đã xảy ra lỗi, vui lòng thử lại!');
                });
            }
        });
    </script>
@endpush

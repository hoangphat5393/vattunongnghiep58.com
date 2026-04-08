@inject('ProductModel', 'App\\Models\\Frontend\\Product')


@php $carts = Cart::content(); @endphp

<div class="dropdown-menu dropdown-menu-end bg-dark" id="header-cart">

    <div class="widget widget-cart px-3 pt-2 pb-3" style="width: 20rem;">

        <div class="mini-products-list" data-simplebar data-simplebar-auto-hide="false">

            @php $subtotal = 0; @endphp

            @foreach ($carts as $cart)

                @php

                    $product = $ProductModel::find($cart->id);

                    $lineTotal = (float) $cart->price * (int) $cart->qty;
                    $subtotal += $lineTotal;
                @endphp



                @if (!empty($product))

                    <div class="{{ request()->route()->uri() != 'checkout' ? 'widget-cart-item' : '' }} pb-2 border-bottom">

                        @if (request()->route()->uri() != 'checkout')

                            <button class="btn-close btn-close-white text-danger remove px-2 d-block" type="button" aria-label="Remove" data={{ $cart->rowId }}></button>

                        @endif

                        <div class="d-flex align-items-center">

                            <a class="flex-shrink-0" href="{{ route('product.detail', [$product->slug, $product->id]) }}">
                                <img src="{{ asset($product->image) }}" width="64" alt="{{ $product->name }}">

                            </a>

                            <div class="ps-2">

                                <h6 class="widget-product-title">

                                    <a href="{{ route('product.detail', [$product->slug, $product->id]) }}">{{ $product->name }}</a>
                                </h6>

                                <div class="widget-product-meta">

                                    {{-- <span class="text-accent me-2">{!! render_price($cart->price, 'USD') !!}</span> --}}

                                    <span class="text-accent me-2">{!! render_price($lineTotal, 'VND') !!}</span>
                                    <span class="text-muted">x {{ $cart->qty }} {{ $product->unit ?? '' }}</span>
                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>



        @if ($carts->count())

            <div class="d-flex flex-wrap justify-content-between align-items-center py-3">

                <div class="fs-sm me-2 py-2">

                    <span class="text-muted text-subtotal">Subtotal:</span>

                    <span class="text-accent fs-base ms-1 money-total">{!! render_price($subtotal, 'VND') !!}
                    </span>

                </div>

                <a class="btn btn-outline-info btn-sm" href="{{ route('cart') }}">Expand cart <i class="ci-arrow-right ms-1 me-n1"></i>

                </a>

            </div>

            <a class="btn btn-primary btn-sm d-block w-100" href="{{ route('cart.checkout') }}">

                <i class="ci-card me-2 fs-base align-middle"></i>Checkout

            </a>

        @else

            <div class="text-center text-white">Cart empty</div>

        @endif

    </div>

</div>


@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div class="carts-content">
        <div class="cart_bg">
            @include($templatePath . '.cart.cart-confirm-list', compact('carts'))
        </div>
    </div>
@endsection

@push('after-footer')
    <script>
        $(document).on('click', '.cart__remove', function(event) {
            event.preventDefault();
            $(this).closest('.cart__row_item').remove();
            var rowId = $(this).attr('data');
            axios({
                method: 'post',
                url: '/cart/ajax/remove',
                data: {
                    rowId: rowId
                }
            }).then(res => {
                if (res.data.error == 0) {
                    setTimeout(function() {
                        $('#CartCount').html(res.data.count_cart);
                        $('#header-cart .total .money').html(res.data.total);
                    }, 1000);
                    alertJs('success', res.data.msg);
                } else {
                    alertJs('error', res.data.msg);
                }
            }).catch(e => console.log(e));
        });

        $(document).on('change', '.quantity1', function() {

            var qty = $(this).val(),
                rowId = $(this).data('rowid');

            if (qty > 0) {
                axios({
                    method: 'post',
                    url: '{{ route('carts.update') }}',
                    data: {
                        rowId: rowId,
                        qty: qty
                    }
                }).then(res => {
                    if (res.data.error == 0) {
                        $('.carts-content').html(res.data.view);
                        setTimeout(function() {
                            $('#CartCount').html(res.data.count_cart);
                            $('.site-cart #header-cart').remove();
                            $('.site-cart').append(res.data.view_cart_mini);
                        }, 1000);
                        alertJs('success', res.data.msg);
                    } else {
                        alertJs('error', res.data.msg);
                    }
                }).catch(e => console.log(e));
            }
        })
    </script>
@endpush

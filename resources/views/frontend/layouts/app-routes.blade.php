<script>
    window.AppRoutes = Object.assign(window.AppRoutes || {}, {
        login: @json(route('customer.login.submit')),
        register: @json(route('customer.register.submit')),
        registerSuccess: @json(route('customer.register.success')),
        cartRemove: @json(route('cart.remove-item')),
        cartRemoveLegacy: @json(route('cart.ajax.remove')),
        cartUpdate: @json(route('carts.update')),
        contactSubmit: @json(route('contact.submit')),
    });
</script>

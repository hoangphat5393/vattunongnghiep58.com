<?php

// use Illuminate\Support\Facades\Response; // JSON response
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', '\App\Http\Controllers\PageController@index')->name('index');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        Session::put('locale', $locale);
    }

    return redirect()->back();
})->name('change_language');

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\CustomerAuthController@showLoginForm')->name('customer.login');
    Route::post('login', 'Auth\CustomerAuthController@postLogin')->name('customer.login.submit')->middleware('throttle:auth');
    Route::get('register', 'Auth\CustomerAuthController@registerCustomer')->name('customer.register');
    Route::post('register', 'Auth\RegisterController@register')->name('customer.register.submit')->middleware('throttle:auth');
    Route::get('register-success', 'Auth\CustomerAuthController@createCustomerSuccess')->name('customer.register.success');
    Route::post('logout', 'Auth\CustomerAuthController@logoutCustomer')->name('customer.logout');

    Route::get('forgot-password', 'Auth\ForgotPasswordController@forget')->name('customer.password.forgot');
    Route::post('forgot-password', 'Auth\ForgotPasswordController@actionForgetPassword')->name('customer.password.forgot.submit')->middleware('throttle:auth');
    Route::get('forgot-password/verify', 'Auth\ForgotPasswordController@forgetPassword_step2')->name('customer.password.verify');
    Route::post('forgot-password/verify', 'Auth\ForgotPasswordController@actionForgetPassword_step2')->name('customer.password.verify.submit');
    Route::get('forgot-password/reset', 'Auth\ForgotPasswordController@forgetPassword_step3')->name('customer.password.reset');
    Route::post('forgot-password/reset', 'Auth\ForgotPasswordController@actionForgetPassword_step3')->name('customer.password.reset.submit');

    // Legacy VNPay wallet top-up — deferred (tables dropped, project uses offline checkout)
    // Route::post('nap-tai-khoan', 'PaymentController@checkout')->name('customer.vnpay');
});
Route::post('customer/login-or-register', 'CustomerController@loginOrregister')->name('login_or_register');

// login facebook and google
Route::get('social/{provider}', 'RegisterAuthController@redirectToProvider')->name('auth.social');
Route::get('callback/{provider}', 'RegisterAuthController@handleProviderCallback')->name('auth.social.callback');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'Account\AccountController@index')->name('customer.dashboard');
        Route::get('profile', 'Account\AccountController@profile')->name('customer.profile');
        Route::post('profile', 'Account\AccountController@updateProfile')->name('customer.profile.update');
        Route::get('orders', 'Account\AccountController@myOrder')->name('customer.orders.index');
        Route::get('orders/{id_cart}', 'Account\AccountController@myOrderDetail')->name('customer.orders.show');
        Route::get('password', 'Account\AccountController@changePassword')->name('customer.password.edit');
        Route::post('password', 'Account\AccountController@postChangePassword')->name('customer.password.update');
        Route::get('my-reviews', 'CustomerController@myReviews')->name('customer.reviews');
        Route::get('quan-ly-tin-dang', 'CustomerController@myPost')->name('customer.post');
        Route::get('refused', 'CustomerController@refused')->name('customer.refused');
        // Legacy wallet / VNPay — deferred
        // Route::get('payment-point', 'PaymentController@paymentPoint')->name('customer.payment.point');
        Route::post('post-reviews', 'CustomerController@postReviews')->name('customer.post_reviews');
        Route::get('messages', 'CustomerController@messages')->name('customer.messages');
    });
});

// 301 redirects — URL cũ (Hướng B)
Route::redirect('/customer', '/account', 301);
Route::redirect('/customer/thong-tin', '/account/profile', 301);
Route::redirect('/customer/my-orders', '/account/orders', 301);
Route::redirect('/customer/my-orders-detail/{id_cart}', '/account/orders/{id_cart}', 301);
Route::redirect('/customer/change-pass', '/account/password', 301);
Route::redirect('/forget/password', '/auth/forgot-password', 301);
Route::redirect('/forget/password-step-2', '/auth/forgot-password/verify', 301);
Route::redirect('/forget/password-step-3', '/auth/forgot-password/reset', 301);

Route::group(['prefix' => 'cart'], function () {
    Route::get('/', 'CartController@cart')->name('cart');
    Route::get('remove', 'CartController@removeCarts')->name('carts.remove');
    Route::post('update', 'CartController@updateCarts')->name('carts.update');
    // Route::get('/checkout', 'CartController@checkout')->name('cart.checkout');

    Route::post('checkout-confirm', 'CartController@checkoutConfirm')->name('cart.checkout.confirm');
    Route::get('checkout-checkemail', 'CartController@checkEmail')->name('cart.checkout.checkemail');
    Route::get('checkout-checkphone', 'CartController@checkphone')->name('cart.checkout.checkphone');

    Route::post('contact', 'ContactController@submit')->name('cart.contact.submit');

    // Legacy quick-buy routes → CartController@quickBuyConfirm redirects to cart.checkout
    Route::get('quick-buy-checkout-confirm', 'CartController@quickBuyConfirm')->name('quick_buy.get.confirm');
    Route::post('quick-buy-checkout-confirm', 'CartController@quickBuyConfirm')->name('quick_buy.checkout.confirm');

    Route::get('check-payment/{cart_id}', 'CartController@checkPayment')->name('cart.check_payment');

    Route::post('addCart', 'CartController@addCart')->name('cart.addCart');
    Route::post('remove-item', 'CartController@removeCart')->name('cart.remove-item');
    Route::post('ajax/remove', 'CartController@removeCart')->name('cart.ajax.remove');
    // Route::post('ajax/get-shipping-cost', 'CartController@shipping')->name('cart.ajax.shipping');

    Route::get('checkout/success', 'CartController@success')->name('cart.checkout.success');
    Route::get('view/{id}', 'CartController@view')->name('cart.view');
});
Route::get('checkout', 'CartController@checkout')->name('cart.checkout');
Route::post('checkout', 'CartController@checkoutConfirm')->name('cart.checkout.submit');
Route::get('checkout-completed', 'CartController@completed')->name('checkout_completed');

// Route::post('checkout', 'CheckoutController@submit')->name('checkout.submit');

// Route::get('payment', 'PayPalTestController@index');
// Legacy checkout-process (Stripe/quick-buy cũ) → redirect sang checkout mới (IMP-012)
Route::post('checkout-process', 'CartController@legacyCheckoutProcessRedirect')->name('cart_checkout.process');
// Route::post('checkout-charge', 'PayPalTestController@charge')->name('cart.checkout.charge');
// Route::get('payment-success/{id?}', 'PayPalTestController@paymentStrip_success');
// Route::get('paymentsuccess', 'PayPalTestController@payment_success');
// Route::get('paymenterror', 'PayPalTestController@payment_error');

Route::post('subscription', 'CustomerController@subscription')->name('subscription');

// All Product
Route::get('san-pham', '\App\Http\Controllers\ProductController@index')->name('product');
Route::get('product', '\App\Http\Controllers\ProductController@index')->name('product.all');

// Product detail
Route::get('san-pham/{slug}-{id}.html', '\App\Http\Controllers\ProductController@productDetail')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+', 'id' => '[0-9]+'])
    ->name('product.detail');
Route::get('product/{slug}-{id}.html', '\App\Http\Controllers\ProductController@productDetail')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+', 'id' => '[0-9]+']);

// Product category
Route::get('san-pham/{slug}.html', '\App\Http\Controllers\ProductController@index')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+'])
    ->name('product.category');
Route::get('product/{slug}.html', '\App\Http\Controllers\ProductController@index')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+']);

Route::post('quick-view', 'ProductController@quickView')->name('shop.quickView');
Route::get('buy-now/{id}', 'ProductController@buyNow')->name('shop.buyNow');
Route::post('buy-now', 'ProductController@getBuyNow')->name('shop.buyNow.post');

// Posts (URL giữ /news/ cho SEO)
Route::get('news', '\App\Http\Controllers\PostController@index')->name('news');

Route::get('news/{slug}-{id}.html', '\App\Http\Controllers\PostController@show')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+', 'id' => '[0-9]+'])
    ->name('news.detail');

Route::get('news/{slug}.html', '\App\Http\Controllers\PostController@index')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+'])
    ->name('news.category');

// Contact
// Route::post('/get-contact-form/{type}', array('as' => 'contact.get', 'uses' => 'ContactController@getContact'));
// Route::get('contact', 'ContactController@index')->name('contact');
Route::post('contact', 'ContactController@submit')->name('contact.submit');
Route::get('contact-completed', 'ContactController@completed')->name('contact_completed');

// Search
// Route::post('/input/search-text/{type}', 'AjaxController@inputSearchText');

Route::get('search', 'SearchController@index')->name('search');

// Page
Route::get('{slug}', 'PageController@page')->name('page');

// Route::group(['prefix' => 'ajax'], function () {
//     Route::post('change-attr', 'ProductController@changeAttr')->name('ajax.attr.change');
//     Route::post('order-view', 'CustomerController@orderView');
// });

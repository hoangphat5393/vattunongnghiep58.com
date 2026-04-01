<?php

// use Illuminate\Support\Facades\Response; // JSON response
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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
        \Illuminate\Support\Facades\Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('change_language');

Route::group(['prefix' => 'auth'], function () {
    Route::get('register', 'CustomerController@registerCustomer')->name('registerCustomer');
    Route::post('register', 'Auth\RegisterController@register')->name('postRegisterCustomer');
    Route::get('register-success', 'CustomerController@createCustomerSuccess')->name('user.register.success');
    Route::get('login', 'CustomerController@showLoginForm')->name('user.login');
    Route::post('login', 'CustomerController@postLogin')->name('loginCustomerAction');

    Route::get('logout', array('as' => 'customer.logout', 'uses' => 'CustomerController@logoutCustomer'));
    Route::post('nap-tai-khoan', 'PaymentController@checkout')->name('customer.vnpay');
});
Route::post('customer/login-or-register', 'CustomerController@loginOrregister')->name('login_or_register');

// login facebook and google
Route::get('social/{provider}', 'RegisterAuthController@redirectToProvider')->name('auth.social');
Route::get('callback/{provider}', 'RegisterAuthController@handleProviderCallback')->name('auth.social.callback');

// User forget password
Route::group(['prefix' => 'forget'], function () {

    // User forget password
    Route::get('password', 'Auth\ForgotPasswordController@forget')->name('forgetPassword');
    Route::post('password', 'Auth\ForgotPasswordController@actionForgetPassword')->name('actionForgetPassword');

    Route::get('password-step-2', 'Auth\ForgotPasswordController@forgetPassword_step2')->name('forgetPassword_step2');
    Route::post('password-step-2', 'Auth\ForgotPasswordController@actionForgetPassword_step2')->name('actionForgetPassword_step2');

    Route::get('password-step-3', 'Auth\ForgotPasswordController@forgetPassword_step3')->name('forgetPassword_step3');
    Route::post('password-step-3', 'Auth\ForgotPasswordController@actionForgetPassword_step3')->name('actionForgetPassword_step3');
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', 'CustomerController@index')->name('customer.dashboard');
        Route::get('thong-tin', array('as' => 'customer.profile', 'uses' => 'CustomerController@profile'));
        Route::post('thong-tin', array('as' => 'customer.updateprofile', 'uses' => 'CustomerController@updateProfile'));
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'customer'], function () {
        Route::get('my-orders', array('as' => 'customer.my-orders', 'uses' => 'CustomerController@myOrder'));
        Route::get('my-orders-detail/{id_cart}', array('as' => 'customer.myordersdetail', 'uses' => 'CustomerController@myOrderDetail'));
        Route::get('my-reviews', array('as' => 'customer.reviews', 'uses' => 'CustomerController@myReviews'));

        Route::get('quan-ly-tin-dang', array('as' => 'customer.post', 'uses' => 'CustomerController@myPost'));
        Route::get('refused', array('as' => 'customer.refused', 'uses' => 'CustomerController@refused'));

        Route::get('payment-point', array('as' => 'customer.payment.point', 'uses' => 'PaymentController@paymentPoint'));

        Route::get('change-pass', array('as' => 'customer.changePassword', 'uses' => 'CustomerController@changePassword'));
        Route::post('change-pass', array('as' => 'customer.post.ChangePassword', 'uses' => 'CustomerController@postChangePassword'));
        Route::post('post-reviews', array('as' => 'customer.post_reviews', 'uses' => 'CustomerController@postReviews'));

        Route::get('messages', 'CustomerController@messages')->name('customer.messages');
    });
});


Route::group(['prefix' => 'cart'], function () {
    Route::get('/', 'CartController@cart')->name('cart');
    Route::get('remove', 'CartController@removeCarts')->name('carts.remove');
    Route::post('update', 'CartController@updateCarts')->name('carts.update');
    // Route::get('/checkout', 'CartController@checkout')->name('cart.checkout');

    Route::post('checkout-confirm', 'CartController@checkoutConfirm')->name('cart.checkout.confirm');
    Route::get('checkout-checkemail', 'CartController@checkEmail')->name('cart.checkout.checkemail');
    Route::get('checkout-checkphone', 'CartController@checkphone')->name('cart.checkout.checkphone');

    Route::post('contact', 'ContactController@submit')->name('cart.contact.submit');

    Route::get('quick-buy-checkout-confirm', 'CartController@quickBuyConfirm')->name('quick_buy.get.confirm');
    Route::post('quick-buy-checkout-confirm', 'CartController@quickBuyConfirm')->name('quick_buy.checkout.confirm');

    Route::get('check-payment/{cart_id}', 'CartController@checkPayment')->name('cart.check_payment');

    Route::post('addCart', 'CartController@addCart')->name('cart.addCart');
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
Route::post('checkout-process', '\App\Http\Controllers\CheckoutController@checkoutProcess')->name('cart_checkout.process');
// Route::post('checkout-charge', 'PayPalTestController@charge')->name('cart.checkout.charge');
// Route::get('payment-success/{id?}', 'PayPalTestController@paymentStrip_success');
// Route::get('paymentsuccess', 'PayPalTestController@payment_success');
// Route::get('paymenterror', 'PayPalTestController@payment_error');

Route::post('subscription', 'CustomerController@subscription')->name('subscription');

// All Product
Route::get('product', '\App\Http\Controllers\ProductController@index')->name('product');

// Product detail 
Route::get('product/{slug}-{id}.html', '\App\Http\Controllers\ProductController@productDetail')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+', 'id' => '[0-9]+'])
    ->name('product.detail');

// Product category
Route::get('product/{slug}.html', '\App\Http\Controllers\ProductController@index')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+'])
    ->name('product.category');

Route::post('quick-view', 'ProductController@quickView')->name('shop.quickView');
Route::get('buy-now/{id}', 'ProductController@buyNow')->name('shop.buyNow');
Route::post('buy-now', 'ProductController@getBuyNow')->name('shop.buyNow.post');

// All News
Route::get('news', '\App\Http\Controllers\NewsController@index')->name('news');

// News detail 
Route::get('news/{slug}-{id}.html', '\App\Http\Controllers\NewsController@newsDetail')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+', 'id' => '[0-9]+'])
    ->name('news.detail');

// News category 
Route::get('news/{slug}.html', '\App\Http\Controllers\NewsController@index')
    ->where(['slug' => '[a-zA-Z0-9$-_.+!]+'])
    ->name('news.category');

// Contact
// Route::post('/get-contact-form/{type}', array('as' => 'contact.get', 'uses' => 'ContactController@getContact'));
// Route::get('contact', 'ContactController@index')->name('contact');
Route::post('contact-confirmation', 'ContactController@confirmation')->name('contact.confirmation');
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

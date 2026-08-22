<?php

// use CodeZero\LocalizedRoutes\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// use App\Http\Controllers\Admin\PageController;
// use App\Http\Controllers\Admin\AlbumController;

// Route xử lý cho admin

// Route::localized(function () {

Route::namespace('Admin')->group(function () {

    Route::get('login', 'LoginController@showLoginForm');
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::post('logout', 'LoginController@logout')->name('admin.logout');
    Route::get('404', 'AdminController@error')->name('admin.error');

    // Route::get('/404', array(
    //     'as' => 'adminError',
    //     'uses' => 'AdminController@error'
    // ));

    Route::group(['middleware' => ['auth:admin']], function () {

        Route::get('/', 'HomeController@index')->name('admin.dashboard');
        Route::post('cc', 'AdminController@clearCache')->name('admin.cache.clear');

        Route::get('change-password', 'AdminController@changePassword')->name('admin.change-password');
        Route::get('change-password/alias', 'AdminController@changePassword')->name('admin.changePassword');
        Route::post('change-password', 'AdminController@postChangePassword')->name('admin.postChangePassword');
        Route::get('check-password', 'AjaxController@checkPassword')->name('admin.checkPassword');

        Route::group(['middleware' => 'checkAdminPermission'], function () {

            // Xử lý users admin
            Route::group(['prefix' => 'user'], function () {
                Route::get('', 'UserAdminController@index')->name('admin.user.index');
                Route::get('list', 'UserAdminController@index')->name('admin.userList'); // Added alias for controller compatibility
                Route::get('create', 'UserAdminController@create')->name('admin.user.create');
                Route::post('', 'UserAdminController@store')->name('admin.user.store');
                // Route::get('{id}', 'UserAdminController@show')->name('admin.user.show'); // Method not exists
                Route::get('{id}/edit', 'UserAdminController@edit')->name('admin.user.edit');
                Route::put('{id}', 'UserAdminController@update')->name('admin.user.update');
                Route::delete('{id}', 'UserAdminController@deleteUserAdmin')->name('admin.user.destroy');
            });

            Route::group(['prefix' => 'role'], function () {
                Route::get('', 'Auth\RoleController@index')->name('admin.role.index');
                Route::get('create', 'Auth\RoleController@create')->name('admin.role.create');
                Route::post('', 'Auth\RoleController@store')->name('admin.role.store');
                Route::get('{id}', 'Auth\RoleController@show')->name('admin.role.show');
                Route::get('{id}/edit', 'Auth\RoleController@edit')->name('admin.role.edit');
                Route::put('{id}', 'Auth\RoleController@update')->name('admin.role.update');
                Route::delete('{id}', 'Auth\RoleController@destroy')->name('admin.role.destroy');
            });

            Route::group(['prefix' => 'permission'], function () {
                Route::get('', 'Auth\PermissionController@index')->name('admin.permission.index');
                Route::get('create', 'Auth\PermissionController@create')->name('admin.permission.create');
                Route::post('', 'Auth\PermissionController@store')->name('admin.permission.store');
                Route::get('{id}', 'Auth\PermissionController@show')->name('admin.permission.show');
                Route::get('{id}/edit', 'Auth\PermissionController@edit')->name('admin.permission.edit');
                Route::put('{id}', 'Auth\PermissionController@update')->name('admin.permission.update');
                Route::delete('{id}', 'Auth\PermissionController@destroy')->name('admin.permission.destroy');
            });

            Route::group(['prefix' => 'accounts', 'as' => 'apermissioncount.'], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'Auth\PermissionController@index']);
            });

            // Orders
            Route::group(['prefix' => 'order'], function () {
                Route::get('', 'OrderController@index')->name('admin.order.index');
                Route::get('search', 'OrderController@searchOrder')->name('admin.order.search');
                Route::get('{id}', 'OrderController@orderDetail')->name('admin.order.detail');
                Route::post('update', 'OrderController@postOrderDetail')->name('admin.order.update');
            });

            // Payment
            // Route::get('payment', 'PaymentController@index')->name('admin.paymentList');
            // Route::get('payment/create', 'PaymentController@create')->name('admin.paymentCreate');
            // Route::get('payment/{id}', 'PaymentController@edit')->name('admin.paymentEdit');
            // Route::post('payment/post', 'PaymentController@post')->name('admin.paymentPost');

            Route::group(['prefix' => 'album_item'], function () {
                Route::get('{id}', 'AlbumItemController@show')->name('albums.albumItem.show');
                Route::get('{id}/edit', 'AlbumItemController@edit')->name('admin.albumItem.edit');
                Route::put('{id}', 'AlbumItemController@update')->name('admin.albumItem.update');
                Route::delete('{id}', 'AlbumItemController@destroy')->name('admin.albumItem.destroy');

                // Other method
                Route::post('update-sort', 'AlbumItemController@ajaxUpdateSort')->name('admin.albumItem.update_sort');
                Route::post('ajax_update_sort', 'AlbumItemController@ajaxUpdateSort')->name('admin.albumItem.ajax_update_sort');
            });

            Route::get('album-library', 'AlbumController@library')->name('admin.album.library');

            Route::get('album/{album_id}/album_item/create', 'AlbumItemController@create')->name('admin.album.albumItem.create');
            Route::post('album/{album_id}/album_item', 'AlbumItemController@store')->name('admin.album.albumItem.store');
            Route::post('album/{album_id}/storeMultiple', 'AlbumItemController@storeMultiple')->name('admin.album.storeMultiple');

            $admin_module = ['contact', 'email-template', 'album', 'page', 'post', 'product'];
            $modules_with_category = ['product', 'post'];

            // Product Import Routes
            Route::get('product/import', 'ProductController@import')->name('admin.product.import');
            Route::post('product/import', 'ProductController@importProcess')->name('admin.product.import_process');

            foreach ($admin_module as $item) {

                // Module data
                $prefix_controller = ucfirst(Str::camel($item)).'Controller'; // postController
                // $prefix_name = 'admin.' . Str::camel($item); // admin.post
                // $prefix_name = 'admin.' . Str::snake($item, '-'); // admin.post
                $prefix_name = 'admin.'.$item; // admin.post

                // List / index
                Route::get($item, $prefix_controller.'@index')->name($prefix_name.'.index');

                // Create
                Route::get($item.'/create', $prefix_controller.'@create')->name($prefix_name.'.create');
                Route::post($item, $prefix_controller.'@store')->name($prefix_name.'.store');

                // Show
                Route::get($item.'/{id}', $prefix_controller.'@show')->name($prefix_name.'.show');

                // Edit
                Route::get($item.'/{id}/edit', $prefix_controller.'@edit')->name($prefix_name.'.edit');
                Route::put($item.'/{id}', $prefix_controller.'@update')->name($prefix_name.'.update');

                // Delete
                Route::delete($item.'/{id}', $prefix_controller.'@destroy')->name($prefix_name.'.destroy');

                // Module Category
                if (in_array($item, $modules_with_category)) {
                    $prefix_controller = ucfirst(Str::camel($item)).'CategoryController'; // postCategoryController
                    // $prefix_category_name = 'admin.' . Str::camel($item) . 'Category'; // admin.product.category
                    $prefix_category_name = 'admin.'.$item.'-category'; // admin.product.category

                    // List / index
                    Route::get($item.'-category', $prefix_controller.'@index')->name($prefix_category_name.'.index');

                    // Create
                    Route::get($item.'-category/create', $prefix_controller.'@create')->name($prefix_category_name.'.create');
                    Route::post($item.'-category', $prefix_controller.'@store')->name($prefix_category_name.'.store');

                    // Show
                    Route::get($item.'-category/{id}', $prefix_controller.'@show')->name($prefix_category_name.'.show');

                    // Edit
                    Route::get($item.'-category/{id}/edit', $prefix_controller.'@edit')->name($prefix_category_name.'.edit');
                    Route::put($item.'-category/{id}', $prefix_controller.'@update')->name($prefix_category_name.'.update');

                    // if ($item == 'post')
                    //     dd($prefix_controller, $item . '-category', $prefix_category_name . '.destroy');

                    // Delete
                    Route::delete($item.'-category/{id}', $prefix_controller.'@destroy')->name($prefix_category_name.'.destroy');
                }

                // // Product
                // Route::get('product', 'ProductController@index')->name('admin.product.index');
                // Route::get('product/create', 'ProductController@create')->name('admin.product.create');
                // Route::post('product', 'ProductController@store')->name('admin.product.store');
                // Route::get('product/{product}', 'ProductController@show')->name('admin.product.show');
                // Route::get('product/{product}/edit', 'ProductController@edit')->name('admin.product.edit');
                // Route::put('product/{product}', 'ProductController@update')->name('admin.product.update');
                // Route::delete('product/{product}', 'ProductController@destroy')->name('admin.product.destroy');
            }

            Route::post('bulk-delete', 'AjaxController@ajax_delete')->name('admin.bulk.delete');
            Route::post('bulk-replicate', 'AjaxController@ajax_replicate')->name('admin.bulk.replicate');
            Route::post('quick-change', 'AjaxController@ajax_quickchange')->name('admin.quick-change');

            Route::post('delete-id', 'AjaxController@ajax_delete')->name('admin.ajax_delete');
            Route::post('replicate-id', 'AjaxController@ajax_replicate')->name('admin.ajax_replicate');

            // Ajax update all sort
            // Route::post('ajax-update-sort', 'AjaxController@ajax_delete')->name('admin.ajax_update_sort');

            Route::group(['prefix' => 'admin-menu'], function () {
                Route::get('/', 'AdminMenuController@index')->name('admin.admin-menu.index');
                Route::post('create', 'AdminMenuController@postCreate')->name('admin.admin-menu.store');
                Route::get('edit/{id}', 'AdminMenuController@edit')->name('admin.admin-menu.edit');
                Route::post('edit/{id}', 'AdminMenuController@postEdit')->name('admin.admin-menu.update');
                Route::post('delete', 'AdminMenuController@deleteList')->name('admin.admin-menu.destroy');
                Route::post('update_sort', 'AdminMenuController@updateSort')->name('admin.admin-menu.update_sort');
            });

            // Setting | Theme-option
            Route::group(['prefix' => 'theme-option'], function () {
                Route::get('/', 'AdminController@getThemeOption')->name('admin.theme-option');
                Route::post('/', 'AdminController@postThemeOption')->name('admin.theme-option.post');
                Route::post('update-sort', 'AdminController@ajaxUpdateSort')->name('admin.theme-option.update_sort');
                Route::post('ajax_update_sort', 'AdminController@ajaxUpdateSort')->name('admin.theme-option.ajax_update_sort');
            });

            Route::group(['prefix' => 'theme-css'], function () {
                Route::get('/', 'AdminController@getCSS')->name('admin.css.get');
                Route::put('/', 'AdminController@updateCSS')->name('admin.css.update');
            });

            // Setting | Theme-option
            Route::get('menu', 'MenuController@index')->name('admin.menu.index');
            // Route::get('menu/create', 'MenuController@index')->name('admin.menu.create');
            Route::post('menu', 'MenuController@store')->name('admin.menu.store');
            // Route::get('menu/{menu}', 'MenuController@show')->name('admin.menu.show');
            // Route::get('menu/{menu}/edit', 'MenuController@edit')->name('admin.menu.edit');
            Route::put('menu/{menu}', 'MenuController@update')->name('admin.menu.update');
            Route::delete('menu/{menu}', 'MenuController@destroy')->name('admin.menu.destroy');

            // Menu Items
            Route::post('menu/generatemenu', 'MenuController@generatemenucontrol')->name('admin.menu.generate');

            // Route::post('generatemenucontrol', 'MenuController@generatemenucontrol')->name('hgeneratemenucontrol');

            Route::post('menu/{menu}/menuitems', 'MenuController@menuItemStore')->name('admin.menu.menuItem.store');
            Route::post('menu/{menu}/updateMenuItem', 'MenuController@updateitem')->name('admin.menu.menuItem.update');
            Route::delete('menu/{menu}/{menuitems}', 'MenuController@destroyitemmenu')->name('admin.menu.menuItem.destroy');

            // Update url menu
            Route::get('menu/update_url', 'MenuController@updateUrl')->name('admin.menu.updateUrl');

            // Route::get('upload/insertImage', 'UploadController@insertImage')->name('admin.upload.insertImage');
            // Route::get('upload/insertImageSVG', 'UploadController@insertImageSVG')->name('admin.upload.insertImageSVG');
            // Route::post('upload/saveImage', 'UploadController@saveImage')->name('admin.upload.saveImage');
            // Route::post('upload/saveImage', 'UploadController@saveImageSVG')->name('admin.upload.saveImageSVG');

            // Route::resource('upload', 'UploadController');
        });
    });
});
// });

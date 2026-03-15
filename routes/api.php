<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/slider', array(
    'as' => 'slide.api',
    'uses' => 'ApiController@Slider'));

Route::get('/category-products', array(
    'as' => 'category-products.api',
    'uses' => 'ApiController@CategoryProducts'));
Route::get('/category-products/{id}', array(
    'as' => 'category-products-id.api',
    'uses' => 'ApiController@CategoryProductsID'));
Route::get('/category-products-html/{id}', array(
    'as' => 'category-products-id-html.api',
    'uses' => 'ApiController@CategoryProductsIDHTML'));
Route::get('/category-products-html-selling/{id}', array(
    'as' => 'category-products-html-selling.api',
    'uses' => 'ApiController@CategoryProductsIDHTMLSelling'));
Route::get('/category-products-html-offer/{id}', array(
    'as' => 'category-products-html-offer.api',
    'uses' => 'ApiController@CategoryProductsIDHTMLOffer'));
Route::get('/products', array(
    'as' => 'products.api',
    'uses' => 'ApiController@ProductsList'));
Route::get('/products/{id}', array(
    'as' => 'productsSingle.api',
    'uses' => 'ApiController@ProductSingle'));
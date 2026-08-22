<?php

namespace App\Http\Controllers;

use App\Facades\WebService;
use App\Models\Discount_code;
use App\Models\Discount_for_brand;
use App\Models\Frontend\Category;
use App\Models\Frontend\Product;
use App\Models\Frontend\User;
use App\Models\Join_Category_Theme;
use App\Models\Theme;
use App\Models\Variable_Theme;
use App\Models\Wishlist;
use App\Traits\LocalizeController;
// use App\Libraries\Helpers;

use Auth;
use Cart;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{
    use LocalizeController;

    public $data = [];

    public function post_ajax_left()
    {

        if (view()->exists('adminwp.ajax.post_ajax_left')) {
            return view('adminwp.ajax.post_ajax_left');
        }

        abort(404);
    }

    public function cate_ajax_left()
    {

        if (view()->exists('adminwp.ajax.cate_ajax_left')) {
            return view('adminwp.ajax.cate_ajax_left');
        }

        abort(404);
    }

    public function process_postcontent()
    {

        if (view()->exists('adminwp.ajax.process_postcontent')) {
            return view('adminwp.ajax.process_postcontent');
        }

        abort(404);
    }

    public function customers_details()
    {

        if (view()->exists('adminwp.load.customers_details')) {
            return view('adminwp.load.customers_details');
        }

        abort(404);
    }

    public function process_order()
    {

        if (view()->exists('adminwp.ajax.process_order')) {
            return view('adminwp.ajax.process_order');
        }

        abort(404);
    }

    public function process_discount()
    {

        if (view()->exists('adminwp.ajax.process_discount')) {
            return view('adminwp.ajax.process_discount');
        }

        abort(404);
    }

    public function process_discount_for_brand()
    {

        if (view()->exists('adminwp.ajax.process_discount_for_brand')) {
            return view('adminwp.ajax.process_discount_for_brand');
        }

        abort(404);
    }

    public function updateStatus()
    {

        if (view()->exists('adminwp.ajax.process_status')) {
            return view('adminwp.ajax.process_status');
        }

        abort(404);
    }

    public function updateStoreStatus()
    {

        if (view()->exists('adminwp.ajax.process_store_status')) {
            return view('adminwp.ajax.process_store_status');
        }

        abort(404);
    }

    public function check_regiser(Request $rq)
    {

        $data_user = User::all();

        if ($rq->email) {

            foreach ($data_user as $row) {

                if ($rq->email == $row->email) {

                    return 1;
                }
            }
        }

        if ($rq->phone) {

            foreach ($data_user as $row) {

                if ($rq->phone == $row->phone) {

                    return 1;
                }
            }
        }
    }

    public function getDistrict()
    {

        $html = '';

        $id_province = $_POST['data'];

        $district = DB::table('district')

            ->join('province', 'district.provinceid', '=', 'province.provinceid')

            ->where('province.name', '=', $id_province)

            ->orderBy('district.order_sort', 'DESC')

            ->orderBy('district.name', 'ASC')

            ->select('district.*')

            ->get();

        $html .= '<option value="">Chọn Quận/Huyện</option>';

        foreach ($district as $item) {

            $html .= '<option value="'.$item->name.'">'.$item->name.'</option>';
        }

        return $html;
    }

    public function getWard()
    {

        $html = '';

        $id_district = $_POST['data'];

        $ward = DB::table('ward')

            ->join('district', 'district.districtid', '=', 'ward.districtid')

            ->where('district.name', '=', $id_district)

            ->orderBy('ward.name', 'ASC')

            ->select('ward.*')

            ->get();

        $html .= '<option value="">Chọn Phường/Xã</option>';

        foreach ($ward as $item) {

            $html .= '<option value="'.$item->name.'">'.$item->name.'</option>';
        }

        return $html;
    }

    public function getFeeShipping(Request $rq)
    {

        if ($rq->data == 'ghtk') {

            $total_weight = 0;

            foreach (Cart::content() as $cart_items) {

                $id_cart = $cart_items->id;

                $Products = get_product_by_id($id_cart);

                $total_weight += $Products->product_detail_weight;
            }

            session_start();

            $data_cart = $_SESSION['data_cart'];

            $data = [

                'pick_address' => config('app.pick_address'),

                'pick_province' => config('app.pick_province'),

                'pick_district' => config('app.pick_district'),

                'pick_ward' => config('app.pick_ward'),

                'pick_street' => config('app.pick_street'),

                'province' => $data_cart['cart_province'],

                'district' => $data_cart['cart_district'],

                'ward' => $data_cart['cart_ward'],

                'address' => $data_cart['cart_address'],

                'weight' => $total_weight,

                'value' => $data_cart['cart_total'],

                'transport' => 'road',

            ];

            $curl = curl_init();

            curl_setopt_array($curl, [

                CURLOPT_URL => 'https://services.giaohangtietkiem.vn/services/shipment/fee?'.http_build_query($data),

                CURLOPT_RETURNTRANSFER => true,

                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

                CURLOPT_HTTPHEADER => [

                    'Token: '.config('app.api_ghtk'),

                ],

            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            $arr = json_decode($response);

            $fee_html = '+'.WebService::formatMoney12($arr->fee->fee).'<span class="woocommerce-Price-currencySymbol">'.setting_option('currency').'</span>';

            $arr = [

                'fee_html' => $fee_html,

                'fee' => $arr->fee->fee,

                'cart_total' => Cart::total() + $arr->fee->fee,

            ];

            $arr_fee = json_encode($arr);

            return $arr_fee;
        }
    }

    public function LoadAjaxCart()
    {

        $html = '';

        $html .= '<a class="icon_cart_tbn_a action showcart" href="'.route('cart').'" >

                            <span class="basel-cart-totals">

                                <span class="basel-cart-number badge badge-notify my-cart-badge">'.Cart::count().'</span>

                                <span class="subtotal-divider">/</span>

                                <span class="basel-cart-subtotal">

                                   <span id="price_total_view1" class="woocommerce-Price-amount amount">'.WebService::formatMoney12(Cart::total()).' </span><span class="woocommerce-Price-currencySymbol">'.setting_option('currency').'</span>

                                </span>

                            </span>

                        </a>';

        $html .= '<div class="dropdown-wrap-cat">

                            <div class="dropdown-cat">

                                <div class="widget woocommerce widget_shopping_cart">

                                    <div class="widget_shopping_cart_content">';

        if (Cart::content()->count() > 0) {

            $string_json = '';

            $id_cart = 0;

            $cart_items = '';

            $url_img_sp = 'images/product/';

            $html .= '<ul class="woocommerce-mini-cart cart_list product_list_widget">';

            foreach (Cart::content() as $cart_items) {

                if ($cart_items->qty > 0) {

                    $id_cart = $cart_items->id;

                    $Products = get_product_by_id($id_cart);

                    if ($Products) {

                        $id = $Products->id;

                        $name = $Products->title;

                        $code = $Products->theme_code;
                    }

                    $date_now = date('Y-m-d h:i:s');

                    $discount_for_brand = Discount_for_brand::where('brand_id', '=', $Products->id_brand)

                        ->where('start_event', '<', $date_now)

                        ->where('end_event', '>', $date_now)

                        ->first();

                    if ($discount_for_brand) {

                        $price = $Products->price_origin - $Products->price_origin * $discount_for_brand->percent / 100;
                    } else {

                        if (! empty($Products->start_event) && ! empty($Products->end_event)) {

                            $date_start_event = $Products->start_event;

                            $date_end_event = $Products->end_event;

                            $price_sale = $Products->price_origin;

                            $price_regular = $Products->price_promotion;

                            if (strtotime($date_now) < strtotime($date_end_event) && strtotime($date_now) > strtotime($date_start_event)) {

                                if ($price_regular != '' && $price_regular > 1) {
                                    $price = $price_regular;
                                } else {
                                    $price = $price_sale;
                                }
                            } else {

                                $price = $price_sale;
                            }
                        } else {

                            $price_sale = $Products->price_origin;

                            $price_regular = $Products->price_promotion;

                            if ($price_regular != '' && $price_regular > 1) {
                                $price = $price_regular;
                            } else {
                                $price = $price_sale;
                            }
                        }
                    }

                    $quantity = $cart_items->qty;

                    $avariable = $cart_items->avariable;

                    $money = $quantity * $price;

                    $post_thumbnail_news = $url_img_sp.$Products->thubnail;

                    $html .= '<li class="woocommerce-mini-cart-item mini_cart_item">

                                <a href="'.url('/').'/cart/?remove='.$id.'" class="remove remove_from_cart_button">×</a>

                                <a class="cart_item_title" href="'.get_permalink_by_id($cart_items->id).'">

                                    <img width="300" height="300" src="'.$post_thumbnail_news.'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt=""/>&nbsp;'.$name.'&nbsp;

                                </a>

                                <span class="quantity">'.$quantity.' × <span class="woocommerce-Price-amount amount">'.WebService::formatMoney12($price).' <span class="woocommerce-Price-currencySymbol"></span>'.setting_option('currency').'</span></span>

                            </li>';
                }
            }

            $html .= '</ul>';
        }

        $html .= '<p class="woocommerce-mini-cart__total total"><strong>Tổng tiền:</strong> <span class="woocommerce-Price-amount amount" id="price_total_view2">'.WebService::formatMoney12(Cart::total()).'<span class="woocommerce-Price-currencySymbol">'.setting_option('currency').'</span></span></p>

                    <p class="woocommerce-mini-cart__buttons buttons"><a href="'.url('/').'/cart/" class="button checkout wc-forward mini-cart-checkout">Thanh toán</a></p>

                </div><!--widget_shopping_cart_content-->

            </div><!--widget_shopping_cart-->

        </div><!--dropdown-cat-->

    </div><!--dropdown-wrap-cat-->';

        return $html;
    }

    public function updateThemeFast(Request $request)
    {

        $id = (int) $request->id;

        $origin_price = $request->origin_price;

        $promotion_price = $request->promotion_price;

        $order_short = $request->order_short;

        $start_event = $request->start_event;

        $end_event = $request->end_event;

        if ($id > 0) {

            $data = [

                'price_origin' => $origin_price,

                'price_promotion' => $promotion_price,

                'order_short' => $order_short,

                'start_event' => $start_event,

                'end_event' => $end_event,

                'updated' => date('Y-m-d H:i:s'),

            ];

            $respons = Theme::where('id', '=', $id)->update($data);

            echo 'OK';
        } else {

            echo 'Lỗi';
        }

        exit();
    }

    public function ajax_get_cate_on_size()
    {

        $slug = $_GET['cate_slug'];

        $size = $_GET['size'];

        $html = '';

        $category_themes = DB::table('category_theme')

            ->where('category_theme.categorySlug', '=', $slug)

            ->select('category_theme.*')

            ->first();

        $id_category = $category_themes->categoryID;

        $data_customers = DB::table('category_theme')

            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')

            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')

            ->join('theme_join_variable_theme', 'theme.id', '=', 'theme_join_variable_theme.id_theme')

            ->join('variable_theme', 'theme_join_variable_theme.variable_themeID', '=', 'variable_theme.variable_themeID')

            ->where('category_theme.categorySlug', '=', $slug)

            ->whereIn('variable_theme.variable_themeID', $size)

            ->where('theme.status', '=', 0)

            ->groupBy('theme.slug')

            ->orderByRaw('theme.updated DESC')

            ->select('theme.*', 'category_theme.categoryName', 'category_theme.categorySlug', 'category_theme.categoryDescription', 'category_theme.categoryID', 'category_theme.categoryContent', 'category_theme.categoryContent_en', 'category_theme.categoryDescription_en', 'variable_theme.variable_theme_name', 'category_theme.categorySlug', 'category_theme.categoryParent')

            ->paginate(config('app.item_list_category_product'));

        if (count($data_customers) > 0) {

            $html .= '<div class="row row-listpro">';

            $k = 0;

            $thumbnail_thumb = '';

            $url_img = '';

            $ahref = '';

            $price_origin = '';

            $price_promotion = '';

            foreach ($data_customers as $data_customer) {

                $k++;

                $url_img = 'images/product';

                if (! empty($data_customer->thubnail) && $data_customer->thubnail != '') {

                    $thumbnail_thumb = getThumbnail($url_img, $data_customer->thubnail, 350, 485, 'resize');

                    if (strpos($thumbnail_thumb, 'placehold') !== false) {

                        $thumbnail_thumb = $url_img.$thumbnail_thumb;
                    }
                } else {

                    $thumbnail_thumb = 'https://dummyimage.com/350x485/000/fff';
                }

                if (! empty($data_customer->price_origin) && $data_customer->price_origin > 0) {

                    $price_origin = number_format($data_customer->price_origin).' đ ';
                } else {

                    $price_origin = '';
                }

                if (! empty($data_customer->price_promotion) && $data_customer->price_promotion > 0) {

                    $price_promotion = number_format($data_customer->price_promotion).' đ ';
                } else {

                    $price_promotion = 'Liên hệ';
                }

                $html .= '<div id="theme_cate_'.$k.'" class="item_product_list col-lg-4 col-md-4 col-sm-6 col-xs-6 item-page-category-product" data-open="0" data-id="'.$data_customer->id.'" data-parent="'.$data_customer->categoryParent.'" data-cate="'.$id_category.'">

                  <div class="product-item">

                      <div class="item-thumb">

                          <a class="zoom" href="'.route('tintuc.details', [$data_customer->categorySlug, $data_customer->slug]).'">

                              <img src="'.$thumbnail_thumb.'" alt="'.$data_customer->thubnail_alt.'">

                          </a>

                      </div>

                      <div class="pro_info">

                          <h3 class="titleProduct">

                              <a href="'.route('tintuc.details', [$data_customer->categorySlug, $data_customer->slug]).'">'.$data_customer->title.'</a>

                          </h3>

                          <div class="flex product_price">

                              <span class="price_sale">'.$price_promotion.'</span>

                              <span class="current_price">'.$price_origin.'</span>

                          </div>

                      </div>

                  </div>

              </div>';
            }

            $html .= '</div><!--row-listpro-->

            <div class="page_navi clear">'.(string) $data_customers->links().'</div><!--page_navi-->';
        } else {

            $html .= '<div class="alert alert-danger">

               <strong>Trống!</strong> Hiện tại chưa có bài viết nào cho mục này.

           </div>';
        }

        return $html;
    }

    public function update_new_item_status(Request $request)
    {

        if (isset($request['check']) && $request['sid'] != '') {

            $status = $request['check'];

            $postID = (int) $request['sid'];

            if ($postID > 0) {

                $respons1 = Theme::where('id', '=', $postID)->update(['item_new' => $status]);

                echo 'OK';
            } else {

                echo 'Lỗi';
            }
        }
    }

    public function update_process_flash_sale(Request $request)
    {

        if (isset($request['check']) && $request['sid'] != '') {

            $status = $request['check'];

            $postID = (int) $request['sid'];

            if ($postID > 0) {

                $respons1 = Theme::where('id', '=', $postID)->update(['flash_sale' => $status]);

                echo 'OK';
            } else {

                echo 'Lỗi';
            }
        }
    }

    public function update_process_sale_top_week(Request $request)
    {

        if (isset($request['check']) && $request['sid'] != '') {

            $status = $request['check'];

            $postID = (int) $request['sid'];

            if ($postID > 0) {

                $respons1 = Theme::where('id', '=', $postID)->update(['sale_top_week' => $status]);

                echo 'OK';
            } else {

                echo 'Lỗi';
            }
        }
    }

    public function update_process_propose(Request $request)
    {

        if (isset($request['check']) && $request['sid'] != '') {

            $status = $request['check'];

            $postID = (int) $request['sid'];

            if ($postID > 0) {

                $respons1 = Theme::where('id', '=', $postID)->update(['propose' => $status]);

                echo 'OK';
            } else {

                echo 'Lỗi';
            }
        }
    }

    public function postToWishList(Request $rq)
    {

        $id_product = $rq->id_product;

        $datetime_now = date('Y-m-d H:i:s');

        $check_wishlist = Wishlist::where('id_product', '=', $id_product)

            ->where('user_id', '=', Auth::user()->id)

            ->get();

        if (count($check_wishlist) > 0) {

            $response = Wishlist::where('id_product', '=', $id_product)

                ->where('user_id', '=', Auth::user()->id)

                ->delete();

            return 0;
        } else {

            $wish = new Wishlist;

            $wish->id_product = $id_product;

            $wish->user_id = Auth::user()->id;

            $wish->created_at = $datetime_now;

            $wish->updated_at = $datetime_now;

            $wish->save();

            return 1;
        }
    }

    public function checkDiscountCode(Request $rq)
    {

        $code = $rq->code_discount;

        $result = 0;

        $id_combo = setting_option('id-cate-combo');

        $array_combo = explode(', ', $id_combo);

        if (Cart::content()->count() > 0) {

            foreach (Cart::content() as $cart_item) {

                $id_product = $cart_item->id;

                $cate = Join_Category_Theme::join('category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')

                    ->where('join_category_theme.id_theme', '=', $id_product)

                    ->select('category_theme.categoryName', 'category_theme.categoryID')

                    ->groupBy()

                    ->get();

                if ($cate) {

                    $html_cate = '';

                    foreach ($cate as $item_cate) {

                        if (in_array($item_cate->categoryID, $array_combo)) {

                            $result = -1;

                            return $result;

                            break;
                        }
                    }
                }
            }
        }

        $checkcode = Discount_code::where('discount_code.code', '=', $code)

            ->where('discount_code.status', '=', 0)

            ->first();

        if ($checkcode) {

            $date_now = date('Y-m-d h:i:s');

            if (strtotime($date_now) < strtotime($checkcode->expired)) {

                if ($checkcode->apply_for_order > 0) {

                    if (Cart::total() < $checkcode->apply_for_order) {

                        return $result;
                    } else {

                        if ($checkcode->percent != 0) {

                            $price_discount_total = Cart::total() - (Cart::total() * $checkcode->percent / 100);

                            $price_discount_total = WebService::formatMoney12($price_discount_total);

                            $discount = Cart::total() * $checkcode->percent / 100;

                            $discount = WebService::formatMoney12($discount);
                        } else {

                            $price_discount_total = Cart::total() - $checkcode->discount_money;

                            if ($price_discount_total < 0) {

                                $price_discount_total = 0;
                            }

                            $price_discount_total = WebService::formatMoney12($price_discount_total);

                            $discount = $checkcode->discount_money;

                            $discount = WebService::formatMoney12($discount);
                        }

                        $arr = [

                            'price_discount' => $price_discount_total,

                            'discount' => $discount,

                        ];

                        $arr_discount = json_encode($arr);

                        return $arr_discount;
                    }
                } else {

                    if ($checkcode->percent != 0) {

                        $price_discount_total = Cart::total() - (Cart::total() * $checkcode->percent / 100);

                        $price_discount_total = WebService::formatMoney12($price_discount_total);

                        $discount = Cart::total() * $checkcode->percent / 100;

                        $discount = WebService::formatMoney12($discount);
                    } else {

                        $price_discount_total = Cart::total() - $checkcode->discount_money;

                        if ($price_discount_total < 0) {

                            $price_discount_total = 0;
                        }

                        $price_discount_total = WebService::formatMoney12($price_discount_total);

                        $discount = $checkcode->discount_money;

                        $discount = WebService::formatMoney12($discount);
                    }

                    $arr = [

                        'price_discount' => $price_discount_total,

                        'discount' => $discount,

                    ];

                    $arr_discount = json_encode($arr);

                    return $arr_discount;
                }
            } else {

                $checkcode->status = 1;

                $checkcode->save();

                return $result;
            }
        } else {

            return $result;
        }
    }

    public function loadVariable()
    {

        $result = '';

        $child_parent = [];

        $slug_variable_parrent = '';

        $group_child = [];

        $html_col_header = '';

        $datas = json_decode(stripslashes($_POST['data']));

        $id_key_parent = '';

        $count_item = count($datas);

        if ($datas && count($datas) > 0) {

            $mh = 0;

            for ($i = 0; $i < count($datas); $i++) {

                $mh++;

                $variable_child = '';

                $key_child = '';

                $array_child = [];

                $variable_id = (int) $datas[$i];

                if ($variable_id > 0) {

                    $variables = Variable_Theme::where('variable_themeID', $variable_id)

                        ->where('variable_theme_status', 0)

                        ->first();

                    if ($variables) {

                        $slug_variable_parrent = $variables->variable_theme_slug;

                        $variable_childs = Variable_Theme::where('variable_theme_parent', $variable_id)

                            ->where('variable_theme_status', 0)

                            ->get();

                        if ($variable_childs) {

                            $html_col_header .= '<div class="table-cell readonly">'.$variables->variable_theme_name.'</div>';

                            $id_key_parent .= $variables->variable_themeID.',';

                            foreach ($variable_childs as $variable_child) {

                                $key_child = $variable_child->variable_theme_slug;

                                // $array_key[$key_child]=array($variable_child->variable_theme_name);

                                $array_child[$key_child] = $variable_child->variable_theme_name.'_'.$variable_child->variable_themeID;

                                $group_child = $array_child;
                            }
                        }
                    }
                }

                $child_parent[$slug_variable_parrent] = $group_child;
            }

            // print_r($child_parent);

            $array_fitters = variations($child_parent);

            if ($array_fitters && count($array_fitters) > 0) {

                $id_key_parent = substr($id_key_parent, 0, -1);

                // print_r($array_fitters);

                $result .= '<input type="hidden" name="parent_variable_group" value="'.$id_key_parent.'"/>

                    <div class="edit-main variation-table">

                        <div class="table-header">

                            <input type="hidden" name="number_parent_variable" id="number_parent_variable" value="'.$mh.'"/>

                            '.$html_col_header.'

                            <div class="table-cells">

                                <div class="table-cell">Giá</div>

                                <div class="table-cell table-cell-two">Image</div>

                            </div>

                        </div>

                        <div class="table-body">';

                $explode_ex = [];

                for ($m = 0; $m < count($array_fitters); $m++) {

                    $result .= '<div class="table-row">';

                    $k = 0;

                    foreach ($array_fitters[$m] as $key => $value) {

                        $k++;

                        $explode_ex = explode('_', $value);

                        $result .= '<div class="table-cell readonly">'.$explode_ex[0].'</div><input type="hidden" name="variable_'.$m.$k.'" value="'.$explode_ex[1].'">';
                    }

                    $result .= '<div class="table-cells">

                                        <div class="table-cell">

                                            <div class="anhduong-input__prefix">₫<span class="anhduong-input__prefix-split"></span><!----><!----></div>

                                            <input type="text"  class="anhduong-input__input" name="price_aviable_'.$m.'">

                                        </div>

                                        <div class="table-cell table-cell-two">

                                            <div class="group_variable_images clear">

                                                <div class="inside clear">

                                                    <div class="text_input_file_image_variable">

                                                        <input class="myfile_gallery_store_select" type="text" value="" size="50" placeholder="Hình ảnh" name="text_upload_gallery_variable_'.$m.'">

                                                    </div>

                                                    <div class="mybutton_upload_img">

                                                        <input class="upload_gallery_variable_select" type="file"  id="upload_gallery_variable_'.$m.'" name="upload_gallery_variable_'.$m.'">UP

                                                    </div>

                                                </div><!--inside-->

                                            </div>

                                        </div>

                                    </div>

                                </div>';
                }

                $result .= '<input type="hidden" name="count_item_list" value="'.$m.'"/>

                        </div>

                    </div>';
            } else {

                $result = '';
            }
        } else {

            $result = '';
        }

        return $result;
    }

    public function searchSelect(Request $request)
    {

        $this->localized();

        $keyword = trim((string) $request->keyword);

        if ($keyword !== '') {

            $data['keyword'] = $request->keyword;

            $type = $request->type;

            $data['slug'] = null;
            try {
                if ($type == 'mua_ban') {
                    $data['slug'] = route('product.category', 'nha-dat-ban');
                } elseif ($type == 'cho_thue') {
                    $data['slug'] = route('product.category');
                } elseif ($type == 'du_an') {
                    $data['slug'] = route('project.category');
                } else {
                    $data['slug'] = route('search');
                }
            } catch (\Throwable $e) {
                $data['slug'] = url('/search');
            }

            $data['city'] = collect();
            $data['districts'] = collect();
            if (Schema::hasTable('province')) {
                $data['city'] = DB::table('province')
                    ->where('name', 'like', '%'.$keyword.'%')
                    ->limit(10)
                    ->get();
            }
            if (Schema::hasTable('district')) {
                $data['districts'] = DB::table('district')
                    ->where('name', 'like', '%'.$keyword.'%')
                    ->limit(10)
                    ->get();
            }

            $data['products'] = Product::query()
                ->where('status', 1)
                ->where('name', 'like', '%'.$keyword.'%')
                ->limit(10)
                ->get();

            $data['categories_place'] = Category::query()
                ->where('name', 'like', '%'.$keyword.'%')
                ->limit(10)
                ->get();

            if (view()->exists('theme.layout.search-item')) {
                $view = view('theme.layout.search-item', compact('data'))->render();

                return response()->json($view);
            }

            return response()->json('');
        }

        return response()->json('');
    }

    public function inputSearchText(string $type, Request $request)
    {

        if ($type == 'street') {

            $keyword = $request->keyword;

            $district = $request->district ?? 1;

            $this->data['type'] = $type;

            $streets = collect();
            if (Schema::hasTable('street')) {
                $streets = DB::table('street')
                    ->distinct()
                    ->where('name', 'like', '%'.$keyword.'%')
                    ->where('district_id', $district)
                    ->pluck('name');
            }

            $this->data['streets'] = $streets;

            if (view()->exists('theme.dangtin.form.search-input-item')) {
                $this->data['view'] = view('theme.dangtin.form.search-input-item', ['data' => $this->data])->render();
            } else {
                $this->data['view'] = '';
            }
        }

        $this->data['status'] = 'success';

        return response()->json($this->data);
    }
}

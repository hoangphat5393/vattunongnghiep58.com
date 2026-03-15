<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Image;
use App\Facades\WebService;
use DB;
use Session;
use Route;
use HTML;
//use Request;
use Crypt;
use App\Models\Slishow;
use App\Models\Discount_for_brand;

class ApiController extends Controller
{
    public function Slider()
    {
        $sliders = Slishow::where('status', '=', 0)->orderBy('order', 'DESC')->get();
        if ($sliders && count($sliders)) :
            return json_encode($sliders);
        else :
            return null;
        endif;
    }
    public function CategoryProducts()
    {
        $categoryProducts = DB::table('category_theme')
            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')
            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')
            ->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select(
                'theme.id',
                'theme.title',
                'theme.slug',
                'theme.description',
                'theme.thubnail',
                'theme.thubnail_alt',
                'theme.price_origin',
                'theme.price_promotion',
                'theme.start_event',
                'theme.end_event',
                'theme.countdown',
                'theme.theme_code',
                'theme.updated',
                'theme.id_brand',
                'theme.in_stock',
                'category_theme.categoryID',
                'category_theme.categoryName',
                'category_theme.categorySlug',
                'category_theme.categoryDescription',
                'category_theme.categoryID',
                'category_theme.categoryContent',
                'category_theme.categoryContent_en',
                'category_theme.categoryDescription_en'
            )
            ->paginate(config('app.item_api_product'));
        if ($categoryProducts && count($categoryProducts)) :
            return json_encode($categoryProducts);
        else :
            return null;
        endif;
    }
    public function CategoryProductsID($id)
    {
        $categoryProducts = DB::table('category_theme')
            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')
            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')
            ->where('category_theme.categoryID', '=', $id)
            ->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select(
                'theme.id',
                'theme.title',
                'theme.slug',
                'theme.description',
                'theme.thubnail',
                'theme.thubnail_alt',
                'theme.price_origin',
                'theme.price_promotion',
                'theme.start_event',
                'theme.end_event',
                'theme.countdown',
                'theme.theme_code',
                'theme.updated',
                'theme.id_brand',
                'theme.in_stock',
                'category_theme.categoryName',
                'category_theme.categorySlug',
                'category_theme.categoryDescription',
                'category_theme.categoryID',
                'category_theme.categoryContent',
                'category_theme.categoryContent_en',
                'category_theme.categoryDescription_en'
            )
            ->paginate(config('app.item_api_product'));
        if ($categoryProducts && count($categoryProducts)) :
            return json_encode($categoryProducts);
        else :
            return null;
        endif;
    }

    public function ProductsList()
    {
        $products = DB::table('theme')->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select(
                'theme.id',
                'theme.title',
                'theme.slug',
                'theme.description',
                'theme.thubnail',
                'theme.thubnail_alt',
                'theme.price_origin',
                'theme.price_promotion',
                'theme.start_event',
                'theme.end_event',
                'theme.countdown',
                'theme.theme_code',
                'theme.updated',
                'theme.id_brand',
                'theme.in_stock'
            )
            ->paginate(config('app.item_api_product'));
        if ($products && count($products)) :
            return json_encode($products);
        else :
            return null;
        endif;
    }
    public function ProductSingle($id)
    {
        $products = DB::table('theme')
            ->where('theme.id', '=', $id)
            ->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select('theme.*')
            ->first();
        if ($products && count($products)) :
            return json_encode($products);
        else :
            $products_slug = DB::table('theme')
                ->where('theme.slug', '=', $id)
                ->where('theme.status', '=', 0)
                ->orderBy('theme.updated', 'DESC')
                ->groupBy('theme.slug')
                ->select('theme.*')
                ->first();
            if ($products_slug && count($products_slug)) :
                return json_encode($products_slug);
            else :
                return null;
            endif;
        endif;
    }
    public function CategoryProductsIDHTML($id)
    {
        $rows = DB::table('category_theme')
            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')
            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')
            ->where('theme.flash_sale', '=', 1)
            ->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select(
                'theme.id',
                'theme.title',
                'theme.slug',
                'theme.description',
                'theme.thubnail',
                'theme.thubnail_alt',
                'theme.price_origin',
                'theme.price_promotion',
                'theme.start_event',
                'theme.end_event',
                'theme.countdown',
                'theme.theme_code',
                'theme.updated',
                'theme.id_brand',
                'theme.in_stock',
                'theme.item_new',
                'category_theme.categoryName',
                'category_theme.categorySlug',
                'category_theme.categoryDescription',
                'category_theme.categoryID',
                'category_theme.categoryContent',
                'category_theme.categoryContent_en',
                'category_theme.categoryDescription_en'
            )
            ->paginate($id);
        $result = "";
        if ($rows) :
            $color = "";
            $val_td = 0;
            $percent = 0;
            $note_percent = "";
            $circle_sale = '';
            $price_promotion = "";
            //$result.= '<div class="products-grid product-grid-4 flex">';
            $result .= '<div class="row product_list_item_render_home product_list_flash_sale">';
            foreach ($rows as $row) {
                $url_img = "images/product";
                $note_percent = "";
                $note_new_item = "";
                $note_best_seller = "";
                $circle_sale = '';
                //id category bán chạy = 69
                $check_bestseller = DB::table('join_category_theme')
                    ->where('id_theme', '=', $row->id)
                    ->where('id_category_theme', '=', 69)
                    ->get();
                if (count($check_bestseller) > 0) {
                    $note_best_seller = '<span class="label bestseller">BEST</span>';
                } else {
                    $note_best_seller = '';
                }
                $new_item = $row->item_new;
                if ($new_item == 1) {
                    $note_new_item = '<span class="label new">NEW</span>';
                } else {
                    $note_new_item = '';
                }
                if (!empty($row->thubnail) && $row->thubnail != "") :
                    $thumbnail = getThumbnail($url_img, $row->thubnail, 440, 440, "resize");
                    if (strpos($thumbnail, 'placehold') !== false) :
                        $thumbnail = $url_img . $thumbnail;
                    endif;
                else :
                    $thumbnail = "https://dummyimage.com/440x440/000/fff";
                endif;
                //$thumbnail=$url_img."/".$row->thubnail;
                //check event discount
                $date_now = date("Y-m-d H:i:s");
                $discount_for_brand = Discount_for_brand::where('brand_id', '=', $row->id_brand)
                    ->where('start_event', '<', $date_now)
                    ->where('end_event', '>', $date_now)
                    ->first();
                $price_pastion = 0;
                if ($discount_for_brand) {
                    $price_origin = number_format($row->price_origin) . " đ ";
                    $price_promotion = $row->price_origin - $row->price_origin * $discount_for_brand->percent / 100;
                    $price_promotion = number_format($price_promotion) . " đ ";
                    $note_percent = '<span class="label sale">SALE</span>';
                    $circle_sale = '<span class="circle-sale">
                        <span>-' . intval($discount_for_brand->percent) . '%</span>
                    </span>';
                } else {
                    if (!empty($row->start_event) && !empty($row->end_event)) {
                        $date_start_event = $row->start_event;
                        $date_end_event = $row->end_event;
                        if (strtotime($date_now) < strtotime($date_end_event) && strtotime($date_now) > strtotime($date_start_event)) {
                            if (!empty($row->price_origin) &&  $row->price_origin > 0) :
                                $price_origin = number_format($row->price_origin) . " đ ";
                            else :
                                $price_origin = "";
                            endif;
                            if (!empty($row->price_promotion) &&  $row->price_promotion > 0) :
                                $price_promotion = number_format($row->price_promotion) . " đ ";
                            else :
                                $price_promotion = "Liên hệ";
                            endif;

                            if (intval($row->price_promotion) <= intval($row->price_origin) && $row->price_promotion != '' && $row->price_origin != '') :
                                $val_td = intval($row->price_origin) - intval($row->price_promotion);
                                $percent = ($val_td / intval($row->price_origin)) * 100;
                                // $note_percent="<span class='note_percent'>".intval($percent). "%</span>";
                                $note_percent = '<span class="label sale">SALE</span>';
                                $circle_sale = '<span class="circle-sale">
                                    <span>-' . intval($percent) . '%</span>
                                </span>';
                            else :
                                $val_td = 0;
                                $percent = 0;
                                $note_percent = "";
                                $circle_sale = '';
                            endif;
                        } else {
                            if (!empty($row->price_origin) &&  $row->price_origin > 0) {
                                $price_origin = "";
                                $price_promotion = number_format($row->price_origin) . " đ ";
                            }
                        }
                    } else {
                        if (!empty($row->price_origin) &&  $row->price_origin > 0) :
                            $price_origin = number_format($row->price_origin) . " đ ";
                        else :
                            $price_origin = "";
                        endif;
                        if (!empty($row->price_promotion) &&  $row->price_promotion > 0) :
                            $price_promotion = number_format($row->price_promotion) . " đ ";
                        else :
                            $price_promotion = "Liên hệ";
                        endif;

                        if (intval($row->price_promotion) <= intval($row->price_origin) && $row->price_promotion != '' && $row->price_origin != '') :
                            $val_td = intval($row->price_origin) - intval($row->price_promotion);
                            $percent = ($val_td / intval($row->price_origin)) * 100;
                            $note_percent = '<span class="label sale">SALE</span>';
                            $circle_sale = '<span class="circle-sale">
                                <span>-' . intval($percent) . '%</span>
                            </span>';
                        else :
                            $val_td = 0;
                            $percent = 0;
                            $note_percent = "";
                            $circle_sale = '';
                        endif;
                    }
                }
                $price_pastion = 0;
                if ($price_promotion == '') :
                    $price_pastion = intval(str_replace(array(",", "đ", " "), "", $price_origin));
                    $check_product = check_product_variable($row->id);
                    if ($check_product) {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list pull-right" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    } else {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list pull-right" data-id="' . $row->id . '" data-name="' . $row->title . '" data-summary="' . $row->theme_code . '" data-price="' . $price_pastion . '" data-quantity="1" data-image="' . url('/images/product/') . "/" . $row->thubnail . '" tabindex="-1"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    }
                    $result .= '
                        <div class="product-item col col-lg-5ths">
                            <div class="product_item_list clear">
                                <div class="item-thumb">
                                    <a class="effect" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">
                                        <img src="' . $thumbnail . '" alt="' . $row->thubnail_alt . '"/>
                                        <span class="productlabels_icons clear">' . $note_new_item . $note_percent . $note_best_seller . '</span>
                                    </a>
                                </div>
                                <div class="pro-info">
                                    <h3 class="product-name"><a href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">' . $row->title . '</a></h3>
                                    <div class="price-box clear">
                                        <div class="box_price_item">
                                            <span class="regular-price" id="product-price-' . $row->id . '">
                                                <span class="price">' . $price_origin . '</span>                                    
                                            </span>
                                        </div>
                                        <div class="tbl_add_cart asolute_cart clear">
                                             ' . $btn_mua_ngay . '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                else :
                    $price_pastion = intval(str_replace(array(",", "đ", " "), "", $price_promotion));
                    $check_product = check_product_variable($row->id);
                    if ($check_product) {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list pull-right" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    } else {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list pull-right" data-id="' . $row->id . '" data-name="' . $row->title . '" data-summary="' . $row->theme_code . '" data-price="' . $price_pastion . '" data-quantity="1" data-image="' . url('/images/product/') . "/" . $row->thubnail . '" tabindex="-1"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    }
                    $result .= '
                        <div class="product-item col col-lg-5ths">
                            <div class="product_item_list clear">
                                <div class="item-thumb">
                                    <a class="effect" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">
                                        <img src="' . $thumbnail . '" alt="' . $row->thubnail_alt . '"/>
                                        <span class="productlabels_icons clear">' . $note_new_item . $note_percent . $note_best_seller . '</span>
                                    </a>
                                    ' . $circle_sale . '
                                </div>
                                <div class="pro-info">
                                    <h3 class="product-name"><a href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">' . $row->title . '</a></h3>
                                    <div class="price-box clear">
                                        <div class="box_price_item">
                                            <span class="old-price">
                                                <span class="price-label">Regular Price:</span>
                                                <span class="price" id="old-price-' . $row->id . '">
                                                    ' . $price_origin . '               
                                                </span>
                                            </span>
                                            <span class="special-price">
                                                <span class="price-label">Special Price</span>
                                                <span class="price" id="product-price-' . $row->id . '">
                                                    ' . $price_promotion . '              
                                                </span>
                                            </span>
                                            
                                        </div>
                                        <div class="tbl_add_cart asolute_cart clear">
                                             ' . $btn_mua_ngay . '
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>';
                endif;
            }
            $result .= '</div>';
        endif;
        return json_encode($result);
    }
    public function CategoryProductsIDHTMLSelling($id)
    {
        $rows = DB::table('category_theme')
            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')
            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')
            ->where('theme.sale_top_week', '=', 1)
            ->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select(
                'theme.id',
                'theme.title',
                'theme.slug',
                'theme.description',
                'theme.thubnail',
                'theme.thubnail_alt',
                'theme.price_origin',
                'theme.price_promotion',
                'theme.start_event',
                'theme.end_event',
                'theme.countdown',
                'theme.theme_code',
                'theme.updated',
                'theme.id_brand',
                'theme.in_stock',
                'theme.item_new',
                'category_theme.categoryName',
                'category_theme.categorySlug',
                'category_theme.categoryDescription',
                'category_theme.categoryID',
                'category_theme.categoryContent',
                'category_theme.categoryContent_en',
                'category_theme.categoryDescription_en'
            )
            ->paginate($id);
        $result = "";
        if ($rows) :
            $color = "";
            $val_td = 0;
            $percent = 0;
            $note_percent = "";
            $circle_sale = '';
            $price_promotion = "";
            //$result.= '<div class="products-grid product-grid-4 flex">';
            $result .= '<div class="row product_list_item_render_home product_list_flash_sale">';
            foreach ($rows as $row) {
                $url_img = "images/product";
                $note_percent = "";
                $note_new_item = "";
                $note_best_seller = "";
                $circle_sale = '';
                //id category bán chạy = 69
                $check_bestseller = DB::table('join_category_theme')
                    ->where('id_theme', '=', $row->id)
                    ->where('id_category_theme', '=', 69)
                    ->get();
                if (count($check_bestseller) > 0) {
                    $note_best_seller = '<span class="label bestseller">BEST</span>';
                } else {
                    $note_best_seller = '';
                }
                $new_item = $row->item_new;
                if ($new_item == 1) {
                    $note_new_item = '<span class="label new">NEW</span>';
                } else {
                    $note_new_item = '';
                }
                if (!empty($row->thubnail) && $row->thubnail != "") :
                    $thumbnail = getThumbnail($url_img, $row->thubnail, 440, 440, "resize");
                    if (strpos($thumbnail, 'placehold') !== false) :
                        $thumbnail = $url_img . $thumbnail;
                    endif;
                else :
                    $thumbnail = "https://dummyimage.com/440x440/000/fff";
                endif;
                //$thumbnail=$url_img."/".$row->thubnail;
                //check event discount
                $date_now = date("Y-m-d H:i:s");
                $discount_for_brand = Discount_for_brand::where('brand_id', '=', $row->id_brand)
                    ->where('start_event', '<', $date_now)
                    ->where('end_event', '>', $date_now)
                    ->first();
                $price_pastion = 0;
                if ($discount_for_brand) {
                    $price_origin = number_format($row->price_origin) . " đ ";
                    $price_promotion = $row->price_origin - $row->price_origin * $discount_for_brand->percent / 100;
                    $price_promotion = number_format($price_promotion) . " đ ";
                    $note_percent = '<span class="label sale">SALE</span>';
                    $circle_sale = '<span class="circle-sale">
                        <span>-' . intval($discount_for_brand->percent) . '%</span>
                    </span>';
                } else {
                    if (!empty($row->start_event) && !empty($row->end_event)) {
                        $date_start_event = $row->start_event;
                        $date_end_event = $row->end_event;
                        if (strtotime($date_now) < strtotime($date_end_event) && strtotime($date_now) > strtotime($date_start_event)) {
                            if (!empty($row->price_origin) &&  $row->price_origin > 0) :
                                $price_origin = number_format($row->price_origin) . " đ ";
                            else :
                                $price_origin = "";
                            endif;
                            if (!empty($row->price_promotion) &&  $row->price_promotion > 0) :
                                $price_promotion = number_format($row->price_promotion) . " đ ";
                            else :
                                $price_promotion = "Liên hệ";
                            endif;

                            if (intval($row->price_promotion) <= intval($row->price_origin) && $row->price_promotion != '' && $row->price_origin != '') :
                                $val_td = intval($row->price_origin) - intval($row->price_promotion);
                                $percent = ($val_td / intval($row->price_origin)) * 100;
                                // $note_percent="<span class='note_percent'>".intval($percent). "%</span>";
                                $note_percent = '<span class="label sale">SALE</span>';
                                $circle_sale = '<span class="circle-sale">
                                    <span>-' . intval($percent) . '%</span>
                                </span>';
                            else :
                                $val_td = 0;
                                $percent = 0;
                                $note_percent = "";
                                $circle_sale = '';
                            endif;
                        } else {
                            if (!empty($row->price_origin) &&  $row->price_origin > 0) {
                                $price_origin = "";
                                $price_promotion = number_format($row->price_origin) . " đ ";
                            }
                        }
                    } else {
                        if (!empty($row->price_origin) &&  $row->price_origin > 0) :
                            $price_origin = number_format($row->price_origin) . " đ ";
                        else :
                            $price_origin = "";
                        endif;
                        if (!empty($row->price_promotion) &&  $row->price_promotion > 0) :
                            $price_promotion = number_format($row->price_promotion) . " đ ";
                        else :
                            $price_promotion = "Liên hệ";
                        endif;

                        if (intval($row->price_promotion) <= intval($row->price_origin) && $row->price_promotion != '' && $row->price_origin != '') :
                            $val_td = intval($row->price_origin) - intval($row->price_promotion);
                            $percent = ($val_td / intval($row->price_origin)) * 100;
                            // $note_percent="<span class='note_percent'>".intval($percent). "%</span>";
                            $note_percent = '<span class="label sale">SALE</span>';
                            $circle_sale = '<span class="circle-sale">
                                <span>-' . intval($percent) . '%</span>
                            </span>';
                        else :
                            $val_td = 0;
                            $percent = 0;
                            $note_percent = "";
                            $circle_sale = '';
                        endif;
                    }
                }
                $price_pastion = 0;
                if ($price_promotion == '') :
                    $price_pastion = intval(str_replace(array(",", "đ", " "), "", $price_origin));
                    $check_product = check_product_variable($row->id);
                    if ($check_product) {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    } else {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list" data-id="' . $row->id . '" data-name="' . $row->title . '" data-summary="' . $row->theme_code . '" data-price="' . $price_pastion . '" data-quantity="1" data-image="' . url('/images/product/') . "/" . $row->thubnail . '" tabindex="-1"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    }
                    $result .= '
                        <div class="product-item col col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="product_item_list clear">
                                <div class="row">
                                    <div class="item-thumb col-lg-5 col-md-5 col-xs-12">
                                        <a class="effect" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">
                                            <img src="' . $thumbnail . '" alt="' . $row->thubnail_alt . '"/>
                                            <span class="productlabels_icons clear">' . $note_new_item . $note_percent . $note_best_seller . '</span>
                                        </a>
                                    </div>
                                    <div class="pro-info col-lg-7 col-md-7 col-xs-12">
                                        <h3 class="product-name"><a href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">' . $row->title . '</a></h3>
                                        <div class="price-box clear">
                                            <div class="box_price_item">
                                                <span class="regular-price" id="product-price-' . $row->id . '">
                                                    <span class="price">' . $price_origin . '</span>                                    
                                                </span>
                                            </div>
                                            <div class="tbl_add_cart asolute_cart clear">
                                                 ' . $btn_mua_ngay . '
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                else :
                    $price_pastion = intval(str_replace(array(",", "đ", " "), "", $price_promotion));
                    $check_product = check_product_variable($row->id);
                    if ($check_product) {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    } else {
                        $btn_mua_ngay = '<a rel="nofollow" id="tbl_add_cart_' . $row->id . '" class="btn_click_postype btn btn-cart-list" data-id="' . $row->id . '" data-name="' . $row->title . '" data-summary="' . $row->theme_code . '" data-price="' . $price_pastion . '" data-quantity="1" data-image="' . url('/images/product/') . "/" . $row->thubnail . '" tabindex="-1"><span class="fa fa-shopping-cart"></span> Chọn Mua</a>';
                    }
                    $result .= '
                        <div class="product-item col col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="product_item_list clear">
                                <div class="row">
                                    <div class="item-thumb col-lg-5 col-md-5 col-xs-12">
                                        <a class="effect" href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">
                                            <img src="' . $thumbnail . '" alt="' . $row->thubnail_alt . '"/>
                                            <span class="productlabels_icons clear">' . $note_new_item . $note_percent . $note_best_seller . '</span>
                                        </a>
                                        ' . $circle_sale . '
                                    </div>
                                    <div class="pro-info col-lg-7 col-md-7 col-xs-12">
                                        <h3 class="product-name"><a href="' . route('tintuc.details', array($row->categorySlug, $row->slug)) . '">' . $row->title . '</a></h3>
                                        <div class="price-box clear">
                                            <div class="box_price_item">
                                                <span class="special-price">
                                                    <span class="price-label">Special Price</span>
                                                    <span class="price" id="product-price-' . $row->id . '">
                                                        ' . $price_promotion . '              
                                                    </span>
                                                </span>
                                                <span class="old-price">
                                                    <span class="price-label">Regular Price:</span>
                                                    <span class="price" id="old-price-' . $row->id . '">
                                                        ' . $price_origin . '               
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="tbl_add_cart asolute_cart clear">
                                                 ' . $btn_mua_ngay . '
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>';
                endif;
            }
            $result .= '</div>';
        endif;
        return json_encode($result);
    }
    public function CategoryProductsIDHTMLOffer($id, Request $request)
    {
        $items = DB::table('category_theme')
            ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')
            ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')
            ->where('theme.propose', '=', 1)
            ->where('theme.status', '=', 0)
            ->orderBy('theme.updated', 'DESC')
            ->groupBy('theme.slug')
            ->select(
                'theme.id',
                'theme.title',
                'theme.slug',
                'theme.description',
                'theme.thubnail',
                'theme.thubnail_alt',
                'theme.price_origin',
                'theme.price_promotion',
                'theme.start_event',
                'theme.end_event',
                'theme.countdown',
                'theme.theme_code',
                'theme.updated',
                'theme.id_brand',
                'theme.in_stock',
                'theme.item_new',
                'category_theme.categoryName',
                'category_theme.categorySlug',
                'category_theme.categoryDescription',
                'category_theme.categoryID',
                'category_theme.categoryContent',
                'category_theme.categoryContent_en',
                'category_theme.categoryDescription_en'
            )
            ->simplePaginate((int) $request->get('limit', $id));

        return response()
            ->json($items)
            ->withCallback($request->callback);
    }
}

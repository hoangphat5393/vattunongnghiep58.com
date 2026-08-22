<?php

use App\Models\Backend\Setting;
use App\Models\Backend\ShopCurrency;
use App\Models\Frontend\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

// Product kind
define('SC_PRODUCT_SINGLE', 0);
define('SC_PRODUCT_BUILD', 1);
define('SC_PRODUCT_GROUP', 2);

// Product property
define('SC_PROPERTY_PHYSICAL', 'physical');
define('SC_PROPERTY_DOWNLOAD', 'download');

// list ID admin guard
define('SC_GUARD_ADMIN', ['1']); // admin

// list ID language guard
define('SC_GUARD_LANGUAGE', ['1', '2']); // vi, en

// list ID currency guard
define('SC_GUARD_CURRENCY', ['1', '2']); // vndong , usd

// list ID ROLES guard
define('SC_GUARD_ROLES', ['1', '2']); // admin, only view

define('SC_PRICE_FILTER', [1 => 'Từ 0 - 1.000.000 đ', 2 => 'Từ 1.000.000 đ - 3.000.000 đ', 3 => 'Từ 3.000.000 đ - 5.000.000 đ', 4 => 'Từ 5.000.000 đ - 10.000.000 đ', 5 => 'Từ 10.000.000 đ - Trở lên']); // price filter

/**
 * Admin define
 */
define('SC_ADMIN_MIDDLEWARE', ['web', 'admin']);
define('SC_FRONT_MIDDLEWARE', ['web', 'front']);
define('SC_API_MIDDLEWARE', ['api', 'api.extent']);
define('SC_CONNECTION', 'mysql');
define('SC_CONNECTION_LOG', 'mysql');

// Prefix url admin
define('SC_ADMIN_PREFIX', env('ADMIN_PREFIX', 'admin'));

if (! function_exists('setting_option')) {
    function setting_option($variable = '')
    {
        $data = null;

        if (Cache::has('theme_option')) {
            $cached = Cache::get('theme_option');

            if (is_array($cached)) {
                $data = Setting::hydrate($cached);
            } else {
                Cache::forget('theme_option');
            }
        }

        if (! $data) {
            $data = Setting::get();
            Cache::forever('theme_option', $data->toArray());
        }

        if ($data) {
            $option = $data->where('name', $variable)->first();

            if ($option) {
                $content = $option->content;
                if ($option->type == 'editor' || $option->type == 'text') {
                    $content = htmlspecialchars_decode(htmlspecialchars_decode($content));
                }

                return $content;
            }
        }
    }
}

// if (!function_exists('setting_cost')) {
//     function setting_cost($variable = '')
//     {
//         $data = SettingCost::get();
//         if ($data) {
//             $option = $data->where('name', $variable)->first();
//             // dd($option);
//             if ($option) {
//                 $content = $option->content;
//                 if ($option->type == 'editor' || $option->type == 'text')
//                     $content = htmlspecialchars_decode(htmlspecialchars_decode($content));
//                 return $content;
//             }
//         }
//     }
// }

if (! function_exists('permalink_by_id')) {
    function permalink_by_id($sid)
    {
        $product = Product::find($sid);
        if (! $product || ! $product->slug) {
            return '';
        }

        return route('product.detail', ['slug' => $product->slug, 'id' => $product->id]);
    }
}

if (! function_exists('arrayPaginator')) {
    function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = config('app.item_list_category_product');
        $offset = ($page * $perPage) - $perPage;
        $url = str_replace(Request::getRequestUri(), '', URL::current());

        return new LengthAwarePaginator(
            array_slice($array, $offset, $perPage, true),
            count($array),
            $perPage,
            $page,
            ['path' => URL::current(), 'query' => $request->query()]
        );
    }
}

if (! function_exists('remove_accents')) {
    function remove_accents($string)
    {
        if (! preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        if (seems_utf8($string)) {
            $chars = [
                // Decompositions for Latin-1 Supplement
                'ª' => 'a',
                'º' => 'o',
                'À' => 'A',
                'Á' => 'A',
                'Â' => 'A',
                'Ã' => 'A',
                'Ä' => 'A',
                'Å' => 'A',
                'Æ' => 'AE',
                'Ç' => 'C',
                'È' => 'E',
                'É' => 'E',
                'Ê' => 'E',
                'Ë' => 'E',
                'Ì' => 'I',
                'Í' => 'I',
                'Î' => 'I',
                'Ï' => 'I',
                'Ð' => 'D',
                'Ñ' => 'N',
                'Ò' => 'O',
                'Ó' => 'O',
                'Ô' => 'O',
                'Õ' => 'O',
                'Ö' => 'O',
                'Ù' => 'U',
                'Ú' => 'U',
                'Û' => 'U',
                'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'TH',
                'ß' => 's',
                'à' => 'a',
                'á' => 'a',
                'â' => 'a',
                'ã' => 'a',
                'ä' => 'a',
                'å' => 'a',
                'æ' => 'ae',
                'ç' => 'c',
                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',
                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',
                'ð' => 'd',
                'ñ' => 'n',
                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'õ' => 'o',
                'ö' => 'o',
                'ø' => 'o',
                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ü' => 'u',
                'ý' => 'y',
                'þ' => 'th',
                'ÿ' => 'y',
                'Ø' => 'O',
                // Decompositions for Latin Extended-A
                'Ā' => 'A',
                'ā' => 'a',
                'Ă' => 'A',
                'ă' => 'a',
                'Ą' => 'A',
                'ą' => 'a',
                'Ć' => 'C',
                'ć' => 'c',
                'Ĉ' => 'C',
                'ĉ' => 'c',
                'Ċ' => 'C',
                'ċ' => 'c',
                'Č' => 'C',
                'č' => 'c',
                'Ď' => 'D',
                'ď' => 'd',
                'Đ' => 'D',
                'đ' => 'd',
                'Ē' => 'E',
                'ē' => 'e',
                'Ĕ' => 'E',
                'ĕ' => 'e',
                'Ė' => 'E',
                'ė' => 'e',
                'Ę' => 'E',
                'ę' => 'e',
                'Ě' => 'E',
                'ě' => 'e',
                'Ĝ' => 'G',
                'ĝ' => 'g',
                'Ğ' => 'G',
                'ğ' => 'g',
                'Ġ' => 'G',
                'ġ' => 'g',
                'Ģ' => 'G',
                'ģ' => 'g',
                'Ĥ' => 'H',
                'ĥ' => 'h',
                'Ħ' => 'H',
                'ħ' => 'h',
                'Ĩ' => 'I',
                'ĩ' => 'i',
                'Ī' => 'I',
                'ī' => 'i',
                'Ĭ' => 'I',
                'ĭ' => 'i',
                'Į' => 'I',
                'į' => 'i',
                'İ' => 'I',
                'ı' => 'i',
                'Ĳ' => 'IJ',
                'ĳ' => 'ij',
                'Ĵ' => 'J',
                'ĵ' => 'j',
                'Ķ' => 'K',
                'ķ' => 'k',
                'ĸ' => 'k',
                'Ĺ' => 'L',
                'ĺ' => 'l',
                'Ļ' => 'L',
                'ļ' => 'l',
                'Ľ' => 'L',
                'ľ' => 'l',
                'Ŀ' => 'L',
                'ŀ' => 'l',
                'Ł' => 'L',
                'ł' => 'l',
                'Ń' => 'N',
                'ń' => 'n',
                'Ņ' => 'N',
                'ņ' => 'n',
                'Ň' => 'N',
                'ň' => 'n',
                'ŉ' => 'n',
                'Ŋ' => 'N',
                'ŋ' => 'n',
                'Ō' => 'O',
                'ō' => 'o',
                'Ŏ' => 'O',
                'ŏ' => 'o',
                'Ő' => 'O',
                'ő' => 'o',
                'Œ' => 'OE',
                'œ' => 'oe',
                'Ŕ' => 'R',
                'ŕ' => 'r',
                'Ŗ' => 'R',
                'ŗ' => 'r',
                'Ř' => 'R',
                'ř' => 'r',
                'Ś' => 'S',
                'ś' => 's',
                'Ŝ' => 'S',
                'ŝ' => 's',
                'Ş' => 'S',
                'ş' => 's',
                'Š' => 'S',
                'š' => 's',
                'Ţ' => 'T',
                'ţ' => 't',
                'Ť' => 'T',
                'ť' => 't',
                'Ŧ' => 'T',
                'ŧ' => 't',
                'Ũ' => 'U',
                'ũ' => 'u',
                'Ū' => 'U',
                'ū' => 'u',
                'Ŭ' => 'U',
                'ŭ' => 'u',
                'Ů' => 'U',
                'ů' => 'u',
                'Ű' => 'U',
                'ű' => 'u',
                'Ų' => 'U',
                'ų' => 'u',
                'Ŵ' => 'W',
                'ŵ' => 'w',
                'Ŷ' => 'Y',
                'ŷ' => 'y',
                'Ÿ' => 'Y',
                'Ź' => 'Z',
                'ź' => 'z',
                'Ż' => 'Z',
                'ż' => 'z',
                'Ž' => 'Z',
                'ž' => 'z',
                'ſ' => 's',
                // Decompositions for Latin Extended-B
                'Ș' => 'S',
                'ș' => 's',
                'Ț' => 'T',
                'ț' => 't',
                // Euro Sign
                '€' => 'E',
                // GBP (Pound) Sign
                '£' => '',
                // Vowels with diacritic (Vietnamese)
                // unmarked
                'Ơ' => 'O',
                'ơ' => 'o',
                'Ư' => 'U',
                'ư' => 'u',
                // grave accent
                'Ầ' => 'A',
                'ầ' => 'a',
                'Ằ' => 'A',
                'ằ' => 'a',
                'Ề' => 'E',
                'ề' => 'e',
                'Ồ' => 'O',
                'ồ' => 'o',
                'Ờ' => 'O',
                'ờ' => 'o',
                'Ừ' => 'U',
                'ừ' => 'u',
                'Ỳ' => 'Y',
                'ỳ' => 'y',
                // hook
                'Ả' => 'A',
                'ả' => 'a',
                'Ẩ' => 'A',
                'ẩ' => 'a',
                'Ẳ' => 'A',
                'ẳ' => 'a',
                'Ẻ' => 'E',
                'ẻ' => 'e',
                'Ể' => 'E',
                'ể' => 'e',
                'Ỉ' => 'I',
                'ỉ' => 'i',
                'Ỏ' => 'O',
                'ỏ' => 'o',
                'Ổ' => 'O',
                'ổ' => 'o',
                'Ở' => 'O',
                'ở' => 'o',
                'Ủ' => 'U',
                'ủ' => 'u',
                'Ử' => 'U',
                'ử' => 'u',
                'Ỷ' => 'Y',
                'ỷ' => 'y',
                // tilde
                'Ẫ' => 'A',
                'ẫ' => 'a',
                'Ẵ' => 'A',
                'ẵ' => 'a',
                'Ẽ' => 'E',
                'ẽ' => 'e',
                'Ễ' => 'E',
                'ể' => 'e',
                'Ỗ' => 'O',
                'ỗ' => 'o',
                'Ỡ' => 'O',
                'ỡ' => 'o',
                'Ữ' => 'U',
                'ữ' => 'u',
                'Ỹ' => 'Y',
                'ỹ' => 'y',
                // acute accent
                'Ấ' => 'A',
                'ấ' => 'a',
                'Ắ' => 'A',
                'ắ' => 'a',
                'Ế' => 'E',
                'ế' => 'e',
                'Ố' => 'O',
                'ố' => 'o',
                'Ớ' => 'O',
                'ớ' => 'o',
                'Ứ' => 'U',
                'ứ' => 'u',
                // dot below
                'Ạ' => 'A',
                'ạ' => 'a',
                'Ậ' => 'A',
                'ậ' => 'a',
                'Ặ' => 'A',
                'ặ' => 'a',
                'Ẹ' => 'E',
                'ẹ' => 'e',
                'Ệ' => 'E',
                'ệ' => 'e',
                'Ị' => 'I',
                'ị' => 'i',
                'Ọ' => 'O',
                'ọ' => 'o',
                'Ộ' => 'O',
                'ộ' => 'o',
                'Ợ' => 'O',
                'ợ' => 'o',
                'Ụ' => 'U',
                'ụ' => 'u',
                'Ự' => 'U',
                'ự' => 'u',
                'Ỵ' => 'Y',
                'ỵ' => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin)
                'ɑ' => 'a',
                // macron
                'Ǖ' => 'U',
                'ǖ' => 'u',
                // acute accent
                'Ǘ' => 'U',
                'ǘ' => 'u',
                // caron
                'Ǎ' => 'A',
                'ǎ' => 'a',
                'Ǐ' => 'I',
                'ǐ' => 'i',
                'Ǒ' => 'O',
                'ǒ' => 'o',
                'Ǔ' => 'U',
                'ǔ' => 'u',
                'Ǚ' => 'U',
                'ǚ' => 'u',
                // grave accent
                'Ǜ' => 'U',
                'ǜ' => 'u',
            ];
            $string = strtr($string, $chars);
        } else {
            $chars = [];
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
                ."\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
                ."\xc3\xc4\xc5\xc7\xc8\xc9\xca"
                ."\xcb\xcc\xcd\xce\xcf\xd1\xd2"
                ."\xd3\xd4\xd5\xd6\xd8\xd9\xda"
                ."\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
                ."\xe4\xe5\xe7\xe8\xe9\xea\xeb"
                ."\xec\xed\xee\xef\xf1\xf2\xf3"
                ."\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
                ."\xfc\xfd\xff";

            $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';

            $string = strtr($string, $chars['in'], $chars['out']);
            $double_chars = [];
            $double_chars['in'] = ["\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe"];
            $double_chars['out'] = ['OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th'];
            $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }
}
if (! function_exists('seems_utf8')) {
    function seems_utf8($str)
    {
        mbstring_binary_safe_encoding();
        $length = strlen($str);
        reset_mbstring_encoding();
        for ($i = 0; $i < $length; $i++) {
            $c = ord($str[$i]);
            if ($c < 0x80) {
                $n = 0;
            } // 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) {
                $n = 1;
            } // 110bbbbb
            elseif (($c & 0xF0) == 0xE0) {
                $n = 2;
            } // 1110bbbb
            elseif (($c & 0xF8) == 0xF0) {
                $n = 3;
            } // 11110bbb
            elseif (($c & 0xFC) == 0xF8) {
                $n = 4;
            } // 111110bb
            elseif (($c & 0xFE) == 0xFC) {
                $n = 5;
            } // 1111110b
            else {
                return false;
            } // Does not match any model
            for ($j = 0; $j < $n; $j++) { // n bytes matching 10bbbbbb follow ?
                if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
                    return false;
                }
            }
        }

        return true;
    }
}
if (! function_exists('mbstring_binary_safe_encoding')) {
    function mbstring_binary_safe_encoding($reset = false)
    {
        static $encodings = [];
        static $overloaded = null;

        if (is_null($overloaded)) {
            $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
        }

        if ($overloaded === false) {
            return;
        }

        if (! $reset) {
            $encoding = mb_internal_encoding();
            array_push($encodings, $encoding);
            mb_internal_encoding('ISO-8859-1');
        }

        if ($reset && $encodings) {
            $encoding = array_pop($encodings);
            mb_internal_encoding($encoding);
        }
    }
}
if (! function_exists('reset_mbstring_encoding')) {
    function reset_mbstring_encoding()
    {
        mbstring_binary_safe_encoding(true);
    }
}

if (! function_exists('changeTitle')) {
    function changeTitle($str)
    {
        $str = remove_accents($str);
        $str = str_replace(' ', '', $str);
        $str = strtolower($str);

        return $str;
    }
}

if (! function_exists('msg_move_page')) {
    function msg_move_page($msg, $url = 'back', $isExit = 1)
    {
        if ($msg) {
            session()->flash('swal_toast', [
                'icon' => 'success',
                'title' => $msg,
            ]);
            session()->save();
        }

        if ($url) {
            $targetUrl = '/';
            switch ($url) {
                case 'home':
                    $targetUrl = '/';
                    break;
                case 'back':
                    $targetUrl = back()->getTargetUrl();
                    break;
                case 'close':
                    echo "<script language='javascript'>self.close();</script>";
                    if ($isExit) {
                        exit();
                    }

                    return;
                case 'reload':
                case 'top_opener_reload':
                case 'parent_reload':
                    $targetUrl = url()->previous();
                    break;
                case 'not':
                    return;
                default:
                    $targetUrl = $url;
                    break;
            }

            if (! headers_sent()) {
                header('Location: '.$targetUrl);
            } else {
                echo "<script language='javascript'>document.location.replace('".$targetUrl."');</script>";
            }
        }

        if ($isExit) {
            exit();
        }
    }
}

if (! function_exists('render_price')) {
    function render_price(float $money, $currency = null, $rate = null, $space_between_symbol = false, $useSymbol = true)
    {
        return ShopCurrency::render($money, $currency, $rate, $space_between_symbol, $useSymbol);
    }
}

if (! function_exists('render_option_name')) {
    function render_option_name($att)
    {
        if ($att) {
            $att_array = explode('__', $att);
            if (isset($att_array[0])) {
                return $att_array[0];
            }
        }
    }
}

if (! function_exists('render_option_price')) {
    function render_option_price($att)
    {
        if ($att) {
            $att_array = explode('__', $att);
            if (isset($att_array[2])) {
                return render_price($att_array[2]);
            } elseif (isset($att_array[1])) {
                return render_price($att_array[1]);
            }
        }
    }
}
// if (!function_exists('auto_code')) {
//     function auto_code($code = 'Order', $cart_id = 0)
//     {
//         $number_start = 5000;
//         // $strtime_conver=strtotime(date('d-m-Y H:i:s'));
//         // $strtime=substr($strtime_conver,-4);
//         // $rand=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
//         $string_rand = $code . '-' . ($number_start + $cart_id);
//         return $string_rand;
//     }
// }

if (! function_exists('get_image')) {
    function get_image($item_image = '')
    {
        $placeholder = asset('assets/images/placeholder.png');

        if (! $item_image) {
            return $placeholder;
        }

        if (Str::startsWith($item_image, 'http://') || Str::startsWith($item_image, 'https://')) {
            return $item_image;
        }

        $cleanPath = ltrim(urldecode($item_image), '/');
        $rawCleanPath = ltrim($item_image, '/');

        if (File::exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        if (File::exists(public_path($rawCleanPath))) {
            return asset($rawCleanPath);
        }

        return $placeholder;
    }
}

// if (!function_exists('check_product_variable')) {
//     function check_product_variable($sid)
//     {
//         $check_products = \App\Models\Theme_variable_sku::where('id_theme', '=', $sid)->first();
//         return $check_products;
//     }
// }

if (! function_exists('getThumbnail')) {
    function getThumbnail($path, $img_path, $width, $height, $type = 'fit')
    {
        return app('App\Http\Controllers\ImageController')->getImageThumbnail($path, $img_path, $width, $height, $type);
    }
}

if (! function_exists('variations_traverse')) {
    function variations_traverse($array, $parent_ind)
    {
        $r = [];
        $pr = '';
        if (! is_numeric($parent_ind)) {
            $pr = $parent_ind.'-';
        }
        foreach ($array as $ind => $el) {
            if (is_array($el)) {
                $r = array_merge($r, variations_traverse($el, $pr.(is_numeric($ind) ? '' : $ind)));
            } elseif (is_numeric($ind)) {
                $r[] = $pr.$el;
            } else {
                $r[] = $pr.$ind.'-'.$el;
            }
        }

        return $r;
    }
}

if (! function_exists('variations')) {
    function variations($array)
    {
        if (empty($array)) {
            return [];
        }

        // 1. Go through entire array and transform elements that are arrays into elements, collect keys
        $keys = [];
        $size = 1;
        foreach ($array as $key => $elems) {
            if (is_array($elems)) {
                $rr = [];
                foreach ($elems as $ind => $elem) {
                    if (is_array($elem)) {
                        $rr = array_merge($rr, variations_traverse($elem, $ind));
                    } else {
                        $rr[] = $elem;
                    }
                }
                $array[$key] = $rr;
                $size *= count($rr);
            }
            $keys[] = $key;
        }
        // 2. Go through all new elems and make variations
        $rez = [];
        for ($i = 0; $i < $size; $i++) {
            $rez[$i] = [];
            foreach ($array as $key => $value) {
                $current = current($array[$key]);
                $rez[$i][$key] = $current;
            }
            foreach ($keys as $key) {
                if (! next($array[$key])) {
                    reset($array[$key]);
                } else {
                    break;
                }
            }
        }

        return $rez;
    }
}

if (! function_exists('get_permalink_by_id')) {
    function get_permalink_by_id($sid)
    {
        return permalink_by_id($sid);
    }
}

if (! function_exists('get_product_by_id')) {
    function get_product_by_id($id)
    {
        return Product::find($id);
    }
}

if (! function_exists('setting_phone')) {
    function setting_phone($phone = '')
    {
        // $re = '~\s|\([^)]*\)~m';
        // $phone = preg_replace($re, '', $phone);
        $string = Str::swap([
            '(' => '',
            ')' => '',
            '.' => '',
            ' ' => '',
        ], $phone);

        return $string;
    }
}

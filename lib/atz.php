<?php
	ob_start(); session_start(); // First of file
	
	require_once("db.php");
	
	// Thông tin cấu hình do coder cài đặt
	require_once('config.php');

	include_once 'image.php';
?>
<?php

use Image\image;

class atz extends DB{

	public function __construct() {
		// Get url
		$this->site_url = $this->config_url();

		// Lấy thông tin thiết lập của web
		$this->get_site_setting();

		// docker port (useful for file_get_contents)
		$this->local_port = LOCAL_PORT;
		$this->project_port = PROJECT_PORT;

		$this->image = new image;
	}

	public function config_url(){

		// Đường dẫn tuyệt đối | Absolute path
		if(strpos($_SERVER['HTTP_HOST'],'localhost')!== false){
			$web_url['root'] = $_SERVER['DOCUMENT_ROOT'].'/'.DOMAIN.'/';
		}else{
			$web_url['root'] = $_SERVER['DOCUMENT_ROOT'].'/';
		}
		
		if(strpos($_SERVER['HTTP_HOST'],'localhost')!== false){
			$web_url['main'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/".DOMAIN.'/';
		}else{
			$web_url['main'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/';
		}

		// docker url (useful for file_get_contents)
		$web_url['upload_local'] = 'http://localhost:'.LOCAL_PORT.'/'.'upload/';

		$web_url['upload'] = $web_url['main'].'upload/';

		// Đường dẫn trang admin
		$web_url['admin'] = $web_url['main'].'admin/';

		// Đường dẫn hiện tại
		$web_url['full'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$new_pageURL = explode( '?', $web_url['full'] );
		
		// Đường dẫn hiện tại (Không có chuỗi theo sau)
		$web_url['no_string'] = $new_pageURL[0];
		
		return $web_url;
	}

	
	public function get_params($index){
        global $param;

        // CHECK MODULE
        if(!empty($_GET['module'])){
            $module = $_GET['module'];
        }else{
            $module = _module_default;
        }
            
        
        
        // REPLACE ROUTER
        $url = '';
        if (!empty($routes)) {
            foreach($routes as $key => $value){
                if(preg_match('#^'.$key.'$#', $module)){
                    $url = preg_replace('#^'.$key.'$#', $value, $module);
                    break;
                }

            }
        }

        // CHECK URL
        if(!empty($url)){
            $module = $url;
        }

        $module_arr = array_filter(explode('/',$module));

        $url = '';
        
        if(!empty($module_arr)){
            foreach ($module_arr as $key => $item) {
                // $url = $url.'/'.$item;

                $url = $item; // Set url
                if(file_exists($url.'.php')){        
                    for($i=0; $i <= $key; $i++){
                        unset($module_arr[$i]);
                    }
                    break;    
                }
            }
        }
        
        // GET PARAM
        if(!empty($param)){
	        $param = array_values($module_arr);
	        if($param[$index-1]){
	            return $param[$index-1];
	        }
	    }
        return false;
    }

	// Kiểm tra quản trị đăng nhập
	function check_login(){
		if(!isset($_SESSION['user'])){
			header("location:login.php");
		}
	}

	// Kiểm tra khách hàng đăng nhập
	function check_login_customer(){
		if(!isset($_SESSION['customer'])){
			header("location:login.php");
		}
	}

	// Quản trị đăng xuất
	function logout(){
		if(isset($_GET['logout'])){
			if(isset($_SESSION['user']) && !empty($_SESSION['user'])){	
				unset($_SESSION['user']);
				header("location:login.php");
			}	
		}
	}

	// Khách hàng đăng xuất
	function logout_customer(){
		if(isset($_SESSION['customer']) && !empty($_SESSION['customer'])){	
			unset($_SESSION['customer']);
			header("location:index.php");
		}	
	}

	function get_site_setting(){
		if (!defined("SETTING")) {
		    $data = $this->select('setting',array('Setting_Lang'=>'vi'));
			define('SETTING', $data[0]);
		}
	}

	// Get The IP Address Of A Visitor Through PHP
	function getIPAddress() {
	    //whether ip is from the share internet  
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
	        $ip = $_SERVER['HTTP_CLIENT_IP'];  
	    }  
	    //whether ip is from the proxy  
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
	    }
		//whether ip is from the remote address  
	    else{  
	        $ip = $_SERVER['REMOTE_ADDR'];  
	    }  
	    return $ip;  
	}

	function getUserIP(){
	    // Get real visitor IP behind CloudFlare network
	    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
	              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	    }
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}


	public function slug($str, $spaceRepl = "-") {

		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		$str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
		$str = preg_replace("/( )/", '-', $str);

		return $str;
	}
}

// $test_lib = new atz();

// echo '<pre>';
// print_r($test_lib->site_url['upload']);
// exit;

?>
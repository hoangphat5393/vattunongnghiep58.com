<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class setting_page_controller extends atz{

	public function __construct() { 
		parent::__construct();

		$this->check_login();
		
		$this->post = array(
			'Setting_Page_Title_vi'=>'',
			'Setting_Page_Thumbnail'=>'',
			'Setting_Page_Description_vi'=>'',
			'Setting_Page_Content_vi'=>'',
		);
	}

	public $thumb_width = 350;
	public $thumb_height = 230;

	public function get_current_data($id){
		
		$current_data = $this->select('setting_page',array('Setting_Page_ID'=>$id));
			
		if(!empty($current_data)){
			return $current_data[0];	
		}
		header('location:'.$this->site_url['admin'].'setting_page.php');
	}

	public function update_page(){

		$post = $this->post;

		$errors = array();

		// Sửa - Lấy dữ liệu cũ 
		$current_data = $this->get_current_data(1);

		if($current_data){
			foreach ($current_data as $k => $v) {
				if(isset($current_data[$k])){
					$current_data[$k] =$v;
				}
			}
			$this->post = $current_data;
		}
		
		// Thêm, Cập nhật dũ liệu
		if(!empty($_REQUEST) && isset($_REQUEST['submit'])){

			foreach ($_REQUEST as $k => $v) {
				if(isset($post[$k])){
					$post[$k] =$v;
				}
			}	

			$this->post = $post;

			if(!$post['Setting_Page_Content_vi']){
				$errors['Setting_Page_Content_vi'] = 'Chưa nhập nội dung';
			}
		
			// Thư mục ảnh
			$dir = '../upload/contact/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }
	        
			// Tiến hành insert, update
			if(empty($errors)){

		        // Upload ảnh trong editor
				$new_imgs = array();
				preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $post['Setting_Page_Content_vi'], $temp);
				if(isset($temp[2]) && is_array($temp[2])){
					$new_imgs = $temp[2];
				}
				
				// Xoá ảnh cũ trong editor trường hợp sửa

				// Danh sách ảnh cũ
				$old_imgs = array();
				preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Setting_Page_Content_vi'], $temp);
				if(isset($temp[2]) && is_array($temp[2])){
					$old_imgs = $temp[2];
				}
				
				// Danh sách ảnh đã bị xoá khỏi editor
				$remove_imgs = array_diff($old_imgs, $new_imgs);

				// Danh sách ảnh mới được thêm vào editor
				$new_upload_imgs = array_diff($new_imgs, $old_imgs);
					
				if(!empty($new_upload_imgs)){
					// duyệt ảnh mới trong editor
					foreach ($new_upload_imgs as $src) {
						// Đuôi ảnh
						$ext = pathinfo($src, PATHINFO_EXTENSION);
							
						// Tên ảnh
						$name = date('YmdHis').'-'.rand(100000, 999999).'.'.$ext;

						// Path ảnh
						$img_path = $dir.$name;
						
						// Di chuyển ảnh từ thư mục temp vào thư mục ảnh gốc
						file_put_contents($img_path, file_get_contents($src));
						
						// Đổi link ảnh trong editor
						$post['Setting_Page_Content_vi'] = str_replace($src, $this->site_url['main'].substr($img_path, 3), $post['Setting_Page_Content_vi']);
					}
				}
					
				if(!empty($remove_imgs)){
					// Tiến hành xoá ảnh rác
					foreach($remove_imgs as $img){
						$path = str_replace($this->site_url['main'], $this->site_url['root'], $img);
						if(file_exists($path)){	
							unlink($path);
						}
					}

				}

				// Update
				$post['Setting_Page_Updated'] = time();
				$rs = $this->update('setting_page', $post, array('Setting_Page_ID' => 1));
				
				if(!empty($rs)){
					header("location:setting_page.php");
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}

	// Upload ảnh trong editor
	public function ajax_upload_image(){
			
		if(isset($_FILES['summernote'])){

			$dir = '../upload/tmp/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }

			$file = $_FILES['summernote'];
				
			$filename = $file['name'];
			$ext = $this->image->file_type($file['name']);
			
			// Chỉ cho upload ảnh
			if($this->image->check_file($filename)!=1){
				echo 'error';
			}else{
				$name = time().'_'.rand(100000, 999999).'.'.$ext;

				// upload file
				if($this->image->upload($file['tmp_name'],$dir,$name)==1){
					echo $dir.$name;exit;
				}
			}
		}
	}
	
}
?>
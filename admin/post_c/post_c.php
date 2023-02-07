<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class post_controller extends atz{

	public function __construct() { 
		parent::__construct();

		$this->check_login();
		
		$this->post = array(
			'Post_Title_vi'=>'',
			'Post_Thumbnail'=>'',
			'Post_Description_vi'=>'',
			'Post_Keywords_vi'=>'',
			'Post_Content_vi'=>'',
			'Post_Priority'=>1,
			'Post_Show'=>1,
			'Post_Hot'=>'',
			'Post_Cat'=>'',
		);
	}

	public $thumb_width = 350;
	public $thumb_height = 230;

	public function get_post(){
		$posts = $this->select('post','',array('Post_Priority'=>'DESC','Post_ID'=>'ASC','Post_Title_vi'=>'ASC'));
		return $posts;
	}

	public function get_cat(){
		$cats = $this->select('cat',array('Cat_Type'=>'Post'));
		return $cats;
	}

	public function get_current_data($id){

		$current_data = $this->select('post',array('Post_ID'=>$id));
					
		if(!empty($current_data)){
			return $current_data[0];	
		}
		header('location:'.$this->site_url['admin'].'post_list.php');
	}

	public function add_post(){

		$post = $this->post;

		$errors = array();

		// Sửa - Lấy dữ liệu cũ 
		if(isset($_GET['edit'])){
			$current_data = $this->get_current_data($_GET['edit']);
			if($current_data){
				foreach ($current_data as $k => $v) {
					if(isset($current_data[$k])){
						$current_data[$k] =$v;
					}
				}
				$this->post = $current_data;
			}
		}
		
		// Thêm, Cập nhật dũ liệu
		if(!empty($_REQUEST) && isset($_REQUEST['submit'])){

			foreach ($_REQUEST as $k => $v) {
				if(isset($post[$k])){
					$post[$k] =$v;
				}
			}	

			$this->post = $post;
				
			if(!$post['Post_Title_vi']){
				$errors['Post_Title_vi'] = 'Chưa nhập tên';
			}
			if(!$post['Post_Thumbnail'] && !$_FILES['Post_Thumbnail']['tmp_name']){
				$errors['Post_Thumbnail'] = 'Chưa chọn ảnh đại diện';	
			}else{
				if($_FILES['Post_Thumbnail']['name']){
					// Kiểm tra file
					$check_file = $this->image->check_file($_FILES['Post_Thumbnail']['name']);
					if ($check_file!=1) {
					    $errors['Post_Thumbnail'] = $check_file;
					}	
				}	
			}

			if(!$post['Post_Cat']){
				$errors['Post_Cat'] = 'Chưa chọn chuyên mục';
			}
			if(!$post['Post_Content_vi']){
				$errors['Post_Content_vi'] = 'Chưa nhập nội dung';
			}
			if($post['Post_Priority']==''){
				$errors['Post_Priority'] = 'Chưa nhập độ ưu tiên';
			}elseif(!is_numeric($post['Post_Priority'])){
				$errors['Post_Priority'] = 'Chỉ được nhập số';
			}

			// Thư mục ảnh
			$dir = '../upload/post/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }
	        
			// Tiến hành insert, update
			if(empty($errors)){

				$dir_thumb = $dir.'thumbnail/'; 
				if(!is_dir($dir_thumb)){
					mkdir($dir_thumb);
		        }

				// Upload ảnh đại diện (thumbnail)
				if(isset($_FILES['Post_Thumbnail']) && $_FILES['Post_Thumbnail']['tmp_name']){
					$file = $_FILES['Post_Thumbnail'];

					$ext = $this->image->file_type($file['name']);
		        }


		        // Upload ảnh trong editor
				$new_imgs = array();
				preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $post['Post_Content_vi'], $temp);
				if(isset($temp[2]) && is_array($temp[2])){
					$new_imgs = $temp[2];
				}
				
				if (!isset($_REQUEST['edit'])) {

					// Tiến hành upload ảnh đại diện
					$thumb_name = time().'_'.rand(100000, 999999).'.'.$ext;

					if($this->image->upload($file['tmp_name'],$dir_thumb,$thumb_name,$this->thumb_width,$this->thumb_height)==1){
						$post['Post_Thumbnail'] = $dir_thumb.$thumb_name;
					}
						
					// $this->image->resize_image('fixed',$dir_thumb.$thumb_name,$dir_thumb.$thumb_name,$this->thumb_width,$this->thumb_height);

					// duyệt ảnh trong editor
					foreach ($new_imgs as $src) {
						// Đuôi ảnh
						$ext = pathinfo($src, PATHINFO_EXTENSION);
							
						// Tên ảnh
						$name = date('YmdHis').'-'.rand(100000, 999999).'.'.$ext;

						// Path ảnh
						$img_path = $dir.$name;
						
						// Di chuyển ảnh từ thư mục temp vào thư mục ảnh gốc
						file_put_contents($img_path, file_get_contents($src));
						
						// Đổi link ảnh trong editor
						$post['Post_Content_vi'] = str_replace($src, $this->site_url['main'].substr($img_path, 3), $post['Post_Content_vi']);
					}

					// Insert
					$post['Post_Created'] = time();

					$rs = $this->insert('post', $post);

				}else{

					// Xóa ảnh (thumbnail) trong trường hợp sửa
					$old_thumb = $current_data['Post_Thumbnail'];
					
					if(isset($_FILES['Post_Thumbnail']) && !empty($_FILES['Post_Thumbnail']['tmp_name'])){
						
						$thumb_name = time().'_'.rand(100000, 999999).'.'.$ext;
							
						// Tiến hành upload ảnh đại diện
						if($this->image->upload($file['tmp_name'],$dir_thumb,$thumb_name,$this->thumb_width,$this->thumb_height)==1){
							$post['Post_Thumbnail'] = $dir_thumb.$thumb_name;

							if(file_exists($old_thumb)){
								unlink($old_thumb);	
							}
						}
					}

					// Xoá ảnh cũ trong editor trường hợp sửa

					// Danh sách ảnh cũ
					$old_imgs = array();
					preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Post_Content_vi'], $temp);
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
							$post['Post_Content_vi'] = str_replace($src, $this->site_url['main'].substr($img_path, 3), $post['Post_Content_vi']);
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
					$post['Post_Updated'] = time();
					$rs = $this->update('post', $post, array('Post_ID' => $_REQUEST['edit']));
				}
				
				if(!empty($rs)){
					header("location:post_list.php");
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
			// $dir = $this->site_url['root'].'upload/tmp/';

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


	// Xóa bài viết
	public function remove_post(){
		if(isset($_GET['delete'])){

			// Dữ liệu cần xóa
			$current_data = $this->get_current_data($_GET['delete']);

			// Xóa ảnh đại diện (thumbnail)
			$old_thumb = $current_data['Post_Thumbnail'];
			
			// Tiến hành xoá ảnh nếu có	
			if(file_exists($old_thumb)){		
				unlink($old_thumb);
			}

			// Danh sách ảnh cũ trong editor
			$old_imgs = array();
			preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Post_Content_vi'], $temp);
			if(isset($temp[2]) && is_array($temp[2])){
				$old_imgs = $temp[2];
			}

			// Tiến hành xoá ảnh nếu có
			if (!empty($old_imgs)){

				foreach($old_imgs as $img){
					$path = str_replace($this->site_url['main'], $this->site_url['root'], $img);
					if(file_exists($path)){	
						unlink($path);
					}
				}
			}
			
			// Xóa trong database
			$rs = $this->delete('post',array('Post_ID' => $_GET['delete']));
			if($rs==1){
				header("location:post_list.php");
			}
		}
	}
	
}
?>
<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class article_controller extends atz{

	public function __construct() { 
		parent::__construct();

		$this->check_login();
		
		$this->post = array(
			'Article_Title_vi'=>'',
			'Article_Description_vi'=>'',
			'Article_Content_vi'=>'',
			'Article_Keywords_vi'=>'',
			'Article_Priority'=>1,
			'Article_Show'=>1,
		);
	}

	public function get_article(){
		$articles = $this->select('article','',array('Article_Priority'=>'DESC','Article_ID'=>'ASC','Article_Title_vi'=>'ASC'));
		return $articles;
	}

	public function get_current_data($id){

		$current_data = $this->select('article',array('Article_ID'=>$id));
					
		if(!empty($current_data)){
			return $current_data[0];	
		}
		header('location:'.$this->site_url['admin'].'article_list.php');
	}

	public function add_article(){

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
				
			if(!$post['Article_Title_vi']){
				$errors['Article_Title_vi'] = 'Chưa nhập tên';
			}

			if(!$post['Article_Content_vi']){
				$errors['Article_Content_vi'] = 'Chưa nhập nội dung';
			}
			if($post['Article_Priority']==''){
				$errors['Article_Priority'] = 'Chưa nhập độ ưu tiên';
			}elseif(!is_numeric($post['Article_Priority'])){
				$errors['Article_Priority'] = 'Chỉ được nhập số';
			}

				
			// Thư mục ảnh
			$dir = '../upload/article/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }

			// Tiến hành insert, update
			if(empty($errors)){

				$dir_thumb = $dir.'thumbnail/'; 
				if(!is_dir($dir_thumb)){
					mkdir($dir_thumb);
		        }


		        // Upload ảnh trong editor
				$new_imgs = array();
				preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $post['Article_Content_vi'], $temp);
				if(isset($temp[2]) && is_array($temp[2])){
					$new_imgs = $temp[2];
				}
				
				if (!isset($_REQUEST['edit'])) {
					
						
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
						$post['Article_Content_vi'] = str_replace($src, $this->site_url['main'].substr($img_path, 3), $post['Article_Content_vi']);
					}
					
					// Insert
					$post['Article_Created'] = time();
					
					$rs = $this->insert('article', $post);

				}else{

					// Xoá ảnh cũ trong editor trường hợp sửa

					// Danh sách ảnh cũ
					$old_imgs = array();
					preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Article_Content_vi'], $temp);
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
							$post['Article_Content_vi'] = str_replace($src, $this->site_url['main'].substr($img_path, 3), $post['Article_Content_vi']);
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
					$post['Article_Updated'] = time();
					$rs = $this->update('article', $post, array('Article_ID' => $_REQUEST['edit']));
				}
				
				if(!empty($rs)){
					header("location:article_list.php");
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
	public function remove_article(){
		if(isset($_GET['delete'])){

			// Dữ liệu cần xóa
			$current_data = $this->get_current_data($_GET['delete']);

			// Danh sách ảnh cũ trong editor
			$old_imgs = array();
			preg_match_all('/(?<!_)<img[^>]*src=([\'"])?(.*?)\\1/', $current_data['Article_Content_vi'], $temp);
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
			$rs = $this->delete('article',array('Article_ID' => $_GET['delete']));
			if($rs==1){
				header("location:article_list.php");
			}
		}
	}
	
}
?>
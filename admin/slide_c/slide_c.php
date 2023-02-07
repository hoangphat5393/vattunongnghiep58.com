<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class slide_controller extends atz{

	public function __construct() { 
		parent::__construct();

		$this->check_login();
		
		$this->post = array(
			'Slide_Title_vi'=>'',
			'Slide_Description_vi'=>'',
			'Slide_Img'=>'',
			'Slide_Target'=>'_blank',
			'Slide_Order'=>1,
			'Slide_Show'=>1
		);
	}

	public function get_slide(){
		$posts = $this->select('slide','',array('Slide_Order'=>'ASC','Slide_ID'=>'ASC','Slide_Title_vi'=>'ASC'));
		return $posts;
	}

	public function get_current_data($id){

		$current_data = $this->select('slide',array('Slide_ID'=>$id));
					
		if(!empty($current_data)){
			return $current_data[0];	
		}
		header('location:'.$this->site_url['admin'].'slide_list.php');
	}

	public function add_slide(){

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
				
			if(!$post['Slide_Title_vi']){
				$errors['Slide_Title_vi'] = 'Chưa nhập tên';
			}
			if(!$post['Slide_Img'] && !$_FILES['Slide_Img']['tmp_name']){
				$errors['Slide_Img'] = 'Chưa chọn ảnh đại diện';	
			}else{
				if(!isset($_FILES['Slide_Img']) && !$_FILES['Slide_Img']){
					// Chỉ cho upload ảnh
					$allowed = array('jpg','jepg','png','gif');
					$ext = pathinfo($_FILES['Slide_Img']['name'], PATHINFO_EXTENSION);
					if (!in_array($ext, $allowed)) {
					    $errors['Slide_Img'] = 'Chỉ nhận ảnh jpg, jepg, png, gif';
					}	
				}
			}
			
			// if(!$post['Slide_Description_vi']){
			// 	$errors['Slide_Description_vi'] = 'Chưa nhập nội dung';
			// }
			if($post['Slide_Order']==''){
				$errors['Slide_Order'] = 'Chưa nhập thứ tự';
			}elseif(!is_numeric($post['Slide_Order'])){
				$errors['Slide_Order'] = 'Chỉ được nhập số';
			}

			// Thứ mục ảnh
			$dir = '../upload/slide/';

			if(!is_dir($dir)){
				mkdir($dir);
	        }

				
			// Tiến hành insert, update
			if(empty($errors)){

				// Upload ảnh đại diện (thumbnail)
				if(isset($_FILES['Slide_Img']) && $_FILES['Slide_Img']['tmp_name']){
			        
					$file = $_FILES['Slide_Img'];

					$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		        }

			

				if (!isset($_REQUEST['edit'])) {

					// Tiến hành upload ảnh đại diện
					$thumb_name = time().'_'.rand(100000, 999999).'.'.$ext;
					if(move_uploaded_file($file['tmp_name'], $dir.$thumb_name)){
						$post['Slide_Img'] = $dir.$thumb_name;
					}
					
					// Insert
					$post['Slide_Created'] = time();

					$rs = $this->insert('slide', $post);

				}else{

					$old_slide = $current_data['Slide_Img'];

					// Xóa ảnh (thumbnail) trong trường hợp sửa
					if(isset($_FILES['Slide_Img']) && !empty($_FILES['Slide_Img'])){
						
						$name = time().'_'.rand(100000, 999999).'.'.$ext;

						// Tiến hành upload ảnh đại diện
						if(move_uploaded_file($file['tmp_name'], $dir.$name)){
							$post['Slide_Img'] = $dir.$name;

							if(file_exists($old_slide)){
								unlink($old_slide);	
							}
						}
					}

					// Update
					$post['Slide_Updated'] = time();
					$rs = $this->update('slide', $post, array('Slide_ID' => $_REQUEST['edit']));
				}
				
				if(!empty($rs)){
					header("location:slide_list.php");
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}


	// Xóa bài viết
	public function remove_slide(){
		if(isset($_GET['delete'])){

			// Dữ liệu cần xóa
			$current_data = $this->get_current_data($_GET['delete']);

			// Xóa ảnh đại diện (thumbnail)
			$old_slide = $current_data['Slide_Img'];
			
			// Tiến hành xoá ảnh nếu có	
			if(file_exists($old_slide)){		
				unlink($old_slide);
			}
			
			// Xóa trong database
			$rs = $this->delete('slide',array('Slide_ID' => $_GET['delete']));
			if($rs==1){
				header("location:slide_list.php");
			}
		}
	}
	
}
?>
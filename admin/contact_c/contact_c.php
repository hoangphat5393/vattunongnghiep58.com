<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class contact_controller extends atz{

	public function __construct() { 

		// Kiểm tra đăng nhập
		$this->check_login();
		
		$this->contact_type = array(
			'product' => 'Sản phẩm',
			'post' => 'Bài viết',
			'photo' => 'Hình ảnh',
			'hosting' => 'Hosting'
		);

		$this->post = array(
			'Contact_Name'=>'',
			'Contact_Mobile'=>'',
			'Contact_Email'=>'',
			'Contact_Address'=>'',
			'Contact_Created'=>''
		);
	}

	// Select Contact
	public function get_contact(){
		$contacts = $this->select('contact');
		return $contacts;
	}

	// Insert, Update Cat
	public function add_cat(){

		$post = $this->post;

		if(isset($_GET['edit'])){

			$cat = $this->select('cat',array('Cat_ID'=>$_GET['edit']));

			if($cat){
				$cat = $cat[0];

				foreach ($cat as $k => $v) {
					if(isset($post[$k])){
						$post[$k] =$v;
					}
				}

				$this->post = $post;
			}
		}

		// Insert, Update
		if(!empty($_REQUEST) && isset($_REQUEST['submit'])){
			
			foreach ($_REQUEST as $k => $v) {
				if(isset($post[$k])){
					$post[$k] =$v;
				}
			}

			$this->post = $post;
			
			$errors = array();

			if(!$post['Cat_Name_vi']){
				$errors['Cat_Name_vi'] = 'Chưa nhập tên';
			}
			if($post['Cat_Order']==''){
				$errors['Cat_Order'] = 'Chưa nhập thứ tự';
			}elseif(!is_numeric($post['Cat_Order'])){
				$errors['Cat_Order'] = 'Chỉ được nhập số';
			}
			
			

			if(empty($errors)){
				$rs = '';
				if (!isset($_REQUEST['edit'])) {

					// Insert
					$post['Cat_Created'] = time();
					$rs = $this->insert('cat', $post);
				}else{

					// Update
					$post['Cat_Updated'] = time();
					$rs = $this->update('cat', $post, array('Cat_ID' => $_REQUEST['edit']));
				}

				if(!empty($rs)){
					header("location:cat_list.php");die;
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}

	// Delete Cat
	public function remove_contact(){
		// echo '<pre>';
		// print_r($_GET);
		// die;
			
		if(isset($_GET['delete'])){
			$rs = $this->delete('contact',array('Contact_ID' => $_GET['delete']));
		
			if($rs==1){
				header("location:contact_list.php");
			}
		}
	}
	
}
?>
<?php ob_start(); require_once('lib/atz.php');?>
<?php 
class contact_controller extends atz{

	public function __construct() { 

		parent::__construct();

		$this->post = array(
			'Contact_Name'=>'',
			'Contact_Address'=>'',
			'Contact_Mobile'=>'',
			'Contact_Email'=>'',
			'Contact_Message'=>''
		);
	}

	public function get_page_content($id){

		$data = $this->select('setting_page',array('Setting_Page_ID'=>$id));
			
		if(!empty($data)){
			return $data[0];	
		}
	}

	// Insert contact
	public function add_contact(){

		$post = $this->post;
		
		// Insert, Update
		if(isset($_REQUEST) && !empty($_REQUEST)){
			
			foreach ($_REQUEST as $k => $v) {
				if(isset($post[$k])){
					$post[$k] =$v;
				}
			}	

			$errors = array();

			if(!$post['Contact_Name']){
				$errors['Contact_Name'] = 'Chưa nhập tên';
			}
			if($post['Contact_Mobile']==''){
				$errors['Contact_Mobile'] = 'Chưa nhập số điện thoại';
			}elseif(!is_numeric($post['Contact_Mobile'])){
				$errors['Contact_Mobile'] = 'Chỉ được nhập số';
			}
			if($post['Contact_Email']!='' && !filter_var($post['Contact_Email'],FILTER_VALIDATE_EMAIL)){
				$errors['Contact_Email'] = 'Email không đúng';
			}
			// if(!$post['Contact_Address']){
			// 	$errors['Contact_Address'] = 'Chưa nhập địa chỉ';
			// }
			if($post['Contact_Message']==''){
				$errors['Contact_Message'] = 'Chưa nhập lời nhắn';
			}
			
			if(empty($errors)){

				// Insert
				$post['Contact_Created'] = time();

				$rs = $this->insert('contact', $post);

				if($rs){
					// $success = 'Gửi thành công';
					// $rs = array('success' => $success);
					echo 'Gửi thành công';exit;
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}
}
?>
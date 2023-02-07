<?php require_once('lib/atz.php');?>
<?php 
class product_list_controller extends atz{

	public function __construct() {
		parent::__construct();

		// Get param
		$this->page = $this->get_params(1);
		$this->param = $this->get_params(2);
		
		$ex = explode('.',$this->param);
		$ex1 = explode('-',$ex[0]);
		$this->id = end($ex1);
	}

	// Lấy thông tin chuyên mục
	public function get_cat($id){
		
		$cat = array();
		if(!empty($id)){
			$cat = $this->select('cat',array('Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

			// Kiểm tra có chuyên mục con hay không
			if(!empty($cat)){
				$cat = $cat[0];
			}
		}
		return $cat;
	}

	// Lấy danh sách chuyên mục
	public function get_cats($cat_id){
		
		$cat_list = $cat_id;
		// Kiểm tra có chuyên mục con hay ko
		if(!empty($cat_id)){

			$cat_child = $this->select('cat',array('Cat_Parent'=>$cat_id));

			if (!empty($cat_child)) {
				
				foreach ($cat_child as $v) {
					$cat_list .= ','.$v['Cat_ID']; 
				}
			}
		}
		return $cat_list;
	}

	// Lấy danh sách chuyên mục liên quan
	public function get_relate_cats($cat_id){
		
		$cat = array();
		if(!empty($id)){
			$cat = $this->select('cat',array('Cat_ID'=>$id), array('Cat_Order'=>'ASC', 'Cat_Name_vi'=>'ASC'));

			// Kiểm tra có chuyên mục con hay không
			if(!empty($cat)){
				$cat = $cat[0];
			}
		}
		return $cat;
	}

	// Lấy danh sách sản phẩm
	public function get_products($cat_list){	
		$data = array();
		if(isset($_GET['id']) && !empty($_GET['id'])){
			$data = $this->select_in('product',array('Product_Cat'=>$cat_list),array('Product_Priority'=>'DESC'));
		}
		return $data;
	}

	// Lấy thông tin bài viết
	public function get_project(){
		
		$project = array();
		if(isset($_GET['id']) && !empty($_GET['id'])){
			$project = $this->select('project',array('Project_ID'=>$_GET['id'], 'Project_Show'=>1));

			// Kiểm tra có chuyên mục con hay ko
			if(!empty($project)){
				$project = $project[0];
			}
		}
			
		return $project;
	}

}
?>
<?php require_once('lib/atz.php');?>
<?php 
class project_controller extends atz{

	public function __construct() {
		parent::__construct();

		// Get param
		$this->page = $this->get_params(1);
		$this->param = $this->get_params(2);

		// $replacement = '$1 $2';
		// $rs = preg_replace('/(.+?).(html)/', $replacement, $this->param)
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

	// Lấy danh sách bài viết
	public function get_projects(){
		
		$data = array();
		$data = $this->select('project',array('Project_Show'=>1),array('Project_Priority'=>'DESC'));
		return $data;
	}

	// Lấy thông tin dự án
	public function get_project($id){
		
		$project = array();
		if(isset($id) && !empty($id)){
			$project = $this->select('project',array('Project_ID'=>$id, 'Project_Show'=>1));

			// Kiểm tra có chuyên mục con hay ko
			if(!empty($project)){
				$project = $project[0];
			}
		}
			
		return $project;
	}

	// Lấy thông tin bài viết liên quan
	public function get_relative_projects($id){
		$projects = array();
		$sql = "SELECT  * 
                FROM    `project` 
                where   `Project_ID`!=$id AND `Project_Show`=1
                ORDER BY `Project_Priority` DESC, `Project_Name_vi` ASC";
        $projects = $this->raw_query($sql);

		return $projects;
	}

	public function update_view($id,$view){

		if(isset($id) && $id){
			// Update
			$project = array();
			$project['Project_View_vi'] = $view+1;
			$project['Project_Updated'] = time();
			$this->update('project', $project, array('Project_ID' => $id));
		}
	}

}
?>
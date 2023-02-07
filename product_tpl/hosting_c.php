<?php require_once('lib/atz.php');?>
<?php 
class hosting_controller extends atz{

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

	public function get_hosting(){
		$hostings = $this->select('hosting');
		return $hostings;
	}
}
?>
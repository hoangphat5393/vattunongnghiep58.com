<?php ob_start(); require_once('../lib/atz.php');?>
<?php 
class invoice_controller extends atz{

	public function __construct() { 

		$this->check_login();
		
		$this->post = array(
			'Invoice_Name'=>'',
			'Invoice_Email'=>'',
			'Invoice_Mobile' =>'',
			'Invoice_Address'=>'',
			'Invoice_Price'=>'',
			'Invoice_Paid'=>'',
		);
	}

	public function get_invoices(){
		$invoices = $this->select('invoice','',array('Invoice_ID'=>'DESC'));
		return $invoices;
	}

	public function get_invoice_detail($invoice_id){

		$sql = "SELECT * 
				FROM invoiceproduct
				JOIN invoice on invoice.Invoice_ID = invoiceproduct.InvoiceProduct_Invoice
				JOIN product on product.Product_ID = invoiceproduct.InvoiceProduct_Product
				WHERE InvoiceProduct_Invoice=".$invoice_id;

		$invoiceproduct = $this->mysql_query($sql);
			
		return $invoiceproduct;
	}


	// Insert, Update Cat
	public function add_invoice(){

		$post = $this->post;

		
		if(isset($_GET['edit'])){

			$cat = $this->select('invoice',array('Invoice_ID'=>$_GET['edit']));

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

			$errors = array();

			if(!is_numeric($post['Invoice_Paid'])){
				$errors['Invoice_Paid'] = 'Chưa nhập tên';
			}
		

			if(empty($errors)){

				if (!isset($_REQUEST['edit'])) {

					// Insert
					$post['Invoice_Created'] = time();
					$rs = $this->insert('invoiceẨn', $post);

				}else{

					// Update
					$post['Invoice_Updated'] = time();
					$rs = $this->update('invoice', $post, array('Invoice_ID' => $_REQUEST['edit']));
				}

				if($rs==1){
					header("location:invoice_list.php");die;
				}

			}else{
				$rs = array('errors' => $errors);
				return $rs;	
			}
		}
	}

}
?>
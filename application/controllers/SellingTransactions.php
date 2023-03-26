<?php 
  include_once(dirname(__FILE__)."/Data_format.php");


  class SellingTransactions extends Data_format{
    
    public function __construct(){
        parent::__construct();
        $this->load->model(array("SellingTransactions_Model"));
    }

    public function transactions_get($user_id){
        $data = $this->SellingTransactions_Model->getMyTransactions($user_id);

        $this->res(1,$data,"",0);
    }

    public function transaction_get($id){
        $data = $this->SellingTransactions_Model->transaction($id)[0];

        $this->res(1,$data,"",0);
    }

}
?>
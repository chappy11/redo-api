<?php 

include_once(dirname(__FILE__)."/Data_format.php");

class RepubrishItem extends Data_format{
        public function __construct(){
            parent::__construct();
            $this->load->model(array("RepubrishItem_Model"));
        }

        public function items_get($user_id){
            $data = $this->RepubrishItem_Model->getRepubrishByUserId($user_id);

            $this->res(1,$data,"d",0);
        }

        public function item_get($id){
            $data = $this->RepubrishItem_Model->getById($id)[0];

            $this->res(1,$data,"",0);


        }
    }

?>
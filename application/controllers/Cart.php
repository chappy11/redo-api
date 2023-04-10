<?php 
include_once(dirname(__FILE__)."/Data_format.php");

    class Cart extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array("Cart_Model"));
        }
    
    
        public function addcart_post(){
            $data = $this->decode();

            
        }
    }

?>
<?php 

    class ShopOrder_Model extends CI_Model{

        private $table_name = "shoporder";
        public function __construct(){
            
            parent::__construct();
            $this->load->database();
        }

        public function createShopOrder($data=array()){
            return $this->db->insert($this->table_name,$data);
        }

        public function getShopOrderByOrderId($order_id){
            $this->db->select("*");
            $this->db->from($this->table_name);
            $this->db->where("order_id",$order_id);
            $this->db->join("shop","shop.shop_id=shoporder.shop_id");
            $query = $this->db->get();
            return $query->result();
        }

    }
?>
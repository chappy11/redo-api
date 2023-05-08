<?php 

    class SalvageCart_Model extends CI_Model{

        private $tbl_name = 'salvage_cart';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getbybuyer($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvage_cart.sbuyer_id",$user_id);
            $this->db->join("salvage_item",'salvage_item.salvageItem_id=salvage_cart.salvageItem_id');
            $this->db->join('users',"users.user_id=salvage_cart.seller_id");
            $query = $this->db->get();
            return $query->result();
        }


        public function getActive($seller_id,$buyer_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvage_cart.sbuyer_id",$buyer_id);
            $this->db->where('salvage_cart.seller_id',$seller_id);
            $this->db->where("scActive",1);
            $this->db->join("salvage_item",'salvage_item.salvageItem_id=salvage_cart.salvageItem_id');
            $this->db->join('users',"users.user_id=salvage_cart.seller_id");
            $query = $this->db->get();
            return $query->result();
        }

        public function getcartdata($user_id,$salvageItem_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvage_cart.sbuyer_id",$user_id);
            $this->db->where('salvage_cart.salvageItem_id',$salvageItem_id);
            $this->db->join("salvage_item",'salvage_item.salvageItem_id=salvage_cart.salvageItem_id');
            $query = $this->db->get();
            return $query->result();
        }

        public function update($id,$payload){
            return $this->db->update($this->tbl_name,$payload,'salvagecart_id='.$id);
        }

        public function getCartByid($cart_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvage_cart.salvagecart_id",$cart_id);
            $this->db->join("salvage_item",'salvage_item.salvageItem_id=salvage_cart.salvageItem_id');
            $query = $this->db->get();
            return $query->result();
        }

        public function remove($cart_id){
            return $this->db->delete($this->tbl_name,array('salvagecart_id'=>$cart_id));
        }

        public function removeActive($buyer_id,$seller_id){
            return $this->db->delete($this->tbl_name,array("sbuyer_id"=>$buyer_id,"seller_id"=>$seller_id));
        }


        public function removeBySalvageItem($salvageitem_id){
            return $this->db->delete($this->tbl_name,array('salvageItem_id'=>$salvageitem_id));
        }
    }

?>
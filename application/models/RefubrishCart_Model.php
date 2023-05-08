<?php 

    class RefubrishCart_Model extends CI_Model{

        private $tbl_name = 'refubrish_cart';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function create($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getCart($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("refubrish_cart.user_id",$user_id);
            $this->db->join("repubrish_item","repubrish_item.repubrishItem_id=refubrish_cart.repubrishItem_id");
            $this->db->join('repair_shop',"repair_shop.user_id=refubrish_cart.seller_id");
            $query = $this->db->get();
            return $query->result();
        }

        public function getRefubrish($repubrish_id,$user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("refubrish_cart.repubrishItem_id",$repubrish_id);
            $this->db->where("refubrish_cart.user_id",$user_id);
            $this->db->join("repubrish_item","repubrish_item.repubrishItem_id=refubrish_cart.repubrishItem_id");
            $query = $this->db->get();
            return $query->result();
        }

  
        public function getActive($user_id,$seller_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("refubrish_cart.seller_id",$seller_id);
            $this->db->where("refubrish_cart.user_id",$user_id);
            $this->db->where('refubrish_cart.crisActive',1);
            $this->db->join("repubrish_item","repubrish_item.repubrishItem_id=refubrish_cart.repubrishItem_id");
            $this->db->join('users',"users.user_id=refubrish_cart.seller_id");
            $query = $this->db->get();
            return $query->result();

        }
        public function update($id,$payload){
            return $this->db->update($this->tbl_name,$payload,"rcart_id=".$id);
        }

        public function getCartById($rcart_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("rcart_id",$rcart_id);
            $query = $this->db->get();
            return $query->result();
        }

        public function removeActive($user_id,$seller_id){
            return $this->db->delete($this->tbl_name,array('user_id'=>$user_id,'seller_id'=>$seller_id));
        }

        public function remove($cart_id){
            return $this->db->delete($this->tbl_name,array('rcart_id'=>$cart_id));
        }

        public function removeByRefurbrishItem($refurbrishItem_id){
            return $this->db->delete($this->tbl_name,array('repubrishItem_id'=>$refurbrishItem_id));
        }
    }
?>
<?php 

    class OrderSalvageItem_Model extends CI_Model{

        private $tbl_name = "order_salvageItem";

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getOrderBySeller($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("order_salvageitem.seller_id",$user_id);
            $this->db->where("order_salvageitem.salvageorder_status !=","SUCCESS");
            $this->db->join("users","users.user_id=order_salvageitem.buyer_id");
            $this->db->order_by("order_salvageItem.salvageOrder_date","DESC");
            $query = $this->db->get();
            return $query->result();
        }

        public function getSuccessTransactions($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("order_salvageitem.seller_id",$user_id);
            $this->db->where("order_salvageitem.salvageorder_status","SUCCESS");
            $this->db->join("users","users.user_id=order_salvageitem.buyer_id");
            $this->db->order_by("order_salvageItem.salvageOrder_date","DESC");
            $query = $this->db->get();
            return $query->result();
        }

        public function getOrderByUserOrder($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("buyer_id",$user_id);
            $query =$this->db->get();
            return $query->result();
        }

        public function getAllTransactionByBuyerId($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("order_salvageItem.buyer_id",$user_id);
            $this->db->join("users","users.user_id=order_salvageItem.seller_id");
            $this->db->order_by("salvageOrder_date","DESC");
            $query = $this->db->get();
            return $query->result();
        }

        public function getTransactionById($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvageorder_id",$id);
            $this->db->join("users","users.user_id=order_salvageitem.seller_id");
            $query = $this->db->get();
            return $query->result();
        }

        public function update($data,$id){
            return $this->db->update($this->tbl_name,$data,"salvageorder_id=".$id);
        }

        public function updateAllPending($id,$data){
            return $this->db->update($this->tbl_name,$data,"salvageorder_id=".$id." AND salvageorder_status='PENDING'");
        }
  
        public function getLatest(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->order_by('salvageorder_id','DESC');
            $this->db->limit(1);
            $query =$this->db->get();
            return $query->result();
        }

        
        public function getAllSuccess(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("order_salvageitem.salvageorder_status","SUCCESS");
            $this->db->join("users","users.user_id=order_salvageitem.seller_id");
            $this->db->order_by("order_salvageItem.salvageOrder_date","DESC");
            $query = $this->db->get();
            return $query->result();
        }
    }
?>
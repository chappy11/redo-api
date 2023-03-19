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
            $this->db->join("salvage_item","salvage_item.salvageItem_id = order_salvageItem.salvageItem_id");
            $this->db->where("salvage_item.user_id",$user_id);
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
            $this->db->where("buyer_id",$user_id);
            $this->db->join("salvage_item","salvage_item.salvageItem_id=order_salvageItem.salvageItem_id");
            $this->db->order_by("salvageOrder_date","DESC");
            $query = $this->db->get();
            return $query->result();
        }

        public function getTransactionById($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvageorder_id",$id);
            $this->db->join("salvage_item","salvage_item.salvageItem_id=order_salvageItem.salvageItem_id");
            $query = $this->db->get();
            return $query->result();
        }

        public function update($data,$id){
            return $this->db->update($this->tbl_name,$data,"salvageorder_id=".$id);
        }

        public function updateAllPending($id,$data){
            return $this->db->update($this->tbl_name,$data,"salvageorder_id=".$id." AND salvageorder_status='PENDING'");
        }
    }
?>
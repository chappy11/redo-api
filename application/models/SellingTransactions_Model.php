<?php 

    class SellingTransactions_Model extends CI_Model{

        private $tbl_name = 'sellingtransactions';
        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($data){
            return $this->db->insert($this->tbl_name,$data);
        }

        public function getMyTransactions($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("sellingtransactions.user_id",$user_id);
            $this->db->join("order_salvageitem","order_salvageitem.salvageorder_id=sellingtransactions.salvageorder_id");
            $this->db->join("salvage_item","salvage_item.salvageitem_id=order_salvageitem.salvageitem_id");
            $this->db->join("users","users.user_id=order_salvageitem.buyer_id");
            $query = $this->db->get();
            return $query->result();
        }

        public function transaction($sellingtransaction_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("sellingtransactions.selling_transaction_id",$sellingtransaction_id);
            $this->db->join("order_salvageitem","order_salvageitem.salvageorder_id=sellingtransactions.salvageorder_id");
            $this->db->join("salvage_item","salvage_item.salvageitem_id=order_salvageitem.salvageitem_id");
            $this->db->join("users","users.user_id=order_salvageitem.buyer_id");
            $query = $this->db->get();
            return $query->result();
        }
    }

?>
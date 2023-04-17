<?php

    class RefubrishOrder_Model extends CI_Model{

        private $tbl_name = 'refubrish_order';

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
            $this->db->where("refubrish_order.seller_id",$user_id);
            $this->db->join("users","users.user_id=refubrish_order.buyer_id");
            $this->db->order_by("r_order_date","DESC");
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

        public function getOrderByBuyerId($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("refubrish_order.buyer_id",$user_id);
            $this->db->join("users","users.user_id=refubrish_order.seller_id");
            $this->db->order_by("r_order_date","DESC");
            $query = $this->db->get();
            return $query->result();
        }

        
        public function getTransactionById($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("refubrish_order.refubrishorder_id",$id);
            $this->db->join("users","users.user_id=refubrish_order.seller_id");
            $query = $this->db->get();
            return $query->result();
            
        }


        public function update($id,$data){
            return $this->db->update($this->tbl_name,$data,"refubrishorder_id=".$id);
        }

        public function getLatest(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->order_by('refubrishorder_id','DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->result();
        }

     
  
    }

?>
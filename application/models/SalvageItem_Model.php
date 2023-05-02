<?php 

    class SalvageItem_Model extends CI_Model{

        private $tbl_name = "salvage_item";

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function update($id,$payload){
            return $this->db->update($this->tbl_name,$payload,"salvageItem_id=".$id);
        }

        public function salvageItems(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("squantity >",0);
            $this->db->where("isDeleted",0);
            $query = $this->db->get();
            return $query->result();
        }

        public function getSalvageItemByUserId($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("user_id",$user_id);
            $this->db->where("isDeleted",0);
            $this->db->order_by("salvageItem_created",'DESC');
            $query = $this->db->get();
            return $query->result();
        }

        public function getSalvageItemById($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvage_item.salvageItem_id",$id);
            $this->db->join("users","users.user_id=salvage_item.user_id");
            $query = $this->db->get();
            return $query->result();
        }
    }
?>
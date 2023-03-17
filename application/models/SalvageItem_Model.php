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

        public function salvageItems(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $query = $this->db->get();
            return $query->result();
        }

        public function getSalvageItemByUserId($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("user_id",$user_id);
            $query = $this->db->get();
            return $query->result();
        }

        public function getSalvageItemById($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvageItem_id",$id);
            $query = $this->db->get();
            return $query->result();
        }
    }
?>
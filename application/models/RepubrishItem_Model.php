<?php

    class RepubrishItem_Model extends CI_Model{

        private $tbl_name = "repubrish_item";

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getRepubrishByUserId($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("repubrish_item.user_id",$user_id);
            $this->db->join("salvage_item","salvage_item.salvageItem_id = repubrish_item.salvageItem_id");
            $query = $this->db->get();
            return $query->result();
        }
    }
?>
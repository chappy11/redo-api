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
            $this->db->where("isDeleted",0);
            $this->db->where("repubrish_item.user_id",$user_id);
            $query = $this->db->get();
            return $query->result();
        }

        public function getById($repubrishItem_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("repubrish_item.repubrishItem_id",$repubrishItem_id);
            $query =$this->db->get();
            return $query->result();
        }

        public function getTheLatest(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->order_by("repubrishItem_id",'DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->result();
        }

        public function update($id,$payload){
            return $this->db->update($this->tbl_name,$payload,'repubrishItem_id='.$id);
        }
  
        public function all(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("isDeleted","0");
            $this->db->where("rquantity >",0);
            $this->db->where("risActive",1);
            $query =$this->db->get();
            return $query->result();
        }
  
    }
?>
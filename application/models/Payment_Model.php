<?php 

    class Payment_Model extends CI_Model{

        private $tbl_name = 'payment';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }
  
  
        public function getSalvage($salvage_ref){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvage_ref",$salvage_ref);
            $query =$this->db->get();
            return $query->result();
        }

        public function getRefubrish($refubrish_ref){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("refubrish",$refubrish_ref);
            $query =$this->db->get();
            return $query->result();
        }
  
    }

?>
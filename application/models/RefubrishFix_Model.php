<?php

    class RefubrishFix_Model extends CI_Model{

        private $tbl_name = 'refubrish_fix';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }
    
    
        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getrefubrish($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where('repubrishItem_id',$id);
            $query = $this->db->get();
            return $query->result();
        }


        public function getById(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where('refubrishFix_id',$id);
            $query = $this->db->get(); 
            return $query->result();
        }

        public function delete($id){
            return $this->db->delete($this->tbl_name,"refubrishFix_id=".$id);
        }
    }

?>
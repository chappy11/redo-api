<?php 

    class Brand_Model extends CI_Model{

        private $tbl_name = 'brand';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($data){
            return $this->db->insert($this->tbl_name,$data);
        }


        public function brands(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $query = $this->db->get();
            return $query->result();
        }
    }

?>
<?php 

    class IncomeReport_Model extends CI_Model{

        private $tbl_name = 'income_report';
        public function __construct(){   
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getIncome(){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $query = $this->db->get();
            return $query->result();
        }
    }

?>
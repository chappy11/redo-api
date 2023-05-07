<?php 

    class RefurbrishItemRating_Model extends CI_Model{

        private $tbl_name = 'refurbrishitem_rating';
        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function createRate($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getRating($refurbrihsitem_id){
            $this->db->select_avg('rate');
            $this->db->from($this->tbl_name);
            $this->db->where("refurbrishitem_id",$refurbrihsitem_id);
            $query = $this->db->get();
            return $query->result();
        }

    }

?>
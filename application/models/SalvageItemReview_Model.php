<?php 

    class SalvageItemReview_Model extends CI_Model{

        private $tbl_name = 'salvageitem_review';
        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getReview($id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where('salvageitem_review.salvageitem_id',$id);
            $this->db->join("users","users.user_id=salvageitem_review.user_id");
            $this->db->order_by("dateCreated","DESC");
            $query = $this->db->get();
            return $query->result();
        }
    }
?>
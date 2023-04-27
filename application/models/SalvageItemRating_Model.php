<?php 

    class SalvageItemRating_Model extends CI_Model{

        private $tbl_name = 'salvageitem_rating';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getAverage($salvageitem_id){
            $this->db->select_avg('rate');
            $this->db->from($this->tbl_name);
            $this->db->where("salvageitem_id",$salvageitem_id);
            $query = $this->db->get();
            return $query->result();
        }

        public function update($id,$payload){
          return  $this->db->update($this->tbl_name,$payload,"rate_id=".$id);
        }
    }

?>
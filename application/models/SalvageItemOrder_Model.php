<?php 

    class SalvageItemOrder_Model extends CI_Model{

        private $tbl_name = 'salvageitem_order';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getItems($salvageorder_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("salvageitem_order.salvageorder_id",$salvageorder_id);
            $this->db->join('salvage_item','salvage_item.salvageItem_id=salvageitem_order.salvageItem_id');
            $query = $this->db->get();
            return $query->result();
        }
    } 
?>
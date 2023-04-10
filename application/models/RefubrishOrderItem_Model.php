<?php 

    class RefubrishOrderItem_Model extends CI_Model{

        private $tbl_name = 'refubrish_orderitem';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function insert($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }

        public function getItems($refubrishorder_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where('refubrish_orderitem.refubrishorder_id',$refubrishorder_id);
            $this->db->join('repubrish_item','repubrish_item.repubrishItem_id=refubrish_orderitem.refubrishItem_id');
            $query = $this->db->get();
            return $query->result();
        }
    }

?>
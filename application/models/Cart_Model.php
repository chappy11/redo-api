<?php 

    class Cart_Model extends CI_Model{

        private $tbl_name = 'cart';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function addcart($data){
            return $this->db->insert($this->tbl_name,$data);
        }

        public function updateCart($id,$data){
            return $this->db->update($this->tbl_name,$data,'cart_id='.$id);
        }

        public function getUserCart($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where("user_id",$user_id);
            $query = $this->db->get();
            return $query->result();
        }

    }
?>
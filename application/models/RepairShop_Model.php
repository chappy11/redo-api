<?php 

    class RepairShop_Model extends CI_Model{

        private $table = 'repair_shop';

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }


        public function insert($data){
            return $this->db->insert($this->table,$data);
        }

        public function getShopDataByUserId($user_id){
            $this->db->select("*");
            $this->db->from($this->table);
            $this->db->where("user_id",$user_id);
            $query = $this->db->get();
            return $query->result();
        }

        public function deleteShop($user_id){
            return $this->db->delete($this->table,array("user_id"=>$user_id));

        }

        public function getAllShop(){
            $this->db->select("*");
            $this->db->from($this->table);
            $this->db->join('users','users.user_id=repair_shop.user_id');
            $query = $this->db->get();
            return $query->result();
        }      

    }

?>
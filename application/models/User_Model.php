<?php 

    class User_Model extends CI_Model{
        private $tbl = "users";

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        //create new User
        public function create($user=array()){
            return $this->db->insert($this->tbl,$user);
        }


        //login user
        public function login($email,$password){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("users.email",$email);
            $this->db->where("users.password",$password);
            $query = $this->db->get();
            return $query->result();
        }


        public function getUserData($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("users.user_id",$user_id);
            $this->db->join("repair_shop",'repair_shop.user_id=users.user_id');
            $query = $this->db->get();
            return $query->result();
        }

        public function update($data=array(),$user_id){
            return $this->db->update($this->tbl,$data,"user_id=".$user_id);
        }

        public function user($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("user_id",$user_id);
            $query = $this->db->get();
            return $query->result();
        }

        public function users(){
            $this->db   ->select("*");
            $this->db->from($this->tbl);
            $this->db->where("userRoles !=",'admin');
            $query = $this->db->get();
            return $query->result();
        }


        public function getUserByStatus($status){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("status",$status);
            $query = $this->db->get();
            return $query->result();
        }

        public function getpendingShop(){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("users.userRoles",'repairer');
            $this->db->where("users.isPending","1");
            $this->db->join("repair_shop","repair_shop.user_id=users.user_id");
            $query = $this->db->get();
            return $query->result();
        }

        public function getUserByEmail($email){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("email",$email);
            $query = $this->db->get();
            return $query->result();
        }

        public function getUserByShop($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl);
            $this->db->where("users.user_id",$user_id);
            $this->db->join("repair_shop","repair_shop.user_id=users.user_id");
            $query = $this->db->get();
            return $query->result();
        }
    }

?>
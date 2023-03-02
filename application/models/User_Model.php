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
            $this->db->where("email",$email);
            $this->db->where("password",$password);
            $query = $this->db->get();
            return $query->result();
        }

    
    }

?>
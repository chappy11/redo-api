<?php 

    class Notification_Model extends CI_Model{

        private $tbl_name = 'notification';
        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function createNotif($payload){
            return $this->db->insert($this->tbl_name,$payload);
        }  

        public function getNotification($user_id){
            $this->db->select("*");
            $this->db->from($this->tbl_name);
            $this->db->where('reciever_id',$user_id);
            $this->db->order_by('date','DESC');
            $query = $this->db->get();
            return $query->result();
        }
    }

?>
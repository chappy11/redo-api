<?php 

class RefurbrishItemReview_Model extends CI_Model{

    private $tbl_name = 'refubrish_review';
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
        $this->db->where('refubrish_review.refubrishItem_id',$id);
        $this->db->join("users","users.user_id=refubrish_review.user_id");
        $this->db->order_by("refubrish_review.refurbrishReviewDate","DESC");
        $query = $this->db->get();
        return $query->result();
    }
}

?>
<?php 
include_once(dirname(__FILE__)."/Data_format.php");
    

class RefurbrishItemReview extends Data_format{


    public function __construct(){
        parent::__construct();
        $this->load->model(array("RefurbrishItemReview_Model"));
    }

    public function create_post(){
        $data = $this->decode();

        $review = $data->review;
        $user_id = $data->user_id;
        $refubrishItem_id = $data->refubrishItem_id;

        $payload = array(
            "review" => $review,
            "refubrishItem_id" => $refubrishItem_id,
            "user_id" => $user_id
        );

        $isCreated = $this->RefurbrishItemReview_Model->insert($payload);

        if($isCreated){
            $this->res(1,null,"Successfully Created",0);
        }else{
            $this->res(0,null,"Something went wrong",0  );
        }
    }

    public function getreview_get($refurbrishItem_id){
        $data = $this->RefurbrishItemReview_Model->getReview($refurbrishItem_id);
        
        $this->res(1,$data,"GG",0);
    }
}

?>
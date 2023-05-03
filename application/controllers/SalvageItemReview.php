<?php 
include_once(dirname(__FILE__)."/Data_format.php");
    

class SalvageItemReview extends Data_format{


    public function __construct(){
        parent::__construct();
        $this->load->model(array("SalvageItemReview_Model"));
    }

    public function create_post(){
        $data = $this->decode();

        $review = $data->review;
        $user_id = $data->user_id;
        $salvageItem_id = $data->salvageItem_id;

        $payload = array(
            "review" => $review,
            "salvageitem_id" => $salvageItem_id,
            "user_id" => $user_id
        );

        $isCreated = $this->SalvageItemReview_Model->insert($payload);

        if($isCreated){
            $this->res(1,null,"Successfully Created",0);
        }else{
            $this->res(0,null,"Something went wrong",0  );
        }
    }

    public function getreview_get($salvageitem_id){
        $data = $this->SalvageItemReview_Model->getReview($salvageitem_id);
        
        $this->res(1,$data,"GG",0);
    }
}

?>
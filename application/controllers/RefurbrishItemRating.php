<?php 
include_once(dirname(__FILE__)."/Data_format.php");
    

    class RefurbrishItemRating extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array("RefurbrishItemRating_Model"));
        }

        public function rateitem_post(){
            $data = $this->decode();

            $user_id = $data->user_id;
            $item_id = $data->item_id;
            $rate = $data->rate;
        
            $payload = array(
                'rate' => $rate,
                'user_id' => $user_id,
                'refurbrishitem_id' => $item_id,
            );

            $isInsert = $this->RefurbrishItemRating_Model->createRate($payload);

            if($isInsert){
                $this->res(1,null,"Thank you for your rating",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }

        public function rate_get($refurbrishitem_id){
            $rate = $this->RefurbrishItemRating_Model->getRating($refurbrishitem_id);
        
            $this->res(1,$rate[0],"gg",0);
        }


    }

 ?>
<?php 
include_once(dirname(__FILE__)."/Data_format.php");
    

    class SalvageItemRating extends Data_format{


        public function __construct(){
            parent::__construct();
            $this->load->model(array("SalvageItemRating_Model"));
        }

        public function insert(){
            $data = $this->decode();
            $user_id = $data->user_id;
            $salvageItem_id = $data->salvageItem_id;
            $rate = $data->rate;

            $payload = array(
                "rate" => $rate,
                "user_id" => $user_id,
                "salvageitem_id" => $salvageItem_id
            );
            $isInsert = $this->SalvageItemRating_Model->insert($payload);

            if($isInsert){
                $this->res(1,null,"Successfully Rate",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }

        public function rating_get($salvageItem_id){
            $rate =  $this->SalvageItemRating_Model->getAverage($salvageItem_id);

            $this->res(1,$rate,"gg",0);
        }
    }
?>
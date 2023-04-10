<?php 
    include_once(dirname(__FILE__)."/Data_format.php");

    class Refubrish_Fix extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array("RefubrishFix_Model","RepubrishItem_Model"));
        }

        public function insert_post(){
            $data = $this->decode();
            $repubrish_id = $data->id;
            $fix = $data->fix;
            $amount = $data->amount;

            $payload = array(
                "repubrishItem_id" => $repubrish_id,
                "fix" => $fix,
                "amount" => $amount
            );

            $resp = $this->RefubrishFix_Model->insert($payload);

            if($resp){
                $repubrishItem = $this->RepubrishItem_Model->getById($repubrish_id)[0];

                $initialPrice = (float)$repubrishItem->selling_price;
                $newSellingPrice = $initialPrice + (float)$amount;
                $update_payload = array(
                    "selling_price" => $newSellingPrice,
                    'risActive' => 1,
                );
                
                $update = $this->RepubrishItem_Model->update($repubrish_id,$update_payload);
                
                if($update){
                    $this->res(1,null,"Successfully Added",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }
           
            }
        }

        public function fix_get($id){
            $data = $this->RefubrishFix_Model->getrefubrish($id);

            $this->res(1,$data,"Data found",count($data));
        }

        public function remove_post($id){
            $refubrishData = $this->RefubrishFix_Model->getById($id)[0];
            $repubrishItem = $this->RepubrishItem_Model->getById($refubrishData->repubrishItem_id)[0];
            $amount = (float)$repubrishItem->selling_price - (float)$refubrishData->amount;

            $payload = array(
                "selling_price" => $amount
            );
            $update = $this->RepubrishItem_Model->update($refubrishData->repubrishItem_id,$payload);
            if($update){
                $this->RefubrishFix_Model->delete($id);
                
                $this->res(1,null,"Successfully Remove",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }
 
 

    }

?>
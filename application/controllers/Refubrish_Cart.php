<?php 
include_once(dirname(__FILE__)."/Data_format.php");

    class Refubrish_Cart extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array("RefubrishCart_Model","RepubrishItem_Model"));
        }
  
        public function insert_post(){
            $data = $this->decode();
            $repubrishItem = $data->id;
            $user_id = $data->user_id;
            $seller_id = $data->seller_id;

            $repubrishData = $this->RefubrishCart_Model->getRefubrish($repubrishItem,$user_id);

            if(count($repubrishData) > 0){
                $stock =(int)$repubrishData[0]->rquantity;
                $cartQuantity = (int)$repubrishData[0]->cquantity + 1;
                
                $totalStockLeft = $stock - $cartQuantity;

                if($totalStockLeft < 0){
                    $this->res(0,null,'You cannot add anymore',0);
                }else{
                    $updateCartPayload = array(
                        "cquantity" => $cartQuantity
                    );

                    $update = $this->RefubrishCart_Model->update($repubrishData[0]->rcart_id,$updateCartPayload);

                    if($update){
                        $this->res(1,null,"Successfully Created Cart",0);
                    }else{
                        $this->res(0,null,"Something went wrong",0);
                    }

                }
            }else{
                $createPayload = array(
                    "repubrishItem_id" => $repubrishItem,
                    "user_id" => $user_id,
                    "cquantity" => 1,
                    'crisActive' => 0,
                    "seller_id" => $seller_id
                );

                $isCreated = $this->RefubrishCart_Model->create($createPayload);

                if($isCreated){
                    $this->res(1,null,"Successfully Added",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }
            }
        }
  
        public function cart_get($id){
            $data = $this->RefubrishCart_Model->getCart($id);

            $this->res(1,$data,"FGG",count($data));
        }

        public function updateStatus_post(){
            $data = $this->decode();
            $rcart_id = $data->id;
            $status = $data->status;

            $newStatus = $status === "1" ? "0" : "1";

            $payload = array("crisActive" => $newStatus);

            $isUpate = $this->RefubrishCart_Model->update($rcart_id,$payload);

            if($isUpate){
                $this->res(1,null,"Successfully Updated",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }

        }

        public function updatequantity_post(){
            $data = $this->decode();
            $id = $data->rcart_id;
            $type = $data->type;

            $cartData = $this->RefubrishCart_Model->getCartById($id)[0];

            $itemData = $this->RepubrishItem_Model->getById($cartData->repubrishItem_id)[0];

            $stock = (int)$itemData->rquantity;
            $quantity = (int)$cartData->cquantity;

            if($type === 'increment'){
                $addItem = $quantity + 1;

                if($addItem > $stock){
                    $this->res(0,null,"You cannot add anymore",0);
                }else{
                    $addpayload = array(
                        "cquantity" => $addItem
                    );

                    $updateAdd = $this->RefubrishCart_Model->update($id,$addpayload);

                    if($updateAdd){
                        $this->res(1,null,"Successfully Updated",0);
                    }else{
                        $this->res(0,null,"Something went wrong",0);
                    }
                }
            }

            if($type === 'decrement'){
                $diffItem = $quantity - 1;

                if($diffItem < 1){
                    $this->res(0,null,"You cannot diff anymore",0);
                }else{
                    $diffPayload = array(
                        "cquantity" => $diffItem
                    );

                    $updatediff =  $this->RefubrishCart_Model->update($id,$diffPayload);
            
                    if($updatediff){
                        $this->res(1,null,"Successfully Updated",0);
                    }else{
                        $this->res(0,null,"Something went wrong",0);
                    }
                }
            }
        }

        public function getactive_get($user_id,$seller_id){
            $data = $this->RefubrishCart_Model->getActive($user_id,$seller_id);

            $this->res(1,$data,"Data found",count($data));
        }
    }


?>
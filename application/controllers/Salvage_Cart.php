<?php 
include_once(dirname(__FILE__)."/Data_format.php");

    class Salvage_Cart extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array('SalvageCart_Model',"SalvageItem_Model"));
        }


        public function insert_post(){
            $data = $this->decode();
            $user_id = $data->user_id;
            $item_id = $data->item_id;

            $cartData = $this->SalvageCart_Model->getcartdata($user_id,$item_id);

            if(count($cartData) > 0){
                $stock = (int)$cartData[0]->squantity;
                $quantity = (int)$cartData[0]->quantity + 1;

                if($quantity > $stock){
                    $this->res(0,null,"You cannot add anymore",0);
                }else{
                    $updatePayload = array(
                        "salvageItem_id" => $item_id,
                        "sbuyer_id" => $user_id,
                        "quantity" => $quantity
                    );

                    $updated = $this->SalvageCart_Model->update($cartData[0]->salvagecart_id,$updatePayload);
                    
                    if($updated){
                        $this->res(1,null,"Successfully Updated",0);
                    }else{
                        $this->res(0,null,"Something went wrong",0);
                    }
                }
            
            }else{
                $salvageItem = $this->SalvageItem_Model->getSalvageItemById($item_id)[0];

                $createPayload = array(
                    "salvageItem_id" => $item_id,
                    "sbuyer_id" => $user_id,
                    "quantity" => 1,
                    "scActive" => 0,
                    "seller_id" => $salvageItem->user_id
                );

                $created = $this->SalvageCart_Model->insert($createPayload);

                if($created){
                    $this->res(1,null,"Successfully Updated",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }
            }
        }

        public function carts_get($user_id){
            $data = $this->SalvageCart_Model->getbybuyer($user_id);

            $this->res(1,$data,"data found",count($data));
        }

        public function updatestatus_post(){
            $data = $this->decode();

            $id = $data->id;
            $status = $data->status;

            $newStatus = $status === '1' ? 0 : 1;

            $payload = array(
                "scActive" => $newStatus
            );

            $updated = $this->SalvageCart_Model->update($id,$payload);
        
            if($updated){
                $this->res(1,null,"Successfully Updated",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }

        public function updatequantity_post(){
            $data = $this->decode();

            $id =  $data->id;
            $type = $data->type;

            $cartData = $this->SalvageCart_Model->getCartByid($id)[0];
            
            if($type === 'increment'){
                $stock = (int)$cartData->squantity;
                $quantity = (int)$cartData->quantity + 1;

                if($quantity > $stock){
                    $this->res(0,null,"You cannot add anymore",0);
                }else{

                    $incrementPayload = array(
                        "quantity" => $quantity
                    );

                    $updateIncremented = $this->SalvageCart_Model->update($id,$incrementPayload);

                    if($updateIncremented){
                        $this->res(1,null,"Successfully Incremented",0);
                    }else{
                        $this->res(0,null,"something went wrong",0);
                    }
                }

            }

            if($type === 'decrement'){
                $quantity = (int)$cartData->quantity - 1;

                if($quantity < 1){
                    $this->res(0,null,"You cannot deduct anymore",0);
                }else{
                    $derementPayload = array(
                        "quantity" => $quantity
                    );
                    $updateDecrement = $this->SalvageCart_Model->update($id,$derementPayload);

                    if($updateDecrement){
                        $this->res(1,null,"Successfully Incremented",0);
                    }else{
                        $this->res(0,null,"something went wrong",0);
                    }

                }
            }
        }

        public function getactive_get($seller_id,$buyer_id){
            $data = $this->SalvageCart_Model->getActive($seller_id,$buyer_id);

            $this->res(1,$data,"data found",0);
        }

        public function remove_get($cart_id){
            $isSuccess = $this->SalvageCart_Model->remove($cart_id);

            if($isSuccess){
                $this->res(1,null,"Successfully Remove",0);
            }else{
                $this->res(0,null,"Error Remove",0);
            }
            
        }
    }

?>
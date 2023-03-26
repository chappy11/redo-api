<?php 
  include_once(dirname(__FILE__)."/Data_format.php");


  class Salvage_Order extends Data_format{

    public function __construct(){
        parent::__construct();
        
        $this->load->model(array("OrderSalvageItem_Model","RepubrishItem_Model","SalvageItem_Model","SellingTransactions_Model"));
    }


    public function warmp_get(){
        $this->res(1,null,"DG",0);
    }

    
    public function insert_post(){
        $data = $this->decode();
        $user_id = $data->user_id;
        $item_id = $data->salvageItem_id;
        $amount = $data->amount;
        $recieverName = $data->recieverName;
        $address = $data->address;
        $mobileNumber = $data->mobile;
        $refNo = $this->generateRefNo("SALVAGE");
        
            $payload = array(
                "salvageItem_id" => $item_id,
                "ref_id" => $refNo,
                "buyer_id" => $user_id,
                "salvageorder_status" => "PENDING",
                "salvage_amount" => $amount,
                "salvage_recievername" => $recieverName,
                "salvage_shippingAddress" => $address,
                "salvage_recieverMobile" => $mobileNumber
            );

            $isOrderSuccess = $this->OrderSalvageItem_Model->insert($payload);

            if($isOrderSuccess){
                
                $this->res(1,null,"Successfully Ordered",0);
                
            }else{
                $this->res(0,null,"Order Failed",0);
            }
        }

        public function transactions_get($user_id){
            $data = $this->OrderSalvageItem_Model->getAllTransactionByBuyerId($user_id);        
            
            $this->res(1,$data,"data found",count($data));
        }

        public function transaction_get($salvageorder_id){
            $data = $this->OrderSalvageItem_Model->getTransactionById($salvageorder_id);
            
            if(count($data) > 0){
                $this->res(1,$data[0],"Data found",0);
            }else{
                $this->res(0,null,"no data found",0);
            }
        }


        public function orders_get($user_id){
            $data = $this->OrderSalvageItem_Model->getOrderBySeller($user_id);
        
        
            $this->res(1,$data,"Data found",0);
        }

        public function updatestatus_post(){
            $data = $this->decode();
            $id = $data->id;
            $status = $data->status;
            $payload = array(
                "salvageorder_status" => $status
            );
            $isUpdate = $this->OrderSalvageItem_Model->update($payload,$id);
            $orderData = $this->OrderSalvageItem_Model->getTransactionById($id)[0];
            if($isUpdate){
                if($status === 'ACCEPTED'){
                    $updatePayload = array(
                        "salvageorder_status" => "CANCELED"
                    );

                    $updateSalvage = array("isSold"=> 1);
                    
                    $this->SalvageItem_Model->update($orderData->salvageItem_id,$updateSalvage);  

                    $this->OrderSalvageItem_Model->updateAllPending($id,$updatePayload);
                }

                if($status === 'SUCCESS'){
                    $salvageData = $this->SalvageItem_Model->getSalvageItemById($orderData->salvageItem_id)[0];
                    $repubrishPayload = array(
                        "reseller_id" => $orderData->buyer_id,
                        "salvageItem_id" => $orderData->salvageItem_id,
                        "resell"=> 0,
                        "repubrish_price"=> 0,
                        "repubrish_status" => "SALVAGE",
                        "repubrish_isSold" => 0,
                    );
            
                     $this->RepubrishItem_Model->insert($repubrishPayload);
                    
                     $ar = array(
                        "salvageorder_id" => $id,
                        "user_id" => $salvageData->user_id
                     );

                     $this->SellingTransactions_Model->insert($ar);

                }
         
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }
        
    }



?>
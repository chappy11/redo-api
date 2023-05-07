<?php 
  include_once(dirname(__FILE__)."/Data_format.php");


  class Salvage_Order extends Data_format{

    public function __construct(){
        parent::__construct();
        
        $this->load->model(array("OrderSalvageItem_Model","RepubrishItem_Model","SalvageItem_Model","SellingTransactions_Model","Payment_Model","SalvageCart_Model","SalvageItemOrder_Model","User_Model","RefubrishCart_Model",'Notification_Model'));
    }


    public function warmp_get(){
        $this->res(1,null,"DG",0);
    }

    
      public function insert_post(){
        $data = $this->decode();
        $user_id = $data->user_id;
        $seller_id = $data->seller_id;
        $amount = $data->amount;
        $recieverName = $data->recieverName;
        $address = $data->address;
        $mobileNumber = $data->mobile;
        $courier = $data->courier;
        $refNo = $this->generateRefNo("SALVAGE");
        
            $payload = array(
                "ref_id" => $refNo,
                "buyer_id" => $user_id,
                "salvageorder_status" => "PENDING",
                "order_totalAmount" => $amount,
                "salvage_recievername" => $recieverName,
                "seller_id" => $seller_id,
                "salvage_shippingAddress" => $address,
                "salvage_recieverMobile" => $mobileNumber,
                "courier" => $courier,
                "courierRef" => ''
            );

            $isOrderSuccess = $this->OrderSalvageItem_Model->insert($payload);

            if($isOrderSuccess){
                $latest = $this->OrderSalvageItem_Model->getLatest()[0];
                $senderInfo = $this->User_Model->user($user_id)[0];
                $recieverMobile = '09999999999';
                $this->insertOrderItem($seller_id,$user_id,$latest->salvageorder_id);
        

                $ispayment = $this->createpayment($amount,$senderInfo->phoneNumber,$recieverMobile,$latest->ref_id,'salvage');
                if($ispayment){
                    $this->createOrderNotification($user_id,$seller_id);
                    $this->res(1,null,"Successfully Ordered",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }
              
                
            }else{
                $this->res(0,null,"Order Failed",0);
            }
        }

        public function transactions_get($user_id){
            $data = $this->OrderSalvageItem_Model->getAllTransactionByBuyerId($user_id);        
            $arr = array();
            
            foreach ($data as  $value) {
               $payload = array(
                "salvageorder_id" => $value->salvageorder_id,
                "ref_id" =>$value->ref_id,
                "salvageorder_status" => $value->salvageorder_status,
                "order_totalAmount" => $value->order_totalAmount,
                "seller" => $value->fullname,
                "no_items" => count($this->SalvageItemOrder_Model->getItems($value->salvageorder_id)),
                "item" => $this->SalvageItemOrder_Model->getItems($value->salvageorder_id)[0]
            );

               array_push($arr,$payload);
            }

            $this->res(1,$arr,"data found",count($data));
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

        public function successtransactions_get($user_id){
            $data = $this->OrderSalvageItem_Model->getSuccessTransactions($user_id);

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

  
                    $this->OrderSalvageItem_Model->updateAllPending($id,$updatePayload);
                }

                if($status === 'SUCCESS'){
                    $orderItem = $this->SalvageItemOrder_Model->getItems($orderData->salvageorder_id);
                    
                    foreach ($orderItem as $item) {
                    
                        $repubrishPayload = array(
                            "rpic1" => $item->pic1,
                            "rpic2" => $item->pic2,
                            "rpic3" => $item->pic3,
                            "rdevice_name" => $item->deviceName,
                            "rdevice_description" => $item->deviceDescription,
                            "rsalvage_price" => $item->salvage_price,
                            "rdevice_type" => $item->deviceType,
                            "rdeviceBrand" => $item->deviceBrand,
                             "rquantity" => $item->order_quantity,
                            "risActive" => 0,
                            "risSold" => 0,
                            "selling_price" => $item->salvage_price,
                            "user_id" => $orderData->buyer_id
                         );
                         $this->RepubrishItem_Model->insert($repubrishPayload);
                     }
                    
                    $userData = $this->User_Model->user($orderData->seller_id)[0];
                    $this->createpayment($orderData->order_totalAmount,'09999999999',$userData->phoneNumber,$orderData->ref_id,'salvage');
                    $this->res(1,null,"Successfully Updated",0);

                }
         
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }

        public function item_get($salvageorder_id){
            $data = $this->SalvageItemOrder_Model->getItems($salvageorder_id);
        
            $this->res(1,$data,'gg',0);
        }

        public function payment_get($ref_id){
            $data = $this->Payment_Model->getSalvage($ref_id);
        
            $this->res(1,$data,'gg',0);
        }

        public function cartitems_get($user_id,$type){
            $data = [];
            if($type === 'Ref'){
                $data = $this->SalvageCart_Model->getbybuyer($user_id);
            }else if($type === 'Sal'){
                $data = $this->RefubrishCart_Model->getCart($user_id);
            }

            $this->res(1,$data,"GG",count($data));
        }

        public function allsuccess_get(){
            $data = $this->OrderSalvageItem_Model->getAllSuccess();

            $this->res(1,$data,"GG",count($data));
        }
//helper

        public function createpayment($amount,$sender,$reciever,$transaction,$type){
            $ref = rand(100000000,999999999);

            $payload = array(
                "paymentRefNo" => $ref,
                "amount" => $amount,
                "sender_mobileNumber" => $sender,
                "reciever_mobileNumber" => $reciever,
                "refubrish_ref" => $type === 'refubrish' ? $transaction : null,
                'salvage_ref' => $type === 'salvage' ? $transaction : null
            );
            
           return $this->Payment_Model->insert($payload);

          
        }
   
        public function insertOrderItem($seller_id,$buyer_id,$salvageorder_id){
            $getActive = $this->SalvageCart_Model->getActive($seller_id,$buyer_id);
            
            foreach ($getActive as $item) {
                $payload = array(
                    "salvageorder_id" => $salvageorder_id,
                    "salvageItem_id" => $item->salvageItem_id,
                    "order_quantity" => $item->quantity
                );

                $this->SalvageItemOrder_Model->insert($payload);
            }

            foreach($getActive as $val){
                $payload = array(
                    "squantity" => (int)$val->squantity - (int)$val->quantity 
                );

                $this->SalvageItem_Model->update($val->salvageItem_id,$payload);
            }

            $this->SalvageCart_Model->removeActive($buyer_id,$seller_id);
        
        }

        public function createOrderNotification($user_id,$seller_id){
            $userData = $this->User_Model->user($user_id)[0];
            $header = 'You have new order!!';
            $body = $userData->fullname." create new order";

            $payload = array(
                "reciver_id" => $seller_id,
                'header' => $header,
                'body' => $body,
                'isRead' => 0,
            );
        
           $this->Notification_Model->createNotif($payload);
        }

        public function updateOrderStatusNotification($user_id,$type){
            $userData = $this->User_Model->user($user_id)[0];
            $header = '';
            $body = '';

            if($type === 'SUCCESS'){
                $header = 'Your';
            }

            $payload = array(
                "reciver_id" => $seller_id,
                'header' => $header,
                'body' => $body,
                'isRead' => 0,
            );
        
           $this->Notification_Model->createNotif($payload);
        }
    
        
    }



?>
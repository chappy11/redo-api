<?php 

include_once(dirname(__FILE__)."/Data_format.php");

    class Refubrish_Order extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array('RefubrishOrder_Model','RefubrishOrderItem_Model','RefubrishCart_Model',"Payment_Model","User_Model","RepubrishItem_Model","Notification_Model"));
        }

        public function insert_post(){
            $data = $this->decode();
            $user_id = $data->user_id;
            $seller_id = $data->seller_id;
            $amount = $data->amount;
            $recieverName = $data->recieverName;
            $address = $data->address;
            $courier = $data->courier;
            $mobileNumber = $data->mobile;
            $refNo = $this->generateRefNo("REPUBRISHED");

            $payload = array(
                'ref_id' => $refNo,
                "refubrishorder_status"=> 'PENDING',
                'total_amount' => $amount,
                'r_recievername'=>$recieverName,
                'r_shippingAddress' => $address,
                'r_mobileNumber' => $mobileNumber,
                'buyer_id' => $user_id,
                'seller_id' => $seller_id,
                'courier'=>$courier,
                "courierRef"=>""
            );


            $orderSuccess = $this->RefubrishOrder_Model->insert($payload);
            if($orderSuccess){
                $latest = $this->RefubrishOrder_Model->getLatest()[0];
                $senderInfo = $this->User_Model->user($user_id)[0];
                $recieverNo = '09999999999';
                $this->insertOrderItem($seller_id,$user_id,$latest->refubrishorder_id);

                $isPaymentCreated = $this->createpayment($amount,$senderInfo->phoneNumber,$recieverNo,$latest->ref_id,'refubrish');
                
                if($isPaymentCreated){
                    $this->createOrderNotification($user_id,$seller_id);
                    $this->res(1,null,"Succesfully Ordered",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }
            }else{
                $this->res(0,null,"Something went wrong",0);   
            }

        }


        public function sales_get($user_id){
            $data = $this->RefubrishOrder_Model->getOrderBySeller($user_id);
            $temp = array();
            
            foreach ($data as  $value) {
                $paylaod = array(
                    "refubrishorder_id" => $value->refubrishorder_id,
                    "ref_id" => $value->ref_id,
                    'refubrishorder_status' => $value->refubrishorder_status,
                    'total_amount' => $value->total_amount,
                    'seller' => $value->fullname,
                    'no_items' => count($this->RefubrishOrderItem_Model->getItems($value->refubrishorder_id)),
                    'item' => $this->RefubrishOrderItem_Model->getItems($value->refubrishorder_id)[0]
                );
                
                array_push($temp,$paylaod);

            }
            $this->res(1,$temp,'',0);
        }

        public function transactions_get($user_id){
            $data = $this->RefubrishOrder_Model->getOrderByBuyerId($user_id);
            $temp = array();
            
            foreach ($data as  $value) {
                $paylaod = array(
                    "refubrishorder_id" => $value->refubrishorder_id,
                    "ref_id" => $value->ref_id,
                    'refubrishorder_status' => $value->refubrishorder_status,
                    'total_amount' => $value->total_amount,
                    'seller' => $value->fullname,
                    'no_items' => count($this->RefubrishOrderItem_Model->getItems($value->refubrishorder_id)),
                    'item' => $this->RefubrishOrderItem_Model->getItems($value->refubrishorder_id)[0]
                );
                
                array_push($temp,$paylaod);

            }
            $this->res(1,$temp,'',0);
        }

        public function transaction_get($id){
            $data = $this->RefubrishOrder_Model->getTransactionById($id)[0];
            $this->res(1,$data,"",0);
        }

        public function updatestatus_post(){
            $data = $this->decode();
            $id = $data->id;
            $status = $data->status;
            $courierRef = $data->courierRef  ? $data->courierRef : '';
            $updatepayload = array();
            
            if($status === 'DELIVERED'){
              $updatepayload =  array(
                    "refubrishorder_status" => $status,
                    'courierRef' => $courierRef
                );
    
            }else{
                $updatepayload = array( "refubrishorder_status" => $status);
            }
           
            $isUpdate = $this->RefubrishOrder_Model->update($id,$updatepayload);
            $orderData = $this->RefubrishOrder_Model->getTransactionById($id);

            if($isUpdate){
                $this->updateOrderStatusNotification($orderData[0]->buyer_id,$status);
                if($status === 'SUCCESS'){
                    $userData = $this->User_Model->user($orderData[0]->seller_id)[0];
                    $ispayment = $this->createpayment($orderData[0]->total_amount,'09999999999',$userData->phoneNumber,$orderData[0]->ref_id,'refubrish');   
                
                    if($ispayment){
                        $this->res(1,null,"Successfully Updated",0);
                    }else{
                        $this->res(0,null,"Somenthing went wrong",0);
                    }
                }
            }else{
                $this->res(0,null,"Somenthing went wrong",0);
            }
        }

        public function items_get($order_id){
            $data = $this->RefubrishOrderItem_Model->getitems($order_id);
        
            $this->res(1,$data,"dd",0);
        }


        public function payment_get($ref_id){
            $data = $this->Payment_Model->getRefubrish($ref_id);

            $this->res(1,$data,"GG",0);
        }
//transactions
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

        public function insertOrderItem($seller_id,$buyer_id,$refubrishorder_id){
            $getActive = $this->RefubrishCart_Model->getActive($buyer_id,$seller_id);
            
            foreach ($getActive as $item) {
                $payload = array(
                    "refubrishorder_id" => $refubrishorder_id,
                    "refubrishItem_id" => $item->repubrishItem_id,
                    "order_quantity" => $item->cquantity
                );

                $this->RefubrishOrderItem_Model->insert($payload);
            }

            foreach($getActive as $val){
                $payload = array(
                    "rquantity" => (int)$val->rquantity - (int)$val->cquantity 
                );

                $this->RepubrishItem_Model->update($val->repubrishItem_id,$payload);
            }

            $this->RefubrishCart_Model->removeActive($buyer_id,$seller_id);
        
        }

        public function success_get($user_id){
            $data = $this->RefubrishOrder_Model->getAllSuccess($user_id);
            $temp = array();
            
            foreach ($data as  $value) {
                $paylaod = array(
                    "refubrishorder_id" => $value->refubrishorder_id,
                    "ref_id" => $value->ref_id,
                    'refubrishorder_status' => $value->refubrishorder_status,
                    'total_amount' => $value->total_amount,
                    'seller' => $value->fullname,
                    'no_items' => count($this->RefubrishOrderItem_Model->getItems($value->refubrishorder_id)),
                    'item' => $this->RefubrishOrderItem_Model->getItems($value->refubrishorder_id)[0]
                );
                
                array_push($temp,$paylaod);

            }
            $this->res(1,$temp,'',0);
        }

        public function allsuccess_get(){
            $data =  $data = $this->RefubrishOrder_Model->getSuccess();

            $this->res(1,$data,'',count($data));
        }

        public function createOrderNotification($user_id,$seller_id){
            $userData = $this->User_Model->user($user_id)[0];
            $header = 'You have new order!!';
            $body = $userData->fullname." create new order";

            $payload = array(
                "reciever_id" => $seller_id,
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
                $header = 'Your order is successfully delivered';
                $body = 'Your has been successfully delivered';
            }else if($type === 'ACCEPTED'){
                $header = 'Your order is successfully accepted';
                $body = 'Your has been successfully accepted';
            }else if($type === 'DELIVERED'){
                $header = 'Your order is successfully delivered';
                $body = 'Your has been successfully accepted';
            }

            $payload = array(
                "reciever_id" => $user_id,
                'header' => $header,
                'body' => $body,
                'isRead' => 0,
            );
        
           $this->Notification_Model->createNotif($payload);
        }


    }
?>
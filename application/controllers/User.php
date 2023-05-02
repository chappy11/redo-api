<?php

    include_once(dirname(__FILE__)."/Data_format.php");

    class User extends Data_format{

        public function __construct(){
            parent::__construct();
            
            $this->load->model(array("User_Model","RepairShop_Model"));
        }

        public function register_post(){
            $data = $this->decode();
            $fullname = $data->fullname;
            $email = $data->email;
            $address = $data->address;
            $phoneNumber = $data->phone;
            $password =$data->password;

            $getEmail =  $this->User_Model->getUserByEmail($email);

            if(count($getEmail) > 0){
                $this->res(0,null,"This Email is Already Exist",0);
            }else{
                $data = array(
                    "profilePic" => 'profiles/no_user.png',
                    "fullname" => $fullname,
                    "email" => $email,
                    "password" => $password,
                    "address" => $address,
                    "phoneNumber" => $phoneNumber,
                    "userRoles" => "user",
                    "status" => 1
                );
    
                $response = $this->User_Model->create($data);
    
                if($response){
                    $this->res(1,null,"Successfully Registered",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }
    
            }
            
        }

        public function login_post(){
            $data= $this->decode();

            $email = $data->email;
            $password = $data->password;

            $resp = $this->User_Model->login($email,$password);
           

            $noResp = count($resp);
            if($noResp > 0){

                $this->res(1,$resp[0],"Successfully Login",$noResp);
            }else{
                $this->res(0,null,"Invalid credentials",0);
            }
        }

        public function userinfo_get($user_id){
            $data = $this->User_Model->user($user_id);

            if(count($data) > 0){
                $this->res(1,$data[0],"data found",0);
            }else{
                $this->res(0,null,"data not found",0);
            }
        }

        public function users_get(){
            $data = $this->decode();
        
             $resp = $this->User_Model->users($data);

            $this->res(1,$resp,"data found",0);
        }


        public function userstatus_get($status){
            $data = $this->User_Model->getUserByStatus($status);

            $this->res(1,$data,"data found",count($data));
        }


        public function pending_get(){
            $data =$this->User_Model->getpendingShop();

            $this->res(1,$data,"data found",0);
        }


        public function approved_post($user_id){
            $payload = array(
                "isPending" => 0
            );

            $update = $this->User_Model->update($payload,$user_id);
        
        
            if($update){
                $this->res(1,null,"Successfully Approved",0);
            }else{
                $this->res(0,null,"something went wrong",0);
            }
        }

        public function changepass_post(){
            $data = $this->decode();

            $email = $data->email;
            $password = $data->password;

            $userData = $this->User_Model->getUserByEmail($email)[0];

            $payload = array("password"=> $password);

            $isUpdated = $this->User_Model->update($payload,$userData->user_id);
       
            if($isUpdated){
                $this->res(1,null,"Successfully Updated",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
       
        }

        public function updateuser_post(){
              $user_id = $this->post("user_id");
              $image = isset($_FILES['pic']['name']) ? $_FILES['pic']['name'] : ""; 
              $fullname = $this->post("fullname");
              $address = $this->post("address");  

              $userInfo = $this->User_Model->user($user_id)[0];
              
              $imageData =  isset($_FILES['pic']['name']) ? 'profiles/'.$image : $userInfo->profilePic;
              $fullnameData = $fullname === "" ? $userInfo->fullname: $fullname;
              $addressData = $address === "" ? $userInfo->address : $address;


              $payload = array(
                "profilePic" => $imageData,
                "fullname" => $fullnameData,
                "address" =>$addressData
              );

              $isUpdated = $this->User_Model->update($payload,$user_id);
        
              if($isUpdated){
                if(isset($_FILES['pic']['name'])){
                    move_uploaded_file($_FILES['pic']['tmp_name'],"profiles/".$image);
                }
                $user = $this->User_Model->login($userInfo->email,$userInfo->password)[0];


                $this->res(1,$user,"Successfully Updated",0);
              }else{
                $this->res(0,null,"Something went wrong",0);
              }      
        }

        public function updatestatus_post(){
            $data = $this->decode();

            $user_id = $data->user_id;
            $status = $data->status;

            $payload = array("status"=>$status);

            $isUpdated = $this->User_Model->update($payload,$user_id);

            if($isUpdated){
                $this->res(1,null,"Successfully Updated",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }
    }

?>  


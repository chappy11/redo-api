<?php

    include_once(dirname(__FILE__)."/Data_format.php");

    class User extends Data_format{

        public function __construct(){
            parent::__construct();
            
            $this->load->model(array("User_Model"));
        }
        //note:
        //user role:
        //0 admin
        //1 seller
        //2 customer
        
        public function register_post(){
            $data = $this->decode();
            $fullname = $data->fullname;
            $email = $data->email;
            $phoneNumber = $data->phone;
            $password =$data->password;
            $data = array(
                "profilePic" => 'profiles/no_user.png',
                "fullname" => $fullname,
                "email" => $email,
                "password" => $password,
                "phoneNumber" => $phoneNumber,
                "userRoles" => "user",
                "status" => 1
            );

            $response = $this->User_Model->create($data);

            if($response){
                $this->res(1,null,"Successfully Login",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
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
    }

?>  
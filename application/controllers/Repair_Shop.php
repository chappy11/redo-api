<?php 
  include_once(dirname(__FILE__)."/Data_format.php");


  class Repair_Shop extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->model(array("RepairShop_Model","User_Model"));
        }

        public function insert_post(){
            $user_id = $this->post('user_id');
            $name = $this->post("name");
            $pic = $_FILES['pic']['name'];
            $birPhoto = $_FILES['bir']['name'];
            $dtiPhoto = $_FILES['dti']['name'];
            $address = $this->post('address');
            $isActive = true;

            $arr = array(
                "user_id" => $user_id,
                "shopImage" => "shops/".$pic,
                "shop_name" => $name,
                "birPhoto" => "shops/".$birPhoto,
                "dtiPhoto" => "shops/".$dtiPhoto,
                "shopAddress" => $address,
                "shopIsActive" => $isActive
            );

            $resp = $this->RepairShop_Model->insert($arr);

            if($resp){
                move_uploaded_file($_FILES['bir']['tmp_name'],"shops/".$birPhoto);
                move_uploaded_file($_FILES['dti']['tmp_name'],"shops/".$dtiPhoto);
                move_uploaded_file($_FILES['pic']['tmp_name'],"shops/".$pic);

                $payload = array(
                    "userRoles" => "repairer"
                );

                $isUpdate = $this->User_Model->update($payload,$user_id);

                if($isUpdate){
                    $responseUser = null;
                    $userData = $this->User_Model->user($user_id)[0];
                    $shopData = $this->RepairShop_Model->getShopDataByUserId($user_id)[0];
                    
                    $responseUser = (object)array_merge((array)$userData,(array)$shopData);

                    $this->res(1,$responseUser,"Successfully Inserted",0);
                }else{
                    $this->res(0,null,"Something went wrong",0);
                }


            }else{
                $this->res(0,null,"Something went wrong",0);
            }            
        }

        public function shop_get($user_id){
            $data = $this->RepairShop_Model->getShopDataByUserId($user_id);

            if(count($data) > 0){
                $this->res(1,$data,"Data found",count($data));
            }else{
                $this->res(0,null,"Data not found",0);
            }
        }
    }
?>
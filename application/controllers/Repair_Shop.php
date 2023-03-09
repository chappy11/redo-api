<?php 
  include_once(dirname(__FILE__)."/Data_format.php");


  class Repair_Shop extends Data_format{

        public function __construct(){
            parent::__construct();
            $this->load->mode(array("RepairShop_Model"));
        }

        public function insert_post(){
            $user_id = $this->post('user_id');
            $name = $this->post("name");
            $birPhoto = $_FILES['bir']['name'];
            $dtiPhoto = $_FILES['dti']['name'];
            $address = $this->post('address');
            $isActive = true;

            $arr = array(
                "user_id" => $user_id,
                "birPhoto" => "shop/".$birPhoto,
                "dtiPhoto" => "shop/".$dtiPhoto,
                "shopAddress" => $address,
                "shopIsActive" => $isActive
            );

            $resp = $this->RepairShop_Model->insert($arr);

            if($resp){
                move_uploaded_file($_FILES['bir']['tmp_name'],"shop/".$birPhoto);
                move_uploaded_file($_FILES['dti']['tmp_name'],"shop/".$dtiPhoto);
                $this->res(1,null,"Successfully Insert",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }            
        }

        public function shop_get($user_id){
            $data = $this->ShopRepair_Model->getShopDataByUserId($user_id);

            if(count($data) > 0){
                $this->res(1,$data,"Data found",count($data));
            }else{
                $this->res(0,null,"Data not found",0);
            }
        }
    }

?>
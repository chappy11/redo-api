<?php 

include_once(dirname(__FILE__)."/Data_format.php");

class RepubrishItem extends Data_format{
        public function __construct(){
            parent::__construct();
            $this->load->model(array("RepubrishItem_Model",'RefubrishCart_Model'));
        }

        public function add_post(){
            $user_id = $this->post('user_id');
            $name = $this->post("name");
            $quantity = $this->post("quantity");
            $description = $this->post("description");
            $type = $this->post("type");
            $brand = $this->post("brand");
            $price = $this->post("price");
            $pic1 = $_FILES['pic1']['name'];
            $pic2 = $_FILES['pic2']['name'];
            $pic3 = $_FILES['pic3']['name'];
                
            $payload = array(
                "user_id" => $user_id,
                "rpic1" => "products/".$pic1,
                "rpic2" => "products/".$pic2,
                "rpic3" => "products/".$pic3,
                "rdevice_name" => $name,
                "rdevice_description" => $description,
                "rsalvage_price" => $price,
                "rdevice_type" => $type,
                "rdeviceBrand" => $brand,
                "selling_price" => $price,
                "rquantity" => $quantity,
                "risActive" => 0,
                "risSold" => 0
            );
    
            $resp = $this->RepubrishItem_Model->insert($payload);
        
            if($resp){
                move_uploaded_file($_FILES['pic1']['tmp_name'],"products/".$pic1);
                move_uploaded_file($_FILES['pic2']['tmp_name'],"products/".$pic2);
                move_uploaded_file($_FILES['pic3']['tmp_name'],"products/".$pic3);
                $latest = $this->RepubrishItem_Model->getTheLatest()[0];

                $this->res(1,$latest,"Successfully Inserted",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
    
        }


        public function items_get($user_id){
            $data = $this->RepubrishItem_Model->getRepubrishByUserId($user_id);

            $this->res(1,$data,"d",0);
        }

        public function item_get($id){
            $data = $this->RepubrishItem_Model->getById($id)[0];

            $this->res(1,$data,"",0);


        }
  
        public function refubrish_get(){
            $data = $this->RepubrishItem_Model->all();

            $this->res(1,$data,"data found",count($data));
        }
  

        public function update_post(){
            $id = $this->post('id');
            $pic2 = isset($_FILES['pic2']['name']) ? $_FILES['pic2']['name'] : "";
            $pic3 = isset($_FILES['pic3']['name']) ? $_FILES['pic3']['name'] : "";
            $quantity = $this->post('quantity');
            $rdevice_description = $this->post("details");
            $data = $this->RepubrishItem_Model->getById($id)[0];
            $hasImage1 = $data->rpic2 === "" ? "" : $data->rpic2;
            $hasImage2 = $data->rpic3 === "" ? "" : $data->rpic3;

            $image2 = isset($_FILES['pic2']['name']) ? "products/".$pic2 : $hasImage1;
            $image3 = isset($_FILES['pic3']['name']) ? "products/".$pic3 : $hasImage2;

            $payload = array(
                "rpic2" => $image2,
                "rpic3" => $image3,
                "rquantity" => $quantity,
                "rdevice_description" => $rdevice_description
            );

            $isUpdate = $this->RepubrishItem_Model->update($id,$payload);
       
            if($isUpdate){
                if(isset($_FILES['pic2']['name'])){
                    move_uploaded_file($_FILES['pic2']['tmp_name'],"products/".$pic2);       
                }

                if(isset($_FILES['pic3']['name'])){
                    move_uploaded_file($_FILES['pic3']['tmp_name'],"products/".$pic3);       
                }
                
                $this->res(1,null,"Succesfully Updated",0);
            }else{
                $this->res(0,null,"SOmething went wrong",0);
            }
        }


        public function remove_get($id){
            $payload = array(
                "isDeleted" => 1
            );

            $isUpdate = $this->RepubrishItem_Model->update($id,$payload);
       
            if($isUpdate){
                $this->RefubrishCart_Model->removeByRefurbrishItem($id);
                $this->res(1,null,"Succesfully Removed",0);
            }else{
                $this->res(0,null,"SOmething went wrong",0);
            }
        }
    }

?>
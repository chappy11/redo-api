<?php 

include_once(dirname(__FILE__)."/Data_format.php");

 class Salvage_Item extends Data_format{

    public function __construct(){
        parent::__construct();
        $this->load->model(array("SalvageItem_Model","SalvageCart_Model"));
    }

    public function samp_post(){
        $pic1 =isset($_FILES['pic1']['name']) ? "Nice" : ""; 
    }

    public function insert_post(){
        $user_id = $this->post('user_id');
        $name = $this->post("name");
        $description = $this->post("description");
        $type = $this->post("type");
        $brand = $this->post("brand");
        $purchasePrice = $this->post("purchasePrice");
        $numberOfYears = $this->post("numberOfYears");
        $salvageLevel = $this->post("salvageLevel");
        $quantity = $this->post('quantity');
        $price = $this->post("price");
        $pic1 = $_FILES['pic1']['name'];
        $pic2 = isset($_FILES['pic2']['name']) ? $_FILES['pic2']['name'] : "";
        $pic3 = isset($_FILES['pic3']['name']) ? $_FILES['pic3']['name'] : "";
        

        $image2 = isset($_FILES['pic2']['name']) ? "products/".$pic2 : "";
        $image3 = isset($_FILES['pic3']['name']) ? "products/".$pic3 : "";

        $payload = array(
            "user_id" => $user_id,
            "pic1" => "products/".$pic1,
            "pic2" => $image2,
            "pic3" => $image3,
            "deviceName" => $name,
            "purchase_price" =>$purchasePrice,
            "number_years" => $numberOfYears,
            "salvage_level" => $salvageLevel,
            "deviceDescription" => $description,
            "salvage_price" => $price,
            "deviceType" => $type,
            "squantity" => $quantity,
            "deviceBrand" => $brand,
            "isActive" => 1,
            "isSold" => 0
        );

        $resp = $this->SalvageItem_Model->insert($payload);
    
        if($resp){
            move_uploaded_file($_FILES['pic1']['tmp_name'],"products/".$pic1);
            if(isset($_FILES['pic2']['name'])){
                move_uploaded_file($_FILES['pic2']['tmp_name'],"products/".$pic2);
            }
            
            if(isset($_FILES['pic3']['name'])){
                move_uploaded_file($_FILES['pic3']['tmp_name'],"products/".$pic3);
            }
            
            $this->res(1,null,"Successfully Inserted",0);
        }else{
            $this->res(0,null,"Something went wrong",0);
        }
    }

    public function salvageitem_get($user_id){
        $data = $this->SalvageItem_Model->getSalvageItemByUserId($user_id);
    
        if(count($data) > 0){
            $this->res(1,$data,"data found",count($data));
        }else{
            $this->res(0,null,"Something went wrong",0);
        }
    }

    public function salvageitemid_get($id){
        $data = $this->SalvageItem_Model->getSalvageItemById($id);

        if(count($data) > 0){
            $this->res(1,$data[0],"data found",count($data));
        }else{
            $this->res(0,null,"Something went wrong",0);
        }
    }

    public function salvageitems_get(){
        $data = $this->SalvageItem_Model->salvageItems();

        if(count($data) > 0){
            $this->res(1,$data,"Data found",0);
        }else{
            $this->res(0,null,"Something went wrong",0);
        }
    }

    public function updateitems_post(){
        $id = $this->post("id");
        $quantity = $this->post("quantity");
        $details = $this->post("details");
        $image2 = isset($_FILES['pic2']['name']) ? $_FILES['pic2']['name'] : ""; 
        $image3 = isset($_FILES['pic3']['name']) ? $_FILES['pic3']['name'] : "";
        $data = $this->SalvageItem_Model->getSalvageItemById($id)[0];

        $hasImage1 = $data->pic2 === "" ? "" : $data->pic2;
        $hasImage2 = $data->pic3 === "" ? "" : $data->pic3;
        $imgName1 = isset($_FILES['pic2']['name']) ? "products/".$image2 : $hasImage1;
        $imgName2 = isset($_FILES["pic3"]['name']) ? "products/".$image3 : $hasImage2;
    
        $payload = array(
            "pic2" => $imgName1,
            "pic3" => $imgName2,
            "squantity" => $quantity,
            "deviceDescription" => $details
        );

        $isUpdate = $this->SalvageItem_Model->update($id,$payload);

        if($isUpdate){
            if(isset($_FILES['pic2']['name'])){
                move_uploaded_file($_FILES['pic2']['tmp_name'],"products/".$image2);
            }
            
            if(isset($_FILES['pic3']['name'])){
                move_uploaded_file($_FILES['pic3']['tmp_name'],"products/".$image3);
            }   


            $this->res(1,null,"Successfully Updated",0);
        }else{
            $this->res(0,null,"Something went wrong",0);
        }
    }

    public function remove_get($id){
        $payload = array(
            "isDeleted" => 1
        );

        $resp = $this->SalvageItem_Model->update($id,$payload);

        if($resp){
            $this->SalvageCart_Model->removeBySalvageItem($id);
            $this->res(1,null,"Successfully Updated",0);
        }else{
            $this->res(0,null,"Something went wrong",0);
        }
    }
 }
?>
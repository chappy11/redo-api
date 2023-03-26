<?php 

include_once(dirname(__FILE__)."/Data_format.php");

 class Salvage_Item extends Data_format{

    public function __construct(){
        parent::__construct();
        $this->load->model(array("SalvageItem_Model"));
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
        $price = $this->post("price");
        $pic1 = $_FILES['pic1']['name'];
        $pic2 = $_FILES['pic2']['name'];
        $pic3 = $_FILES['pic3']['name'];
        

        $payload = array(
            "user_id" => $user_id,
            "pic1" => "products/".$pic1,
            "pic2" => "products/".$pic2,
            "pic3" => "products/".$pic3,
            "deviceName" => $name,
            "purchase_price" =>$purchasePrice,
            "number_years" => $numberOfYears,
            "salvage_level" => $salvageLevel,
            "deviceDescription" => $description,
            "salvage_price" => $price,
            "deviceType" => $type,
            "deviceBrand" => $brand,
            "isActive" => 1,
            "isSold" => 0
        );

        $resp = $this->SalvageItem_Model->insert($payload);
    
        if($resp){
            move_uploaded_file($_FILES['pic1']['tmp_name'],"products/".$pic1);
            move_uploaded_file($_FILES['pic2']['tmp_name'],"products/".$pic2);
            move_uploaded_file($_FILES['pic3']['tmp_name'],"products/".$pic3);
            
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
 }
?>
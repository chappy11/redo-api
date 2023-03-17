<?php 

include_once(dirname(__FILE__)."/Data_format.php");

 class Brand extends Data_format{

    public function __construct(){
        parent::__construct();
        $this->load->model(array("Brand_Model"));
    }

    public function insert_post(){
        $data = $this->decode();

        $brandName = $data->brandName;

        $payload = array(
            "brandName" => $brandName
        );

        $resp = $this->Brand_Model->insert($payload);

        if($resp){
            $this->res(1,null,"Successfully Inserted");
        }else{
            $this->res(0,null,"Something went wrong");
        }
    }

    public function brands_get(){
        $data = $this->Brand_Model->brands();

        if(count($data) > 0){
            $this->res(1,$data,"data found",count($data));
        }else{
            $this->res(0,[],"data not found",0);
        }

    }
 }

?>

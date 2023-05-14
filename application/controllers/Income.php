<?php

    include_once(dirname(__FILE__)."/Data_format.php");

    class Income extends Data_format{

        public function __construct(){
            parent::__construct();
            
            $this->load->model(array("User_Model","RepairShop_Model","Notification_Model","IncomeReport_Model"));
        }

        public function income_get(){
            $data = $this->IncomeReport_Model->getIncome();

            $this->res(1,$data,"",count($data));
        }
    }
?>
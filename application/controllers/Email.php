<?php 
    include_once(dirname(__FILE__)."/Data_format.php");

    class Email extends Data_format {

        public function __construct(){
            parent::__construct();
            $this->load->model(array("User_Model"));
        }
    
        public function sendEmail_post(){
            $data = $this->decode();
            $email = isset($data->email) ? $data->email : "";
            $code = isset($data->code) ? $data->code : "";
            
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'smtp.mailtrap.io';
            $config['smtp_port']    = '2525';
            $config['smtp_user'] = '97e866dd948879';
            $config['smtp_pass'] = '0ebfc149ebaf56';
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = TRUE; // bool whether to validate email or not      
            $this->load->library('email');

            $this->email->initialize($config);
            $this->email->from("no-reply@petsoceity.com");
            $this->email->to($email);
            $this->email->subject("Email Verificatoin Code");
            $this->email->message($code);
        $getEmail = $this->User_Model->getUserByEmail($email);
        if(count($getEmail) < 1){
            $this->res(0,null,"This Email is not Exist",0);
        }
        else if($this->isEmail($email)){
            $this->res(0,null,"Invalid Email",0);
        }
        else{
            $res = $this->email->send();
            if($res){
                $this->res(1,null,"We send verification code to your email",0);
            }else{
                $this->res(0,null,"Something went wrong",0);
            }
        }
    }

    public function sendotp_post(){
        $data = $this->decode();
        $email = isset($data->email) ? $data->email:"";
        $code = isset($data->code) ? $data->code : "";
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'smtp.mailtrap.io';
        $config['smtp_port']    = '2525';
        $config['smtp_user'] = '7ec9d17b2163b3';
        $config['smtp_pass'] = '55c83a05d8d4cb';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not      
        $this->load->library('email');

        $this->email->initialize($config);
        $this->email->from("no-reply@petsoceity.com");
        $this->email->to($email);
        $this->email->subject("Email Verificatoin Code");
        $this->email->message($code);

        $shopData = $this->Shop_Model->getShopByEmail($email);
        $customer = $this->Customer_Model->getCustomerEmail($email);
  
        if(count($shopData) > 0){
            $res = $this->email->send();
            if($res){
               $message = "We send Verification code to your email ".$shopData[0]->shopEmail;
                $this->res(1,$shopData,$message,0);
            }
        }else if(count($customer) > 0){
            $res = $this->email->send();
            $message = "We send Verification code to your email ".$customer[0]->email;
            $this->res(1,$customer[0],$message,0);
        }else{
            $this->res(0,null,"Invalid Account",0);
        }
    }
  
    public function emailVerification($send_to,$ver_code){
        // Email Sender order placed
        $to =  $send_to;  // User email pass here
        $subject = 'PetSociety | Code';
        $from = 'no-reply@jannrey.tech';              // Pass here your mail id
                  
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'smtp.hostinger.com'; // ssl://smtp.gmail.com //hostinger
        $config['smtp_port']    = '587'; //465 //587
        $config['smtp_timeout'] = '60';
    
        $config['smtp_user']    = 'no-reply@jannrey.tech';    //Important
        $config['smtp_pass']    = 'tzwvhA@4';  //Important
    
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not
    
        $this->load->library('email', $config);
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message("Use this as your verification code: $ver_code");
        $this->email->send();
        // show_error($this->email->print_debugger());
        // Email Sender order placed
    
    }

}

?>
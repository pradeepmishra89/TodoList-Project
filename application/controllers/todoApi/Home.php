<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Home extends RestController{
     public function __construct()
{
    parent::__construct();

     header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    // Handle preflight request
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

public function login_post(){
    $json_data = file_get_contents("php://input");
    $check_response = $this->todo->isValidJSON($json_data);

    if($check_response){
        $email = $check_response->email;
        $password = $check_response->password;
        
        if($email && $password){
           
            $valid = $this->DatabaseModel->validLogin($email, $password);

            if($valid && $valid['status'] === true){
    
                $user_id = $valid['id'];
                $name = $valid['name'];

                $condition=[
                    'user_id'=>$user_id
                ];
                // print_r($condition);die;
                $tokenData = [
                    'user_id' => $user_id,
                    'email'   => $email,
                ];
                $token = $this->jwttoken->generateToken($tokenData);
                $storeToken = ['tokens' => $token];
                $this->DatabaseModel->updateSingleRow('users', $storeToken, $condition);
                
                // Success Response
                return $this->response([
                    "status"  => true,
                    "message" => "Login Successfully",
                    "token"   => $token,
                     'name'=> $name
                ]);
                
            } else {
                return $this->response([
                    "status"  => false,
                    "message" => isset($valid['Message']) ? $valid['Message'] : "Failed to login"
                ]);
            }
            
        } else {
            return $this->response([
                "status"  => false,
                "message" => "email and password missing."
            ]);
        }
    } else {
        return $this->response([
            "status"  => false,
            "message" => "Enter valid json."
        ]);
    }
}
    public function updatePassword_put(){
        $json_data = file_get_contents('php://input');
        $check_json = $this->todo->isValidJSON($json_data);

        if($check_json){
            $password = $check_json->password;
            $email=$check_json->email;
            $pass = password_hash($password,PASSWORD_DEFAULT);

            if($pass){
                $data=[
                  'password'=>$pass
                ];
                $condition=[
                    'email'=>$email
                ];

                // print_r($data);
                // print_r($condition);
                // die;
               $update= $this->DatabaseModel->updateSingleRow('users',$data,$condition);
                if($update){
                    return $this->response([
                'status'=>true,
                'message'=>"Password Updated Successfully"
            ]);
                }else{
                 return $this->response([
                'status'=>false,
                'message'=>"Failed To Update"
            ]);
            }
            }else{
                 return $this->response([
                'status'=>false,
                'message'=>"Please enter password"
            ]);
            }
        }else{
             return $this->response([
                'status'=>false,
                'message'=>"Invalid Json"
            ]);
        }
    }

    public function logout_post()
    {
  
     $headers = $this->input->request_headers();

    if(!isset($headers['Authorization'])){
        return $this->response([
            'status' => false,
            'message' => 'Token Required'
        ]);
    }$authHeader = $headers['Authorization'];

    $token = str_replace('Bearer ','',$authHeader);

    // print_r($token);
    // die;
    $verify_token = $this->jwttoken->verifyToken($token);
    if(!$verify_token){
        return $this->response([
            'status' => false,
            'message' => 'Invalid Token'
        ]);
    }
    $condition = [
        'tokens' => $token
    ];

    $data = [
        'tokens' => null
    ];

    $result = $this->DatabaseModel->updateSingleRow('users',$data,$condition);

    if($result){
       
        return $this->response([
            'status' => true,
            'message' => 'Logout Successfully'
        ]);

    }else{

        return $this->response([
            'status' => false,
            'message' => 'Logout Failed'
        ]); 
    }

    }

    
}
?>
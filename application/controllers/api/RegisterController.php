<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class RegisterController extends RestController {

#[Override]
	public function __construct()
    {
         parent::__construct();
    }
   public function getAllUser_get()
{
       
        $result = $this->DatabaseModel->getUsers();

        return $this->response($result, 200);

     
}

public function insertData_post(){
 
$json_data = json_decode(file_get_contents("php://input"), true);
     
    
    if(!$json_data){
        return $this->response([
            'status' => false,
            'msg' => 'Invalid input'
        ], 400);
    }

     $password = password_hash($json_data['password'],PASSWORD_DEFAULT);
       $data=[
        'name'=>$json_data['name'],
        'email'=>$json_data['email'],
        'password'=>$password
       ];

       $result = $this->DatabaseModel->insertData($data);

       if($result){
        return $this->response([
            'status'=>true,
            'msg'=>"User added successfully"
        ],200);
       }else{
         return $this->response([
            'status'=>false,
            'msg'=>"failed to add"
        ],500);
       }

}


       public function login_post()
{
    $json_data = json_decode($this->input->raw_input_stream, true);

    if(!$json_data || !isset($json_data['email']) || !isset($json_data['password'])){
        return $this->response([
            'status' => false,
            'msg' => 'Invalid input'
        ], 400);
    }

    $email = $json_data['email'];
    $password = $json_data['password'];

    $login = $this->DatabaseModel->login($email, $password);

    if($login){
        return $this->response([
            "status" => true,
            "message" => "Login successful"
        ], 200);
    }

    return $this->response([
        "status" => false,
        "message" => "Invalid credentials"
    ], 401);
}

public function updateData_put($id)
{
    $json_data = json_decode(file_get_contents("php://input"), true);

    if(!$json_data){
        return $this->response([
            "status" => false,
            "message" => "Invalid json"
        ], 400);
    }

    if(
        empty($json_data['name']) ||
        empty($json_data['email']) ||
        empty($json_data['password'])
    ){
        return $this->response([
            "status" => false,
            "message" => "All fields are required"
        ], 400);
    }

    $data = [
        'name' => $json_data['name'],
        'email' => $json_data['email'],
        'password' => password_hash($json_data['password'], PASSWORD_DEFAULT)
    ];

    $result = $this->DatabaseModel->updateData($id, $data);

if($result && $this->db->affected_rows() > 0){

    return $this->response([
        "status" => true,
        "message" => "Updated Successfully"
    ], 200);
}

return $this->response([
    "status" => false,
    "message" => "No data updated"
], 404);
}

public function fetchDataById_get($id){

    $result= $this->DatabaseModel->getUserById($id);   

    if($result){
        return $this->response($result,200);
    }else{
        return $this->response("User does not exists");
    }
    }
}
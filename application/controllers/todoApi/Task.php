<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Task extends RestController{
    public function __construct()
    {
         parent::__construct();
          header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    // Handle preflight request
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
         header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        http_response_code(200);
        exit();
    }
    }

    

    public function addTask_post(){
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

    $decode = $this->jwttoken->verifyToken($token);
     if(!$decode || !isset($decode->data->user_id)){
        return $this->response([
            'status' => false,
            'message' => 'Invalid or Expired Token'
        ]);
    }
    $user_id = $decode->data->user_id;
    // print_r($decode);
    // echo $user_id;
    // die;
        $json_data = file_get_contents("php://input");
        $check_json = $this->todo->isValidJSON($json_data);
    // print_r($check_json);
    
        if($check_json){
          $insert=[
            'title'=> isset($check_json->title) ? $check_json->title : '',
            'user_id'     => $user_id,
            'description' => isset($check_json->description) ? $check_json->description : '',
            'status'=>isset($check_json->status) ? $check_json->status : ''
];
        $check = $this->DatabaseModel->insertData('tasks',$insert);
        if($check){
            return $this->response([
                "status"=>true,
                "message"=>"Task Added Successfully",
                "id"=>$check
            ]);
        }else{
            return $this->response([
                "status"=>false,
                "message"=>"Failed to add task."
            ]);
        }
        }else{
            return $this->response([
                "status"=>false,
                "message"=>"Invalid Json"
            ]);
        }
        
    }

    public function updateTask_put(){
        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

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
     $decode = $this->jwttoken->verifyToken($token);

    //  print_r($decode);die;
     
   

if(!$decode || !isset($decode->data->user_id)){
        return $this->response(['status' => false, 'message' => 'Invalid or Expired Token']);
    }
     $user_id = $decode->data->user_id;
        //  print_r($user_id);die;
      $json_data = file_get_contents("php://input");
      $check_json = $this->todo->isValidJSON($json_data);

        if($check_json && isset($check_json->id)){
            $id = $check_json->id;
            $task = $this->db->get_where('tasks', ['id' => $id])->row();
        //    print_r($task);die;
           if (!$task) {
            return $this->response(["status" => false, "Message" => "Task not found"]);
        }
        // print_r($task);
        // print_r($user_id);
        // die;
        if ($task->user_id != $user_id) {
            return $this->response(["status" => false, "Message" => "Unauthorized! You cannot edit this task."]);
        } 
        $data = [];

        if (isset($check_json->title)) {
            $data['title'] = $check_json->title;
        }

        if (isset($check_json->description)) {
            $data['description'] = $check_json->description;
        }
        if (isset($check_json->status)) {
            $data['status'] = $check_json->status;
        }

        // Agar dono me se kuch nahi aaya to database hit mat karo
        if (empty($data)) {
            return $this->response(["status" => false, "message" => "No fields to update"]);
        }

        $condition = [
            'id' => $id
        ];
         
        // print_r($condition);
        // die;
        $update = $this->DatabaseModel->updateTable('tasks',$data,$condition);
        // var_dump($update);die;
         if($update){
            return $this->response([
                "status"=>true,
                "message"=>"Task Updated Successfully"
            ]);
         }else{
             return $this->response([
                "status"=>false,
                "message"=>"Failed To Update"
            ]);
         }
        }else{
            return $this->response([
                "status"=>false,
                "message"=>"Invalid json"
            ]);
        }
    }

    public function getMyTasks_get(){

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
     $decode = $this->jwttoken->verifyToken($token);

    //  print_r($decode);die;
if(!$decode || !isset($decode->data->user_id)){
        return $this->response(['status' => false, 'message' => 'Invalid or Expired Token']);
    }
     $user_id = $decode->data->user_id;



     $allData = $this->DatabaseModel->fetchData('tasks',array('user_id'=>$user_id));
              
    //  print_r($allData);die;
     if($allData){
        return $this->response([
            "status"=>true,
            "meassage"=>"fetch successfully",
            "data"=>$allData
        ]);
     }else{
        return $this->response([
            "status"=>false,
            "message"=>"Data Not Found"
        ]);
     }

    }
    public function getTaskbyId_post(){
        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

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
     $decode = $this->jwttoken->verifyToken($token);

    //  print_r($decode);die;

if(!$decode || !isset($decode->data->user_id)){
        return $this->response(['status' => false, 'message' => 'Invalid or Expired Token']);
    }
     $user_id = $decode->data->user_id;
        //  print_r($user_id);die;
      $json_data = file_get_contents("php://input");
        $check_json = $this->todo->isValidJSON($json_data);

        if($check_json){
            $id = $check_json->id;
            $task = $this->db->get_where('tasks', ['id' => $id])->row();
        //    print_r($task);die;
           if (!$task) {
            return $this->response(["status" => false, "Message" => "Data not found"]);
        } 

       $data =  $this->DatabaseModel->fetchData('tasks',array('id'=>$id));
        if($data){
        return $this->response([
            "status"=>true,
            "meassage"=>"fetch successfully",
            "data"=>$data
        ]);
     }else{
        return $this->response([
            "status"=>false,
            "message"=>"Data Not Found"
        ]);
     }
    }
    }
public function dlttask_delete() {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

    $id = $this->input->get('id'); 

    if (empty($id)) {
        $id = $this->delete('id');
    }

    if (empty($id)) {
        return $this->response([
            'status' => false,
            'message' => "Task ID is missing from Frontend"
        ], 400);
    }

    $condition = [
        'id' => $id
    ];
    
    $data = [
        'isdeleted' => 1,
        'status' => 'rejected'
    ];

    $result = $this->DatabaseModel->updateSingleRow('tasks', $data, $condition);

    if ($result) {
        return $this->response([
            'status' => true,
            'message' => "Deleted Successfully",
            'id'=>$id
        ], 200);
    } else {
        return $this->response([
            'status' => false,
            'message' => "Failed to Delete"
        ], 400);
    }
}

}
?>
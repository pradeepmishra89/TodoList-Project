<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;
use SebastianBergmann\ResourceOperations\ResourceOperations;

class User extends RestController
{
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

    public function register_post()
    {

        $json_data = file_get_contents("php://input");

        $check_josn = $this->todo->isValidJSON($json_data);

        // return "hello";

        if (!$check_josn) {

            return $this->response([
                'status' => false,
                'message' => "Invalid JSON"
            ], 400);
        }
        if (
            empty($check_json->name) ||
            empty($check_json->email) ||
            empty($check_json->password)
        ) {

            return $this->response([
                'status' => false,
                'message' => "All fields are required"
            ], 200);
        }

        $insert = [
            'name' => $check_josn->name,
            'email' => $check_josn->email,
            'password' => password_hash($check_josn->password, PASSWORD_DEFAULT),

        ];
        $check = $this->DatabaseModel->insertData('users', $insert);
        //    echo $check;
        //     die;

        $data = [
            'user_id' => $check,
        ];
        $token = $this->jwttoken->generateToken($data);
        $resultToken = $this->jwttoken->verifyToken($token);

        //  print_r($resultToken);
        //  die;
        if ($check) {
            return $this->response([
                "status" => true,
                "message" => "User Added Successfully"
            ]);
        } else {
            return $this->response([
                "status" => false,
                "message" => "Failed to add."
            ]);
        }
    }

    public function fetchData_get()
    {
        $result =  $this->DatabaseModel->fetchAllData('users');
        if ($result) {
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        } else {
            return $this->response([
                "status" => false,
                "message" => "No Data Found"
            ]);
        }
    }
    public function update_put($id)
    {
        $json_data = file_get_contents("php://input");
        $check_json = $this->todo->isValidJSON($json_data);


        if (!$check_json) {

            return $this->response([
                'status' => false,
                'message' => "Invalid JSON"
            ], 400);
        }
        if (
            empty($check_json->name) ||
            empty($check_json->email) ||
            empty($check_json->password)
        ) {

            return $this->response([
                'status' => false,
                'message' => "All fields are required"
            ], 400);
        }
        $data = [
            'name' => $check_json->name,
            'email' => $check_json->email,
            'password' => password_hash($check_json->password, PASSWORD_DEFAULT)
        ];

        $condition = [
            'user_id' => $id
        ];
        $check_update =  $this->DatabaseModel->updateTable('users', $data, $condition);
        //  print_r($check_update);
        //  die;
        if ($check_update) {
            return $this->response([
                'status' => $check_update,
                'message' => "Update Successfully"
            ]);
        } else {
            return $this->response([
                'status' => $check_update,
                'message' => "Failed To Update"
            ]);
        }
    }
    public function updateById_patch($id)
    {
        $json_data = file_get_contents("php://input");
        $check_json = $this->todo->isValidJSON($json_data);

        if ($check_json) {
            $data = [
                'password' => password_hash($check_json->password, PASSWORD_DEFAULT)
            ];
        }
        $check_update = $this->DatabaseModel->updateSingleRow('users', $data, $id);

        if ($check_update) {
            return $this->response([
                'status' => $check_update,
                'message' => "Update Successfully"
            ]);
        } else {
            return $this->response([
                'status' => $check_update,
                'message' => "Failed To Update"
            ]);
        }
    }
    public function fetchByid_get($id)
    {

        $condition = [
            'user_id' => $id
        ];

        $result = $this->DatabaseModel->fetchData('users', $condition);

        if ($result) {
            return $this->response([
                "status" => true,
                "meassage" => "fetch successfully",
                "data" => $result
            ]);
        } else {
            echo "No data found";
        }
    }
    public function delete_delete($id)
    {
        $condition = [
            'user_id' => $id
        ];
        $data = [
            'isdeleted' => 1,
            'status' => 0,
            'tokens' => null
        ];
        $result = $this->DatabaseModel->updateSingleRow('users', $data, $condition);
        //    print_r($result);
        //    die;
        if ($result) {
            return $this->response([
                'status' => $result,
                'message' => " Deleted Successfully"
            ]);
        } else {
            return $this->response([
                'status' => $result,
                "message" => "Failed to Deleted"
            ]);
        }
    }
}

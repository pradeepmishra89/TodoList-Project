<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Home extends RestController{
        public function login(){
        $json_data = json_decode(file_get_contents("php://input"),true);
        if(empty($json_data)){
            return $this->response([
                "status"=>false,
                "Message"=>"Invalid Json"
            ]);
        }
             
        }
}
?>
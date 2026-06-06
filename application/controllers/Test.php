<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('JWTToken');
  }

  public function testJwt(){
    $data=[
        "name"=>"pradeep",
        "email"=>"pradeep12@gmail",
        "password"=>"@32423"
    ];
    // print_r($data);

    $token = $this->jwttoken->generateToken($data);

    echo $token;

    $result = $this->jwttoken->verifyToken($token);

    echo "<pre>";
    print_r($result);
    echo "</pre>";
  }


}
?>
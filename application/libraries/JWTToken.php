<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken {

    private $CI;
    
    private $secret_key = "this_is_my_super_secret_jwt_key_for_authentication_2026";

    public function __construct()
    {
        $this->CI = &get_instance();
    }
    // Generate Token
    public function generateToken($data)
    {

        $payload = [
            'iss' => 'localhost',
            'iat' => time(),
            'exp' => time() + (24 * 60 * 60) ,
            'status'=>true,
            'data' => $data
        ];

        return JWT::encode(
            $payload,
            $this->secret_key,
            'HS256'
        );
    }

    // Verify Token
    public function verifyToken($token)
    {

        try {

            return JWT::decode(
                $token,
                new Key($this->secret_key, 'HS256')
            );

        } catch (Exception $e) {

            return false;
        }
    }
}
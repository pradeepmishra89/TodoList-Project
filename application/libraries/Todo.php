<?php

class Todo{
    private $CI;

        public function __construct()
    {
        $this->CI = &get_instance();
        // //   Set CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // If the request method is OPTIONS, exit after sending headers
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }
        if (php_sapi_name() === 'cli') {
            if (!isset($_SERVER['REQUEST_METHOD'])) {
                $_SERVER['REQUEST_METHOD'] = 'GET';
            }
        }
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }
        }

        public function isValidJSON($str)
    {
        if (json_decode($str) !== null && strlen($str) > 0) {
            return json_decode($str);
        } else {
            return false;
        }
    }

    public function randomPassword($length = 22)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pass = [];                    //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
?>
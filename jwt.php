<?php
require_once __DIR__ . '/vendor/autoload.php';  // Adjust path if needed

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler {
    public function generateToken($payload){
        $key = 'abcdefghijklmnoplqrst';
        $token  = JWT::encode($payload, $key, 'HS256');
        if($token){
            return $token;
        }

    }
}

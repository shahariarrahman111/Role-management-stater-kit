<?php

namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTToken {

    /*
        1. First JWT_KEY  name e env. file e JWT_KEY banaite hobe
        2. Payload  e ki ki cookie te thakbe segulo a khane thakbe
        3.iss,iat,exp hocce issue nmae,create timer and expired date/time...**
        4.Decode er somoy amra try catch use korter pari
        5.public static function deyar kron jata baire thake call kora jay ba controller ba view ba middleware thake call kora jay..
    */

    public static function CreateToken($email):string {

        $key = env("JWT_KEY", '123XYZ');

        $payload = [

            'iss'=> 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60*60,
            'email' =>$email
        ];

        return  JWT::encode($payload, $key, 'HS256');
    }



    public static function VerifyToken($token):string {

        try {

            $key = env("JWT_KEY", '123XYZ');

            if (!$key) {
                throw new Exception("JWT_KEY is not defined in the .env file");
            }

            $decode = JWT::decode($token, new Key($key, 'HS256'));

            return $decode->email;


        }

        catch (Exception $e) {

            return "unothorized";

        }


    }


}

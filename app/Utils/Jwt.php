<?php

namespace App\Utils;

/**
 * Class Utils
 *
 * @package App\Utils
 */
final class Jwt
{

    public static function isEnabled() {
        return !empty(env('OFFICE_JWT_ENABLED'));
    }

    /**
     * Encode jwt palyload
     * 
     * @param array|mixed 
     * @return string token
     */
    public static function encode($payload) 
    {
        $header = [
            "alg" => "HS256",  // the hashing algorithm
            "typ" => "JWT"  // the token type
        ];
        // three parts of token
        $encHeader = self::base64UrlEncode(json_encode($header));  // header
        $encPayload = self::base64UrlEncode(json_encode($payload));  // payload
        $hash = self::base64UrlEncode(self::calculateHash($encHeader, $encPayload));  // signature
    
        return "$encHeader.$encPayload.$hash";
    }

    /**
     * Decode jwt token
     * 
     * @param string $token
     * @return string json string payload
     */
    public static function jwtDecode($token) {
        $split = explode(".", $token);
        if (count($split) != 3) return "";
    
        $hash = self::base64UrlEncode(self::calculateHash($split[0], $split[1]));
    
        if (strcmp($hash, $split[2]) != 0) return "";
        return self::base64UrlDecode($split[1]);
    }

    /**
     * Calculate Hash
     * 
     * @param string $encHeader json string header jwt
     * @param string $encPayload json string payload jwt
     * @return string hash
     */
    public static function calculateHash($encHeader, $encPayload) {
        $secret = env('OFFICE_SECRET', 'secret');
        return hash_hmac("sha256", "$encHeader.$encPayload", $secret, true);
    }

    /**
     * base64url encode
     * 
     * @param string $str url to be base64encode
     * @return string 
     */
    public static function base64UrlEncode($str) {
        return str_replace("/", "_", str_replace("+", "-", trim(base64_encode($str), "=")));
    }

    /**
     * base64url decode
     * 
     * @param string $payload url to be base64decode
     * @return string 
     */
    public static function base64UrlDecode($payload) {
        $b64 = str_replace("_", "/", str_replace("-", "+", $payload));
        switch (strlen($b64) % 4) {
            case 2:
                $b64 = $b64 . "=="; break;
            case 3:
                $b64 = $b64 . "="; break;
        }
        return base64_decode($b64);
    }
}
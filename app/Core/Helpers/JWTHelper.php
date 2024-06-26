<?php

namespace App\Core\Helpers;


use App\Core\Http\Request\RequestInterface;

class JWTHelper
{
    public static function validateAccessToken(RequestInterface $request): \stdClass|bool
    {
        $token = self::bearerToken($request);
        if(! $token){
            return false;
        }
        try{
            return JWT::decode($token, new Key($_ENV['JWTKEY'], 'HS256'));
        }catch (\Exception $e){
            return false;
        }
    }

    private static function bearerToken(RequestInterface $request): ?string
    {
        if(isset($request->server['HTTP_AUTHORIZATION'])){
            return str_replace('Bearer ', '', $request->server['HTTP_AUTHORIZATION']);
        }
        return null;
    }
}
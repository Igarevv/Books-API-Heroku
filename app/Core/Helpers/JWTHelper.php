<?php

namespace App\Core\Helpers;

use App\Core\Http\Request\RequestInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    private string $key = 'my-secret-key'; // в .env в будущем
    public function validateAccessToken(RequestInterface $request): \stdClass|bool
    {
        $token = $this->bearerToken($request);
        if(! $token){
            return false;
        }
        try{
            return JWT::decode($token, new Key($this->key, 'HS256'));
        }catch (\Exception $e){
            return false;
        }
    }
    private function bearerToken(RequestInterface $request): ?string
    {
        if(isset($request->server['HTTP_AUTHORIZATION'])){
            return str_replace('Bearer ', '', $request->server['HTTP_AUTHORIZATION']);
        }
        return null;
    }
}
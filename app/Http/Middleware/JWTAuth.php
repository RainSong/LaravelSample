<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class JWTAuth
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = Input::get('token');
            if(empty($token)){
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $user = $this->guard()->user($token);
            if (empty($user)){
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'Token is Expired']);
            }else{
                return response()->json(['error'=>'Something is wrong']);
            }
        }
        return $next($request);
    }

    protected function guard()
    {
        return Auth::guard();
    }

//    protected $except= [
//        '/api/auth/login'
//    ];
}

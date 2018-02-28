<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function Login()
    {
        $user_name = Input::get('user_name');
        $password = Input::get('password');
        if (empty($user_name)) {
            return response()->json(['status' => false, 'title' => '用户名', 'message' => '请输入用户名']);
        }
        if (empty($password)) {
            return response()->json(['status' => false, 'title' => '密码', 'message' => '请输入密码']);
        }
//        $password = password_hash($password,PASSWORD_DEFAULT);
        try {

            $users = User::where('user_name', '=', $user_name)
//                ->where('password', '=', $password)
                ->get();
            $user = null;
            foreach ($users as $u){
                if (password_verify($password,$u->password)){
                    $user = $u;
                }
            }
            if (empty($user)) {
                return response()->json(['status' => false, 'title' => '凭据无效', 'message'=>'用户名或密码错误，请重试']);
            }
            if ($user->state == 1) {
                return response()->json(['status' => false, 'title' => '用户状态异常', 'message'=>'用户状态异常，无法登陆']);
            }
            $token = $this->guard()->fromUser($user);
            return response()->json(['status' => true, 'token' => $token,'uid'=>$user->id]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false, 'title' => '登陆失败', '系统错误，登陆失败']);
        }
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
    protected function guard()
    {
        return Auth::guard();
    }
}

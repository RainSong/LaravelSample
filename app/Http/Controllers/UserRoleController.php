<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Support\Facades\Input;
use DB;

class UserRoleController extends Controller
{
    //

    public function Index($user_id)
    {
        try {
            $items = DB::table('user_roles')
                ->where('user_id', '=', $user_id)
                ->select('role_id')
                ->get();
            $role_ids = [];
            foreach ($items as $item) {
                array_push($role_ids, $item->role_id);
            }
            return response()->json(['status' => true, 'data' => $role_ids]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $user_id = Input::get('user_id');
        if ($user_id == null || empty($user_id)) {
            return response()->json(['status' => false, 'field' => 'user_id', 'message' => '用户ID为空无法保存']);
        }
        $str_role_ids = Input::get('role_ids', '');
        $role_ids = [];
        if (!empty($str_role_ids)) {
            $role_ids = explode(',', $str_role_ids);
        }
        try {
            DB::transaction(function () use ($user_id, $role_ids) {
                DB::table('user_roles')
                    ->where('user_id', '=', $user_id)
                    ->delete();
                foreach ($role_ids as $role_id) {
                    $user_role = new UserRole();
                    $user_role->user_id = $user_id;
                    $user_role->role_id = $role_id;
                    $user_role->save();
                }
            });
            DB::beginTransaction();
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }
}

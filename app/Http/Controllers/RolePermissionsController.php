<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\RolePermissions;
use Illuminate\Support\Facades\Input;

class RolePermissionsController extends Controller
{
    //
    public function Index($role_id)
    {
        try {
            $items = RolePermissions::where('role_id', '=', $role_id)
                ->select(['permissions_id'])
                ->get();
            $permissions_ids = [];
            foreach ($items as $item) {
                array_push($permissions_ids, $item->permissions_id);
            }
            return response()->json(['status' => true, 'permissions_ids' => $permissions_ids]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $role_id = Input::get('role_id');
        if ($role_id == null || empty($role_id)) {
            return response()->json(['status' => false, 'field' => 'role_id', 'message' => '角色ID为空无法保存']);
        }
        $str_permissions_ids = Input::get('permissions_ids');
        $permissions_ids = explode(',', $str_permissions_ids);
        if (empty($permissions_ids)) {
            return response()->json(['status' => false, 'field' => 'user_id', 'message' => '权限ID为空无法保存']);
        }
        try {
            DB::transaction(function () use ($role_id, $permissions_ids) {
                DB::table('role_permissions')
                    ->where('role_id', '=', $role_id)
                    ->delete();
                foreach ($permissions_ids as $permissions_id) {
                    $role_permissions = new RolePermissions();
                    $role_permissions->role_id = $role_id;
                    $role_permissions->permissions_id = $permissions_id;
                    $role_permissions->save();
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

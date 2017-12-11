<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionsModule;
use Illuminate\Support\Facades\Input;
use DB;

class PermissionsModuleController extends Controller
{
    //

    public function Index($permissions_id)
    {
        try {
            $items = PermissionsModule::where('permissions_id', '=', $permissions_id)
                ->select(['module_id'])
                ->get();
            $modules_ids = [];
            foreach ($items as $item) {
                array_push($modules_ids, $item->module_id);
            }
            return response()->json(['status' => true, 'moduleIds' => $modules_ids]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $permissions_id = Input::get('permissions_id');
        if ($permissions_id == null || empty($permissions_id)) {
            return response()->json(['status' => false, 'field' => 'permissions_id', 'message' => '权限ID为空无法保存']);
        }
        $str_module_ids = Input::get('module_ids');
        $module_ids = explode(',', $str_module_ids);
        if (empty($module_ids)) {
            return response()->json(['status' => false, 'field' => 'module_id', 'message' => '模块ID为空无法保存']);
        }
        try {
            DB::transaction(function () use ($permissions_id, $module_ids) {
                DB::table('permissions_modules')
                    ->where('permissions_id', '=', $permissions_id)
                    ->delete();
                foreach ($module_ids as $module_id) {
                    $permissions_module = new PermissionsModule();
                    $permissions_module->permissions_id = $permissions_id;
                    $permissions_module->module_id = $module_id;
                    $permissions_module->save();
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

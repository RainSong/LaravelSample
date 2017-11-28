<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //

    public function Index()
    {
        $key_word = Input::get('key_word');
        $sort_column = Input::get('sort_column');
        $order = Input::get('order');
        $query = DB::table('roles')
            ->where('state', '=', '0');
        if (!empty($key_word)) {
            $query->where('name', 'like', '%' . $key_word . '%');
        }
        if (!empty($sort_column)) {
            if ($order == 'descending') {
                $order = 'desc';
            } else {
                $order = 'asc';
            }
        } else {
            $sort_column = 'level';
            $order = 'asc';
        }
        try {
            $roles = $query
                ->select(['id', 'name', 'level', 'created_at'])
                ->orderBy($sort_column, $order)
                ->get();
            return response()->json(['status' => true, 'roles' => $roles]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Add()
    {
        $role = new Role();
        $this->PickData($role);
        list($result, $field, $message) = $this->Check($role);
        if (!$result) {
            return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
        }
        try {
            $role->save();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Show($id)
    {
        try {
            $role = Role::find($id);
            return response()->json(['status' => true, 'data' => $role]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $id = Input::get('id');
        if (empty($id)) {
            return response()->json(['status' => false, 'message' => 'ID无效无法修改']);
        }
        try {
            $role = Role::find($id);
            if (empty($role)) {
                return response()->json(['status' => false, 'message' => 'ID无效无法修改']);
            }
            $this->PickData($role);
            list($result, $field, $message) = $this->Check($role);
            if (!$result) {
                return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
            }
            $role->save();
            return response(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response(['status' => false]);
        }
    }

    public function Delete()
    {
        $strIds = Input::get('ids');
        $arrIds = explode(',', $strIds);
        if (count($arrIds) == 0) {
            return response()->json(['status' => false, 'message' => '没有数据要删除']);
        }
        try {
            DB::table('roles')->whereIn('id', $arrIds)->update(['state' => 1]);
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Check($role)
    {
        if (empty($role->name)) {
            return array(false, 'name', '角色名不能为空');
        }
        if (!empty($role->id)) {
            $count = DB::table('roles')
                ->where('name', '=', $role->name)
                ->where('id', '!=', $role->id)
                ->where('state', '=', '0')
                ->count('id');
            if ($count > 0) {
                return array(false, 'name', '角色名不能重复');
            }
        }
        return array(true, '', '');
    }

    public function PickData($role)
    {
        $role->name = Input::get('name');
        $role->level = Input::get('level');
    }

}

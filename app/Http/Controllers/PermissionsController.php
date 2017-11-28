<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PermissionsController extends Controller
{
    //
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function Index()
    {
        $key_word = Input::get('key_word');
        $parent_id = Input::get('parent_id');
        $sort_column = Input::get('sort_column');
        $order = Input::get('order');
        $query = DB::table('permissions as c')
            ->leftJoin('permissions as p', 'c.parent_id', '=', 'p.id')
            ->where('c.state', '=', '0');
        if (!empty($key_word)) {
            $query->where(function ($q) use ($key_word) {
                $q->orWhere('c.name', 'like', '%' . $key_word . '%')
                    ->orWhere('p.name', 'like', '%' . $key_word . '%');
            });
        }
        if ($parent_id != null) {
            $query->where('c.parent_id', '=', $parent_id);
        }
        if (!empty($sort_column)) {
            if ($order == 'descending') {
                $order = 'desc';
            } else {
                $order = 'asc';
            }
            if ($sort_column == 'parent') {
                $sort_column = 'p.sort';
            } else {
                $sort_column = 'c.' . $sort_column;
            }
        } else {
            $sort_column = 'c.sort';
            $order = 'asc';
        }
        try {
            $permissions = $query->select(DB::raw('c.id,c.parent_id,c.name,p.name as parent_name,c.sort,c.created_at'))
                ->orderBy($sort_column, $order)
                ->get();
            return response()->json(['status' => true, 'permissions' => $permissions]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false, 'permissions' => $permissions]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Show($id)
    {
        try {
            $permission = Permission::find($id);
            return response()->json(['status' => true, 'data' => $permission]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function Add()
    {
        $permission = new Permission();
        $this->PickData($permission);
        list($result, $field, $message) = $this->Check($permission);
        if (!$result) {
            return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
        }
        try {
            $permission->save();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function Update()
    {
        $id = Input::get('id');
        if (empty($id)) {
            return response()->json(['status' => false, 'message' => 'ID无效无法修改']);
        }
        try {
            $permission = Permission::find($id);
            if (empty($permission)) {
                return response()->json(['status' => false, 'message' => 'ID无效无法修改']);
            }
            $this->PickData($permission);
            list($result, $field, $message) = $this->Check($permission);
            if (!$result) {
                return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
            }
            $permission->save();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function Delete()
    {
        $strIds = Input::get('ids');
        $arrIds = explode(',', $strIds);
        if (count($arrIds) == 0) {
            return response()->json(['status' => false, 'message' => '没有数据要删除']);
        }
        try {
            $childIds = DB::table('permissions')
                ->whereIn('parent_id', $arrIds)
                ->where('state', '=', '0')
                ->select('id')
                ->get();
            if ($childIds->count() > 0) {
                foreach ($childIds as $childId) {
                    if (!in_array($childId->id, $arrIds)) {
                        return response()->json(['status' => false, 'message' => '要删除的权限包含子级权限，请先删除子级权限']);
                    }
                }
            }
            DB::table('permissions')
                ->whereIn('id', $arrIds)
                ->update(['state' => 1]);
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    /**
     * @param $permission
     */
    public function PickData($permission)
    {
        $permission->name = Input::get('name');
        $permission->parent_id = Input::get('parent_id');
        if ($permission->parent_id == null) {
            $permission->parent_id = 0;
        }
        $permission->sort = Input::get('sort');
    }

    /**
     * @param $permission
     * @return array
     */
    public function Check($permission)
    {
        if (empty($permission->name)) {
            return array(false, 'name', '权限名称不能为空');
        } else {
            $query = DB::table('permissions')
                ->where('name', '=', $permission->name)
                ->where('state', '=', '0');
            if ($permission->id != null) {
                $query->where('id', '!=', $permission->id);
            }
            $count = $query->count('id');
            if ($count > 0) {
                return array(false, 'name', '已存在相同名称的权限');
            }
        }
        return array(true, '', '');
    }
}

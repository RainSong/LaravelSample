<?php

namespace App\Http\Controllers;

use App\Models\Module;
use DB;
use Illuminate\Support\Facades\Input;

class ModuleController extends Controller
{
    public function Index()
    {
        // try {
        //     $menus = Menu::where('state', '0')->get();
        //     return response()->json(['status' => true, 'data' => $menus]);
        // } catch (\Exception $ex) {
        //     \Log::error('获取菜单信息失败', $ex);
        //     return response()->json(['status' => false]);
        // }
        $query = DB::table('modules as c')
            ->leftJoin('modules as p', 'c.parent_id', '=', 'p.id')
            ->select(['c.id', 'c.name', 'c.display_name', 'c.visible', 'c.path', 'c.parent_id', 'c.sort', 'c.created_at', 'p.display_name AS parent'])
        // ->where(function ($q) {
        //     $q->orWhere('p.state', '=', '0')
        //         ->orWhereNull('p.state');
        // })
            ->where('c.state', '=', '0');
        $key_word = Input::get('key_word');
        if (!empty($key_word)) {
            $query->where('c.display_name', 'like', '%' . $key_word . '%');
        }
        $str_levels = Input::get('levels');
        $arr_levels = explode(',', $str_levels);
        if (!empty($str_levels) && count($arr_levels) > 0) {
            $query->whereIn('c.level', $arr_levels);
        }
        $parent_id = Input::get('parent_id');
        if (!empty($parent_id)) {
            $query->where('c.parent_id', '=', $parent_id);
        }
        $sort_column = Input::get('sortColumn');
        $order = Input::get('order');
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
            $query->orderBy($sort_column, $order);
        }

        try {
            $modules = $query->get();
            return response()->json(['status' => true, 'modules' => $modules]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Add()
    {
        $module = new Module();
        $this->PickData($module);
        list($reuslt, $field, $message) = $this->CheckInput($module);
        if (!$reuslt) {
            return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
        }
        try {
            $module->save();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $id = Input::get('id');
        try {
            $module = Module::find($id);
            if (empty($module)) {
                return response()->json(['status' => 'false', 'message' => 'ID无效，无法修改']);
            }
            $this->PickData($module);
            list($result, $field, $message) = $this->CheckInput($module);
            if (!$result) {
                return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
            }
            $module->save();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            return response()->josn(['status' => false]);
        }

    }

    public function Show($id)
    {
        try {
            $module = Module::find($id);
            if (empty($module)) {
                return response()->json(['status' => 'false', 'message' => 'ID无效']);
            }
            return response()->json(['status' => true, 'moduleInfo' => $module]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Delete()
    {
        $str_ids = Input::get('ids');
        $arr_ids = explode(',', $str_ids);
        if (count($arr_ids) == 0) {
            return response()->json(['status' => false, 'message' => '没有要删除的数据']);
        }
        try {
            $child_ids = DB::table('modules')
                ->whereIn('parent_id', $arr_ids)
                ->where('state', '=', '0')
                ->select('id')
                ->get();
            foreach ($child_ids as $child_id) {
                if (!in_array($child_id->id, $arr_ids)) {
                    return response()->json(['status' => false, 'message' => '要删除的摸快包含子级模块，请先删除子级模块']);
                }
            }
            DB::table('modules')
                ->whereIn('id', $arr_ids)
                ->update(['state' => 1]);
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false]);
        }
    }
    public function PickData($module)
    {
        $module->name = Input::get('name');
        $module->display_name = Input::get('displayName');
        $module->path = Input::get('path');
        $module->component = $module->name;
        $module->visible = Input::get('visible');
        $module->sort = Input::get('sort');
        $module->parent_id = Input::get('parentId');
        if (empty($module->parent_id)) {
            $module->level = 1;
            $module->parent_id = 0;
        } else {
            $parent = Module::where("id", "=", $module->parent_id)
                ->first();
            if ($parent != null || $parent->parent_id == null) {
                $module->level = 1;
            } else {
                $mdoule->level = $parent->level + 1;
            }
        }

    }

    public function CheckInput($module)
    {
        if (empty($module->name)) {
            return array(false, 'name', '模块名不能为空');
        }
        if(empty($module->display_name)){
            return array(false,'display_name','模块显示名不能为空');
        }
        if(empty($module->path)){
            return array(false,'path','路径不能为空');
        }
        return array(true,'','');
    }
}

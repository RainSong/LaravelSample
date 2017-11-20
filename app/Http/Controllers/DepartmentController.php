<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deaprtment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;

class DepartmentController extends Controller
{
    //
    public function Index(Request $request)
    {
        $query = DB::table('departments as c')
            ->leftjoin('departments as p', 'c.parent_id', '=', 'p.id')
            ->select(DB::raw('c.id,c.parent_id,c.name,p.name as parent,c.created_at,c.level'))
            ->where('c.state', '=', '0');
        $key_word = $request->get('key_word');
        if (!empty($key_word)) {
            $query->where('c.name', 'like', '%'.$key_word.'%');
        }
        $strLevel = $request->get('levels');
        if (!empty($strLevel)) {
            $levels = explode(',', $strLevel);
            $query->whereIn('c.level', $levels);
        }
        try {
            $departments = $query->get();
            return response()->json(['status' => true, 'data' => ['departments' => $departments, 'total' => 0]]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }

    }

    public function Show(int $id)
    {
        try {
            $department = DB::table('departments as c')
                ->leftJoin('departments as p', 'c.parent_id', '=', 'p.id')
                ->leftJoin('departments as gp', 'p.parent_id', '=', 'gp.id')
                ->select(DB::raw('c.id,c.name,c.parent_id,gp.parent_id as grand_parent_id'))
                ->where('c.id', '=', $id)
                ->first();
            return response()->json(['status' => true, 'data' => ['id' => $department->id, 'name' => $department->name, 'parent_ids' => [$department->grand_parent_id, $department->parent_id]]]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $id = Input::get('id');
        $department = Deaprtment::find($id);
        if (!empty($department)) {
            $department->name = Input::get('name');
            $department->parent_id = Input::get('parent_id');
            list($result, $field, $message) = $this->Check($department);
            if (!$result) {
                return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
            }
            try {
                $department->save();
                return response()->json(['status' => true]);
            } catch (\Exception $ex) {
                \Log::error($ex);
                return response()->json('发生错误，保存失败');
            }
        } else {
            return response()->json(['status' => false, 'message' => '部门ID无效无法修改']);
        }
    }

    public function Add()
    {
        $department = new Deaprtment();
        $department->name = Input::get('name');
        $department->parent_id = Input::get('parnet_id');
        list($result, $field, $message) = $this->Check($department);
        if ($result) {
            try {
                $department->save();
            } catch (\Exception $ex) {
                \Log::error($ex);
                return response()->json(['status' => false, 'message' => '发生错误，保存失败']);
            }
        } else {
            return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
        }
    }

    public function Delete($id)
    {
//        $id = Input::get('id');
//        if (empty($id)) {
//            return response()->json(['status' => false, 'message' => 'id为空，无法删除']);
//        }
        if ($this->HasChild(array($id))) {
            return response()->json(['status' => false, 'message' => '请先删除子级部门']);
        }
        try {
            DB::table('departments')
                ->where('id', $id)
                ->update(['state' => 1]);
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error('删除部门失败', $ex);
            return response()->json(['status' => false, 'message' => '发生错误，删除失败']);
        }
    }

    public function HasChild($id)
    {
        $count = DB::table('departments')
            ->where('parent_id', $id)
            ->where('state','!=','1')
            ->count('id');
        return $count > 0;
    }

    private function Check(Deaprtment $department)
    {
        if (empty($department->name)) {
            return array(false, 'name', '部门名称不能为空');
        } else {
            $query = DB::table('departments')
                ->where('name', '=', $department->name);
            if (!empty($department->id) && $department->id != '0') {
                $query->where('id', '<>', $department->id);
            }
            $count = $query->count('id');
            if ($count > 0) {
                return array(false, 'name', '部门名称不能重复');
            }
        }
        return array(true, '', '');
    }
}

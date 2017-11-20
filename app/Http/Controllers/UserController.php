<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function Index()
    {
        $pageSize = Input::get('pageSize', 0);
        $currentPage = Input::get('currentPage', 1);
        $key_word = Input::get('key_word');
        $entry_time_start = Input::get('entry_time_start');
        $entry_teim_end = Input::get('entry_time_end');
        $sex = Input::get('sex', '');
        $department_id = Input::get('department_id', '');
        $sort_column = Input::get('sort_column');
        $order = Input::get('order');
        $query = \DB::table('users as u')
            ->leftJoin('departments as p1', 'u.department_id', '=', 'p1.id')
            ->leftJoin('departments as p2', 'p1.parent_id', '=', 'p2.id')
            ->leftJoin('departments as p3', 'p2.parent_id', '=', 'p3.id')
            ->where('u.state', '=', '0');

        if (!empty($key_word)) {
            $query->where(function ($q) use ($key_word) {
                $q->orWhere('c.name', 'like', '%' . $key_word . '%')
                    ->orWhere('c.user_name', 'like', '%' . $key_word . '%')
                    ->orWhere('c.phone', 'like', '%' . $key_word . '%')
                    ->orWhere('c.email', 'like', '%' . $key_word . '%');
            });
        }
        if (!empty($entry_time_start)) {
            $query->where('c.entry_time', '>', $entry_time_start);
        }
        if (!empty($entry_teim_end)) {
            $dt_end = new \DateTime($entry_teim_end);
            date_add($dt_end, date_interval_create_from_date_string('1 days'));
            $query->where('c.entry_time', '<', $dt_end->format('Y-m-d'));
        }
        if (strlen($sex) > 0) {
            $query->where('sex', '=', $sex);
        }
        if (!empty($department_id)) {
            $query->where(function ($q) use ($department_id) {
                $q->orWhere('p1.id', '=', $department_id)
                    ->orWhere('p2.id', '=', $department_id)
                    ->orWhere('p3.id', '=', $department_id);
            });
        }
        try {
            $total = $query->count();
            $query->select(DB::raw('u.id,u.user_name,u.name,u.sex,u.phone,u.email,u.address,u.brithday,u.entry_time,'
                . 'p1.name as department1,p2.name as department2,p3.name as department3'));
            if (!empty($sort_column)) {
                if ($order == 'descending') {
                    $order = 'desc';
                } else {
                    $order = 'asc';
                }
                $query->orderBy($sort_column, $order);
            }
            $users = $query->offset(($currentPage - 1) * $pageSize)
                ->limit($pageSize)
                ->get();
            return response()->json(['status' => true, 'total' => $total, 'users' => $users]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Show($id)
    {
        try {
            $user = DB::table('users as u')
                ->leftJoin('departments as p1', 'u.department_id', '=', 'p1.id')
                ->leftJoin('departments as p2', 'p1.parent_id', '=', 'p2.id')
                ->leftJoin('departments as p3', 'p2.parent_id', '=', 'p3.id')
                ->select(DB::raw('u.id,u.user_name,u.name,u.sex,u.brithday,u.phone,u.email,u.entry_time'
                    . ',p1.id as pid1,p2.id as pid2,p3.id as pid3'))
                ->where('u.id', '=', $id)
                ->first();
            return response()->json(['status' => true, 'data' => $user]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Add()
    {
        $user = new User();
        $this->PickData($user);
        $user->password = Input::get('password');
        list($result, $field, $message) = $this->Check($user, true);
        if ($result) {
            try {
                $user->password = md5($user->password);
                $user->save();
                return response()->json(['status' => true]);
            } catch (\Exception $ex) {
                \Log::error($ex);
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false, $field, $message]);
        }
    }

    public function Update()
    {
        $id = Input::get('id');
        $user = User::find($id);
        if (empty($user)) {
            return response()->json(['status' => false, 'message' => 'ID无效无法修改']);
        }
        $this->PickData($user);
        list($result, $field, $message) = $this->Check($user, false);
        if (is_null($user->department_id)) {
            $user->department_id = 0;
        }
        if ($result) {
            try {
                $user->save();
                return response()->json(['status' => true]);
            } catch (\Exception $ex) {
                \Log::error($ex);
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
        }
    }

    public function Delete()
    {
        $ids = Input::get('ids', '');
        $arr_ids = explode(',', $ids);
        if (count($arr_ids) == 0) {
            return response()->json(['status' => false, 'message' => '没有数据要删除']);
        }
        try {
            DB::table('users')->whereIn('id', $arr_ids)->update(['state' => 1]);
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function ResetPassword()
    {
        $id = Input::get('id');
        $user = User::find($id);
        if (empty($user)) {
            return response()->json(['status' => false, 'message' => 'ID无效无法修改']);
        }
        $password = Input::get('password');
        if (empty($password)) {
            return response()->json(['status' => false, 'field' => 'password', 'message' => '密码不能为空']);
        }
        $user->password = md5($password);
        try {
            $user->save();
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    private function PickData($user)
    {
        $user->user_name = Input::get('user_name', '');
        $user->name = Input::get('name', '');
        $user->sex = Input::get('sex', '1');
        $user->brithday = Input::get('brithday', '');
        $user->phone = Input::get('phone', 0);
        $user->email = Input::get('email');
        $user->department_id = Input::get('department_id');
    }

    private function Check($user, $isNew = false)
    {
        if (empty($user->user_name)) {
            return array(false, 'user_name', '用户名不能为空');
        }
        if ($isNew && empty($user->password)) {
            return array(false, 'password', '用户密码不能为空');
        }
        if (empty($user->name)) {
            return array(false, 'user_name', '用户姓名不能为空');
        }
        if (is_null($user->sex)) {
            return array(false, 'sex', '用户性别不能为空');
        }
        if (empty($user->brithday)) {
            return array(false, 'brithday', '用户出生日期不能为空');
        }
        if (empty($user->phone)) {
            return array(false, 'phone', '用户电话不能为空');
        }
        return array(true, '', '');
    }
}

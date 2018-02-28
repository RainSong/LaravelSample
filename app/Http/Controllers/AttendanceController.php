<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

class AttendanceController extends Controller
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Index()
    {
        $query = DB::table('attendances as a')
            ->leftjoin('departments as d', 'a.department_id', '=', 'd.id')
            ->leftjoin('users as u', 'a.report_user_id', '=', 'u.id')
            ->where('a.state', '=', '0');
        $pageSize = Input::get('page_size', 0);
        $pageIndex = Input::get('page_index', 1);
        $department_id = Input::get('id');
        if (!empty($department_id)) {
            $query->where('a.department_id', '=', $department_id);
        }
        $date = Input::get('date');
        if (!empty($date)) {
            $query->where('a.date', '=', $date);
        }

        $sort_column = Input::get('sort_column');
        $order = Input::get('order');

        if ($order == 'descending') {
            $order = 'desc';
        } else {
            $order = 'asc';
        }
        if (empty($sort_column)) {
            $sort_column = 'date';
            $order = 'desc';
        }
        $sort_column = 'a.' . $sort_column;

        try {
            $total = $query->count();
            $query->select(DB::raw('a.id,a.date,d.name,u.user_name,a.total,a.attendance,a.leave,a.other,a.remark,a.created_at'));
            $query->orderBy($sort_column, $order);
            $attendances = $query->offset(($pageIndex - 1) * $pageSize)
                ->limit($pageSize)
                ->get();
            return response()->json(['status' => true, 'total' => $total, 'attendances' => $attendances]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Add()
    {
        $attendance = new Attendance();
        $this->PickData($attendance);
        list($result, $field, $message) = $this->Check($attendance, false);
        if ($result) {
            try {
                $attendance->save();
                return response()->json(['status' => true]);
            } catch (\Exception $ex) {
                \Log::error($ex);
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false, 'field' => $field, 'message' => $message]);
        }
    }

    public function Show($id)
    {
        try {
            $attendance = Attendance::find($id);
            return response()->json(['status' => true, 'data' => $attendance]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    public function Update()
    {
        $id = Input::get('id',0);
        $attendance = Attendance::find($id);
        $this->PickData($attendance);
        list($result, $field, $message) = $this->Check($attendance, false);
        if ($result) {
            try {
                $attendance->save();
                return response()->json(['status' => true]);
            } catch (\Exception $ex) {
                \Log::error($ex);
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false, $field, $message]);
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
            DB::table('attendances')->whereIn('id', $arr_ids)->update(['state' => 1]);
            return response()->json(['status' => true]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }

    private function PickData($attendance)
    {
        $attendance->department_id = Input::get('deparent_id', 0);
        $attendance->date = Input::get('date');
        $attendance->total = Input::get('total');
        $attendance->attendance = Input::get('attendance', 0);
        $attendance->leave = Input::get('leave', 0);
        $attendance->other = Input::get('other', 0);
        $attendance->report_user_id = Input::get('report_user_id');
        $attendance->remark = Input::get('remark');
    }

    private function Check($attendance, $isNew = false)
    {
        $result = true;
        $field = '';
        $message = '';
        if (empty($attendance->date)) {
            $result = false;
            $field = 'date';
            $message = '日期不能为空';

        } else {
            $ret = strtotime($attendance->date);
            if ($ret == FALSE || $ret == -1) {
                $result = false;
                $field = 'date';
                $message = '日期无效';
            }
        }
        if(empty($attendance->total)){
            $result = false;
            $field = 'total';
            $message = '应到人数不能为空';
        }
        if(empty($attendance->total)){
            $result = false;
            $field = 'attendance';
            $message = '实到人数不能为空';
        }
        return array($result, $field, $message);
    }
}

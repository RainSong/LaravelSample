<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use function Sodium\add;
use \Exception;
use \Log;

class InterviewController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Index(Request $request)
    {
        $key_word = $request->get('key_word');
        $date = $request->get('date');
        $interviews = Models\Interview::where('state', 0)->get();
        $total = Models\Interview::where('state', 0)->count('id');
        return response()->json(['status' => true, 'data' => array('interviews' => $interviews, 'total' => $total)]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Add(Request $request)
    {
        $interview = new Models\Interview();
        $interview->name = $request->input('name');
        $interview->sex = $request->input('sex');
        $interview->id_card = $request->input('id_card');
        $interview->mobile = $request->input('mobile');
        $interview->brithday = $request->input('brithday');
        $interview->entry_time = $request->input('entry_time');
        $interview->address = $request->input('address');
        $interview->remark = $request->input('remark');
        list($result, $field, $message) = $this->CheckInput($interview);
        if ($result) {
            try {
                $interview->save();
                return response()->json(['status' => true]);
            } catch (Exception $ex) {
                Log::error('保存面试信息失败', $ex);
                return response()->json(['status' => false]);
            }
        } else {
            return response()->json(['status' => false, 'message' => $message, 'field' => $field]);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Show(int $id)
    {
        try {
            $interview = Models\Interview::find($id);
            return response()->json(['status' => true, 'message' => $interview]);
        } catch (Exception $ex) {
            Log::error('获取去面试信息失败，ID：' . $id, $ex);
            return response()->json(['status' => false]);
        }
    }

    /**
     * @param Request $request
     */
    public function Update(Request $request)
    {
        try {
            $id = $request->input('id');
            $interview = Models\Interview::find($id);
            if ($interview != null) {
                $interview->name = $request->input('name');
                $interview->sex = $request->input('sex');
                $interview->id_card = $request->input('id_card');
                $interview->mobile = $request->input('mobile');
                $interview->brithday = $request->input('brithday');
                $interview->interview_time = $request->input('interview_time');
                $interview->entry_time = $request->input('entry_time');
                $interview->address = $request->input('address');
                $interview->remark = $request->input('remark');
                list($result, $field, $message) = $this->CheckInput($interview);
                if ($result) {
                    $interview->save();
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false, 'message' => $message, 'field' => $field]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'ID无效，更新失败']);
            }
        } catch (Exception $ex) {
            Log::error('更新面试信息失败', $ex);
            return response()->json(['status' => true, 'message'=>'发生错误，数据更新失败']);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Delete(int $id)
    {
        try {
            $interview = Models\Interview::find($id);
            $interview->state = 1;
            $interview->save();
            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            Log::error('删除面试信息失败', $ex);
            return response()->json(['status' => false]);
        }
    }

    /**
     * @param Models\Interview $interview
     * @return array
     */
    public function CheckInput(\App\Models\Interview $interview)
    {
        if (empty($interview->name)) {
            return array(false, 'name', '姓名不能为空');
        }
        if (empty($interview->id_card)) {
            return array(false, 'id_card', '身份证号码不能为空');
        }
        if (empty($interview->mobile)) {
            return array(false, 'mobile', '电话不能为空');
        }
        if (empty($interview->brithday)) {
            return array(false, 'brithday', '出生日期不能为空');
        }
        return array(true, '', '');
    }
}

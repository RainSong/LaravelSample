<?php

namespace App\Http\Controllers;

use App\Models\Module;
use DB;

class ModuleController extends Controller
{
    //

    public function Index()
    {
        // try {
        //     $menus = Menu::where('state', '0')->get();
        //     return response()->json(['status' => true, 'data' => $menus]);
        // } catch (\Exception $ex) {
        //     \Log::error('获取菜单信息失败', $ex);
        //     return response()->json(['status' => false]);
        // }
        try {
            $modules = DB::table('modules as c')
            ->leftJoin('modules as p','c.parent_id','=','p.id')
            ->select(['c.id','c.name','c.display_name','p.name AS parent_name','c.parent_id','c.sort'])
            ->where('c.state','=','0')
            ->where(function($q){
                $q->orWhere('p.state','=','0')
                ->orWhereNull('p.state');
            })
            ->get();
            return response()->json(['status' => true, 'modules' => $modules]);
        } catch (\Exception $ex) {
            \Log::error('获取菜单信息失败', $ex);
            return response()->json(['status' => false]);
        }
    }
}

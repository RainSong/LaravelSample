<?php

namespace App\Http\Controllers;

use DB;

class MenuController extends Controller
{
    //
    public function Index()
    {
        try {
            $menus = DB::table('modules')
                ->select(['id', 'parent_id', 'name','path', 'display_name', 'component'])
                ->where('state', '=', '0')
                ->where('visible', '=', '1')
                ->get();
            return response()->json(['status' => true, 'menus' => $menus]);
        } catch (\Exception $ex) {
            \Log::error($ex);
            return response()->json(['status' => false]);
        }
    }
}

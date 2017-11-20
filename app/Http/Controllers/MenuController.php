<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    //

    public function Index()
    {
        try {
            $menus = Menu::where('state', '0')->get();
            return response()->json(['status' => true, 'data' => $menus]);
        } catch (\Exception $ex) {
            \Log::error('获取菜单信息失败', $ex);
            return response()->json(['status' => false]);
        }
    }
}

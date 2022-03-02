<?php
/*
 * @Author: your name
 * @Date: 2020-10-21 14:16:34
 * @LastEditTime: 2020-10-21 22:15:44
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \ShopX\app\Http\Controllers\Home\advController.php
 */

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adv;
use App\Models\AdvPosition;

class advController extends NotokenController
{
    public function index(Request $req){
        $res=AdvPosition::where("ap_name",$req->get("name"))->with('adv')->get();
        return $this->success_msg('Success',$res);

    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Tools\Commission;

class CommissionUsersController extends BaseController
{

	public function index(Request $req,Users $users_model){
        $list = $users_model->orderBy('id','desc')->with(['roles','inviter_user'])->paginate(10);
        $Commission_model=new Commission();
        $list=$Commission_model->getInfo($list);
		return $this->success_msg('Success',$list);
    }
}

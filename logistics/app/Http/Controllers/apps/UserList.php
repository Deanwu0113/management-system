<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserList extends Controller
{
    public function index()
    {
        $users = User::all(); // 获取所有用户数据
        return view('content.apps.app-user-list', compact('users'));
    }
}

<?php
namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    if (Auth::check()) {
        return redirect('/app/logistics/dashboard'); // 登录后重定向到新的主页
    }

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    // 处理登录逻辑
    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect()->intended('/app/logistics/dashboard'); // 登录成功后重定向
    }

    return back()->withErrors(['email' => '登录失败']);
  }
}

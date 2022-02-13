<?php

namespace App\Http\Controllers\CP;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function from_login(){
       return view('login');
    }

    public function login_action(LoginRequest $request){
        if (Auth::guard('web')->attempt(['email' => $request['email'], 'password' => $request['password'], 'enabled' => 1], isset($request->remember))) {
            return redirect()->route('admin.dashboard');
        } else {
            return back()->with('validationErr','الرجاء التحقق من كلمة المرور او البريد الالكتروني');
        }
    }

    public function dashboard(){

        return view('index');
    }
    public function logout(){
      Auth::logout();

        return redirect()->route('admin.login');
    }
}

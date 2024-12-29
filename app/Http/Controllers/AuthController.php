<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6'
            ], [
                'email.exists' => 'Email không tồn tại trong hệ thống',
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Vui lòng nhập đúng định dạng email',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự'
            ]);


            if (auth()->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('listUser'));
            }

            return redirect()->back()
                ->withInput($request->only('email'))

                ->with('error', 'Email hoặc mật khẩu không chính xác.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($request->only('email'))

                ->with('error', 'Đã xảy ra lỗi trong quá trình đăng nhập. Vui lòng thử lại.');
        }
    }

    public function register()
    {
        return view('auth.register');
    }
    public function registerPost(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ], [
                'name.required' => 'Vui lòng nhập tên',
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Vui lòng nhập đúng định dạng email',
                'email.unique' => 'Email đã tồn tại trong hệ thống',
                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            ]);

            $data['password'] = bcrypt($data['password']);


            $user = DB::table('users')->insert($data);

            if ($user) {
                return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công');
            }

            return redirect()->back()
                ->withInput($request->only(['name', 'email']))
                ->with('error', 'Đăng ký tài khoản thất bại. Vui lòng thử lại.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($request->only(['name', 'email']))
                ->with('error', 'Đã xảy ra lỗi trong quá trình đăng ký. Vui lòng thử lại.');
        }
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
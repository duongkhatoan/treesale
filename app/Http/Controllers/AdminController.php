<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function login(Request $request)
    {
        $check = $request->all();
        // dd($check);
        // dd(Auth::guard('admin')->attempt(['email' => 'jenkins.adolph@example.com', 'password' => 'passsword']));
        if (Auth::guard('admin')->attempt(['email' => $check['email'], 'password' => $check['password']])) {
            return redirect()->route('admin.manager')->with('error', 'Admin Login Successful');
        } else {
            return back()->with('error', 'Admin Login Failed');
        }
    }
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function register()
    {
        return view('admin.register');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        Admin::insert([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'created_at' => Carbon::now(),
        ]);
        return redirect()->route('login_form')->with('error', 'Admin register successful');
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login_form')->with('error', 'Admin logout Successful');
    }

    public function manager()
    {
        return view('layout_admin.master');
    }
}

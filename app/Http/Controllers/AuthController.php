<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    private $_app = "";
    private $_page = null;
    private $_data = [];

    public function _construct()
    {
    }

    public function signup(Request $request)
    {
        return view('layout.signup');
    }

    public function signupAction(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
        ]);
        // dd($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        // event(new Registered($user));
        return redirect(route('login'));
    }

    public function login(Request $request)
    {
        if (Auth::user()) {
            return redirect()->route('dashboard');
        }
        return view('layout.login');
    }

    public function loginAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withInput($request->only('email'))->with(['fail' => 'Credentials did not match our record']);
        } else {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('dashboard');
            }
            return redirect()->back()->withInput($request->only('email'))->with(['fail' => 'Credentials did not match our record']);
        }
    }

    public function logout(Request $request)
    {
        $user_id = Auth::user()->id;
        // $activityLog = new ActivityLog();
        // $activityLog->user_id = $user_id;
        // $activityLog->ip_address = $request->ip();
        // $activityLog->user_agent = $request->header('User-Agent');
        // $activityLog->activity = 'logged_out';
        // $activityLog->save();
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('login');
    }
    public function approvalPending()
    {
        return view('auth.approval_page');
    }
}


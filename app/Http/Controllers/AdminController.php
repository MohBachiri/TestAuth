<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   /*  public function __construct()
    {
        $this->middleware('auth');
    } */
    function check(Request $request)
    {
        //Validate inputs
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required'
        ], [
            'email.exists' => 'Email Not Correct',
            'email.email' => 'This Email Not Valide',
            'email.required' => 'Add Your Email',
            'password.required' => 'Add Your Password',
        ]);
        $creds = array(
            'email' => $request->email,
            'password' => $request->password
        );
        if (Auth::guard('admin')->attempt($creds)) {
            return view('admin.home');
        } else {
            return redirect()->route('admin.login')->with('fail', 'Incorrect credentials');
        }
    }
    function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $admin_apps = new admin();
        $admin_apps->name = $request->name;
        $admin_apps->email = $request->email;
        $admin_apps->password = Hash::make($request->password);
        $save = $admin_apps->save();
        if ($save) {
            return redirect()->back()->with('success', 'successfully!');
        } else {
            return redirect()->back()->with('fail', 'Something went wrong, failed to register');
        }
    }
    function home(){
        return view('admin.home');
    }

    function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}

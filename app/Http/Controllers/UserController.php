<?php

namespace App\Http\Controllers;
use App\Admin;
use App\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loginUser(Request $request) {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if(Admin::where('user_id',Auth::user()->id)->get()->count() > 0) {
                return redirect()->route('home');
            }
            return redirect()->route('home');
        } else {
            return redirect()->back();
        }
    }

    public function adminroute() {
        if(Admin::where('user_id',Auth::user()->id)->get()->count() > 0) {
            return view('admin');
        } else {
            return redirect()->route('home');
        }
    }

    public function getAll() {
        $users = User::all();
        return response()->json($users);

    }

    public function getUserInfo(Request $request) {
        $id = $request->get('id');
        if(is_null($id)) {
            $id = Auth::user()->id;
        }
        $userInfo = User::where('id',$id)->first();

        return response()->json($userInfo);
    }

    public function isAdmin() {
        $admin = Admin::where('user_id', Auth::user()->id)->get()->count();

        if ($admin > 0) return response()->json(['isAdmin'=>true]);
        else return response()->json(['isAdmin'=>false]);

    }

    public function newUser(Request $request) {
        $user = new User();

        $user->name = $request['name'];
        $user->department = $request['department'];
        $user->designation = $request['designation'];
        $user->email = $request['email'];
        $user->contact_number = $request['phone'];
        $user->password = bcrypt($request['password']);

        $user->save();

        return redirect()->back();
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('loginpage');
    }
}

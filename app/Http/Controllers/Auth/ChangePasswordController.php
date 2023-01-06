<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Rules\CurrentPassword;

class ChangePasswordController extends Controller
{
    //
    public function create_form()
    {
        return view('auth/change-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'current_password' => [new CurrentPassword()],
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if($request->current_password === $request->new_password) {
            return back()->withErrors(['error' => '現在とは異なるパスワードを指定してください']);
        }

        $status = \Auth::user()->forceFill([
            'password' => Hash::make($request->new_password),
            'remember_token' => Str::random(60),
        ])->save();
        
        return $status
                    ? redirect()->route('password.change')->with('success', 'パスワードを変更しました')
                    : back()->withErrors(['error' => 'パスワードの変更に失敗しました']);
    }
}

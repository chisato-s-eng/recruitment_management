<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Permission;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }

        $departments = Department::select('id', 'name')->get();
        return view('auth.register', [
            'departments' => $departments
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->is_admin === 1) {
            return redirect(route('applicant.list'))->withErrors(['permissionError' => 'このユーザーには権限がありません。']);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => 'required|integer|between:0,1',
            'permission.*' => 'nullable|exists:App\Models\Department,id'
        ]);

        if($request->is_admin === '1' && !isset($request->permission)) {
            return back()->withErrors(['error' => '一般利用者の場合は権限を1つ以上選択してください']);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->is_admin,
        ]);

        if ($request->is_admin === '1' && isset($request->permission)) {
            $last_id = $user->id;
            foreach($request->permission as $value) {
                $permission = Permission::create([
                    'user_id' => $last_id,
                    'department_id' => $value
                ]);
            }
        }

        event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
        return redirect(route('register'))->with('success', 'ユーザーの登録に成功しました');
    }
}

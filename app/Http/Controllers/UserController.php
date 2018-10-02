<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\User;
use Mail;
use Auth;

class UserController extends Controller
{
    public function getRegister()
    {
    	return view('auth.register');
    }

    public function postRegister(RegisterRequest $request)
    {
    	$confirmation_code = time().uniqid(true);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'confirmation_code' => $confirmation_code,
            'confirmed' => 0,
        ]);
        Mail::send('email.verify', ['confirmation_code' => $confirmation_code, 'user' => $user], function($m) use ($user) {
            $m->to($user->email)->subject('Verify your email address');
        });
        return redirect(route('login'))->with('status', 'Vui lòng xác nhận tài khoản email');
    }

    public function verify($code)
    {
        $user = User::where('confirmation_code', $code);

        if ($user->count() > 0) {
            $user->update([
                'confirmed' => 1,
                'confirmation_code' => null
            ]);
            $notification_status = 'Bạn đã xác nhận thành công';
        } else {
            $notification_status ='Mã xác nhận không chính xác';
        }

        return redirect(route('login'))->with('status', $notification_status);
    }

    public function authenticate(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'confirmed' => 1
        ];

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors(['email'  =>  'Bạn không thể đăng nhập.']);
        }
        
        return redirect('/');
    }
}

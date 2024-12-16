<?php

// namespace App\Http\Controllers\Auth;
namespace Modules\Auth\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $login = $request->input('email');
        $user = User::where('email', $login)->orWhere('username', $login)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Invalid login credentials']);
        }

        $request->validate([
            'password' => 'required|min:8',
        ]);
        

        if (Auth::attempt(['email' => $user->email, 'password' => $request->password]) ||
            Auth::attempt(['username' => $user->username, 'password' => $request->password])) {
                if(Auth::user()->status=='Disable'){
                    Auth::logout();
                    return  redirect()->route('login')
                        ->withInput()
                        ->withErrors(['email'=>'account is blocked !']);
                }
            
            Auth::loginUsingId($user->id);
            return redirect('/');
        } else {
            return redirect()->back()->withErrors(['password' => 'Invalid login credentials']);
        }
    }

    public function showLoginForm()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view("auth::login");
    }
}

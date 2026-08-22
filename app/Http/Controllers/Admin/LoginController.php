<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backend\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        $login = $request->input($this->username());
        $userTable = (new User)->getTable();
        $hasUsername = Schema::hasColumn($userTable, 'username');

        // Dùng email nếu input giống email; nếu không thì dùng username chỉ khi bảng có cột username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif ($hasUsername) {
            $field = 'username';
        } else {
            $field = 'email';
        }

        $credentials = [
            $field => $login,
            'password' => $request->input('password'),
        ];

        if (Schema::hasColumn($userTable, 'status')) {
            $credentials['status'] = 1;
        }

        return $credentials;
    }

    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }
}

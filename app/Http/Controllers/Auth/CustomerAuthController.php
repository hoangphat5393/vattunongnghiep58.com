<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Frontend\User;
use App\Services\CustomerAccountService;
use App\Traits\LocalizeController;
use Auth;
use Gornymedia\Shortcodes\Facades\Shortcode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    use LocalizeController;

    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    public function __construct(
        protected CustomerAccountService $accountService,
    ) {
        parent::__construct();
    }

    public function showLoginForm(): RedirectResponse|string
    {
        if (! Auth::check()) {
            $this->localized();
            $this->data['seo'] = [
                'seo_title' => 'Đăng nhập',
            ];
            $html = view($this->templatePath.'.account.login', $this->data)->render();
            try {
                $html = Shortcode::compile($html);
            } catch (\Throwable $e) {
            }

            return $html;
        }

        return redirect(url('/'));
    }

    public function postLogin(Request $request): JsonResponse
    {
        $login = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $remember_me = $request->remember_me == 1;

        $check_user = User::where('email', $request->email)->first();
        if ($check_user != '' && $check_user->status == 1) {
            if (Auth::attempt($login, $remember_me)) {
                $this->accountService->linkGuestOrdersToUser(Auth::id(), $request->email);

                return response()->json([
                    'error' => 0,
                    'redirect_back' => $request->url_back ?? '/',
                    'view' => view($this->templatePath.'.account.includes.login_success')->render(),
                    'msg' => 'Đăng nhập thành công!',
                ]);
            }

            $message = 'Email hoặc mật khẩu không chính xác!';
        } else {
            $message = 'Tài khoản không tồn tại hoặc đã bị khóa!';
        }

        return response()->json([
            'error' => 1,
            'msg' => $message,
        ]);
    }

    public function registerCustomer(): string
    {
        $this->data['seo'] = [
            'seo_title' => 'Đăng ký thành viên',
        ];
        $html = view($this->templatePath.'.account.register', $this->data)->render();
        try {
            $html = Shortcode::compile($html);
        } catch (\Throwable $e) {
        }

        return $html;
    }

    public function createCustomerSuccess(): string
    {
        $this->data['seo'] = [
            'seo_title' => 'Đăng ký thành công',
        ];
        $html = view($this->templatePath.'.account.register-success', $this->data)->render();
        try {
            $html = Shortcode::compile($html);
        } catch (\Throwable $e) {
        }

        return $html;
    }

    public function logoutCustomer(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('index');
    }
}

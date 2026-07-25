<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CustomerRegisterRequest;
use App\Models\Frontend\User;
use App\Providers\RouteServiceProvider;
use App\Services\CustomerAccountService;
use App\Services\CustomerRegistrationEmailService;
use Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(
        protected CustomerRegistrationEmailService $registrationEmailService,
        protected CustomerAccountService $accountService,
    ) {}

    public function register(CustomerRegisterRequest $request): JsonResponse
    {
        if ($this->registrationScore($request) <= 0.3) {
            return response()->json([
                'error' => 1,
                'msg' => 'Hệ thống nghi ngờ bạn là bot tự động. Vui lòng thử lại.',
            ]);
        }

        $validated = $request->validated();
        $user = $this->createRegisteredUser($validated);

        try {
            $this->registrationEmailService->sendRegistrationEmails($user);
        } catch (\Throwable $e) {
            report($e);
        }

        Auth::login($user);
        $this->accountService->linkGuestOrdersToUser($user->id, $user->email);

        return response()->json([
            'error' => 0,
            'redirect_back' => $request->input('url_back', route('customer.register.success')),
            'view' => view(($this->templatePath ?: env('APP_THEME', 'frontend')).'.account.includes.register_success')->render(),
            'msg' => 'Đăng ký tài khoản thành công!',
        ]);
    }

    /**
     * @param  array{name: string, email: string, phone: string, password: string}  $data
     */
    protected function createRegisteredUser(array $data): User
    {
        $displayName = trim($data['name']);
        if ($displayName === '') {
            $displayName = explode('@', $data['email'])[0];
        }

        return User::create([
            'fullname' => $displayName,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => 1,
        ]);
    }

    /**
     * reCAPTCHA v3 score. Trên local / domain .test thường fail nên bỏ qua khi chưa cấu hình secret.
     */
    protected function registrationScore(Request $request): float
    {
        $secret = config('recaptchav3.secret');
        if (empty($secret)) {
            return 1.0;
        }

        if (! app()->environment('production')) {
            return 1.0;
        }

        $token = $request->get('g-recaptcha-response');
        if (empty($token)) {
            return 0.0;
        }

        try {
            $score = RecaptchaV3::verify($token, 'register');
        } catch (\Throwable $e) {
            report($e);

            return 0.0;
        }

        return is_numeric($score) ? (float) $score : 0.0;
    }

    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }
}

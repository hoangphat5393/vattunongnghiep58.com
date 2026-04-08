<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer_forget_pass_otp;
use App\Models\Frontend\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Mail;
use Validator;

class ForgotPasswordController extends Controller
{
    /*

    |--------------------------------------------------------------------------

    | Password Reset Controller

    |--------------------------------------------------------------------------

    |

    | This controller is responsible for handling password reset emails and

    | includes a trait which assists in sending these notifications from

    | your application to your users. Feel free to explore this trait.

    |

    */

    use SendsPasswordResetEmails;

    // xử lý quên mật khẩu

    public function forget()
    {

        return view('auth.passwords.forget-password');

    }

    public function actionForgetPassword(Request $rq)
    {
        $rq->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $rq->email)->first();

        if ($user) {

            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $customer_forget_pass_otp = new Customer_forget_pass_otp;

            $customer_forget_pass_otp->email = $rq->email;

            $customer_forget_pass_otp->user_id = $user->id;

            $customer_forget_pass_otp->otp_mail = rand(100000, 999999);

            $customer_forget_pass_otp->status = 0;

            $customer_forget_pass_otp->save();

            $_SESSION['otp_forget'] = $customer_forget_pass_otp->otp_mail;

            $_SESSION['email_forget'] = $customer_forget_pass_otp->email;

            $site_name = setting_option('company_name');

            $data = [

                'email' => $customer_forget_pass_otp->email,

                'emailadmin' => $email_admin = setting_option('email'),

                'otp' => $customer_forget_pass_otp->otp_mail,

                'name' => $user->first_name,

                'created_at' => $customer_forget_pass_otp->created_at,

                'site_name' => $site_name,

            ];

            Mail::send(

                $this->templatePath.'.mail.forget-password.forget-password',

                $data,

                function ($message) use ($data) {

                    $message->from($data['emailadmin'], $data['site_name']);

                    $message->to($data['email'])

                        ->subject($data['otp'].' là mã OTP của '.$data['site_name']);

                }

            );

            return redirect()->route('forgetPassword_step2');

        } else {

            return redirect()
                ->back()
                ->withErrors(['email' => 'Email không tồn tại.'])
                ->withInput();

        }

    }

    public function forgetPassword_step2()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ((! isset($_SESSION['otp_forget']) && ! isset($_SESSION['email_forget'])) || $_SESSION['otp_forget'] == '' || $_SESSION['email_forget'] == '') {

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');

        } else {

            return view('auth.passwords.forget-password-step-2');

        }

    }

    public function actionForgetPassword_step2(Request $rq)
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $customer_forget_pass_otp = Customer_forget_pass_otp::where('otp_mail', '=', $rq->otp_mail)

            ->where('otp_mail', '=', $_SESSION['otp_forget'])

            ->where('status', '=', 0)

            ->whereRaw("TIME_TO_SEC('".Carbon::now()."') - TIME_TO_SEC(created_at) < 300 ")

            ->first();

        if ($customer_forget_pass_otp) {

            $_SESSION['otp_true'] = 1;

            return redirect()->route('forgetPassword_step3');

        } else {

            return redirect()->back()->withErrors(['otp_mail' => 'OTP không đúng.']);

        }

    }

    public function forgetPassword_step3()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ((! isset($_SESSION['otp_forget']) && ! isset($_SESSION['email_forget']) && ! isset($_SESSION['otp_true'])) || $_SESSION['otp_forget'] == '' || $_SESSION['email_forget'] == '') {

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');

        } else {

            return view('auth.passwords.forget-password-step-3');

        }

    }

    public function actionForgetPassword_step3(Request $rq)
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $customer_forget_pass_otp = Customer_forget_pass_otp::where('email', '=', $_SESSION['email_forget'])

            ->where('otp_mail', '=', $_SESSION['otp_forget'])

            ->where('status', '=', 0)

            ->first();

        if ($customer_forget_pass_otp) {

            $validator = Validator::make($rq->all(), [

                'new_password' => 'required|min:3|required_with:confirm_new_password|same:confirm_new_password',

                'confirm_new_password' => 'required|min:3',

            ]);

            if ($validator->fails()) {

                return redirect()->back()

                    ->withErrors($validator);

            }

            $customer = User::where('email', '=', $_SESSION['email_forget'])->first();

            $customer->password = bcrypt($rq->new_password);
            $customer->save();

            $customer_forget_pass_otp->status = 1;

            $customer_forget_pass_otp->save();

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()
                ->route('user.login')
                ->with('success', 'Mật khẩu đã được thay đổi.');

        } else {

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');

        }

    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Verify\Service;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    use RedirectsUsers;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';


    /**
     * Verification service
     *
     * @var Service
     */
    protected $verify;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Service $verify)
    {
        parent::__construct();
        $this->verify = $verify;

        $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the phone verification form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedPhone()
            ? redirect($this->redirectPath())
            : view($this->templatePath . '.auth.verify-page');
    }

    /**
     * Mark the authenticated user's phone number as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedPhone()) {
            return redirect($this->redirectPath());
        }

        $code = $request->post('code');
        $phone = $request->user()->full_phone;

        $verification = $this->verify->checkVerification($phone, $code);

        if ($verification->isValid()) {
            $request->user()->markPhoneAsVerified();
            return redirect($this->redirectPath());
        }

        $errors = new MessageBag();
        foreach ($verification->getErrors() as $error) {
            $errors->add('verification', $error);
        }

        // return view($this->templatePath .'.auth.verify-page')->withErrors($errors);
        return redirect(route('auth.verify'))->withErrors($errors)->withInput();
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedPhone()) {
            return redirect($this->redirectPath());
        }

        $phone = $request->user()->full_phone;
        if ($phone) {
            $channel = $request->post('channel', 'sms');
            $verification = $this->verify->startVerification($phone, $channel);

            if (!$verification->isValid()) {

                $errors = new MessageBag();
                foreach ($verification->getErrors() as $error) {
                    $errors->add('verification', $error);
                }

                return redirect(route('auth.verify'))->withErrors($errors)->withInput();
            }

            /*$messages = new MessageBag();
            $messages->add('verification', "Another code sent to {$request->user()->full_phone}");*/

            return redirect(route('auth.verify'))->with('message', "Another code sent to {$request->user()->full_phone}");
        } else {
            return redirect(route('auth.verify'))->with('error', "Phone number is empty or malformed!");
        }
    }
}

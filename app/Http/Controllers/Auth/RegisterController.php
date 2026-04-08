<?php



namespace App\Http\Controllers\Auth;



use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\MessageBag;

use Illuminate\Auth\Events\Registered;

use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;

use App\Models\Frontend\User;
use Auth, Response;

use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;



// use App\Verify\Service;



class RegisterController extends Controller

{

    /*

    |--------------------------------------------------------------------------

    | Register Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles the registration of new users as well as their

    | validation and creation. By default this controller uses a trait to

    | provide this functionality without requiring any additional code.

    |

    */



    use RegistersUsers;



    /**

     * Where to redirect users after registration.

     *

     * @var string

     */

    protected $redirectTo = RouteServiceProvider::HOME;



    /**

     * Verification service

     *

     * @var Service

     */

    // protected $verify;



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    //    public function __construct(Service $verify)

    //     {

    //         parent::__construct();

    //         $this->middleware('guest');

    //         $this->verify = $verify;

    //     }



    public $data = [

        'error' => false,

        'success' => false,

        'message' => ''

    ];





    /**

     * Create a new user instance after a valid registration.

     *

     * @param  array  $data

     * @return \App\User

     */

    protected function create(array $data)

    {

        $fullname = $data['fullname'] ?? '';

        if ($fullname == '')

            $fullname = explode('@', $data['email'])[0];



        $name = $data['name'] ?? '';



        return User::create([
            'fullname' => $fullname,

            'name' => $name,

            'email' => $data['email'],

            'phone' => $data['phone'],

            'password' => Hash::make($data['password']),
            'status' => 1,
        ]);

    }



    /**

     * Get a validator for an incoming registration request.

     *

     * @param  array  $data

     * @return \Illuminate\Contracts\Validation\Validator

     */

    protected function validator(array $data)

    {

        // $validation_rules = array(

        //     'email' => 'required|email|max:255|unique:users',

        //     'phone' => 'required|string|min:10|unique:users',

        //     'password' => 'required',

        //     'password_confirm' => 'required|same:password',

        // );



        $validation_rules = array(

            'name' => 'required',

            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|min:10|unique:users,phone',
            'password' => 'required',

            'password_confirm' => 'required|same:password',

        );

        $messages = array(

            'name.required' => 'Please enter your name',

            'email.required' => 'Please enter your email',

            'email.email' => 'Email address is not in the correct format',

            'email.max' => 'Email address up to 255 characters',

            'email.unique' => 'Email address already exists',

            'phone.required' => 'Phone address already exists',

            'password.required' => 'Please enter password',

            'password_confirm.same' => 'Password and re-enter password do not match!',

        );

        return Validator::make($data, $validation_rules, $messages);

    }



    /**

     * Handle a registration request for the application.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function register(Request $request)

    {

        $data = $request->input();



        $score = RecaptchaV3::verify($request->get('g-recaptcha-response'), 'contact');



        // Xét lỗi Recaptcha

        if ($score > 0.7 && $data) {



            $validator = $this->validator($data); //->validate();

            if ($validator->fails()) {

                $error = $validator->errors()->first();

                $this->data['status'] = 'error';

                $this->data['message'] = $error;

            } else {

                // $user = $this->create($request->all());

                $this->create($request->all());

                $this->data['status'] = 'success';

                $this->data['message'] = 'Register Successfully';

            }

            return response()->json($this->data);

        } elseif ($score > 0.3) {

            $this->data['status'] = 'error';

            $this->data['message'] = 'Require additional email verification';

            return response()->json($this->data);

        } else {

            $this->data['status'] = 'error';

            $this->data['message'] = 'You are most likely a bot';

            return response()->json($this->data);

        }

    }



    /**

     * Get the guard to be used during registration.

     *

     * @return \Illuminate\Contracts\Auth\StatefulGuard

     */

    protected function guard()

    {

        return Auth::guard();

    }



    //   Verify phone

    // protected function registered(Request $request, User $user)

    // {

    //     $verification = $this->verify->startVerification($user->full_phone, $request->post('channel', 'sms'));

    //     // dd($verification);

    //     if (!$verification->isValid()) {

    //         $user->delete();



    //         /*$errors = new MessageBag();

    //         foreach($verification->getErrors() as $error) {

    //             $errors->add('verification', $error);

    //         }*/

    //         $error = $verification->getErrors()[0] ?? 'Error!';

    //         return response()->json([

    //             'error' => 1,

    //             'msg'   => $error

    //         ]);

    //         return view('auth.register')->withErrors($errors);

    //     }



    //     $messages = new MessageBag();

    //     $messages->add('verification', "Code sent to {$request->user()->phone}");

    //     $view = view($this->templatePath . '.auth.verify', $messages)->render();

    //     return response()->json([

    //         'error' => 0,

    //         'view'    => $view,

    //         'url_redirect'    => route('auth.verify'),

    //         'msg'   => "Code sent to {$request->user()->phone}"

    //     ]);

    //     // return redirect('/verify')->with('messages', $messages);

    // }

}


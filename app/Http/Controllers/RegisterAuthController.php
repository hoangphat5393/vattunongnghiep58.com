<?php

namespace App\Http\Controllers;

use App\Models\Frontend\User;
use Auth;
use File;
use Illuminate\Http\Response;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Session;
use Socialite;
use URL;

class RegisterAuthController extends Controller
{
    public function redirectToProvider($provider)
    {

        if (! Session::has('pre_url')) {

            Session::put('pre_url', URL::previous());

        } else {

            if (URL::previous() != URL::to('login')) {

                Session::put('pre_url', URL::previous());

            }

        }

        return Socialite::driver($provider)->redirect();

    }

    /**
     * Obtain the user information from facebook.

     *

     * @return Response
     */
    public function handleProviderCallback($provider)
    {

        $user = Socialite::driver($provider)->user();

        if ($user->email == '') {

            $user->email = changeTitle(trim($user->name)).'@gmail.com';
        }

        $authUser = $this->findOrCreateUser($user, $provider);

        // Chỗ này để check xem nó có chạy hay không

        // dd($user);

        Auth::login($authUser);

        // Auth::login($authUser, true);

        return redirect()->route('index');

    }

    private function findOrCreateUser($User_Data, $provider)
    {

        // create avatar folder if doesn't exist

        $path = public_path('images/users/avatar/');

        if (! File::isDirectory($path)) {

            File::makeDirectory($path, 0777, true, true);

        }

        $authUser = User::where('provider_id', $User_Data->id)->first();

        // dd($User_Data);

        if ($authUser) {

            return $authUser;

        }

        if ($User_Data->email != '') {

            $email = $User_Data->email;

        } else {

            $email = $User_Data->id.'@gmail.com';

        }

        $username = str_replace(' ', '', remove_accents($User_Data->name));

        $username = strtolower($username.rand(0, 10000));

        $check_email = User::where('email', $email)->first();

        if ($check_email) {

            return $check_email;

            /*$msg = "Email Facebook đã được sử dụng để đăng ký tài khoản";

            $result = "";

            $result .= "<script language='javascript'>alert('".$msg."');</script>";

            $result .= "<script language='javascript'>history.go(-1);</script>";

            echo $result;

            exit();*/

        } else {

            $datetime_now = date('Y-m-d H:i:s');

            $datetime_convert = strtotime($datetime_now);

            // $avatar = file_get_contents($facebookUser->getAvatar());

            $name_avatar = '';

            if ($provider == 'facebook') {

                // get avatar from facebook account

                // https://graph.facebook.com/v3.3/[facebook_user_id]/picture?type=normal&access_token=[fbtoken]

                $avatar = file_get_contents($User_Data->getAvatar().'&access_token='.$User_Data->token);

            } else {

                $avatar = file_get_contents($User_Data->avatar);

            }

            $name_avatar = 'avatar-'.$datetime_convert.'-'.$User_Data->getId().'.jpg';

            $manager = new ImageManager(new Driver);
            $image_resize = $manager->read($avatar);
            $image_resize = $image_resize->scale(height: 600);
            $image_resize->save($path.'/'.$name_avatar);

            $avatar_path = '/images/users/avatar/'.$name_avatar;

            // File::put('/images/avatar/' ."avatar-".$datetime_convert.'-'.$facebookUser->getId().".jpg", $avatar);

            $result = User::create([

                'fullname' => $User_Data->name,

                'password' => bcrypt($User_Data->token),

                'username' => $username,

                'avatar' => $avatar_path,

                'email' => $email,

                'provider_id' => $User_Data->id,

                'provider' => $provider,

            ]);

            return $result;

        }

    }
}

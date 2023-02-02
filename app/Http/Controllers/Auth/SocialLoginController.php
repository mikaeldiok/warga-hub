<?php

namespace App\Http\Controllers\Auth;

use App\Events\Frontend\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Userprofile;
use App\Models\UserProvider;
use App\Providers\RouteServiceProvider;
use Auth;
use Log;
use Socialite;

class SocialLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        $redirectTo = request()->redirectTo;

        if ($redirectTo) {
            return $redirectTo;
        } else {
            return RouteServiceProvider::HOME;
        }
    }

    /**
     * Redirect the user to the Provider (Facebook, Google, GitHub...) authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider (Facebook, Google, GitHub...).
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $authUser = $this->findOrCreateUser($user, $provider);

            Auth::login($authUser, true);
        } catch (Exception $e) {
            return redirect('/');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Return user if exists; create and return if doesn't.
     *
     * @param $githubUser
     *
     * @return User
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        if ($authUser = UserProvider::where('provider_id', $socialUser->getId())->first()) {
            $authUser = User::findOrFail($authUser->user->id);

            return $authUser;
        } elseif ($authUser = User::where('email', $socialUser->getEmail())->first()) {
            UserProvider::create([
                'user_id'     => $authUser->id,
                'provider_id' => $socialUser->getId(),
                'avatar'      => $socialUser->getAvatar(),
                'provider'    => $provider,
            ]);

            return $authUser;
        } else {
            $name = $socialUser->getName();

            $name_parts = $this->split_name($name);
            $first_name = $name_parts[0];
            $last_name = $name_parts[1];
            $email = $socialUser->getEmail();

            if ($email == '') {
                Log::error('Social Login does not have email!');

                flash('Email address is required!')->error()->important();

                return redirect()->intended(RouteServiceProvider::HOME);
            }

            $user = User::create([
                'first_name'  => $first_name,
                'last_name'   => $last_name,
                'name'        => $name,
                'email'       => $email,
            ]);

            //USER AVATAR REMOVE
            // $media = $user->addMediaFromUrl($socialUser->getAvatar())->toMediaCollection('users');
            $user->avatar = "img/default-avatar.jpg";
            
            $userprofil = $this->createUserProfile($user);

            UserProvider::create([
                'user_id'     => $user->id,
                'provider_id' => $socialUser->getId(),
                'avatar'      => $socialUser->getAvatar(),
                'provider'    => $provider,
            ]);

            return $user;
        }
    }

    /**
     * Split Name into first name and last name.
     */
    public function split_name($name)
    {
        $name = trim($name);

        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#'.$last_name.'#', '', $name));

        return [$first_name, $last_name];
    }


    /**
     * Create the profile
     */
    public function createUserProfile($user){

        $userprofile = new Userprofile();
        $userprofile->user_id = $user->id;
        $userprofile->name = $user->name;
        $userprofile->first_name = $user->first_name;
        $userprofile->last_name = $user->last_name;
        $userprofile->address = $user->address;
        $userprofile->username = $user->username;
        $userprofile->email = $user->email;
        $userprofile->mobile = $user->mobile;
        $userprofile->gender = $user->gender;
        $userprofile->date_of_birth = $user->date_of_birth;
        $userprofile->avatar = $user->avatar;
        $userprofile->status = ($user->status > 0) ? $user->status : 0;
        $userprofile->save();

        return $userprofile;
    }
}

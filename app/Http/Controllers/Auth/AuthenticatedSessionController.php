<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $redirectTo = request()->redirectTo;

        if (Auth::check()) {
            if (env('EMAIL_MUST_VERIFY') == 'true'){
                if(!Auth::user()->email_verified_at && !Auth::user()->can('view_backend')){
                    
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    Flash::error("<i class='fas fa-times-circle'></i> Silakan konfirmasi email anda terlebih dahulu. Kami telah mengirimkan konfirmasi ke alamat email anda.")->important();
                    
                    return redirect('/login');
                }
            }

            $canViewBackend = Auth::user()->can('view_backend'); 

            if($canViewBackend){
                $redirectTo = '/admin';
            }
        }

        if ($redirectTo) {
            return redirect($redirectTo);
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

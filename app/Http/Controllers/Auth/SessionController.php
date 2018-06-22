<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Config, Log};
use Validator;
use App\Libs\Ldap;
use App\Models\User;

class SessionController extends Controller {

    public function login(Request $request)
    {
        return view('auth/session/new');
    }

    public function create(Request $request)
    {   
        $validator = Validator::make(
            $request->all(),
            [
                'auth.login' => 'required',
                'auth.password' => 'required',
            ],
            [
                'auth.login.required' => 'Bitte geben Sie ihren Benutzername ein.',
                'auth.password.required' => 'Bitte geben Sie ihr Passwort ein.'
            ]
        );
        
        $validator->after(function($validator) use ($request) {
            $login = $request->input('auth.login');

            if (Ldap::authenticate($login, $request->input('auth.password'))) {

                $user = User::where('login', $login)->first();
                if (empty($user)) {
                    $validated_data = $validator->valid();
                    $validated_data = $validated_data['auth'];

                    $validated_data['name'] = $this->get_ldap_user_name($login);
                    $validated_data['remember_token'] = str_random(32);

                    User::insert($validated_data);

                    $user = User::where('login', $login)->first();
                } else {

                }

                Auth::login($user, !empty($request->input('remember')));

            } else {
                $config = Ldap::get_config();
                $error_msg = "Das Login und/oder das Password ist/sind falsch.";
                $validator->errors()->add('login', $error_msg);
                $request->session()->flash('flash_error', $error_msg);
            }
        });

        if ($validator->fails()) {
            return redirect()
                ->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        //$request->session()->regenerate();
        $request->session()->flash('flash_notice', 'Sie sind nun eingeloggt.');
        return redirect()
                ->route('root')
                ->withInput();
    }

    public function delete(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->flush();
        }
        $request->session()->flash('flash_notice', 'Sie sind nun ausgeloggt.');
        return redirect()->route('login');
    }

    protected function get_ldap_user_name($login) 
    {
        $result = null;
        $user_info = Ldap::get_user_info($login);
        if (!empty($user_info[0]['cn'][0])) {
            $result = $user_info[0]['cn'][0];
        }
        return $result;
    }
}

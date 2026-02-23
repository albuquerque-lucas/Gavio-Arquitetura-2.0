<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');

            $validator = Validator::make($credentials, [
                'username' => 'required|string',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (Auth::attempt($credentials)) {
                return redirect()->intended(route('admin.home'));
            }

            return redirect()->back()->withErrors(['username' => 'Credenciais inválidas'])->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocorreu um erro durante o login.'])->withInput();
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $data = $request->only('name', 'username', 'email', 'password', 'password_confirmation');

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // dd($user);
            $user = User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Auth::login($user);

            return redirect()->intended(route('admin.home'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ocorreu um erro durante o registro.'])->withInput();
        }
    }

    public function logout()
    {
        try {
            Auth::logout();

            return redirect()->route('login');
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Ocorreu um erro durante o logout.']);
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Ocorreu um erro ao enviar o link de redefinição de senha.']);
        }
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();

                    $user->setRememberToken(Str::random(60));

                    Auth::login($user);
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Ocorreu um erro ao redefinir a senha.']);
        }
    }
}

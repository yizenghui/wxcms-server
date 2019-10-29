<?php

namespace Encore\Admin\Registration\Http\Controllers;

use Encore\Admin\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends AuthController
{
    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        $view = config('admin.registration.views.login', 'registration::login');

        return view($view);
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make(
            $request->only([$this->username(), 'password', 'captcha']),
            [
                $this->username()   => 'required',
                'password'          => 'required',
                'captcha'           => 'required|captcha',
            ]
        );

        $credentials = $request->only([$this->username(), 'password']);
        $remember = $request->get('remember', false);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'email';
    }
}
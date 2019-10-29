<?php

namespace Encore\Admin\Registration\Http\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Encore\Admin\Registration\Events\Verified;
use Encore\Admin\Widgets\Callout;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    use RedirectsUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectTo()
    {
        return config('admin.route.prefix');
    }

    /**
     * Show the email verification notice.
     *
     * @param Content $content
     * @return Content|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show(Content $content)
    {
        if ($this->guard()->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $view = config('admin.registration.views.verify', 'registration::verify');

        return $content
            ->header(trans('registration.verify_email'))
            ->description(' ')
            ->body(view($view));
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if ($request->route('id') != $this->guard()->user()->getKey()) {
            throw new AuthorizationException;
        }

        if ($this->guard()->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($this->guard()->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($this->guard()->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $this->guard()->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}

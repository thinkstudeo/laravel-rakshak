<?php

namespace Thinkstudeo\Guardian\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Thinkstudeo\Guardian\Guardian;
use Thinkstudeo\Guardian\Settings;
use Illuminate\Support\Facades\Auth;

class VerifyTwoFactorOtp
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return void
     */
    public function handle($request, Closure $next)
    {
        if (config('guardian.enable_2fa')) {
            $user = Auth::user();
            if ($user->otp_expiry > Carbon::now()) {
                return $next($request);
            }

            $user->otp_token = Guardian::generateOtp();
            $user->save();

            Guardian::sendOtp($user);

            return redirect(Guardian::verifyOtpPath());
        }

        return $next($request);
    }
}
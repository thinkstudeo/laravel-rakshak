<?php

namespace Thinkstudeo\Rakshak\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Thinkstudeo\Rakshak\Rakshak;
use Thinkstudeo\Rakshak\RakshakSetting;

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
        if ($this->is2faEnabled()) {
            $user = Auth::user();
            if ($user->otp_expiry > Carbon::now()) {
                return $next($request);
            }

            $user->otp_token = Rakshak::generateOtp();
            $user->save();

            Rakshak::sendOtp($user);

            return redirect(Rakshak::verifyOtpPath());
        }

        return $next($request);
    }

    /**
     * Determine whether the 2fa is enabled for the current user.
     *
     * @return bool
     */
    private function is2faEnabled()
    {
        $enabled = config('rakshak.enable_2fa');
        // $controlLevel = RakshakSetting::first()->control_level_2fa;
        $controlLevel = Cache::get('rakshak.control_level_2fa');

        if ($enabled && $controlLevel === 'admin') {
            return true;
        }

        if ($enabled && $controlLevel === 'user') {
            return Auth::user()->enable_2fa;
        }
    }
}

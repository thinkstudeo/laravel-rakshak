<?php

namespace Thinkstudeo\Guardian\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TwoFactorController extends Controller
{
    /**
     * Show the form to confirm the otp.
     *
     * @return View
     */
    public function showOtpForm()
    {
        return view('guardian::otp.verify');
    }

    /**
     * Verify the otp entered by the user with the value from the database.
     * When verified redirect the user to the intended page.
     *
     * @param Request $request
     * @return void
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);
        $user = auth()->user();
        if ($request->otp === $user->otp_token)
        {
            $user->otp_expiry = Carbon::now()->addMinutes(config('session.lifetime'));
            $user->save();

            return redirect($request->session()->previousUrl());
        }
    }
}

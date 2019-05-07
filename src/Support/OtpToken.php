<?php

namespace Thinkstudeo\Guardian\Support;

class OtpToken
{
    /**
     * Generate a random 6 digit otp.
     *
     * @return void
     */
    public static function generate()
    {
        return mt_rand(100000, 999999);
    }
}
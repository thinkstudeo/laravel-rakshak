@component('mail::message')
# Please verify it's you.

There has been an attempt to login to your account.

To verify that you have initiated the attempt, please enter the below OTP

OTP : {{$user->otp_token}}

However, if you have not attempted this login. Please reset your password immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

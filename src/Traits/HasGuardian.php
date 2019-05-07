<?php

namespace Thinkstudeo\Guardian\Traits;

trait HasGuardian
{
    use HasRoles;

    /**
     * Route notifications for the Mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->mobile;
    }

    /**
     * Route notifications for the Textlocal channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForTextlocal($notification)
    {
        return $this->mobile;
    }

    /**
     * Path to the User resource.
     *
     * @return string
     */
    public function path()
    {
        return "/" . config('guardian.route_prefix') . "/users/{$this->{$this->getRouteKeyName()}}";
    }
}
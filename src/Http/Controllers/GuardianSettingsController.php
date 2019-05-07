<?php

namespace Thinkstudeo\Guardian\Http\Controllers;

use Thinkstudeo\Guardian\GuardianSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GuardianSettingsController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $setting = GuardianSetting::first();
        $this->authorize('update', $setting);

        return view('guardian::settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $setting = GuardianSetting::first();
        $this->authorize('update', $setting);

        $validated = $request->validate([
            'enable_2fa' => ['required', 'boolean'],
            'channel_2fa' => ['required', 'in:email,sms'],
            'control_level_2fa' => ['required', 'in:admin,user']
        ]);

        $setting = tap($setting)->update($validated);
        $this->updateCache($setting);

        if ($request->expectsJson()) {
            return response(['message' => 'Guardian settings updated successfully!', 'record' => $setting], 200);
        }

        return redirect()
            ->back()
            ->with("status", "success")
            ->with("message", "Guardian settings updated successfully.");
    }

    /**
     * Update the values for the cache keys.
     *
     * @param GuardianSetting $setting
     * @return void
     */
    protected function updateCache($setting)
    {
        Cache::forever('guardian.enable_2fa', $setting->enable_2fa);
        Cache::forever('guardian.channel_2fa', $setting->channel_2fa);
        Cache::forever('guardian.control_level_2fa', $setting->control_level_2fa);
    }
}
<?php

namespace Thinkstudeo\Rakshak\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Thinkstudeo\Rakshak\RakshakSetting;

class RakshakSettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $setting = RakshakSetting::first();
        $this->authorize('update', $setting);

        return view('rakshak::settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $setting = RakshakSetting::first();
        $this->authorize('update', $setting);

        $validated = $request->validate([
            'enable_2fa' => ['required', 'boolean'],
            'channel_2fa' => ['required', 'in:email,sms'],
            'control_level_2fa' => ['required', 'in:admin,user'],
        ]);

        $setting = tap($setting)->update($validated);
        $this->updateCache($setting);

        if ($request->expectsJson()) {
            return response(['message' => 'Rakshak settings updated successfully!', 'record' => $setting], 200);
        }

        return redirect()
            ->back()
            ->with('status', 'success')
            ->with('message', 'Rakshak settings updated successfully.');
    }

    /**
     * Update the values for the cache keys.
     *
     * @param RakshakSetting $setting
     * @return void
     */
    protected function updateCache($setting)
    {
        Cache::forever('rakshak.enable_2fa', $setting->enable_2fa);
        Cache::forever('rakshak.channel_2fa', $setting->channel_2fa);
        Cache::forever('rakshak.control_level_2fa', $setting->control_level_2fa);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingsService;
use App\Traits\FileUploadTrait;
use Cache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use FileUploadTrait;

    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index(): View
    {
        return view('admin.setting.index');
    }

    public function UpdateGeneralSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'site_name' => ['required', 'max:255'],
            'site_email' => ['nullable', 'max:255'],
            'site_phone' => ['nullable', 'max:255'],
            'site_default_currency' => ['required', 'max:4'],
            'site_currency_icon' => ['required', 'max:4'],
            'site_currency_icon_position' => ['required', 'max:255'],
        ]);

        $this->saveSettings($validatedData);

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }

    public function UpdatePusherSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'pusher_app_id' => ['required'],
            'pusher_key' => ['required'],
            'pusher_secret' => ['required'],
            'pusher_cluster' => ['required'],
        ]);

        $this->saveSettings($validatedData);

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }

    public function UpdateMailSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'mail_driver' => ['required'],
            'mail_host' => ['required'],
            'mail_port' => ['required'],
            'mail_username' => ['required'],
            'mail_password' => ['required'],
            'mail_encryption' => ['required'],
            'mail_from_address' => ['required'],
            'mail_receive_address' => ['required'],
        ]);

        $this->saveSettings($validatedData);

        Cache::forget('mail_settings');

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }

    public function UpdateLogoSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'logo' => ['nullable', 'image', 'max:1000'],
            'footer_logo' => ['nullable', 'image', 'max:1000'],
            'favicon' => ['nullable', 'image', 'max:1000'],
            'breadcrumb' => ['nullable', 'image', 'max:1000'],
        ]);

        foreach ($validatedData as $key => $value) {
            $imagePath = $this->uploadImage($request, $key);
            if ($imagePath !== null) {
                $oldPath = config('settings.' . $key);
                if ($oldPath) {
                    $this->removeImage($oldPath);
                }

                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $imagePath]
                );
            }
        }

        $this->settingsService->clearCachedSettings();
        Cache::forget('mail_settings');

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }

    public function UpdateAppearanceSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'site_color' => ['required']
        ]);

        $this->saveSettings($validatedData);

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }

    public function UpdateSeoSetting(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'seo_title' => ['required', 'max:255'],
            'seo_description' => ['nullable', 'max:600'],
            'seo_keywords' => ['nullable']
        ]);

        $this->saveSettings($validatedData);

        toastr()->success('Updated Successfully!');

        return redirect()->back();
    }

    private function saveSettings(array $validatedData): void
    {
        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->settingsService->clearCachedSettings();
    }
}

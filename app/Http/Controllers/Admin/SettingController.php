<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(string $group = 'general', SettingsService $settings): View
    {
        $groups = config('crm.groups', []);
        abort_unless(array_key_exists($group, $groups), 404);

        $currentGroup = $groups[$group];
        $currentGroupKey = $group;

        return view('admin.settings.index', compact('groups', 'currentGroup', 'currentGroupKey', 'settings'));
    }

    public function update(Request $request, string $group, SettingsService $settings, MediaService $media): RedirectResponse
    {
        $groups = config('crm.groups', []);
        abort_unless(array_key_exists($group, $groups), 404);

        $data = $request->all();

        // Handle file uploads for fields of type 'image'
        foreach ($groups[$group]['fields'] ?? [] as $fieldKey => $fieldConfig) {
            if ($fieldConfig['type'] === 'image' && $request->hasFile($fieldKey)) {
                $file = $request->file($fieldKey);
                $path = $media->storePath($file, 'settings');
                $data[$fieldKey] = $path;
            }
        }

        $settings->save($group, $data);

        return back()->with('success', 'Settings updated successfully.');
    }
}

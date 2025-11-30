<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function toggleEmailVerification(Request $request)
    {
        $enabled = $request->boolean('enabled') ? 'true' : 'false';
        
        SystemSetting::updateOrCreate(
            ['key' => 'email_verification_enabled'],
            ['value' => $enabled, 'description' => 'Enable or disable email verification requirement']
        );

        return response()->json(['success' => true, 'enabled' => $enabled]);
    }
}

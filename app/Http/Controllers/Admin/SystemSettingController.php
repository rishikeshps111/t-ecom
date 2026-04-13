<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SystemSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware(PermissionMiddleware::using('system-setting.view'), ['index']),
            new Middleware(PermissionMiddleware::using('system-setting.edit'),  ['store']),
        ];
    }
    public function index()
    {
        return view('admin.system-setting.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'financial_year' => 'required|string|max:20',
        ], [
            'financial_year.required' => 'The Financial Year field is required.',
        ]);

        $setting = SystemSetting::updateOrCreate(
            ['key' => 'financial_year'],
            ['value' => $request->financial_year]
        );

        activity()
            ->causedBy(Auth::id())
            ->performedOn($setting)
            ->log('Settings Updated');

        return redirect()->back()->with('success', 'System setting saved successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function login_submit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        $remember = $request->filled('remember');
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], $remember)) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()
                    ->withErrors([
                        'email' => 'Your account has been deactivated. Please contact the administrator.'
                    ])
                    ->withInput($request->only('email'));
            }

            if ($user->is_locked) {
                return back()->withErrors([
                    'email' => 'Your account is locked. Reason: ' . $user->locked_reason
                ])
                    ->withInput($request->only('email'));
            }
            return redirect()->route('admin.dashboard');
        }
        return back()
            ->withErrors([
                'email' => 'Invalid email or password.'
            ])
            ->withInput($request->only('email'));
    }


    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to admin login page
        return redirect()->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone' => [
                'required',
                'regex:/^\+60\d{8,10}$/'
            ],
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name  = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        if ($request->hasFile('profile_image')) {

            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $image     = $request->file('profile_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/profile'), $imageName);

            $user->profile_image = 'uploads/profile/' . $imageName;
        }

        $user->save();
        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => bcrypt($request->new_password),
            'show_password' => $request->new_password
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}

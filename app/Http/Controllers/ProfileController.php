<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // 1. RENDER PROFILE PAGE
    public function index()
    {
        return view('myprofile', ['user' => Auth::user()]);
    }

    // 2. UPDATE USERNAME & EMAIL
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        // Laravel automatically saves the changes through Eloquent
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Profile info updated successfully!'
        ]);
    }

    // 3. UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Password updated successfully!'
        ]);
    }

    // 4. HANDLE AVATAR FILE UPLOAD
    public function updateAvatar(Request $request) 
{
    // 1. Validate that it's actually an image file
    $request->validate([
        'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // 2. Safely get the logged-in user's ID from Session or Auth
    $user_id = session('user_id') ?? session('user')->id ?? auth()->id();
    
    if (!$user_id) {
        return redirect()->to('/login')->with('toast', ['type' => 'danger', 'message' => 'Session expired. Please log in again.']);
    }

    if ($request->hasFile('profile_pic')) {
        $file = $request->file('profile_pic');
        
        // 3. Generate a clean, unique name for the file
        $filename = 'avatar_' . $user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // 4. Move the file physically inside your public/uploads/ folder
        $file->move(public_path('uploads'), $filename);

        // 5. THE FIX: Update your Database row directly using standard SQL
        // NOTE: Make sure your table name is 'users' and your primary key column is 'id'
        \DB::update("UPDATE users SET profile_pic = ? WHERE id = ?", [$filename, $user_id]);

        // 6. IF you store the user data inside a session variable, update it here too!
        if (session()->has('user')) {
            $userSession = session('user');
            if (is_object($userSession)) {
                $userSession->profile_pic = $filename;
            } elseif (is_array($userSession)) {
                $userSession['profile_pic'] = $filename;
            }
            session(['user' => $userSession]);
        }

        return redirect()->back()->with('toast', ['type' => 'success', 'message' => 'Profile image updated successfully!']);
    }

    return redirect()->back()->with('toast', ['type' => 'danger', 'message' => 'No image file discovered.']);
}
    public function showProfile()
    {
        $user_id = session('user_id') ?? auth()->id();
        if (!$user_id) {
            return redirect()->to('/login');
        }

        // ALWAYS pull the latest row from the database so the new profile_pic filename is included
        $user = \DB::selectOne("SELECT * FROM users WHERE id = ?", [$user_id]);

        // Pass the $user object directly into your view
        return view('profile', compact('user'));
    }
}
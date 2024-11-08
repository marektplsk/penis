<?php
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    // Method to show the user's profile
    public function showProfile()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    // Method to show the edit profile form
    public function editProfile()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    // Method to update the profile
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
    public function showRegistrationForm()
    {
        return view('auth.register'); // Make sure you have a view file for registration
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Optionally, log the user in automatically after registration
        auth()->login($user);

        // Redirect to the named route for /app after registration
        Cookie::queue('was_logged_in', true, 60); // expires in 60 minutes
        return redirect()->route('app.index')->with('success', 'Registration successful! You are now logged in.');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Make sure you have a view file for login
    }

    // Handle the login logic
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Use "remember" checkbox if provided
        $remember = $request->has('remember');

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            // Redirect to the named route for /app after successful login
            Cookie::queue('was_logged_in', true, 60);
            return redirect()->route('app.index')->with('success', 'You are logged in.');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}

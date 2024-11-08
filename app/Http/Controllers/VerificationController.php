<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerificationController extends Controller
{
    // Show the email verification notice
    public function showVerificationNotice()
    {
        return view('auth.verify');  // Create a view that tells the user to verify their email
    }

    // Handle email verification
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Check if the hash matches the user's verification hash
        if (! hash_equals((string) $hash, (string) $user->getEmailVerificationHash())) {
            return redirect()->route('home')->withErrors('The verification link is invalid.');
        }

        // If the user has already verified their email
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('status', 'Your email is already verified.');
        }

        // Mark the email as verified and trigger the Verified event
        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('app.index')->with('status', 'Your email has been verified!');
    }

    // Resend the verification email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent!');
    }
}

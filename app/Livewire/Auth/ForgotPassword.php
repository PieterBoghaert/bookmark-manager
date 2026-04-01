<?php

namespace App\Livewire\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.guest')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    public string $email = '';
    public bool $emailSent = false;

    public function sendPasswordResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Find the user
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'No user found with this email address.');
            return;
        }

        // Generate a token
        $token = Str::random(64);

        // Store the token in the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );

        // Create the reset link
        $resetLink = url('/reset-password') . '?token=' . $token . '&email=' . urlencode($this->email);

        // Send the email
        Mail::send(new ResetPasswordMail($resetLink, $user->name));

        $this->emailSent = true;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}

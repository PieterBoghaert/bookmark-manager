<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.guest')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    #[Url]
    public string $token = '';

    #[Url]
    public string $email = '';

    public string $password = '';
    public string $password_confirmation = '';
    public string $errorMessage = '';

    public function mount()
    {
        // Validate the token and email
        if (!$this->token || !$this->email) {
            $this->errorMessage = 'Invalid password reset link.';
            return;
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        if (!$resetRecord) {
            $this->errorMessage = 'Invalid password reset link.';
            return;
        }

        // Check if token is valid (not expired - 60 minutes)
        if (!hash_equals(hash('sha256', $this->token), $resetRecord->token)) {
            $this->errorMessage = 'Invalid password reset link.';
            return;
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            $this->errorMessage = 'Password reset link has expired. Please request a new one.';
            return;
        }
    }

    public function resetPassword()
    {
        // Re-validate token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        if (!$resetRecord || !hash_equals(hash('sha256', $this->token), $resetRecord->token)) {
            $this->errorMessage = 'Invalid password reset link.';
            return;
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            $this->errorMessage = 'Password reset link has expired. Please request a new one.';
            return;
        }

        // Validate input
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find and update user
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->errorMessage = 'User not found.';
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Delete the reset token
        DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->delete();

        // Redirect to login with success message
        session()->flash('status', 'Your password has been reset successfully. You can now log in.');
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}

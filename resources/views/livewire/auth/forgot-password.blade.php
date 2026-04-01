<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 flex flex-col gap-8">

    <div class="flex gap-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
            <g clip-path="url(#clip0_584_20279)">
                <rect x="7" y="5" width="18" height="23" fill="white" />
                <path d="M11.7358 11.7565C11.7358 11.315 11.7358 10.3944 13.7493 10.3944H18.2457C20.2576 10.3944 20.2576 11.3282 20.2576 11.8877L20.2609 21.2038L16.6244 19.1476C16.2503 18.9359 15.7924 18.9359 15.4183 19.146L11.7358 21.2104V11.7565Z" fill="#014745" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M22.9187 31.5898H9.0783C3.89266 31.5898 0.4104 27.945 0.4104 22.5215V9.47859C0.4104 4.055 3.89266 0.410278 9.0783 0.410278H22.9203C28.106 0.410278 31.5899 4.055 31.5899 9.47859V22.5215C31.5899 27.945 28.1043 31.5898 22.9187 31.5898ZM9.27478 22.1655C9.27478 23.2141 10.1281 24.0675 11.1767 24.0675C11.5082 24.0675 11.8348 23.9805 12.1072 23.8246L16.0177 21.6322L19.8905 23.8213C20.8029 24.3366 21.9664 24.0051 22.4833 23.0878C22.6392 22.8039 22.7229 22.4839 22.7229 22.1622L22.7197 11.8878C22.7197 9.41148 21.0474 7.93292 18.2462 7.93292H13.7498C10.9896 7.93292 9.27478 9.39836 9.27478 11.7565V22.1655Z" fill="#014745" />
            </g>
            <defs>
                <clipPath id="clip0_584_20279">
                    <rect width="32" height="32" fill="white" />
                </clipPath>
            </defs>
        </svg>
        <span class="text-teal-700 font-bold text-xl">
            Bookmark Manager
        </span>
    </div>

    <h1>Forgot Your Password?</h1>

    @if ($emailSent)
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        Check your email for a link to reset your password.
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-4">

        <div class="flex flex-col gap-1.5">
            <label for="email">
                Email Address
            </label>
            <input
                type="email"
                id="email"
                wire:model="email"
                required
                @if ($emailSent) disabled @endif>
            @error('email')
            <span class="error mt-1">{{ $message }}</span>
            @enderror
        </div>

        <button
            type="submit"
            class="btn"
            @if ($emailSent) disabled @endif>
            Send Password Reset Link
        </button>

    </form>

    <p class="text-center">
        Remember your password?
        <a href="/login" wire:navigate>
            Log in
        </a>
    </p>
</div>
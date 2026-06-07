<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold font-heading text-slate-900">Reset Password</h2>
        <p class="text-sm text-slate-500 mt-1">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="label-field">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input-field" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-colors">
                Email Password Reset Link
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-center border-t border-slate-100 dark:border-slate-800 pt-4">
        <a href="{{ route('login') }}" class="text-sm font-medium text-brand hover:text-brand-light transition-colors">
            &larr; Back to login
        </a>
    </div>
</x-guest-layout>

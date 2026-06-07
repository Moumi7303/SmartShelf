<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold font-heading text-slate-900">Sign In</h2>
        <p class="text-sm text-slate-500 mt-1">Enter your details to access your account.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="label-field">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input-field" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="label-field !mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-brand hover:text-brand-light transition-colors" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="input-field" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand" name="remember">
            <label for="remember_me" class="ml-2 block text-sm text-slate-600">
                Keep me logged in
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-colors">
                Sign in
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-sm text-slate-600">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-medium text-brand hover:text-brand-light transition-colors">Apply for membership</a>
        </p>
    </div>
</x-guest-layout>

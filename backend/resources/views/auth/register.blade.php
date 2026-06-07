<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold font-heading text-slate-900">Apply for Membership</h2>
        <p class="text-sm text-slate-500 mt-1">Join SmartShelf and access thousands of books.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="label-field">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input-field" placeholder="John Doe">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="label-field">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input-field" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="label-field">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="input-field" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="label-field">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input-field" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>
        
        <!-- Note -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-3 mt-4 text-xs text-blue-700 dark:text-blue-400">
            Note: You will need to visit a branch to finalize your membership and get full borrowing privileges.
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand hover:bg-brand-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-colors">
                Apply Now
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-center border-t border-slate-100 dark:border-slate-800 pt-4">
        <p class="text-sm text-slate-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-medium text-brand hover:text-brand-light transition-colors">Sign in</a>
        </p>
    </div>
</x-guest-layout>

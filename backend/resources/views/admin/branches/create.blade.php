<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.branches.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Create Branch
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card">
            <form action="{{ route('admin.branches.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="label-field">Branch Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input-field">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="code" class="label-field">Branch Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required class="input-field">
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="label-field">Address</label>
                        <textarea name="address" id="address" rows="3" class="input-field">{{ old('address') }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="label-field">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="input-field">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="phone" class="label-field">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="input-field">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <label for="manager_id" class="label-field">Branch Manager</label>
                        <select name="manager_id" id="manager_id" class="input-field">
                            <option value="">Select Manager</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="status" class="label-field">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="input-field">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-4">
                    <a href="{{ route('admin.branches.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        Create Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.members.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                {{ isset($member) ? 'Edit Member: ' . $member->user->name : 'Register New Member' }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card">
            <form action="{{ isset($member) ? route('admin.members.update', $member) : route('admin.members.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                @if(isset($member))
                    @method('PUT')
                @endif

                @if(!isset($member))
                    <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Registering a member here will also create their user account automatically. They will be sent a welcome email with instructions to set their password.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- User Account Details -->
                    <div>
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">User Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="label-field">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input-field" placeholder="Jane Doe">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            
                            <div>
                                <label for="email" class="label-field">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="input-field" placeholder="jane@example.com">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label for="phone" class="label-field">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="input-field" placeholder="+1 (555) 000-0000">
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <label for="branch_id" class="label-field">Home Branch <span class="text-red-500">*</span></label>
                                <select name="branch_id" id="branch_id" required class="input-field">
                                    <option value="">Select Branch...</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Membership Details -->
                <div class="pt-2 {{ !isset($member) ? 'border-t border-slate-200 dark:border-slate-700' : '' }}">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Membership Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(isset($member))
                            <div class="md:col-span-2">
                                <label class="label-field">Membership ID</label>
                                <input type="text" disabled value="{{ $member->membership_id }}" class="input-field bg-slate-100 dark:bg-slate-800 text-slate-500 font-mono">
                            </div>
                        @endif

                        <div>
                            <label for="student_id" class="label-field">Student/Staff ID (Optional)</label>
                            <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $member->student_id ?? '') }}" class="input-field" placeholder="e.g. STU-2023-001">
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="department" class="label-field">Department (Optional)</label>
                            <input type="text" name="department" id="department" value="{{ old('department', $member->department ?? '') }}" class="input-field" placeholder="e.g. Computer Science">
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>
                        
                        <div>
                            <label for="membership_status" class="label-field">Membership Status <span class="text-red-500">*</span></label>
                            <select name="membership_status" id="membership_status" required class="input-field">
                                <option value="active" {{ old('membership_status', $member->membership_status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ old('membership_status', $member->membership_status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                @if(isset($member))
                                    <option value="expired" {{ old('membership_status', $member->membership_status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('membership_status')" class="mt-2" />
                        </div>

                        <div>
                            <label for="expires_at" class="label-field">Expiry Date <span class="text-red-500">*</span></label>
                            <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', isset($member) ? $member->expires_at->format('Y-m-d') : now()->addYear()->format('Y-m-d')) }}" required class="input-field">
                            <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <label for="address" class="label-field">Residential Address (Optional)</label>
                    <textarea name="address" id="address" rows="3" class="input-field resize-y">{{ old('address', $member->address ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-4">
                    <a href="{{ route('admin.members.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        {{ isset($member) ? 'Update Member' : 'Register Member' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

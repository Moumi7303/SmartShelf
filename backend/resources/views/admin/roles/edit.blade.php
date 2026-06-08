<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.roles.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Edit Role: {{ $role->name }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="glass-card p-6">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Role Details</h3>
                
                @php
                    $isSystemRole = in_array($role->name, ['super_admin', 'branch_admin', 'librarian', 'student_member', 'guest_user']);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="label-field">Role Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="input-field @error('name') border-red-500 @enderror" required {{ $isSystemRole ? 'readonly' : '' }}>
                        @if($isSystemRole)
                            <p class="mt-1 text-xs text-amber-600">System role names cannot be changed.</p>
                        @endif
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="label-field">Description</label>
                        <textarea name="description" id="description" rows="3" class="input-field @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <div class="flex justify-between items-center mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Assign Permissions</h3>
                    @if($role->name !== 'super_admin')
                        <button type="button" onclick="document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = true)" class="text-sm text-brand hover:underline">Select All</button>
                    @endif
                </div>
                
                @if($role->name === 'super_admin')
                    <div class="bg-brand/10 border border-brand/20 p-4 rounded-lg mb-6">
                        <p class="text-brand font-medium">The super_admin role automatically has all permissions and they cannot be removed.</p>
                    </div>
                @endif

                <div class="space-y-6">
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg border border-slate-200 dark:border-slate-700">
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-3 uppercase tracking-wider text-sm">{{ ucfirst($module) }} Module</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($modulePermissions as $permission)
                                    <label class="flex items-start space-x-3 cursor-pointer group">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                class="w-4 h-4 text-brand bg-white border-slate-300 rounded focus:ring-brand dark:focus:ring-brand dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600 disabled:opacity-50"
                                                {{ (in_array($permission->id, old('permissions', $rolePermissions)) || $role->name === 'super_admin') ? 'checked' : '' }}
                                                {{ $role->name === 'super_admin' ? 'disabled' : '' }}>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover:text-brand transition-colors {{ $role->name === 'super_admin' ? 'opacity-70' : '' }}">{{ $permission->name }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.roles.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Role</button>
            </div>
        </form>
    </div>
</x-app-layout>

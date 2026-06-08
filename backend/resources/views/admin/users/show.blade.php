<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                User Profile
            </h2>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Profile Header -->
        <div class="glass-card flex flex-col md:flex-row items-center md:items-start p-6 md:p-8 gap-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4">
                @if($user->status === 'active')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Active</span>
                @elseif($user->status === 'inactive')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">Inactive</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400">Suspended</span>
                @endif
            </div>

            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-brand/10 flex items-center justify-center text-3xl font-bold text-brand ring-4 ring-white dark:ring-slate-800 shadow-md">
                    {{ $user->initials }}
                </div>
            </div>
            <div class="flex-1 text-center md:text-left space-y-2">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 pt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand/10 text-brand-dark dark:bg-brand/20 dark:text-brand-light border border-brand/20">
                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Role: {{ $user->role->name ?? 'None' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Branch: {{ $user->branch->name ?? 'None' }}
                    </span>
                </div>
            </div>
            <div class="flex-shrink-0 flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">Edit User</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Info -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 mb-4">Contact Details</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-medium text-slate-500 mb-1">Email Address</span>
                            <div class="text-sm font-medium text-slate-900 dark:text-white flex items-center">
                                <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <a href="mailto:{{ $user->email }}" class="text-brand hover:underline">{{ $user->email }}</a>
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-slate-500 mb-1">Phone Number</span>
                            <div class="text-sm font-medium text-slate-900 dark:text-white flex items-center">
                                <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $user->phone ?: 'Not provided' }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-slate-500 mb-1">Member Profile</span>
                            <div class="text-sm font-medium text-slate-900 dark:text-white flex items-center">
                                <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                @if($user->member)
                                    <a href="{{ route('admin.members.show', $user->member) }}" class="text-brand hover:underline">View Member Card</a>
                                @else
                                    <span class="text-slate-400 italic">Not registered as a library member</span>
                                @endif
                            </div>
                        </div>
                        <div class="pt-3 mt-3 border-t border-slate-100 dark:border-slate-700">
                            <span class="block text-xs font-medium text-slate-500 mb-1">Account Created</span>
                            <div class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $user->created_at->format('M d, Y') }} ({{ $user->created_at->diffForHumans() }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Permissions Tab -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 mb-4">Effective Permissions</h3>
                    @if($user->role && $user->role->permissions->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->role->permissions as $permission)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700/50 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                    {{ str_replace('.', ' • ', $permission->name) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 italic">This user currently has no active permissions.</p>
                    @endif
                </div>

                <!-- Recent Logins -->
                <div class="glass-card">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Recent Login Activity</h3>
                    </div>
                    @if($user->loginLogs && $user->loginLogs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date/Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">IP Address</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Device/Browser</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                    @foreach($user->loginLogs as $log)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                                {{ $log->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                                {{ $log->ip_address ?: 'Unknown' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-500 text-xs">
                                                {{ $log->user_agent ?: 'Unknown' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center text-sm text-slate-500">
                            No recent login activity found.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

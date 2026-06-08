<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.branches.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Branch Details: {{ $branch->name }}
            </h2>
            <x-status-badge :status="$branch->status" />
            
            <div class="ml-auto flex space-x-2">
                @can('branches.edit')
                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn-secondary">Edit Details</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass-card p-6 border-l-4 border-brand">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Users</p>
                <p class="text-3xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="glass-card p-6 border-l-4 border-accent">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Books</p>
                <p class="text-3xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['total_copies']) }}</p>
            </div>
            <div class="glass-card p-6 border-l-4 border-emerald-500">
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Available Books</p>
                <p class="text-3xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['available']) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Branch Information</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Branch Code</dt>
                            <dd class="text-sm font-mono font-bold text-brand">{{ $branch->code }}</dd>
                        </div>
                        <div class="flex flex-col pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500 mb-1">Address</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $branch->address ?: 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Phone</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $branch->phone ?: 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Email</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $branch->email ?: 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <dt class="text-sm text-slate-500">Branch Manager</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $branch->manager?->name ?: 'Not Assigned' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Branch Staff</h3>
                    <span class="bg-brand/10 text-brand py-0.5 px-2 rounded-full text-xs font-bold">{{ collect($branch->users)->count() }}</span>
                </div>
                <div class="p-0 max-h-96 overflow-y-auto custom-scrollbar">
                    <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($branch->users as $user)
                            <li class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-center">
                                <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-sm font-bold text-slate-600 dark:text-slate-300 shrink-0">
                                    {{ $user->initials }}
                                </div>
                                <div class="ml-3 flex-1 overflow-hidden">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $user->role->name }}</p>
                                </div>
                                <div class="ml-2">
                                    <x-status-badge :status="$user->status" />
                                </div>
                            </li>
                        @empty
                            <li class="p-6 text-center text-sm text-slate-500">No staff assigned to this branch.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

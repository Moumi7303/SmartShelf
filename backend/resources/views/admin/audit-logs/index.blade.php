<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
            System Logs
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="glass-card p-4">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/5">
                    <label class="label-field">User</label>
                    <select name="user_id" class="input-field w-full" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="label-field">Action</label>
                    <select name="action" class="input-field w-full" onchange="this.form.submit()">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    </select>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="label-field">Module / Model</label>
                    <select name="model_type" class="input-field w-full" onchange="this.form.submit()">
                        <option value="">All Modules</option>
                        @foreach($modelTypes as $type)
                            @if($type)
                                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="label-field">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" onchange="this.form.submit()">
                </div>
                <div class="w-full md:w-1/5">
                    <label class="label-field">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" onchange="this.form.submit()">
                </div>
                
                <div class="flex items-end">
                    @if(request()->hasAny(['user_id', 'action', 'model_type', 'date_from', 'date_to']))
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn-secondary text-slate-500 hover:text-slate-700 w-full justify-center">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date/Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Module</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">IP Address</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-brand/10 flex items-center justify-center text-xs font-bold text-brand mr-3">
                                                {{ $log->user->initials }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $log->user->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $log->user->role->name ?? '' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-500 italic">System / Guest</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->action === 'created')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Created</span>
                                    @elseif($log->action === 'updated')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">Updated</span>
                                    @elseif($log->action === 'deleted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400">Deleted</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">{{ ucfirst($log->action) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                    <span class="font-semibold">{{ $log->model_name ?: 'N/A' }}</span>
                                    @if($log->model_id)
                                        <span class="text-slate-500 font-mono text-xs ml-1">#{{ $log->model_id }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                    {{ $log->ip_address ?: 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}" class="text-brand hover:text-brand-light">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">No system logs found matching the filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

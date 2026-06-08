<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.audit-logs.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Log Details #{{ $auditLog->id }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Event Info -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Event Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-3">
                        <span class="text-sm text-slate-500">Action</span>
                        <span class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-xs">{{ $auditLog->action }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-3">
                        <span class="text-sm text-slate-500">Module / Model</span>
                        <span class="font-bold text-brand">{{ $auditLog->model_type ?: 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-3">
                        <span class="text-sm text-slate-500">Record ID</span>
                        <span class="font-mono text-sm text-slate-900 dark:text-white">{{ $auditLog->model_id ?: 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm text-slate-500">Timestamp</span>
                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $auditLog->created_at->format('M d, Y H:i:s') }}</span>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Actor Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-3">
                        <span class="text-sm text-slate-500">User</span>
                        @if($auditLog->user)
                            <a href="{{ route('admin.users.show', $auditLog->user) }}" class="font-bold text-brand hover:underline">{{ $auditLog->user->name }}</a>
                        @else
                            <span class="text-sm italic text-slate-500">System / Guest</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-3">
                        <span class="text-sm text-slate-500">IP Address</span>
                        <span class="font-mono text-sm text-slate-900 dark:text-white">{{ $auditLog->ip_address ?: 'Unknown' }}</span>
                    </div>
                    <div class="flex flex-col pt-1">
                        <span class="text-sm text-slate-500 mb-1">User Agent</span>
                        <span class="text-xs text-slate-600 dark:text-slate-400 break-words">{{ $auditLog->user_agent ?: 'Unknown' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Changes -->
        @if($auditLog->old_values || $auditLog->new_values)
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Data Changes</h3>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-1/3">Field</th>
                                @if($auditLog->old_values)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-1/3">Old Value</th>
                                @endif
                                @if($auditLog->new_values)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-1/3">New Value</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @php
                                $keys = array_unique(array_merge(
                                    is_array($auditLog->old_values) ? array_keys($auditLog->old_values) : [],
                                    is_array($auditLog->new_values) ? array_keys($auditLog->new_values) : []
                                ));
                            @endphp
                            
                            @foreach($keys as $key)
                                @if(!in_array($key, ['created_at', 'updated_at', 'deleted_at', 'remember_token', 'password']))
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $key }}
                                        </td>
                                        
                                        @if($auditLog->old_values)
                                            <td class="px-6 py-4 text-sm text-rose-600 dark:text-rose-400 bg-rose-50/50 dark:bg-rose-900/10">
                                                @if(is_array($auditLog->old_values) && array_key_exists($key, $auditLog->old_values))
                                                    @if(is_array($auditLog->old_values[$key]))
                                                        <pre class="whitespace-pre-wrap font-mono text-xs">{{ json_encode($auditLog->old_values[$key], JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ (string) $auditLog->old_values[$key] ?: 'null' }}
                                                    @endif
                                                @else
                                                    <span class="text-slate-400 italic">-</span>
                                                @endif
                                            </td>
                                        @endif
                                        
                                        @if($auditLog->new_values)
                                            <td class="px-6 py-4 text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-900/10">
                                                @if(is_array($auditLog->new_values) && array_key_exists($key, $auditLog->new_values))
                                                    @if(is_array($auditLog->new_values[$key]))
                                                        <pre class="whitespace-pre-wrap font-mono text-xs">{{ json_encode($auditLog->new_values[$key], JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ (string) $auditLog->new_values[$key] ?: 'null' }}
                                                    @endif
                                                @else
                                                    <span class="text-slate-400 italic">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

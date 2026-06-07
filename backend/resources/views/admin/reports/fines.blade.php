<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.reports.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Financial Report
            </h2>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="glass-card mb-8 p-6">
        <form action="{{ route('admin.reports.fines') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="branch_id" class="label-field">Branch</label>
                <select name="branch_id" id="branch_id" class="input-field">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="start_date" class="label-field">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}" class="input-field">
            </div>
            <div>
                <label for="end_date" class="label-field">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" class="input-field">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Generate Report</button>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="glass-card p-4 border-l-4 border-slate-400">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Assessed</p>
            <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">${{ number_format($stats['total_assessed'], 2) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-emerald-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Collected</p>
            <p class="text-2xl font-bold font-heading text-emerald-600">${{ number_format($stats['total_collected'], 2) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-red-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Outstanding Balance</p>
            <p class="text-2xl font-bold font-heading text-red-600">${{ number_format($stats['total_outstanding'], 2) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-amber-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Waived</p>
            <p class="text-2xl font-bold font-heading text-amber-600">${{ number_format($stats['total_waived'], 2) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Fine Log</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date Assessed</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Reason</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount Assessed</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Balance Due</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($fines as $fine)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $fine->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                {{ $fine->member->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 capitalize">
                                {{ $fine->fine_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                ${{ number_format($fine->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                ${{ number_format($fine->remaining_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$fine->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">No financial data found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($fines->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $fines->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

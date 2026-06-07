<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.reports.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Circulation Report
            </h2>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="glass-card mb-8 p-6">
        <form action="{{ route('admin.reports.circulation') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
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
        <div class="glass-card p-4 border-l-4 border-brand">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Issues</p>
            <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['total_issues']) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-emerald-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Returns</p>
            <p class="text-2xl font-bold font-heading text-emerald-600">{{ number_format($stats['total_returns']) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-amber-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Renewals</p>
            <p class="text-2xl font-bold font-heading text-amber-600">{{ number_format($stats['total_renewals']) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-rose-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Overdue Items</p>
            <p class="text-2xl font-bold font-heading text-rose-600">{{ number_format($stats['overdue_count']) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Transaction Log</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Transaction Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Book Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-brand">
                                <a href="{{ route('admin.transactions.show', $transaction) }}">{{ $transaction->transaction_code }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                {{ Str::limit($transaction->bookCopy->book->title, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                {{ $transaction->member->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->return_date)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Return</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Issue</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$transaction->status" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">No circulation data found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

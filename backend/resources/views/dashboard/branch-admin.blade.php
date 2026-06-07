<x-app-layout>
    <x-slot name="header">
        Branch Dashboard: {{ Auth::user()->branch->name }}
    </x-slot>

    <div class="mb-8">
        <h2 class="text-2xl text-display font-bold text-slate-900 dark:text-white">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Here's the current status of your branch.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card title="Total Copies" :value="number_format($stats['total_copies'])" icon="copy" color="brand" />
        <x-stat-card title="Available Books" :value="number_format($stats['available_copies'])" icon="check-circle" color="green" />
        <x-stat-card title="Active Loans" :value="number_format($stats['active_loans'])" icon="refresh" color="purple" />
        <x-stat-card title="Branch Members" :value="number_format($stats['branch_members'])" icon="users" color="blue" />
    </div>

    <!-- System Alerts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="flex items-center p-6 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-800/50 shadow-sm">
            <div class="flex-shrink-0 bg-red-100 dark:bg-red-800 p-3 rounded-lg">
                <svg class="h-8 w-8 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-red-800 dark:text-red-300">Overdue Loans (Branch)</p>
                <p class="text-3xl font-bold font-heading text-red-900 dark:text-red-200">{{ $stats['overdue_loans'] }}</p>
            </div>
        </div>

        <div class="flex items-center p-6 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800/50 shadow-sm">
            <div class="flex-shrink-0 bg-amber-100 dark:bg-amber-800 p-3 rounded-lg">
                <svg class="h-8 w-8 text-amber-600 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-800 dark:text-amber-300">Unpaid Fines (Branch)</p>
                <p class="text-3xl font-bold font-heading text-amber-900 dark:text-amber-200">${{ number_format($stats['unpaid_fines'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="glass-card mt-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Recent Branch Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Transaction Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Book Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($recentTransactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-brand dark:text-brand-light">
                                <a href="{{ route('admin.transactions.show', $transaction) }}">{{ $transaction->transaction_code }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                        {{ $transaction->member->user->initials }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $transaction->member->user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ Str::limit($transaction->bookCopy->book->title, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$transaction->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                No recent transactions found in this branch.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

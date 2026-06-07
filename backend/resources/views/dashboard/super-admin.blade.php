<x-app-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div class="mb-8">
        <h2 class="text-2xl text-display font-bold text-slate-900 dark:text-white">Welcome back, {{ Auth::user()->name }}!</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Here's what's happening in your library network today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card title="Total Books" :value="number_format($stats['total_books'])" icon="book" color="brand" />
        <x-stat-card title="Total Branches" :value="$stats['total_branches']" icon="library" color="accent" />
        <x-stat-card title="Active Members" :value="number_format($stats['total_members'])" icon="users" color="blue" />
        <x-stat-card title="Active Loans" :value="number_format($stats['active_loans'])" icon="refresh" color="purple" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Circulation Chart -->
        <div class="lg:col-span-2 glass-card p-6">
            <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Circulation Trends (Last 6 Months)</h3>
            <div class="h-72">
                <canvas id="circulationChart"></canvas>
            </div>
        </div>

        <!-- System Alerts & Fines -->
        <div class="space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Attention Needed</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800/50">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-300">Overdue Loans</p>
                            <p class="text-2xl font-bold text-red-900 dark:text-red-200">{{ $stats['overdue_loans'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-100 dark:border-amber-800/50">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-amber-800 dark:text-amber-300">Pending Reservations</p>
                            <p class="text-2xl font-bold text-amber-900 dark:text-amber-200">{{ $stats['pending_reservations'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Financial Overview</h3>
                <dl class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                        <dt class="text-sm text-slate-500 dark:text-slate-400">Total Fines Collected</dt>
                        <dd class="text-sm font-medium text-emerald-600 dark:text-emerald-400">${{ number_format($stats['total_fine_collected'], 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-sm text-slate-500 dark:text-slate-400">Outstanding Fines</dt>
                        <dd class="text-sm font-medium text-rose-600 dark:text-rose-400">${{ number_format($stats['unpaid_fines'], 2) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="glass-card mt-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Recent Transactions</h3>
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
                                        <p class="text-xs text-slate-500">{{ $transaction->member->membership_id }}</p>
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
                                No recent transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('circulationChart').getContext('2d');
            const data = @json($monthlyData);
            
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#cbd5e1' : '#475569';
            const gridColor = isDarkMode ? '#334155' : '#e2e8f0';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.month),
                    datasets: [
                        {
                            label: 'Books Issued',
                            data: data.map(item => item.issued),
                            backgroundColor: '#004d40', // Brand primary
                            borderRadius: 4,
                        },
                        {
                            label: 'Books Returned',
                            data: data.map(item => item.returned),
                            backgroundColor: '#f57f17', // Accent
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: textColor }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: { color: textColor }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>

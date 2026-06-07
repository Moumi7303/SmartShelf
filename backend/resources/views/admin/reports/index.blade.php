<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Reports & Analytics
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Inventory Report -->
        <div class="glass-card hover:shadow-lg transition-shadow duration-300 flex flex-col h-full border-t-4 border-brand">
            <div class="p-6 flex-1">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-brand/10 text-brand mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-2">Inventory Report</h3>
                <p class="text-sm text-slate-500 mb-6">Comprehensive view of all library assets, including total books, active copies, status breakdown, and stock value across all branches.</p>
                
                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2 mb-6">
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Filter by Category & Branch</li>
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Asset Status Breakdown</li>
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> PDF & Excel Export</li>
                </ul>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 mt-auto">
                <a href="{{ route('admin.reports.inventory') }}" class="btn-primary w-full justify-center">Generate Report</a>
            </div>
        </div>

        <!-- Circulation Report -->
        <div class="glass-card hover:shadow-lg transition-shadow duration-300 flex flex-col h-full border-t-4 border-accent">
            <div class="p-6 flex-1">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-accent/10 text-accent mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-2">Circulation Report</h3>
                <p class="text-sm text-slate-500 mb-6">Analyze borrowing trends, identify popular titles, track issue/return rates, and monitor branch-specific activity over time.</p>
                
                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2 mb-6">
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Custom Date Ranges</li>
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Top Borrowed Titles</li>
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Overdue Analysis</li>
                </ul>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 mt-auto">
                <a href="{{ route('admin.reports.circulation') }}" class="btn-secondary text-accent border-accent/30 hover:bg-accent/10 w-full justify-center">Generate Report</a>
            </div>
        </div>

        <!-- Financial Report -->
        <div class="glass-card hover:shadow-lg transition-shadow duration-300 flex flex-col h-full border-t-4 border-emerald-500">
            <div class="p-6 flex-1">
                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-2">Financial Report</h3>
                <p class="text-sm text-slate-500 mb-6">Review revenue generated from fines, track outstanding balances, and audit waived fees across different date periods.</p>
                
                <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2 mb-6">
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Revenue Summaries</li>
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Outstanding Debtors List</li>
                    <li class="flex items-center"><svg class="h-4 w-4 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Payment Method Breakdown</li>
                </ul>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 mt-auto">
                <a href="{{ route('admin.reports.fines') }}" class="btn-secondary text-emerald-700 dark:text-emerald-400 border-emerald-300 dark:border-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 w-full justify-center">Generate Report</a>
            </div>
        </div>

    </div>

    <!-- Quick Stats Summary -->
    <div class="glass-card p-6">
        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-2">System Health at a Glance</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Total System Assets</p>
                <p class="text-3xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['total_books'] ?? 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Items Out on Loan</p>
                <p class="text-3xl font-bold font-heading text-brand dark:text-brand-light">{{ number_format($stats['active_loans'] ?? 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Items Overdue</p>
                <p class="text-3xl font-bold font-heading text-red-600">{{ number_format($stats['overdue_loans'] ?? 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Unpaid Fines</p>
                <p class="text-3xl font-bold font-heading text-amber-600">${{ number_format($stats['unpaid_fines'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
</x-app-layout>

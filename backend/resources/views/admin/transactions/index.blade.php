<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Recent Transactions
            </h2>
            @can('transactions.create')
                <a href="{{ route('admin.transactions.create') }}" class="btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Issue Books
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="glass-card mb-6 p-4">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member name, ID, book title, or barcode..." class="input-field pl-10">
            </div>
            
            <div class="w-full md:w-48">
                <select name="status" class="input-field" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued (Active)</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            
            <button type="submit" class="btn-secondary whitespace-nowrap">Filter</button>
            
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Transaction</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Book Copy</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dates</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-sm font-medium text-brand dark:text-brand-light hover:underline">
                                    {{ $transaction->transaction_code }}
                                </a>
                                <div class="text-xs text-slate-500 mt-1">{{ $transaction->branch->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                        {{ $transaction->member->user->initials }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $transaction->member->user->name }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $transaction->member->membership_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900 dark:text-white">{{ Str::limit($transaction->bookCopy->book->title, 40) }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-1">Barcode: {{ $transaction->bookCopy->barcode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-slate-500 mb-1">Out: {{ $transaction->issue_date->format('M d, Y') }}</div>
                                @if($transaction->return_date)
                                    <div class="text-xs text-emerald-600">In: {{ $transaction->return_date->format('M d, Y') }}</div>
                                @else
                                    <div class="text-xs font-medium {{ $transaction->due_date->isPast() ? 'text-red-600' : 'text-slate-700 dark:text-slate-300' }}">
                                        Due: {{ $transaction->due_date->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$transaction->status" />
                                @if($transaction->renewals_count > 0)
                                    <div class="text-xs text-slate-500 mt-1">Renewed {{ $transaction->renewals_count }}x</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-brand hover:text-brand-light">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">No transactions found matching your criteria.</td>
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

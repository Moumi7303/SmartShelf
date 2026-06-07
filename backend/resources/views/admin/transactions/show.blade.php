<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.transactions.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Transaction Details: {{ $transaction->transaction_code }}
            </h2>
            <x-status-badge :status="$transaction->status" />
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        
        <!-- Action Buttons -->
        @if(in_array($transaction->status, ['issued', 'overdue']))
            <div class="glass-card p-4 flex gap-4">
                @can('transactions.return')
                    <form action="{{ route('admin.transactions.return', $transaction) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-primary bg-emerald-600 hover:bg-emerald-700 border-emerald-600 py-3 justify-center text-lg shadow-sm" onclick="return confirm('Process return for this book?');">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Mark as Returned
                        </button>
                    </form>
                @endcan

                @can('transactions.renew')
                    <form action="{{ route('admin.transactions.renew', $transaction) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-secondary py-3 justify-center text-lg bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100 hover:border-blue-300 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800" onclick="return confirm('Renew this loan for another period?');" {{ $transaction->renewals_count >= config('smartshelf.circulation.max_renewals', 2) ? 'disabled' : '' }}>
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            Renew Loan ({{ $transaction->renewals_count }}/{{ config('smartshelf.circulation.max_renewals', 2) }})
                        </button>
                    </form>
                @endcan
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left: Transaction Info -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Timeline & Details</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Transaction ID</dt>
                            <dd class="text-sm font-mono font-bold text-slate-900 dark:text-white">{{ $transaction->transaction_code }}</dd>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Branch Location</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $transaction->branch->name }}</dd>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Issued Date</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $transaction->issue_date->format('F d, Y h:i A') }}</dd>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Due Date</dt>
                            <dd class="text-sm font-medium {{ $transaction->due_date->isPast() && !$transaction->return_date ? 'text-red-600 font-bold' : 'text-slate-900 dark:text-white' }}">
                                {{ $transaction->due_date->format('F d, Y') }}
                            </dd>
                        </div>
                        @if($transaction->return_date)
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700 bg-emerald-50 dark:bg-emerald-900/10 -mx-6 px-6 pt-4">
                                <dt class="text-sm text-emerald-800 dark:text-emerald-400 font-bold">Returned Date</dt>
                                <dd class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ $transaction->return_date->format('F d, Y h:i A') }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-2">
                            <dt class="text-sm text-slate-500">Processed By</dt>
                            <dd class="text-sm text-slate-900 dark:text-white">{{ $transaction->issuedBy->name ?? 'System' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Member Info -->
                <div class="glass-card">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Borrower Profile</h3>
                    </div>
                    <div class="p-6 flex items-start">
                        <div class="flex-shrink-0 h-14 w-14 rounded-full bg-brand/10 flex items-center justify-center border border-brand/20">
                            <span class="text-brand font-bold text-xl">{{ $transaction->member->user->initials }}</span>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $transaction->member->user->name }}</h4>
                            <p class="text-sm font-mono text-slate-500 mb-2">{{ $transaction->member->membership_id }}</p>
                            <a href="{{ route('admin.members.show', $transaction->member) }}" class="text-sm text-brand hover:text-brand-light font-medium">View Full Profile &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="glass-card">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Item Details</h3>
                    </div>
                    <div class="p-6 flex items-start">
                        <div class="h-20 w-14 bg-slate-200 dark:bg-slate-700 rounded overflow-hidden flex-shrink-0 border border-slate-300 dark:border-slate-600">
                            @if($transaction->bookCopy->book->cover_image)
                                <img src="{{ Storage::url($transaction->bookCopy->book->cover_image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-md font-bold text-slate-900 dark:text-white leading-tight mb-1">{{ $transaction->bookCopy->book->title }}</h4>
                            <p class="text-sm text-slate-500 mb-2">By {{ $transaction->bookCopy->book->author->name }}</p>
                            
                            <div class="bg-slate-50 dark:bg-slate-800 p-2 rounded text-xs mt-2 border border-slate-100 dark:border-slate-700">
                                <span class="text-slate-500 uppercase">Barcode:</span>
                                <span class="font-mono font-bold ml-1 text-slate-900 dark:text-white">{{ $transaction->bookCopy->barcode }}</span>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('admin.books.show', $transaction->bookCopy->book) }}" class="text-sm text-brand hover:text-brand-light font-medium">View Catalog Entry &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Fines (if any) -->
        @if($transaction->fine)
            <div class="glass-card overflow-hidden border-t-4 border-red-500">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-red-50/50 dark:bg-red-900/10 flex justify-between items-center">
                    <h3 class="text-lg font-bold font-heading text-red-800 dark:text-red-400 flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Associated Fine
                    </h3>
                    <x-status-badge :status="$transaction->fine->status" />
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Fine Reason</p>
                            <p class="font-bold text-slate-900 dark:text-white">{{ ucfirst($transaction->fine->fine_type) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Amount</p>
                            <p class="font-bold text-red-600 dark:text-red-400">${{ number_format($transaction->fine->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Paid Amount</p>
                            <p class="font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($transaction->fine->paid_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Balance Due</p>
                            <p class="font-bold text-red-600 dark:text-red-400 text-xl">${{ number_format($transaction->fine->remaining_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

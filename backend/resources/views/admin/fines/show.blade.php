<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.fines.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Fine Details (Tx: {{ $fine->transaction->transaction_code }})
            </h2>
            <x-status-badge :status="$fine->status" />
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Fine & Transaction Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Fine Summary</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 border-b border-slate-100 dark:border-slate-700 pb-8">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Amount</p>
                            <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">${{ number_format($fine->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Amount Paid</p>
                            <p class="text-2xl font-bold font-heading text-emerald-600">${{ number_format($fine->paid_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Balance Due</p>
                            <p class="text-2xl font-bold font-heading text-red-600">${{ number_format($fine->remaining_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Status</p>
                            <p class="text-xl font-bold mt-1">
                                <x-status-badge :status="$fine->status" />
                            </p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Fine Type</dt>
                            <dd class="mt-1 text-sm font-bold text-slate-900 dark:text-white capitalize">{{ $fine->fine_type }}</dd>
                        </div>
                        
                        @if($fine->fine_type === 'overdue')
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Days Overdue</dt>
                                <dd class="mt-1 text-sm font-bold text-red-600">{{ $fine->overdue_days }} days</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Generated On</dt>
                            <dd class="mt-1 text-sm text-slate-900 dark:text-white">{{ $fine->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        
                        @if($fine->status === 'paid')
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Resolved On</dt>
                                <dd class="mt-1 text-sm text-emerald-600 font-bold">{{ $fine->updated_at->format('M d, Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Transaction Reference -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/30 flex justify-between items-center">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center">
                        <svg class="h-5 w-5 mr-2 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        Source Transaction
                    </h3>
                    <a href="{{ route('admin.transactions.show', $fine->transaction) }}" class="text-sm text-brand font-medium hover:underline">View Full Details</a>
                </div>
                <div class="p-6 flex flex-col sm:flex-row gap-6 items-start">
                    <div class="h-24 w-16 bg-slate-200 dark:bg-slate-700 rounded overflow-hidden flex-shrink-0 border border-slate-300 dark:border-slate-600">
                        @if($fine->transaction->bookCopy->book->cover_image)
                            <img src="{{ Storage::url($fine->transaction->bookCopy->book->cover_image) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-md font-bold text-slate-900 dark:text-white leading-tight mb-1">{{ $fine->transaction->bookCopy->book->title }}</h4>
                        <p class="text-sm text-slate-500 mb-2">Barcode: <span class="font-mono">{{ $fine->transaction->bookCopy->barcode }}</span></p>
                        
                        <div class="grid grid-cols-2 gap-4 mt-3 pt-3 border-t border-slate-100 dark:border-slate-700 text-sm">
                            <div>
                                <span class="text-slate-500">Issued:</span>
                                <span class="font-medium ml-1">{{ $fine->transaction->issue_date->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500">Due:</span>
                                <span class="font-medium ml-1 {{ $fine->transaction->due_date->isPast() && !$fine->transaction->return_date ? 'text-red-600' : '' }}">{{ $fine->transaction->due_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Actions & Member -->
        <div class="space-y-6">
            
            <!-- Actions -->
            @if(in_array($fine->status, ['unpaid', 'partial']))
                <div class="glass-card overflow-hidden">
                    <div class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-4">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Record Payment</h3>
                    </div>
                    <div class="p-6">
                        @can('payments.create')
                            <form action="{{ route('admin.fines.payment', $fine) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="amount" class="label-field">Amount</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 font-bold">$</span>
                                        </div>
                                        <input type="number" name="amount" id="amount" value="{{ $fine->remaining_amount }}" step="0.01" min="0.01" max="{{ $fine->remaining_amount }}" class="input-field pl-8 font-mono text-lg font-bold text-slate-900 dark:text-white" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="payment_method" class="label-field">Method</label>
                                    <select name="payment_method" id="payment_method" class="input-field" required>
                                        <option value="cash">Cash</option>
                                        <option value="card">Credit/Debit Card</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="online">Online Gateway</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full btn-primary bg-emerald-600 hover:bg-emerald-700 border-emerald-600 justify-center">
                                    Process Payment
                                </button>
                            </form>
                        @endcan

                        @can('fines.waive')
                            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <form action="{{ route('admin.fines.waive', $fine) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full btn-secondary text-red-600 border-red-200 hover:bg-red-50 hover:border-red-300 justify-center" onclick="return confirm('Are you sure you want to waive the remaining balance of ${{ number_format($fine->remaining_amount, 2) }}?');">
                                        Waive Remaining Fine
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            @endif

            <!-- Member Summary -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Borrower</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 rounded-full bg-brand/10 flex items-center justify-center text-brand font-bold text-lg border border-brand/20">
                            {{ $fine->member->user->initials }}
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $fine->member->user->name }}</h4>
                            <p class="text-sm font-mono text-slate-500">{{ $fine->member->membership_id }}</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.members.show', $fine->member) }}" class="btn-secondary w-full justify-center text-sm py-2">
                        View Member Profile
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

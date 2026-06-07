<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Fines & Payments
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 border-l-4 border-emerald-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Collected</p>
            <p class="text-3xl font-bold font-heading text-emerald-600">${{ number_format($stats['total_collected'] ?? 0, 2) }}</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Outstanding Balance</p>
            <p class="text-3xl font-bold font-heading text-red-600">${{ number_format($stats['total_unpaid'] ?? 0, 2) }}</p>
        </div>
        <div class="glass-card p-6 border-l-4 border-slate-500">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Total Waived</p>
            <p class="text-3xl font-bold font-heading text-slate-600">${{ number_format($stats['total_waived'] ?? 0, 2) }}</p>
        </div>
    </div>

    <div class="glass-card mb-6 p-4">
        <form action="{{ route('admin.fines.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by member, transaction code..." class="input-field pl-10">
            </div>
            
            <div class="w-full md:w-48">
                <select name="status" class="input-field" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid in Full</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="waived" {{ request('status') === 'waived' ? 'selected' : '' }}>Waived</option>
                </select>
            </div>
            
            <button type="submit" class="btn-secondary whitespace-nowrap">Filter</button>
            
            @if(request()->hasAny(['search', 'status', 'member_id']))
                <a href="{{ route('admin.fines.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <div class="glass-card overflow-hidden" x-data="{ 
        showPaymentModal: false, 
        currentFine: null,
        amountToPay: 0,
        
        openPaymentModal(fine) {
            this.currentFine = fine;
            this.amountToPay = fine.remaining_amount;
            this.showPaymentModal = true;
        }
    }">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Transaction</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Fine Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($fines as $fine)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300">
                                        {{ $fine->member->user->initials }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $fine->member->user->name }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $fine->member->membership_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.transactions.show', $fine->transaction) }}" class="text-sm font-medium text-brand dark:text-brand-light hover:underline">
                                    {{ $fine->transaction->transaction_code }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-white capitalize">{{ $fine->fine_type }}</div>
                                @if($fine->fine_type === 'overdue')
                                    <div class="text-xs text-red-600">{{ $fine->overdue_days }} days late</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900 dark:text-white">${{ number_format($fine->total_amount, 2) }}</div>
                                @if($fine->paid_amount > 0)
                                    <div class="text-xs text-emerald-600 font-medium">Paid: ${{ number_format($fine->paid_amount, 2) }}</div>
                                @endif
                                @if(in_array($fine->status, ['unpaid', 'partial']))
                                    <div class="text-xs text-red-600 font-bold mt-1">Due: ${{ number_format($fine->remaining_amount, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$fine->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2 flex justify-end">
                                <a href="{{ route('admin.fines.show', $fine) }}" class="text-brand hover:text-brand-light">Details</a>
                                
                                @if(in_array($fine->status, ['unpaid', 'partial']) && Auth::user()->can('payments.create'))
                                    <span class="text-slate-300">|</span>
                                    <button @click="openPaymentModal({{ json_encode(['id' => $fine->id, 'remaining_amount' => $fine->remaining_amount, 'transaction_code' => $fine->transaction->transaction_code, 'member_name' => $fine->member->user->name]) }})" class="text-emerald-600 hover:text-emerald-900 dark:hover:text-emerald-400">
                                        Pay
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">No fines found matching your criteria.</td>
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

        <!-- Payment Modal -->
        <div x-show="showPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPaymentModal" x-transition.opacity class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showPaymentModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showPaymentModal" x-transition.scale class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-200 dark:border-slate-700">
                    <form :action="currentFine ? `/admin/fines/${currentFine.id}/payment` : ''" method="POST">
                        @csrf
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 sm:mx-0 sm:h-10 sm:w-10 border border-emerald-200 dark:border-emerald-800">
                                    <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold font-heading text-slate-900 dark:text-white" id="modal-title">Process Payment</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500">
                                            Recording payment for <span class="font-bold text-slate-900 dark:text-white" x-text="currentFine?.member_name"></span> 
                                            (Tx: <span class="font-mono text-slate-900 dark:text-white" x-text="currentFine?.transaction_code"></span>)
                                        </p>
                                    </div>
                                    
                                    <div class="mt-6 space-y-4">
                                        <div>
                                            <label for="amount" class="label-field">Payment Amount</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-slate-500 font-bold">$</span>
                                                </div>
                                                <input type="number" name="amount" id="amount" x-model="amountToPay" step="0.01" min="0.01" :max="currentFine?.remaining_amount" class="input-field pl-8 font-mono text-lg font-bold text-slate-900 dark:text-white" required>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-500">Maximum: $<span x-text="currentFine?.remaining_amount"></span></p>
                                        </div>

                                        <div>
                                            <label for="payment_method" class="label-field">Payment Method</label>
                                            <select name="payment_method" id="payment_method" class="input-field" required>
                                                <option value="cash">Cash</option>
                                                <option value="card">Credit/Debit Card</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="online">Online Gateway</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200 dark:border-slate-700">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Record Payment
                            </button>
                            <button type="button" @click="showPaymentModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-slate-700 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Circulation Desk: Check Out
            </h2>
            <a href="{{ route('admin.transactions.index') }}" class="btn-secondary">
                View Recent Activity
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto" x-data="checkoutDesk()">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Step 1: Member Selection -->
            <div class="glass-card flex flex-col h-full">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center">
                        <span class="bg-brand text-white text-sm h-6 w-6 rounded-full flex items-center justify-center mr-2">1</span>
                        Select Member
                    </h3>
                </div>
                <div class="p-6 flex-1">
                    <form action="{{ route('admin.transactions.create') }}" method="GET" class="mb-6">
                        <label for="member_id" class="label-field">Scan or Select Member</label>
                        <div class="flex gap-2">
                            <select name="member_id" id="member_id" class="input-field flex-1">
                                <option value="">Select a member...</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}" {{ (request('member_id') == $m->id) ? 'selected' : '' }}>
                                        {{ $m->membership_id }} - {{ $m->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary">Load</button>
                        </div>
                    </form>

                    @if($member)
                        <div class="p-5 border border-brand/20 bg-brand/5 dark:bg-brand/10 rounded-xl">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full bg-brand/20 flex items-center justify-center text-brand font-bold text-lg border border-brand/30">
                                        {{ $member->user->initials }}
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $member->user->name }}</h4>
                                        <p class="text-sm font-mono text-slate-500">{{ $member->membership_id }}</p>
                                    </div>
                                </div>
                                <x-status-badge :status="$member->membership_status" />
                            </div>
                            
                            <div class="mt-4 grid grid-cols-2 gap-4 text-sm pt-4 border-t border-brand/10 dark:border-brand/20">
                                <div>
                                    <p class="text-slate-500 mb-1">Active Loans</p>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $member->transactions->where('status', 'issued')->count() }} / {{ config('smartshelf.circulation.max_books_per_user', 5) }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500 mb-1">Fines Due</p>
                                    @php $fines = $member->fines->where('status', 'unpaid')->sum('total_amount'); @endphp
                                    <p class="font-bold {{ $fines > 0 ? 'text-red-600' : 'text-emerald-600' }}">${{ number_format($fines, 2) }}</p>
                                </div>
                            </div>
                            
                            @if($member->membership_status !== 'active')
                                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm rounded-lg border border-red-200 dark:border-red-800">
                                    <svg class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    Cannot issue books. Membership is {{ $member->membership_status }}.
                                </div>
                            @elseif($fines > 0)
                                <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm rounded-lg border border-amber-200 dark:border-amber-800">
                                    <svg class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    Warning: Member has unpaid fines.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="h-32 flex flex-col items-center justify-center text-slate-400 bg-slate-50 dark:bg-slate-800/30 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                            <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" /></svg>
                            <span class="text-sm">Select a member to view eligibility</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 2: Book Selection & Issue -->
            <div class="glass-card flex flex-col h-full {{ !$member || $member->membership_status !== 'active' ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center">
                        <span class="bg-brand text-white text-sm h-6 w-6 rounded-full flex items-center justify-center mr-2">2</span>
                        Scan Book Copies
                    </h3>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="mb-6">
                        <label for="barcode_input" class="label-field">Scan Book Barcode</label>
                        <div class="flex gap-2">
                            <input type="text" id="barcode_input" x-model="barcodeInput" @keydown.enter="addCopy" class="input-field flex-1 font-mono" placeholder="Scan barcode..." autofocus>
                            <button type="button" @click="addCopy" class="btn-secondary">Add</button>
                        </div>
                    </div>

                    <form action="{{ route('admin.transactions.store') }}" method="POST" id="issueForm" class="flex-1 flex flex-col">
                        @csrf
                        <input type="hidden" name="member_id" value="{{ request('member_id') }}">
                        
                        <!-- List of scanned copies -->
                        <div class="flex-1 min-h-[150px] mb-6">
                            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 border-b border-slate-200 dark:border-slate-700 pb-2">Copies to Issue</h4>
                            
                            <template x-if="scannedCopies.length === 0">
                                <div class="h-24 flex items-center justify-center text-slate-400 text-sm italic">
                                    No copies scanned yet.
                                </div>
                            </template>

                            <ul class="space-y-3">
                                <template x-for="(copy, index) in scannedCopies" :key="index">
                                    <li class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                        <div>
                                            <input type="hidden" name="book_copy_ids[]" :value="copy.id">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white" x-text="copy.title"></div>
                                            <div class="text-xs text-slate-500 font-mono mt-1" x-text="copy.barcode"></div>
                                        </div>
                                        <button type="button" @click="removeCopy(index)" class="text-red-500 hover:text-red-700 p-1">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                            <x-input-error :messages="$errors->get('book_copy_ids')" class="mt-2" />
                            <x-input-error :messages="$errors->get('book_copy_ids.*')" class="mt-2" />
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                            <label for="due_date" class="label-field">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', now()->addDays(config('smartshelf.circulation.loan_period_days', 14))->format('Y-m-d')) }}" required class="input-field mb-4">
                            
                            <button type="submit" class="w-full btn-primary py-3 justify-center text-lg shadow-md" :disabled="scannedCopies.length === 0">
                                Issue Books
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Toast container -->
    <div x-data="{ show: false, message: '' }" 
         @show-error.window="message = $event.detail; show = true; setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 z-50">
        <div x-show="show" x-transition class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span x-text="message"></span>
        </div>
    </div>

    <!-- Available Copies API data -->
    <script>
        const availableCopies = @json($availableCopies ?? []);
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutDesk', () => ({
                barcodeInput: '',
                scannedCopies: [],
                
                addCopy() {
                    const barcode = this.barcodeInput.trim();
                    if (!barcode) return;
                    
                    // Check if already scanned
                    if (this.scannedCopies.find(c => c.barcode === barcode)) {
                        window.dispatchEvent(new CustomEvent('show-error', { detail: 'Copy already scanned.' }));
                        this.barcodeInput = '';
                        return;
                    }
                    
                    // Find copy in available copies list
                    const copy = availableCopies.find(c => c.barcode === barcode);
                    
                    if (copy) {
                        this.scannedCopies.push(copy);
                        this.barcodeInput = ''; // clear input
                    } else {
                        // In a real app, this would be an AJAX call. Here we rely on pre-loaded data or simulated delay.
                        window.dispatchEvent(new CustomEvent('show-error', { detail: 'Invalid barcode or copy is not available.' }));
                    }
                },
                
                removeCopy(index) {
                    this.scannedCopies.splice(index, 1);
                }
            }));
        });
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Member Profile: {{ $member->user->name }}
            </h2>
            <div class="flex space-x-3">
                @if($member->membership_status !== 'active')
                    <form action="{{ route('admin.members.renew', $member) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary">Renew Membership</button>
                    </form>
                @endif
                @can('members.edit')
                    <a href="{{ route('admin.members.edit', $member) }}" class="btn-secondary">Edit Details</a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Member Card & Stats -->
        <div class="space-y-6">
            <div class="glass-card overflow-hidden">
                <div class="bg-brand h-24 relative">
                    <div class="absolute -bottom-10 left-6">
                        <div class="h-20 w-20 rounded-full bg-white dark:bg-slate-800 border-4 border-white dark:border-slate-800 flex items-center justify-center shadow-md overflow-hidden">
                            <span class="text-3xl font-display font-bold text-slate-300 dark:text-slate-600">{{ $member->user->initials }}</span>
                        </div>
                    </div>
                </div>
                <div class="pt-12 px-6 pb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white">{{ $member->user->name }}</h3>
                            <p class="text-sm font-mono text-slate-500">{{ $member->membership_id }}</p>
                        </div>
                        <x-status-badge :status="$member->membership_status" />
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <div class="flex items-start text-sm">
                            <svg class="h-5 w-5 text-slate-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span class="text-slate-600 dark:text-slate-300">{{ $member->user->email }}</span>
                        </div>
                        @if($member->user->phone)
                            <div class="flex items-start text-sm">
                                <svg class="h-5 w-5 text-slate-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <span class="text-slate-600 dark:text-slate-300">{{ $member->user->phone }}</span>
                            </div>
                        @endif
                        <div class="flex items-start text-sm">
                            <svg class="h-5 w-5 text-slate-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <span class="text-slate-600 dark:text-slate-300">{{ $member->user->branch->name }}</span>
                        </div>
                        @if($member->student_id)
                            <div class="flex items-start text-sm">
                                <svg class="h-5 w-5 text-slate-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                <span class="text-slate-600 dark:text-slate-300">ID: {{ $member->student_id }} | Dept: {{ $member->department ?? 'N/A' }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg border border-slate-100 dark:border-slate-700">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-500">Joined</span>
                            <span class="font-medium text-slate-900 dark:text-white">{{ $member->joined_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Expires</span>
                            <span class="font-medium {{ $member->expires_at->isPast() ? 'text-red-600' : 'text-slate-900 dark:text-white' }}">{{ $member->expires_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fines Summary -->
            @php $unpaidFines = $member->fines->where('status', 'unpaid')->sum('total_amount'); @endphp
            <div class="glass-card p-6 border-t-4 {{ $unpaidFines > 0 ? 'border-red-500' : 'border-emerald-500' }}">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Financial Standing</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Unpaid Fines</p>
                        <p class="text-3xl font-bold font-heading {{ $unpaidFines > 0 ? 'text-red-600' : 'text-emerald-600' }}">${{ number_format($unpaidFines, 2) }}</p>
                    </div>
                    @if($unpaidFines > 0 && Auth::user()->can('payments.create'))
                        <a href="{{ route('admin.fines.index', ['member_id' => $member->id, 'status' => 'unpaid']) }}" class="btn-primary py-1.5 px-3 text-sm">Process Payment</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Loans & History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active Loans -->
            <div class="glass-card overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/30">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center">
                        <svg class="h-5 w-5 mr-2 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Current Loans
                    </h3>
                    @can('transactions.create')
                        <a href="{{ route('admin.transactions.create') }}?member_id={{ $member->id }}" class="text-sm font-medium text-brand hover:text-brand-light">Issue New Book &rarr;</a>
                    @endcan
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-white dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Book</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Due Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($member->transactions->whereIn('status', ['issued', 'overdue']) as $loan)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $loan->bookCopy->book->title }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $loan->bookCopy->barcode }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm {{ $loan->status === 'overdue' ? 'text-red-600 font-bold' : 'text-slate-900 dark:text-white' }}">
                                            {{ $loan->due_date->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$loan->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.transactions.show', $loan) }}" class="text-brand hover:text-brand-light">Manage</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">No active loans.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Reservations -->
            <div class="glass-card overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/30">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white flex items-center">
                        <svg class="h-5 w-5 mr-2 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Pending Reservations
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($member->reservations->whereIn('status', ['pending', 'approved']) as $reservation)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $reservation->book->title }}</div>
                                        <div class="text-xs text-slate-500">Reserved: {{ $reservation->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$reservation->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.reservations.show', $reservation) }}" class="text-brand hover:text-brand-light">Manage</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No active reservations.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

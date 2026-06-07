<x-app-layout>
    <x-slot name="header">
        Member Dashboard
    </x-slot>

    <div class="mb-8">
        <h2 class="text-2xl text-display font-bold text-slate-900 dark:text-white">Welcome back, {{ Auth::user()->name }}!</h2>
        @if($member && $member->membership_status === 'active')
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Your membership is active until {{ $member->expires_at->format('F d, Y') }}.</p>
        @else
            <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-medium">Your membership requires attention. Please contact the library staff.</p>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="glass-card p-4 text-center">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Active Loans</p>
            <p class="text-3xl font-bold font-heading text-brand dark:text-brand-light">{{ $stats['active_loans'] }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Overdue</p>
            <p class="text-3xl font-bold font-heading {{ $stats['overdue_loans'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-700 dark:text-slate-300' }}">{{ $stats['overdue_loans'] }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Reservations</p>
            <p class="text-3xl font-bold font-heading text-accent dark:text-accent-light">{{ $stats['reservations'] }}</p>
        </div>
        <div class="glass-card p-4 text-center">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Unpaid Fines</p>
            <p class="text-3xl font-bold font-heading {{ $stats['unpaid_fines'] > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-700 dark:text-slate-300' }}">${{ number_format($stats['unpaid_fines'], 2) }}</p>
        </div>
    </div>

    @if($member)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- My Current Loans -->
            <div class="lg:col-span-2 glass-card overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">My Current Loans</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Book</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Borrowed On</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Due Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($myLoans as $loan)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $loan->bookCopy->book->title }}</div>
                                        <div class="text-xs text-slate-500">{{ $loan->bookCopy->book->author->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $loan->issue_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $isOverdue = $loan->due_date->isPast();
                                            $isDueSoon = !$isOverdue && $loan->due_date->diffInDays(now()) <= 3;
                                        @endphp
                                        <div class="text-sm {{ $isOverdue ? 'text-red-600 font-bold' : ($isDueSoon ? 'text-amber-600 font-bold' : 'text-slate-900 dark:text-white') }}">
                                            {{ $loan->due_date->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$loan->status" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">
                                        You don't have any active loans.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: Fines & Reservations -->
            <div class="space-y-8">
                
                @if($myFines->count() > 0)
                    <!-- Unpaid Fines Alert -->
                    <div class="glass-card overflow-hidden border-red-200 dark:border-red-800">
                        <div class="px-4 py-3 bg-red-50 dark:bg-red-900/20 border-b border-red-100 dark:border-red-800/50 flex items-center">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Unpaid Fines</h3>
                        </div>
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($myFines as $fine)
                                <li class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                    <div class="flex justify-between">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">{{ Str::limit($fine->transaction->bookCopy->book->title, 25) }}</div>
                                        <div class="text-sm font-bold text-red-600 dark:text-red-400">${{ $fine->remaining_amount }}</div>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $fine->overdue_days }} days overdue</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- My Reservations -->
                <div class="glass-card overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">My Reservations</h3>
                    </div>
                    @if($myReservations->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($myReservations as $reservation)
                                <li class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-sm font-medium text-slate-900 dark:text-white mb-1">{{ Str::limit($reservation->book->title, 30) }}</div>
                                            <x-status-badge :status="$reservation->status" />
                                        </div>
                                        @if($reservation->status === 'pending')
                                            <div class="text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-1 rounded">
                                                Pos: #{{ $reservation->queue_position }}
                                            </div>
                                        @elseif($reservation->status === 'approved')
                                            <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 text-right">
                                                Collect by<br>{{ $reservation->expiry_date->format('M d') }}
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-6 text-center text-sm text-slate-500">
                            You have no active reservations.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-500 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">Membership Profile Missing</h3>
                    <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                        <p>Your user account does not have an associated member profile. Please contact the librarian.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

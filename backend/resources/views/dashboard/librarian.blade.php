<x-app-layout>
    <x-slot name="header">
        Librarian Dashboard
    </x-slot>

    <div class="mb-8">
        <h2 class="text-2xl text-display font-bold text-slate-900 dark:text-white">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Here's a summary of circulation activities.</p>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 flex space-x-4">
        <a href="{{ route('admin.transactions.create') }}" class="btn-primary">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Issue Book
        </a>
        <a href="{{ route('admin.book-copies.index') }}" class="btn-secondary">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            Search Catalog
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <x-stat-card title="Issues Today" :value="$stats['issues_today']" icon="trending-up" color="brand" />
        <x-stat-card title="Returns Today" :value="$stats['returns_today']" icon="check-circle" color="green" />
        <x-stat-card title="Active Loans" :value="$stats['active_loans']" icon="refresh" color="blue" />
        <x-stat-card title="Pending Reservations" :value="$stats['pending_reservations']" icon="clock" color="accent" />
        <x-stat-card title="Overdue Loans" :value="$stats['overdue_loans']" icon="exclamation-circle" color="red" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Urgent: Overdue Loans -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-red-50/50 dark:bg-red-900/10">
                <h3 class="text-lg font-bold font-heading text-red-800 dark:text-red-400">Urgent: Overdue Returns</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($overdueLoans as $loan)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $loan->member->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $loan->member->membership_id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 dark:text-white">{{ Str::limit($loan->bookCopy->book->title, 30) }}</div>
                                    <div class="text-xs text-red-600 dark:text-red-400 font-medium">Due: {{ $loan->due_date->format('M d, Y') }} ({{ $loan->due_date->diffInDays(now()) }} days late)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.transactions.show', $loan) }}" class="text-brand hover:text-brand-light">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-500">No overdue loans currently.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Reservations -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-amber-50/50 dark:bg-amber-900/10">
                <h3 class="text-lg font-bold font-heading text-amber-800 dark:text-amber-400">Pending Reservations</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($pendingReservations as $reservation)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $reservation->member->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $reservation->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 dark:text-white">{{ Str::limit($reservation->book->title, 30) }}</div>
                                    <div class="text-xs text-slate-500">Queue Pos: {{ $reservation->queue_position }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.reservations.approve', $reservation) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-900 dark:hover:text-emerald-400">Approve</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-500">No pending reservations.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.reservations.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Reservation Details
            </h2>
            <x-status-badge :status="$reservation->status" />
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        
        <!-- Action Buttons -->
        @if(in_array($reservation->status, ['pending', 'approved']))
            <div class="glass-card p-4 flex gap-4">
                @if($reservation->status === 'pending')
                    @can('reservations.approve')
                        <form action="{{ route('admin.reservations.approve', $reservation) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full btn-primary bg-emerald-600 hover:bg-emerald-700 border-emerald-600 py-3 justify-center text-lg shadow-sm" onclick="return confirm('Approve this reservation and notify the member?');">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Approve & Set Aside Book
                            </button>
                        </form>
                    @endcan
                @endif

                @if($reservation->status === 'approved')
                    <div class="flex-1">
                        <a href="{{ route('admin.transactions.create', ['member_id' => $reservation->member_id]) }}" class="w-full btn-primary py-3 justify-center text-lg shadow-sm flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                            Proceed to Check Out
                        </a>
                    </div>
                @endif

                @can('reservations.cancel')
                    <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-secondary text-red-600 border-red-200 hover:bg-red-50 hover:border-red-300 py-3 justify-center text-lg" onclick="return confirm('Are you sure you want to cancel this reservation?');">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Cancel Reservation
                        </button>
                    </form>
                @endcan
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left: Timeline Info -->
            <div class="glass-card">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Reservation Timeline</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Requested Date</dt>
                            <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $reservation->reservation_date->format('F d, Y h:i A') }}</dd>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700">
                            <dt class="text-sm text-slate-500">Expiration Date</dt>
                            <dd class="text-sm font-medium {{ $reservation->expiration_date->isPast() && in_array($reservation->status, ['pending', 'approved']) ? 'text-red-600 font-bold' : 'text-slate-900 dark:text-white' }}">
                                {{ $reservation->expiration_date->format('F d, Y') }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <dt class="text-sm text-slate-500">Current Status</dt>
                            <dd class="text-sm font-bold text-slate-900 dark:text-white capitalize">{{ $reservation->status }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Member Info -->
                <div class="glass-card">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Reserved By</h3>
                    </div>
                    <div class="p-6 flex items-start">
                        <div class="flex-shrink-0 h-14 w-14 rounded-full bg-brand/10 flex items-center justify-center border border-brand/20">
                            <span class="text-brand font-bold text-xl">{{ $reservation->member->user->initials }}</span>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $reservation->member->user->name }}</h4>
                            <p class="text-sm font-mono text-slate-500 mb-2">{{ $reservation->member->membership_id }}</p>
                            <a href="{{ route('admin.members.show', $reservation->member) }}" class="text-sm text-brand hover:text-brand-light font-medium">View Full Profile &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="glass-card">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Requested Title</h3>
                    </div>
                    <div class="p-6 flex items-start">
                        <div class="h-20 w-14 bg-slate-200 dark:bg-slate-700 rounded overflow-hidden flex-shrink-0 border border-slate-300 dark:border-slate-600">
                            @if($reservation->book->cover_image)
                                <img src="{{ Storage::url($reservation->book->cover_image) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-md font-bold text-slate-900 dark:text-white leading-tight mb-1">{{ $reservation->book->title }}</h4>
                            <p class="text-sm text-slate-500 mb-2">By {{ $reservation->book->author->name }}</p>
                            
                            <div class="bg-slate-50 dark:bg-slate-800 p-2 rounded text-xs mt-2 border border-slate-100 dark:border-slate-700">
                                @php
                                    $availableCopies = $reservation->book->copies->where('status', 'available')->count();
                                @endphp
                                <span class="text-slate-500 uppercase">Availability:</span>
                                <span class="font-bold ml-1 {{ $availableCopies > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $availableCopies }} / {{ $reservation->book->copies->count() }} Copies
                                </span>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('admin.books.show', $reservation->book) }}" class="text-sm text-brand hover:text-brand-light font-medium">View Catalog Entry &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

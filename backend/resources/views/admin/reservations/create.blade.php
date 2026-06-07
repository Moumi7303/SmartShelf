<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.reservations.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Create Reservation
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card">
            <form action="{{ route('admin.reservations.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="member_id" class="label-field">Member <span class="text-red-500">*</span></label>
                        <select name="member_id" id="member_id" required class="input-field">
                            <option value="">Select a member...</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id', request('member_id')) == $member->id ? 'selected' : '' }}>
                                    {{ $member->membership_id }} - {{ $member->user->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="book_id" class="label-field">Book <span class="text-red-500">*</span></label>
                        <select name="book_id" id="book_id" required class="input-field">
                            <option value="">Select a book...</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id', request('book_id')) == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} ({{ $book->author->name }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                        <p class="text-xs text-slate-500 mt-2">Reservations are placed on a specific title, not a specific copy.</p>
                    </div>

                    <div>
                        <label for="reservation_date" class="label-field">Reservation Date</label>
                        <input type="date" name="reservation_date" id="reservation_date" value="{{ old('reservation_date', now()->format('Y-m-d')) }}" required class="input-field">
                        <x-input-error :messages="$errors->get('reservation_date')" class="mt-2" />
                    </div>

                    <div>
                        <label for="expiration_date" class="label-field">Expiration Date</label>
                        <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', now()->addDays(config('smartshelf.circulation.reservation_hold_days', 3))->format('Y-m-d')) }}" required class="input-field">
                        <x-input-error :messages="$errors->get('expiration_date')" class="mt-2" />
                        <p class="text-xs text-slate-500 mt-1">Date when this reservation will automatically expire if not fulfilled.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-4">
                    <a href="{{ route('admin.reservations.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        Create Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

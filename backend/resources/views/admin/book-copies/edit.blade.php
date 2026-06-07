<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.book-copies.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                {{ isset($bookCopy) ? 'Edit Book Copy: ' . $bookCopy->barcode : 'Add New Book Copy' }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="glass-card">
            <form action="{{ isset($bookCopy) ? route('admin.book-copies.update', $bookCopy) : route('admin.book-copies.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                @if(isset($bookCopy))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="book_id" class="label-field">Book <span class="text-red-500">*</span></label>
                        <select name="book_id" id="book_id" required class="input-field">
                            <option value="">Select a book...</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id', $bookCopy->book_id ?? request('book_id')) == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} ({{ $book->author->name }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="branch_id" class="label-field">Branch Location <span class="text-red-500">*</span></label>
                        <select name="branch_id" id="branch_id" required class="input-field">
                            <option value="">Select a branch...</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $bookCopy->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="barcode" class="label-field">Barcode</label>
                        <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $bookCopy->barcode ?? '') }}" class="input-field font-mono" placeholder="Leave empty to auto-generate">
                        <p class="text-xs text-slate-500 mt-1">Unique identifier for this specific physical copy.</p>
                        <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
                    </div>

                    <div>
                        <label for="status" class="label-field">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="input-field">
                            <option value="available" {{ old('status', $bookCopy->status ?? 'available') === 'available' ? 'selected' : '' }}>Available</option>
                            @if(isset($bookCopy))
                                <option value="issued" {{ old('status', $bookCopy->status) === 'issued' ? 'selected' : '' }} disabled>Issued (Managed via Transactions)</option>
                            @endif
                            <option value="lost" {{ old('status', $bookCopy->status ?? '') === 'lost' ? 'selected' : '' }}>Lost</option>
                            <option value="damaged" {{ old('status', $bookCopy->status ?? '') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                            <option value="maintenance" {{ old('status', $bookCopy->status ?? '') === 'maintenance' ? 'selected' : '' }}>Maintenance / Repair</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <label for="condition" class="label-field">Condition <span class="text-red-500">*</span></label>
                        <select name="condition" id="condition" required class="input-field">
                            <option value="new" {{ old('condition', $bookCopy->condition ?? 'new') === 'new' ? 'selected' : '' }}>New</option>
                            <option value="good" {{ old('condition', $bookCopy->condition ?? '') === 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('condition', $bookCopy->condition ?? '') === 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('condition', $bookCopy->condition ?? '') === 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                        <x-input-error :messages="$errors->get('condition')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-4">
                    <a href="{{ route('admin.book-copies.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        {{ isset($bookCopy) ? 'Update Copy' : 'Add Copy' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

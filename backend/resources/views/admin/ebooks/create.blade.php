<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
            Upload eBook
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="glass-card p-6">
            <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Book</label>
                    <select name="book_id" class="input-field mt-1 w-full" required>
                        <option value="">Select a Book</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} ({{ $book->isbn }})
                            </option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">File Format</label>
                    <select name="format" class="input-field mt-1 w-full" required>
                        <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="epub" {{ old('format') == 'epub' ? 'selected' : '' }}>EPUB</option>
                    </select>
                    @error('format') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Access Level</label>
                    <select name="access_level" class="input-field mt-1 w-full" required>
                        <option value="public" {{ old('access_level') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="member_only" {{ old('access_level') == 'member_only' ? 'selected' : '' }}>Member Only</option>
                    </select>
                    @error('access_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">eBook File</label>
                    <input type="file" name="file" class="input-field mt-1 w-full" accept=".pdf,.epub" required>
                    <p class="text-xs text-slate-500 mt-1">Max size: 50MB. Allowed formats: PDF, EPUB.</p>
                    @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.ebooks.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Upload eBook</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

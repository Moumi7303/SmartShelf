<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.authors.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                {{ isset($author) ? 'Edit Author' : 'Add New Author' }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card">
            <form action="{{ isset($author) ? route('admin.authors.update', $author) : route('admin.authors.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                @if(isset($author))
                    @method('PUT')
                @endif

                <div>
                    <label for="name" class="label-field">Author Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $author->name ?? '') }}" required class="input-field" placeholder="e.g. J.K. Rowling">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-700 flex justify-end space-x-4">
                    <a href="{{ route('admin.authors.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">
                        {{ isset($author) ? 'Update Author' : 'Add Author' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

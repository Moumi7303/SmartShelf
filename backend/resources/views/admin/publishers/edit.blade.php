<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
            Edit Publisher
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="glass-card p-6">
            <form action="{{ route('admin.publishers.update', $publisher) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                    <input type="text" name="name" value="{{ old('name', $publisher->name) }}" class="input-field mt-1 w-full" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Contact</label>
                    <input type="text" name="contact" value="{{ old('contact', $publisher->contact) }}" class="input-field mt-1 w-full">
                    @error('contact') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Address</label>
                    <textarea name="address" class="input-field mt-1 w-full" rows="3">{{ old('address', $publisher->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.publishers.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Update Publisher</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

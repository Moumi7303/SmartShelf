<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Publishers
            </h2>
            @can('books.create')
                <a href="{{ route('admin.publishers.create') }}" class="btn-primary">
                    Add Publisher
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="glass-card p-4">
            <form action="{{ route('admin.publishers.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" class="input-field w-full" placeholder="Search publishers...">
                <button type="submit" class="btn-secondary">Search</button>
            </form>
        </div>

        <div id="table-container" class="glass-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Name</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Contact</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Address</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Books</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse ($publishers as $publisher)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/25">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $publisher->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $publisher->contact ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 line-clamp-1">{{ $publisher->address ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $publisher->books_count }}</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                @can('books.edit')
                                    <a href="{{ route('admin.publishers.edit', $publisher) }}" class="text-slate-500 hover:text-amber-500">Edit</a>
                                @endcan
                                @can('books.delete')
                                    @if($publisher->books_count === 0)
                                    <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this publisher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-500 hover:text-red-500">Delete</button>
                                    </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">No publishers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($publishers->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $publishers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

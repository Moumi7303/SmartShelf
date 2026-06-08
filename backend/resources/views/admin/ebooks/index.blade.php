<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                eBooks
            </h2>
            @can('books.create')
                <a href="{{ route('admin.ebooks.create') }}" class="btn-primary">
                    Upload eBook
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="glass-card p-4">
            <form action="{{ route('admin.ebooks.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" class="input-field w-full" placeholder="Search by book title...">
                <button type="submit" class="btn-secondary">Search</button>
            </form>
        </div>

        <div id="table-container" class="glass-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Book Title</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Format</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Size</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Downloads</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300">Access Level</th>
                        <th class="px-6 py-4 font-semibold text-sm text-slate-600 dark:text-slate-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse ($ebooks as $ebook)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/25">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $ebook->book->title }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 uppercase">{{ $ebook->format }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ number_format($ebook->file_size / 1048576, 2) }} MB</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $ebook->download_count }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500 capitalize">{{ str_replace('_', ' ', $ebook->access_level) }}</td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <a href="{{ route('admin.ebooks.download', $ebook) }}" class="text-brand hover:text-brand-dark">Download</a>
                                @can('books.delete')
                                    <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this eBook?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-500 hover:text-red-500">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">No eBooks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($ebooks->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $ebooks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

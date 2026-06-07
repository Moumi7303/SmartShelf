<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.books.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                    {{ $book->title }}
                </h2>
            </div>
            <div class="flex space-x-3">
                @can('books.edit')
                    <a href="{{ route('admin.books.edit', $book) }}" class="btn-secondary">Edit Book</a>
                @endcan
                @can('books.delete')
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book? This will permanently remove it from the catalog.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700 focus:ring-red-500 border-transparent">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Book Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="glass-card p-6 flex flex-col md:flex-row gap-8">
                <div class="flex-shrink-0 w-48 mx-auto md:mx-0">
                    <div class="aspect-[2/3] bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-md">
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                <svg class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                                <span class="text-xs font-medium uppercase tracking-widest">No Cover</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <x-status-badge :status="$book->status" />
                        <span class="text-xs font-semibold text-brand dark:text-brand-light uppercase tracking-wider bg-brand/10 px-2 py-1 rounded">{{ $book->category->name }}</span>
                    </div>
                    
                    <h1 class="text-3xl font-display font-bold text-slate-900 dark:text-white mb-2">{{ $book->title }}</h1>
                    <p class="text-lg text-slate-600 dark:text-slate-300 mb-6">By {{ $book->author->name }}</p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-8 border-y border-slate-100 dark:border-slate-700 py-6">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">ISBN</p>
                            <p class="font-mono text-sm text-slate-900 dark:text-white font-medium">{{ $book->isbn ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Publisher</p>
                            <p class="text-sm text-slate-900 dark:text-white font-medium">{{ $book->publisher->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Year</p>
                            <p class="text-sm text-slate-900 dark:text-white font-medium">{{ $book->publication_year ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Edition</p>
                            <p class="text-sm text-slate-900 dark:text-white font-medium">{{ $book->edition ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Language</p>
                            <p class="text-sm text-slate-900 dark:text-white font-medium">{{ $book->language ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Shelf</p>
                            <p class="text-sm text-slate-900 dark:text-white font-medium">{{ $book->shelf_location ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2 uppercase tracking-wider">Description</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $book->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Ebooks Section -->
            @can('ebooks.view')
                <div class="glass-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Digital Versions (eBooks)</h3>
                    </div>
                    @if($book->ebooks->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($book->ebooks as $ebook)
                                <li class="p-6 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-purple-100 text-purple-600 rounded-lg dark:bg-purple-900/30 dark:text-purple-400">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white uppercase">{{ $ebook->format }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ number_format($ebook->file_size / 1048576, 2) }} MB • {{ $ebook->access_level === 'public' ? 'Public Access' : 'Members Only' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        @can('ebooks.download')
                                            <a href="{{ route('admin.ebooks.download', $ebook) }}" class="btn-primary py-1.5 px-3 text-xs">Download</a>
                                        @endcan
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-8 text-center text-sm text-slate-500">
                            No digital versions available for this title.
                        </div>
                    @endif
                </div>
            @endcan
        </div>

        <!-- Right Column: Availability & Inventory -->
        <div class="space-y-8">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white mb-4">Availability Status</h3>
                
                <div class="space-y-4">
                    @forelse($availability as $branchId => $data)
                        <div class="p-4 border border-slate-100 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-slate-900 dark:text-white">{{ $data['branch_name'] }}</span>
                                <span class="text-sm font-bold {{ $data['available'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $data['available'] }} / {{ $data['total'] }}
                                </span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                @php $percent = $data['total'] > 0 ? ($data['available'] / $data['total']) * 100 : 0; @endphp
                                <div class="bg-brand h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500 italic text-center py-4">No copies exist in any branch.</div>
                    @endforelse
                </div>
                
                @can('book_copies.create')
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('admin.book-copies.create') }}?book_id={{ $book->id }}" class="w-full btn-secondary justify-center text-xs py-2">
                            + Add New Copy
                        </a>
                    </div>
                @endcan
            </div>

            <div class="glass-card overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Active Reservations</h3>
                    <span class="bg-accent text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $book->reservations->whereIn('status', ['pending', 'approved'])->count() }}</span>
                </div>
                @if($book->reservations->whereIn('status', ['pending', 'approved'])->count() > 0)
                    <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($book->reservations->whereIn('status', ['pending', 'approved']) as $reservation)
                            <li class="p-4 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $reservation->member->user->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Pos: {{ $reservation->queue_position }} • {{ $reservation->created_at->diffForHumans() }}</p>
                                </div>
                                <x-status-badge :status="$reservation->status" />
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-6 text-center text-sm text-slate-500">
                        No active reservations for this book.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

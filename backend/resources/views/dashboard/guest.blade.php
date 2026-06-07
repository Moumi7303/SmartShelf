<x-app-layout>
    <x-slot name="header">
        Guest Dashboard
    </x-slot>

    <div class="mb-8">
        <h2 class="text-2xl text-display font-bold text-slate-900 dark:text-white">Welcome to SmartShelf!</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Browse our catalog and sign up at your local branch to borrow books.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass-card p-6 flex items-center justify-between bg-gradient-to-r from-brand to-brand-light text-white">
            <div>
                <p class="text-brand-100 font-medium text-sm">Total Books</p>
                <p class="text-4xl font-bold font-heading mt-1">{{ number_format($stats['total_books']) }}</p>
            </div>
            <div class="p-4 bg-white/20 rounded-full">
                <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
        </div>
        <div class="glass-card p-6 flex items-center justify-between bg-gradient-to-r from-accent to-accent-light text-white">
            <div>
                <p class="text-accent-100 font-medium text-sm">Categories</p>
                <p class="text-4xl font-bold font-heading mt-1">{{ $stats['categories'] }}</p>
            </div>
            <div class="p-4 bg-white/20 rounded-full">
                <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </div>
        </div>
    </div>

    <!-- Featured / Recent Additions -->
    <div>
        <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-2">Recently Added to Catalog</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($recentBooks as $book)
                <div class="glass-card overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-300">
                    <div class="h-48 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                            <svg class="h-16 w-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="mb-1 text-xs font-semibold text-brand dark:text-brand-light uppercase tracking-wider">{{ $book->category->name }}</div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2">{{ Str::limit($book->title, 50) }}</h4>
                        <p class="text-sm text-slate-500 mb-4">{{ $book->author->name }}</p>
                        
                        <div class="mt-auto">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->status === 'available' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $book->status === 'available' ? 'Available to Borrow' : 'Currently Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 p-12 text-center text-slate-500 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                    Our catalog is currently being updated. Please check back later!
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

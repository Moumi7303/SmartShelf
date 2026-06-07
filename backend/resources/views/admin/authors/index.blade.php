<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Books by Author
            </h2>
            @can('authors.create')
                <a href="{{ route('admin.authors.create') }}" class="btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Author
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="glass-card mb-6 p-4">
        <form action="{{ route('admin.authors.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search authors by name..." class="input-field pl-10">
            </div>
            
            <button type="submit" class="btn-secondary whitespace-nowrap">Search</button>
            
            @if(request('search'))
                <a href="{{ route('admin.authors.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($authors as $author)
            <div class="glass-card flex flex-col items-center p-6 text-center hover:shadow-md transition-shadow">
                <div class="h-20 w-20 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center mb-4 text-3xl font-display font-bold text-slate-400">
                    {{ substr($author->name, 0, 1) }}
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $author->name }}</h3>
                <p class="text-sm text-slate-500 mb-4">{{ $author->books_count }} {{ Str::plural('Title', $author->books_count) }}</p>
                
                <div class="mt-auto flex space-x-2 w-full pt-4 border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('admin.books.index', ['search' => $author->name]) }}" class="btn-secondary flex-1 justify-center text-xs py-1.5">View Books</a>
                    @can('authors.edit')
                        <a href="{{ route('admin.authors.edit', $author) }}" class="btn-secondary flex-1 justify-center text-xs py-1.5 border-amber-200 text-amber-700 hover:bg-amber-50 dark:border-amber-800/50 dark:text-amber-500 dark:hover:bg-amber-900/30">Edit</a>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card p-12 text-center">
                <p class="text-slate-500">No authors found.</p>
            </div>
        @endforelse
    </div>
    
    @if($authors->hasPages())
        <div class="mt-6">
            {{ $authors->links() }}
        </div>
    @endif
</x-app-layout>

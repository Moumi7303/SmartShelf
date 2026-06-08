<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Book Categories
            </h2>
            @can('categories.create')
                <a href="{{ route('admin.categories.create') }}" class="btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Category
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="glass-card mb-6 p-4">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="input-field pl-10">
            </div>
            
            <button type="submit" class="btn-secondary whitespace-nowrap">Search</button>
            
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <div id="table-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
            <div class="glass-card flex flex-col p-6 hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="h-16 w-16 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                </div>
                <div class="relative z-10 flex-1">
                    <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-2 pr-8">{{ $category->name }}</h3>
                    <p class="text-sm text-slate-500 mb-6">{{ $category->description ?? 'No description.' }}</p>
                    
                    <div class="flex items-center text-sm font-medium text-slate-600 dark:text-slate-400 mb-4">
                        <svg class="h-4 w-4 mr-1.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        {{ $category->books_count }} Titles
                    </div>
                </div>
                
                <div class="mt-auto relative z-10 flex space-x-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('admin.books.index', ['category_id' => $category->id]) }}" class="btn-secondary flex-1 justify-center text-xs py-1.5">View Books</a>
                    @can('categories.edit')
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn-secondary flex-1 justify-center text-xs py-1.5 border-amber-200 text-amber-700 hover:bg-amber-50 dark:border-amber-800/50 dark:text-amber-500 dark:hover:bg-amber-900/30">Edit</a>
                    @endcan
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card p-12 text-center">
                <p class="text-slate-500">No categories found.</p>
            </div>
        @endforelse
    </div>
    
    @if($categories->hasPages())
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif
    </div>
</x-app-layout>

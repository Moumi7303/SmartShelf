<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Branch Management
            </h2>
            @can('branches.create')
                <a href="{{ route('admin.branches.create') }}" class="btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Branch
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="glass-card p-4">
            <form action="{{ route('admin.branches.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="input-field pl-10 w-full" placeholder="Search branches by name, code, or email...">
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <select name="status" class="input-field w-full" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" class="btn-secondary whitespace-nowrap">Filter</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.branches.index') }}" class="btn-secondary text-slate-500 hover:text-slate-700">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Branches List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($branches as $branch)
                <div class="glass-card flex flex-col relative group transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div class="h-12 w-12 rounded-xl bg-brand/10 flex items-center justify-center text-brand">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            @if($branch->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">
                                    Inactive
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $branch->name }}</h3>
                        <p class="text-sm font-medium text-brand mb-4">{{ $branch->code }}</p>
                        
                        <div class="space-y-2 text-sm text-slate-600 dark:text-slate-400 mb-6">
                            @if($branch->manager)
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Manager: {{ $branch->manager->name }}
                                </div>
                            @endif
                            <div class="flex items-start">
                                <svg class="h-4 w-4 mr-2 mt-0.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="line-clamp-2">{{ $branch->address ?: 'No address specified' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <div>
                                <div class="text-xs text-slate-500 mb-1">Users</div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ number_format($branch->users_count) }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-slate-500 mb-1">Books</div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ number_format($branch->book_copies_count) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-b-xl flex justify-between items-center">
                        <a href="{{ route('admin.branches.show', $branch) }}" class="text-sm font-medium text-brand hover:text-brand-dark transition-colors">
                            View Details &rarr;
                        </a>
                        
                        <div class="flex space-x-2">
                            @can('branches.edit')
                                <a href="{{ route('admin.branches.edit', $branch) }}" class="p-1.5 text-slate-400 hover:text-amber-500 transition-colors" title="Edit branch">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                            @endcan
                            
                            @can('branches.delete')
                                <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors" title="Delete branch" {{ $branch->users_count > 0 || $branch->book_copies_count > 0 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass-card p-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-500">
                        <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <p class="text-xl font-medium">No branches found</p>
                        <p class="mt-2">Try adjusting your filters or create a new branch.</p>
                        @can('branches.create')
                            <a href="{{ route('admin.branches.create') }}" class="btn-primary mt-6">
                                Create First Branch
                            </a>
                        @endcan
                    </div>
                </div>
            @endforelse
        </div>
        
        @if($branches->hasPages())
            <div class="mt-6">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

<div x-data="{ sidebarOpen: false }" @open-sidebar.window="sidebarOpen = true" class="flex-shrink-0 z-20">
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-40 md:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 w-64 bg-slate-900 dark:bg-slate-950 text-slate-300 transition-transform duration-300 ease-in-out z-50 flex flex-col md:translate-x-0 border-r border-slate-800">
        
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 bg-slate-950/50 border-b border-slate-800 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="h-8 w-8 rounded bg-gradient-to-br from-brand to-accent flex items-center justify-center shadow-lg">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-display font-bold text-xl text-white tracking-tight">SmartShelf</span>
            </a>
            
            <button @click="sidebarOpen = false" class="md:hidden ml-auto text-slate-400 hover:text-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
            
            <x-nav-link href="{{ route('dashboard') }}" icon="home" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-nav-link>

            @if(Auth::user()->hasAnyPermission(['books.view', 'categories.view', 'authors.view', 'publishers.view', 'book_copies.view', 'ebooks.view']))
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Catalog</p>
                </div>
                
                @if(Auth::user()->hasPermission('books.view'))
                    <x-nav-link href="{{ route('admin.books.index') }}" icon="book" :active="request()->routeIs('admin.books.*')">
                        Books
                    </x-nav-link>
                @endif
                
                @if(Auth::user()->hasPermission('book_copies.view'))
                    <x-nav-link href="{{ route('admin.book-copies.index') }}" icon="copy" :active="request()->routeIs('admin.book-copies.*')">
                        Inventory Copies
                    </x-nav-link>
                @endif
                
                @if(Auth::user()->hasPermission('categories.view'))
                    <x-nav-link href="{{ route('admin.categories.index') }}" icon="tag" :active="request()->routeIs('admin.categories.*')">
                        Categories
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('authors.view'))
                    <x-nav-link href="{{ route('admin.authors.index') }}" icon="users" :active="request()->routeIs('admin.authors.*')">
                        Authors
                    </x-nav-link>
                @endif
                
                @if(Auth::user()->hasPermission('publishers.view'))
                    <x-nav-link href="{{ route('admin.publishers.index') }}" icon="building" :active="request()->routeIs('admin.publishers.*')">
                        Publishers
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('ebooks.view'))
                    <x-nav-link href="{{ route('admin.ebooks.index') }}" icon="download" :active="request()->routeIs('admin.ebooks.*')">
                        Digital Library
                    </x-nav-link>
                @endif
            @endif

            @if(Auth::user()->hasAnyPermission(['transactions.view', 'reservations.view', 'fines.view']))
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Circulation</p>
                </div>

                @if(Auth::user()->hasPermission('transactions.view'))
                    <x-nav-link href="{{ route('admin.transactions.index') }}" icon="refresh" :active="request()->routeIs('admin.transactions.*')">
                        Loans & Returns
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('reservations.view'))
                    <x-nav-link href="{{ route('admin.reservations.index') }}" icon="clock" :active="request()->routeIs('admin.reservations.*')">
                        Reservations
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('fines.view'))
                    <x-nav-link href="{{ route('admin.fines.index') }}" icon="currency-dollar" :active="request()->routeIs('admin.fines.*')">
                        Fines & Payments
                    </x-nav-link>
                @endif
            @endif

            @if(Auth::user()->hasAnyPermission(['members.view', 'users.view', 'branches.view', 'roles.view']))
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administration</p>
                </div>

                @if(Auth::user()->hasPermission('members.view'))
                    <x-nav-link href="{{ route('admin.members.index') }}" icon="id-card" :active="request()->routeIs('admin.members.*')">
                        Members
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('users.view'))
                    <x-nav-link href="{{ route('admin.users.index') }}" icon="user-group" :active="request()->routeIs('admin.users.*')">
                        System Users
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('branches.view'))
                    <x-nav-link href="{{ route('admin.branches.index') }}" icon="library" :active="request()->routeIs('admin.branches.*')">
                        Branches
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('roles.view'))
                    <x-nav-link href="{{ route('admin.roles.index') }}" icon="shield-check" :active="request()->routeIs('admin.roles.*')">
                        Roles & Permissions
                    </x-nav-link>
                @endif
            @endif

            @if(Auth::user()->hasAnyPermission(['reports.view', 'settings.view', 'audit_logs.view']))
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">System</p>
                </div>

                @if(Auth::user()->hasPermission('reports.view'))
                    <x-nav-link href="{{ route('admin.reports.index') }}" icon="chart-bar" :active="request()->routeIs('admin.reports.*')">
                        Reports
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('audit_logs.view'))
                    <x-nav-link href="{{ route('admin.audit-logs.index') }}" icon="clipboard-list" :active="request()->routeIs('admin.audit-logs.*')">
                        Audit Logs
                    </x-nav-link>
                @endif

                @if(Auth::user()->hasPermission('settings.view'))
                    <x-nav-link href="{{ route('admin.settings.index') }}" icon="cog" :active="request()->routeIs('admin.settings.*')">
                        Settings
                    </x-nav-link>
                @endif
            @endif
            
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Personal</p>
            </div>
            
            <x-nav-link href="{{ route('notifications.index') }}" icon="bell" :active="request()->routeIs('notifications.*')">
                Notifications
                @php $unread = Auth::user()->notifications()->unread()->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto bg-accent text-white py-0.5 px-2 rounded-full text-xs font-bold">{{ $unread }}</span>
                @endif
            </x-nav-link>
            
            <x-nav-link href="{{ route('profile.edit') }}" icon="user" :active="request()->routeIs('profile.*')">
                Profile
            </x-nav-link>

        </div>
        
        <!-- User Footer -->
        <div class="p-4 bg-slate-950/50 border-t border-slate-800 shrink-0">
            <div class="flex items-center">
                <div class="h-9 w-9 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold border border-slate-700">
                    {{ Auth::user()->initials }}
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->role->name }}</p>
                </div>
            </div>
        </div>
    </aside>
</div>

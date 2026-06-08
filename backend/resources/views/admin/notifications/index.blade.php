<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
            Notifications
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-2">
                <a href="{{ route('notifications.index') }}" class="px-4 py-2 rounded-full text-sm font-medium {{ !request()->has('unread') ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }} transition-colors">
                    All
                </a>
                <a href="{{ route('notifications.index', ['unread' => 1]) }}" class="px-4 py-2 rounded-full text-sm font-medium {{ request()->has('unread') ? 'bg-brand text-white shadow-md shadow-brand/30' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }} transition-colors">
                    Unread
                </a>
            </div>

            @if($notifications->count() > 0)
                <form action="{{ route('notifications.read.all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-brand hover:text-brand-dark transition-colors flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="glass-card p-4 flex sm:items-center sm:flex-row flex-col gap-4 relative overflow-hidden group {{ !$notification->read_at ? 'ring-1 ring-brand/50 bg-brand/5 dark:bg-brand/10' : '' }}">
                    @if(!$notification->read_at)
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand"></div>
                    @endif
                    
                    <!-- Icon -->
                    <div class="flex-shrink-0 sm:self-center mt-1 sm:mt-0">
                        @if($notification->notification_type === 'alert' || $notification->notification_type === 'warning')
                            <div class="h-10 w-10 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                        @elseif($notification->notification_type === 'success')
                            <div class="h-10 w-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-full bg-brand/10 flex items-center justify-center text-brand">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-1">
                            <h4 class="text-base font-bold text-slate-900 dark:text-white {{ !$notification->read_at ? '' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $notification->title }}
                            </h4>
                            <span class="text-xs text-slate-500 whitespace-nowrap sm:ml-4 sm:mt-0 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $notification->message }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex-shrink-0 flex items-center space-x-2 self-start sm:self-center mt-3 sm:mt-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2 rounded-full text-slate-400 hover:text-brand hover:bg-brand/10 transition-colors" title="Mark as read">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-full text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors" title="Delete">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="glass-card p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-1">All caught up!</h3>
                    <p class="text-slate-500 dark:text-slate-400">You don't have any {{ request()->has('unread') ? 'unread ' : '' }}notifications at the moment.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="pt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

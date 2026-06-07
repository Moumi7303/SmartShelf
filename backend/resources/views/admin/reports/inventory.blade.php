<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.reports.index') }}" class="text-slate-500 hover:text-brand transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
                Inventory Report
            </h2>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="glass-card mb-8 p-6">
        <form action="{{ route('admin.reports.inventory') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="branch_id" class="label-field">Branch</label>
                <select name="branch_id" id="branch_id" class="input-field">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="category_id" class="label-field">Category</label>
                <select name="category_id" id="category_id" class="input-field">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2 md:col-span-2">
                <button type="submit" class="btn-primary flex-1 justify-center">Generate Report</button>
                <button type="submit" name="export" value="pdf" class="btn-secondary flex-1 justify-center">Export PDF</button>
                <button type="submit" name="export" value="excel" class="btn-secondary flex-1 justify-center text-emerald-600 border-emerald-200 hover:bg-emerald-50 dark:hover:bg-emerald-900/30">Export Excel</button>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="glass-card p-4 border-l-4 border-brand">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Titles</p>
            <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['total_books']) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-accent">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Copies</p>
            <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">{{ number_format($stats['total_copies']) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-emerald-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Available</p>
            <p class="text-2xl font-bold font-heading text-emerald-600">{{ number_format($stats['available_copies']) }}</p>
        </div>
        <div class="glass-card p-4 border-l-4 border-rose-500">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Checked Out/Lost</p>
            <p class="text-2xl font-bold font-heading text-rose-600">{{ number_format($stats['unavailable_copies']) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white">Inventory Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Book Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total Copies</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Available</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Checked Out</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Lost/Damaged</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($inventory as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-slate-900 dark:text-white">{{ Str::limit($item->title, 40) }}</div>
                                <div class="text-xs text-slate-500">{{ $item->author_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $item->category_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900 dark:text-white">
                                {{ $item->total_copies }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-600 font-bold">
                                {{ $item->available_copies }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-600 font-bold">
                                {{ $item->issued_copies }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-rose-600 font-bold">
                                {{ $item->lost_copies + $item->damaged_copies }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">No inventory data found matching the criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($inventory->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $inventory->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

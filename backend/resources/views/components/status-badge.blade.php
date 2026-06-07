@props(['status'])

@php
$classes = match($status) {
    'active', 'available', 'paid', 'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800',
    
    'inactive', 'suspended', 'cancelled', 'expired', 'lost', 'damaged' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800',
    
    'pending', 'issued', 'checked_out', 'unpaid', 'reserved' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800',
    
    'returned', 'waived', 'partial', 'maintenance' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
    
    default => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
};

$label = match($status) {
    'checked_out' => 'Checked Out',
    default => ucfirst($status),
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>

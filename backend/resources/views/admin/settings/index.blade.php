<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-display font-semibold text-slate-800 dark:text-white">
            System Settings
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            @foreach($settings as $group => $groupSettings)
                <div class="glass-card">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-t-xl">
                        <h3 class="text-lg font-bold font-heading text-slate-900 dark:text-white capitalize">
                            {{ str_replace('_', ' ', $group) }} Settings
                        </h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @foreach($groupSettings as $setting)
                            <div>
                                <label for="{{ $setting->key }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                </label>
                                
                                @if($setting->type === 'boolean')
                                    <div class="mt-2">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="{{ $setting->key }}" value="0">
                                            <input type="checkbox" name="{{ $setting->key }}" id="{{ $setting->key }}" value="1" class="sr-only peer" {{ $setting->value == '1' ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/30 dark:peer-focus:ring-brand/80 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-brand"></div>
                                        </label>
                                    </div>
                                @elseif($setting->type === 'integer')
                                    <input type="number" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}" class="input-field max-w-xs">
                                @elseif($setting->type === 'text')
                                    <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3" class="input-field">{{ $setting->value }}</textarea>
                                @else
                                    <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" value="{{ $setting->value }}" class="input-field max-w-lg">
                                @endif
                                
                                @if($setting->description)
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $setting->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end sticky bottom-6 z-10 pt-4">
                <button type="submit" class="btn-primary shadow-lg shadow-brand/30 px-8 py-3 text-base">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

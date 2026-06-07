<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartShelf') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600|outfit:500,600,700|fraunces:700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full flex text-slate-900">
        
        <!-- Left Side: Image/Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:flex-1 lg:flex-col lg:justify-center bg-brand text-white relative overflow-hidden">
            <!-- Decorative background pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="books" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                            <path d="M10 10h40v40H10zM15 15h30v30H15z" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M25 10v40M35 10v40M10 25h40M10 35h40" stroke="currentColor" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#books)" />
                </svg>
            </div>
            
            <div class="relative z-10 p-12 max-w-2xl mx-auto">
                <div class="flex items-center space-x-4 mb-8">
                    <div class="h-12 w-12 rounded-lg bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-xl">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-3xl text-display font-bold tracking-tight">SmartShelf</span>
                </div>
                
                <h1 class="text-5xl font-heading font-bold leading-tight mb-6">
                    Manage your library<br>
                    <span class="text-accent-light">effortlessly.</span>
                </h1>
                
                <p class="text-lg text-brand-100 mb-10 max-w-lg leading-relaxed">
                    The enterprise multi-branch library management system designed for speed, scale, and a premium user experience.
                </p>

                <div class="flex items-center space-x-[-10px]">
                    <div class="h-10 w-10 rounded-full bg-white border-2 border-brand overflow-hidden"><img src="https://ui-avatars.com/api/?name=J+D&background=random" alt="Avatar"></div>
                    <div class="h-10 w-10 rounded-full bg-white border-2 border-brand overflow-hidden"><img src="https://ui-avatars.com/api/?name=S+M&background=random" alt="Avatar"></div>
                    <div class="h-10 w-10 rounded-full bg-white border-2 border-brand overflow-hidden"><img src="https://ui-avatars.com/api/?name=A+R&background=random" alt="Avatar"></div>
                    <div class="h-10 w-10 rounded-full bg-brand-light border-2 border-brand flex items-center justify-center text-xs font-bold">+2k</div>
                    <p class="ml-6 text-sm font-medium">Trusted by librarians worldwide</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="flex items-center justify-center lg:hidden mb-8">
                    <div class="h-10 w-10 rounded-lg bg-brand flex items-center justify-center shadow-md">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <span class="ml-3 text-2xl text-display font-bold text-slate-900">SmartShelf</span>
                </div>

                <div class="bg-white py-8 px-6 shadow-xl border border-slate-100 rounded-2xl sm:px-10">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 text-center text-xs text-slate-500">
                    &copy; {{ date('Y') }} SmartShelf Library Systems. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartShelf API System</title>
    <meta name="description" content="SmartShelf - Smart Multi-Branch University Library Management System API">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Tailwind CSS (via CDN for standalone view) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                            950: '#082f49',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 antialiased min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    
    <!-- Decorative background elements -->
    <div class="absolute inset-0 z-0 flex items-center justify-center opacity-30 pointer-events-none">
        <div class="w-[800px] h-[800px] bg-brand-500 rounded-full blur-[120px] opacity-10"></div>
    </div>

    <main class="relative z-10 w-full max-w-2xl mx-auto px-6 py-12 flex flex-col items-center text-center">
        <!-- Logo Icon -->
        <div class="mb-8 w-20 h-20 bg-brand-600 rounded-2xl flex items-center justify-center shadow-xl shadow-brand-500/20 ring-1 ring-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>

        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-4">
            Smart<span class="text-brand-500">Shelf</span> API
        </h1>
        
        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-lg">
            Smart Multi-Branch University Library Management System. <br/>
            Secure, scalable, and enterprise-ready.
        </p>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-sm w-full text-left">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <h2 class="font-semibold text-lg">System Status: <span class="text-green-600 dark:text-green-500">Operational</span></h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">API Version</p>
                    <p class="font-medium font-mono text-brand-600 dark:text-brand-400">v1.0.0</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Environment</p>
                    <p class="font-medium font-mono capitalize">{{ app()->environment() }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-950 border border-gray-100 dark:border-gray-800 sm:col-span-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Frontend Application</p>
                    <p class="text-sm">The dashboard is securely served from the TanStack Start client application.</p>
                </div>
            </div>
        </div>

    </main>

    <footer class="absolute bottom-6 w-full text-center text-sm text-gray-500 dark:text-gray-500 z-10">
        &copy; {{ date('Y') }} SmartShelf - All Rights Reserved.
    </footer>
</body>
</html>

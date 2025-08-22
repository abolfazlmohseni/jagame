<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاگیم - بهترین گیم نت های شهر</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4B3F72',
                        secondary: '#8E7CC3',
                        accent: '#FFD447',
                        surface: '#FFFFFF',
                        'text-dark': '#111827',
                        'text-on-dark': '#FFFFFF',
                        muted: '#6B7280'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-text-dark">
    <!-- Header -->
    <header class="gradient-bg text-text-on-dark shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-accent rounded-lg flex items-center justify-center">
                        <span class="text-lg sm:text-2xl">🎮</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold">جاگیم</h1>
                </div>
                <nav class="hidden md:flex gap-3">
                    <a href="#" class="hover:text-accent transition-colors text-sm lg:text-base">درباره ما</a>
                    <a href="#" class="hover:text-accent transition-colors text-sm lg:text-base">تماس</a>
                    <a href=<?= home_url('/login/') ?> class="hover:text-accent transition-colors text-sm lg:text-base">ورود اپراتور</a>
                </nav>
                <button class="md:hidden text-text-on-dark">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
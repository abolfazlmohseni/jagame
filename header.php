<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاگیم - بهترین گیم نت های شهر</title>
    <?php wp_head() ?>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4B3F72 0%, #8E7CC3 100%);
        }
    </style>
</head>

<body class="bg-gray-50 text-text-dark">
    <!-- Header -->
    <header class="gradient-bg text-text-on-dark shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a class="flex items-center space-x-3 space-x-reverse" href="https://jagame.hodecode.ir">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-accent rounded-lg flex items-center justify-center">
                        <span class="text-lg sm:text-2xl">🎮</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold">جاگیم</h1>
                </a>
                <?php
                wp_nav_menu([
                    'theme_location' => 'landing header',
                    'menu_class' => 'hidden md:flex gap-7',
                    'container' => false
                ])
                ?>
                <button id="mobile-menu-button" class="md:hidden text-text-on-dark">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- منوی موبایل -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-2">
                <?php
                wp_nav_menu([
                    'theme_location' => 'landing header',
                    'menu_class' => 'space-y-2 hover:text-accent transition-colors',
                    'container' => false
                ])
                ?>
            </div>
        </div>
    </header>


    <script>
        // اسکریپت ساده برای منوی موبایل
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
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
    <!-- در بخش head یا قبل از اسکریپت‌ها -->
<!-- در بخش head یا قبل از اسکریپت‌ها -->
<script src="https://cdn.jsdelivr.net/npm/jalaali-js@1.2.3/dist/jalaali.js"></script>
</head>

<body class="bg-gray-50 text-text-dark">
    <!-- Header -->
    <header class="gradient-bg text-text-on-dark shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
               <div class="flex gap-1 items-center">
                    <?php
                    if (function_exists("the_custom_logo")) {
                        the_custom_logo();
                    }
                    ?>
                    <h1 class="text-xl sm:text-2xl font-bold">جاگیم</h1>
            </div>
                <div class="md:flex items-center justify-center gap-7 hidden">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'landing header',
                        'menu_class' => 'hidden md:flex gap-7',
                        'container' => false
                    ])
                    ?>
                    <?php
                    if (is_user_logged_in()) {
                    ?>
                        <div class="flex items-center justify-between shadow-md rounded-xl px-2 py-1.5 gap-1 text-white bg-secondary">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                            <a href="<?php echo home_url("index.php/login-page/") ?>">حساب کاربری</a>
                        </div>

                    <?php
                    } else {
                    ?>
                        <div class="flex items-center justify-between shadow-md rounded-xl px-2 py-1.5 gap-1 text-white bg-secondary">
                            <a href="<?php echo home_url("index.php/login-page/") ?>" class="content-center">ورود | ثبت نام</a>
                        </div>
                    <?php
                    }


                    ?>

                </div>

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

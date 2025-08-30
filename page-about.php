<?php
/* Template Name: About Us Page */
get_header();
?>
<style>
    body {
        font-family: 'Vazir', sans-serif;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #4B3F72 0%, #8E7CC3 100%);
    }
    
    .mission-vision-card {
        transition: all 0.4s ease;
    }
    .mission-vision-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درباره ما</title>
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
                        darkText: '#111827',
                        lightText: '#FFFFFF',
                        mutedText: '#6B7280',
                    }
                }
            }
        }
    </script>
    <!-- Vazir font for Persian support -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/font-face.css" rel="stylesheet" type="text/css" />
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-surface" style="font-family: Vazir, sans-serif;">
    <!-- Main About Us Section -->
    <main class="pt-24 pb-16">
        <section class="container mx-auto px-6 py-10">
            <!-- Heading -->
            <h1 class="text-primary font-bold text-3xl md:text-4xl lg:text-5xl text-center mb-6 animate__animated animate__fadeIn">
                داستان ما چیه ؟
            </h1>
            
            <!-- Introduction Paragraph -->
            <p class="text-mutedText text-lg text-center max-w-3xl mx-auto mb-12 animate__animated animate__fadeIn animate__delay-1s">
                ما یک تیم خلاق هستیم که با هدف ایجاد راه‌ حل‌ های نوآورانه در زمینه گیم‌ نت و تجربه کاربری بهتر برای کاربران این پروژه رو راه‌ اندازی کردیم.
            </p>
            
            <!-- Mission and Vision Sections -->
            <div class="flex flex-col md:flex-row gap-8 mb-16">
                <!-- Mission Section -->
                <div class="mission-vision-card bg-gray-100 rounded-xl p-8 w-full md:w-1/2 animate__animated animate__fadeIn">
                    <h2 class="text-primary text-2xl md:text-3xl font-bold text-center mb-6">ماموریت ما</h2>
                    <p class="text-mutedText text-lg text-center leading-8">
                        ماموریت ما این است که با ارائه راه‌حل‌های نوآورانه و باکیفیت در زمینه گیم‌نت، تجربه‌ای منحصر به فرد برای کاربران ایجاد کنیم. هدف ما این است که به مرور زمان بخش‌های مختلف گیم‌نت‌ها را دیجیتالی کرده و به صورت آنلاین در اختیار کاربران قرار دهیم.
                    </p>
                </div>
                
                <!-- Vision Section -->
                <div class="mission-vision-card bg-gray-100 rounded-xl p-8 w-full md:w-1/2 animate__animated animate__fadeIn animate__delay-1s">
                    <h2 class="text-primary text-2xl md:text-3xl font-bold text-center mb-6">چشم‌انداز ما</h2>
                    <p class="text-mutedText text-lg text-center leading-8">
                        چشم‌انداز ما این است که در آینده‌ای نزدیک، تمامی گیم‌نت‌های مشهد را تحت پوشش قرار دهیم و سپس این خدمات را به دیگر شهرها گسترش دهیم. هدف ما توسعه تیم و گسترش دامنه خدمات برای تمامی علاقه‌مندان به بازی‌های آنلاین است.
                    </p>
                </div>
            </div>
            
            <!-- Team Members Section -->
            <div class="bg-gray-100 py-16 px-4 rounded-lg">
                <h2 class="text-primary text-2xl md:text-3xl font-bold text-center mb-12">تیم ما</h2>
                
                <div class="flex flex-wrap justify-center gap-8">

                    <div class="text-center transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg p-4">
                        <img src="https://avatars.githubusercontent.com/u/131040476?v=4" alt="Team Member" class="w-36 h-36 rounded-full mx-auto mb-4 object-cover">
                        <h3 class="text-primary text-xl font-bold">ابوالفضل محسنی</h3>
                        <p class="text-mutedText">Founder & Developer</p>
                    </div>

                    <div class="text-center transition-all duration-300 hover:scale-105 hover:shadow-lg rounded-lg p-4">
                        <img src="https://avatars.githubusercontent.com/u/225074918?v=4" alt="Team Member" class="w-36 h-36 rounded-full mx-auto mb-4 object-cover">
                        <h3 class="text-primary text-xl font-bold">پویان داوودی</h3>
                        <p class="text-mutedText">Co-Founder & Developer</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>

<?php
get_footer();
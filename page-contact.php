<?php
/* Template Name: Contact Us Page */
get_header();
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تماس با ما</title>
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
    <!-- Iconify for icons -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        body {
            font-family: Vazir, sans-serif;
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
        .contact-card {
            transition: all 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .social-icon {
            transition: all 0.3s ease;
        }
        .social-icon:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-surface min-h-screen flex flex-col">

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8 md:py-12">
        <!-- Page Title -->
        <h1 class="text-primary font-bold text-3xl md:text-4xl lg:text-5xl text-center mb-6 animate__animated animate__fadeIn">
            تماس با ما
        </h1>
        
        <!-- Guide Text -->
        <p class="text-mutedText text-lg text-center max-w-2xl mx-auto mb-8 animate__animated animate__fadeIn">
            اگر سوالی دارید یا نیاز به کمک دارید، یا اگر میخواهید گیم نت خود را ثبت کنید، می‌توانید از طریق اطلاعات تماس زیر با ما ارتباط برقرار کنید.
        </p>
        
        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Email Card -->
            <div class="contact-card bg-gray-50 rounded-xl p-6 text-center animate__animated animate__fadeInUp">
                <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="ic:outline-email" width="32" height="32" class="text-lightText"></iconify-icon>
                </div>
                <h3 class="text-primary text-xl font-bold mb-2">ایمیل</h3>
                <p class="text-mutedText">jagame.ir@gmail.com</p>
            </div>
            
            <!-- Phone Card -->
            <div class="contact-card bg-gray-50 rounded-xl p-6 text-center animate__animated animate__fadeInUp animate__delay-1s">
                <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="ic:outline-phone" width="32" height="32" class="text-lightText"></iconify-icon>
                </div>
                <h3 class="text-primary text-xl font-bold mb-2">تلفن</h3>
                <p class="text-mutedText">09966403919</p>
            </div>
            
            <!-- Address Card -->
            <div class="contact-card bg-gray-50 rounded-xl p-6 text-center animate__animated animate__fadeInUp animate__delay-2s">
                <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="ic:outline-location-on" width="32" height="32" class="text-lightText"></iconify-icon>
                </div>
                <h3 class="text-primary text-xl font-bold mb-2">آدرس</h3>
                <p class="text-mutedText">مشهد، بلوار ارشاد، دهخدا 15</p>
            </div>
        </div>
        
        <!-- Social Media Section -->
        <div class="text-center animate__animated animate__fadeIn">
            <h2 class="text-primary text-2xl md:text-3xl font-bold mb-6">ما را در شبکه‌های اجتماعی دنبال کنید</h2>
            <div class="flex justify-center gap-3">
                <!-- Instagram -->
                <a href="#" class="social-icon bg-secondary rounded-full w-14 h-14 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                    <iconify-icon icon="mdi:instagram" width="28" height="28" class="text-lightText"></iconify-icon>
                </a>
                
                <!-- LinkedIn -->
                <a href="#" class="social-icon bg-secondary rounded-full w-14 h-14 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                    <iconify-icon icon="mdi:linkedin" width="28" height="28" class="text-lightText"></iconify-icon>
                </a>
                
                <!-- Facebook -->
                <a href="#" class="social-icon bg-secondary rounded-full w-14 h-14 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                    <iconify-icon icon="mdi:facebook" width="28" height="28" class="text-lightText"></iconify-icon>
                </a>
                
                <!-- Twitter -->
                <a href="#" class="social-icon bg-secondary rounded-full w-14 h-14 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                    <iconify-icon icon="mdi:twitter" width="28" height="28" class="text-lightText"></iconify-icon>
                </a>
            </div>
        </div>
    </main>
</body>
</html>

<?php
get_footer();
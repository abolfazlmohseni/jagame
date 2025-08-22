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
                    },
                    fontFamily: {
                        'vazir': ['Vazir', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Vazir:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazir', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #4B3F72 0%, #8E7CC3 100%);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(75, 63, 114, 0.25);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-slide-right {
            animation: slideInRight 0.8s ease-out;
        }

        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .animate-bounce-in {
            animation: bounceIn 0.8s ease-out;
        }

        .filter-section {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease-out 0.3s forwards;
        }

        .hero-content {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease-out 0.2s forwards;
        }

        .feature-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.3s ease;
        }

        .feature-card:nth-child(1) {
            animation: fadeInUp 0.6s ease-out 0.1s forwards;
        }

        .feature-card:nth-child(2) {
            animation: fadeInUp 0.6s ease-out 0.3s forwards;
        }

        .feature-card:nth-child(3) {
            animation: fadeInUp 0.6s ease-out 0.5s forwards;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        button:hover {
            transform: translateY(-2px);
        }

        .card-stagger:nth-child(1) {
            animation-delay: 0.1s;
        }

        .card-stagger:nth-child(2) {
            animation-delay: 0.2s;
        }

        .card-stagger:nth-child(3) {
            animation-delay: 0.3s;
        }

        .card-stagger:nth-child(4) {
            animation-delay: 0.4s;
        }

        .card-stagger:nth-child(5) {
            animation-delay: 0.5s;
        }

        .card-stagger:nth-child(6) {
            animation-delay: 0.6s;
        }

        @media (max-width: 640px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card-hover:hover {
                transform: translateY(-3px) scale(1.01);
            }

            .feature-card:hover {
                transform: translateY(-3px);
            }
        }

        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }
    </style>
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
                    <a href=<?= home_url('/login/')?> class="hover:text-accent transition-colors text-sm lg:text-base">ورود اپراتور</a>
                </nav>
                <button class="md:hidden text-text-on-dark">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg text-text-on-dark py-12 sm:py-16 lg:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center hero-content">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">بهترین گیم نت های مشهد را پیدا کنید</h2>
            <p class="text-base sm:text-lg lg:text-xl mb-6 sm:mb-8 opacity-90 max-w-3xl mx-auto">با جاگیم، گیم نت مناسب خودتون رو بر اساس منطقه، قیمت و امکانات پیدا کنید</p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="py-8 sm:py-12 bg-surface">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-xl sm:text-2xl font-bold text-center mb-6 sm:mb-8">فیلتر کردن گیم نت ها</h3>
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 filter-section">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
                    <!-- Gender Filter -->
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-muted">جنسیت</label>
                        <select id="genderFilter" class="w-full p-2 sm:p-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="mixed">مختلط</option>
                            <option value="male">آقایان</option>
                            <option value="female">بانوان</option>
                        </select>
                    </div>

                    <!-- Area Filter -->
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-muted">منطقه</label>
                        <select id="areaFilter" class="w-full p-2 sm:p-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">همه مناطق</option>
                            <option value="north">منطقه یک</option>
                            <option value="south">منطقه دو</option>
                            <option value="east">منطقه سه</option>
                        </select>
                    </div>

                    <!-- Device Filter -->
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-muted">نوع دستگاه</label>
                        <select id="deviceFilter" class="w-full p-2 sm:p-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">همه دستگاه ها</option>
                            <option value="pc">کامپیوتر</option>
                            <option value="ps5">پلی استیشن 5</option>
                            <option value="ps4">پلی استیشن 4</option>
                            <option value="xbox">ایکس باکس</option>
                            <option value="vr">واقعیت مجازی</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-muted">قیمت (هزار تومان)</label>
                        <select id="priceFilter" class="w-full p-2 sm:p-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">همه قیمت ها</option>
                            <option value="low">کمتر از 40</option>
                            <option value="medium">40تا60</option>
                            <option value="high">بیشتر از 60</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end sm:col-span-2 lg:col-span-1">
                        <button onclick="filterGameNets()" class="w-full bg-primary text-text-on-dark py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base hover:bg-opacity-90 transition-colors">
                            جستجو
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Game Nets Grid -->
    <section class="py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-xl sm:text-2xl font-bold text-center mb-6 sm:mb-8">گیم نت های موجود</h3>
            <div id="gameNetsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                <!-- Game net cards will be populated here -->
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 sm:py-16 bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <h3 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">چرا جاگیم؟</h3>
                <p class="text-base sm:text-lg text-muted max-w-2xl mx-auto">ما بهترین پلتفرم برای پیدا کردن گیم نت های با کیفیت هستیم</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="text-center feature-card">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <span class="text-xl sm:text-2xl text-text-on-dark">🔍</span>
                    </div>
                    <h4 class="text-lg sm:text-xl font-semibold mb-2">جستجوی آسان</h4>
                    <p class="text-sm sm:text-base text-muted">با فیلترهای پیشرفته، گیم نت مناسب خودتون رو پیدا کنید</p>
                </div>
                <div class="text-center feature-card">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-secondary rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <span class="text-xl sm:text-2xl text-text-on-dark">⭐</span>
                    </div>
                    <h4 class="text-lg sm:text-xl font-semibold mb-2">کیفیت تضمینی</h4>
                    <p class="text-sm sm:text-base text-muted">همه گیم نت ها توسط تیم ما بررسی و تایید شده اند</p>
                </div>
                <div class="text-center feature-card sm:col-span-2 lg:col-span-1">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <span class="text-xl sm:text-2xl text-text-dark">💰</span>
                    </div>
                    <h4 class="text-lg sm:text-xl font-semibold mb-2">قیمت مناسب</h4>
                    <p class="text-sm sm:text-base text-muted">بهترین قیمت ها رو برای شما پیدا می کنیم</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gradient-bg text-text-on-dark py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center space-x-3 space-x-reverse mb-4">
                        <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center">
                            <span class="text-lg">🎮</span>
                        </div>
                        <h4 class="text-lg sm:text-xl font-bold">جاگیم</h4>
                    </div>
                </div>
                <div>
                    <h5 class="font-semibold mb-3 sm:mb-4 text-sm sm:text-base">لینک های مفید</h5>
                    <ul class="space-y-2 opacity-90 text-sm sm:text-base">
                        <li><a href="#" class="hover:text-accent transition-colors">درباره ما</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">تماس با ما</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-3 sm:mb-4 text-sm sm:text-base">خدمات</h5>
                    <ul class="space-y-2 opacity-90 text-sm sm:text-base">
                        <li><a href="#" class="hover:text-accent transition-colors">لیست گیم نت ها</a></li>
                        <li><a href="#" class="hover:text-accent transition-colors">رزرو آنلاین</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-3 sm:mb-4 text-sm sm:text-base">تماس با ما</h5>
                    <div class="space-y-2 opacity-90 text-sm sm:text-base">
                        <p>📧 info@jagim.com</p>
                        <p>📍 مشهد،ایران</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Sample game net data
        const gameNets = [{
                id: 1,
                name: "گیم نت آریا",
                area: "north",
                gender: "mixed",
                price: "medium",
                priceValue: 15,
                address: "شمال شهر، خیابان ولیعصر",
                devices: ["ps5", "pc", "vr"],
                features: ["PS5", "PC Gaming", "VR", "کافی شاپ"]
            },
            {
                id: 2,
                name: "گیم استیشن پارس",
                area: "center",
                gender: "male",
                price: "high",
                priceValue: 25,
                address: "مرکز شهر، میدان انقلاب",
                devices: ["pc", "ps5"],
                features: ["RTX 4090", "144Hz Monitor", "Gaming Chair"]
            },
            {
                id: 3,
                name: "بانوان گیمر",
                area: "west",
                gender: "female",
                price: "low",
                priceValue: 8,
                address: "غرب شهر، اکباتان",
                devices: ["ps4", "pc"],
                features: ["محیط امن", "کادر زن", "PS4"]
            },
            {
                id: 4,
                name: "گیم سنتر مدرن",
                area: "south",
                gender: "mixed",
                price: "medium",
                priceValue: 18,
                address: "جنوب شهر، شهرک غرب",
                devices: ["xbox", "nintendo", "pc"],
                features: ["Xbox Series X", "Nintendo Switch", "Arcade"]
            },
            {
                id: 5,
                name: "پرو گیمرز",
                area: "east",
                gender: "mixed",
                price: "high",
                priceValue: 30,
                address: "شرق شهر، تهرانپارس",
                devices: ["pc", "ps5", "xbox"],
                features: ["Esports Setup", "Streaming", "Tournament"]
            },
            {
                id: 6,
                name: "فمیلی گیم",
                area: "center",
                gender: "mixed",
                price: "low",
                priceValue: 12,
                address: "مرکز شهر، بازار",
                devices: ["nintendo", "ps4", "pc"],
                features: ["Family Friendly", "Kids Area", "Snacks"]
            },
            {
                id: 7,
                name: "وی آر ورلد",
                area: "north",
                gender: "mixed",
                price: "high",
                priceValue: 35,
                address: "شمال شهر، نیاوران",
                devices: ["vr", "pc"],
                features: ["VR Gaming", "واقعیت مجازی", "تجربه منحصر"]
            },
            {
                id: 8,
                name: "کنسول کافه",
                area: "west",
                gender: "mixed",
                price: "medium",
                priceValue: 16,
                address: "غرب شهر، ستارخان",
                devices: ["ps5", "xbox", "nintendo"],
                features: ["همه کنسول ها", "کافی شاپ", "محیط دنج"]
            }
        ];

        let filteredGameNets = [...gameNets];

        function createGameNetCard(gameNet) {
            const genderText = gameNet.gender === 'mixed' ? 'مختلط' : gameNet.gender === 'male' ? 'آقایان' : 'بانوان';

            return `
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover card-stagger">
                    <div class="p-4 sm:p-6">
                        <div class="mb-3 sm:mb-4">
                            <h4 class="text-lg sm:text-xl font-bold text-text-dark">${gameNet.name}</h4>
                        </div>
                        
                        <div class="space-y-2 mb-3 sm:mb-4">
                            <div class="flex items-center space-x-2 space-x-reverse text-xs sm:text-sm text-muted">
                                <span>📍</span>
                                <span class="line-clamp-1">${gameNet.address}</span>
                            </div>
                            <div class="flex items-center space-x-3 sm:space-x-4 space-x-reverse text-xs sm:text-sm">
                                <div class="flex items-center space-x-1 space-x-reverse">
                                    <span>👥</span>
                                    <span>${genderText}</span>
                                </div>
                                <div class="flex items-center space-x-1 space-x-reverse">
                                    <span>💰</span>
                                    <span class="whitespace-nowrap">${gameNet.priceValue} هزار تومان</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-1 sm:gap-2 mb-3 sm:mb-4">
                            ${gameNet.features.map(feature => 
                                `<span class="bg-gray-100 text-xs px-2 py-1 rounded-full">${feature}</span>`
                            ).join('')}
                        </div>
                        
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 sm:space-x-reverse">
                            <button class="flex-1 bg-primary text-text-on-dark py-2 sm:py-2 rounded-lg font-semibold text-sm sm:text-base hover:bg-opacity-90 transition-colors">
                                مشاهده جزئیات
                            </button>
                            <button class="flex-1 bg-accent text-text-dark py-2 sm:py-2 rounded-lg font-semibold text-sm sm:text-base hover:bg-yellow-400 transition-colors">
                                رزرو آنلاین
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderGameNets(gameNetsToRender = filteredGameNets) {
            const grid = document.getElementById('gameNetsGrid');
            if (gameNetsToRender.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">😔</div>
                        <h3 class="text-xl font-semibold mb-2">هیچ گیم نتی پیدا نشد</h3>
                        <p class="text-muted">لطفاً فیلترهای خود را تغییر دهید</p>
                    </div>
                `;
            } else {
                grid.innerHTML = gameNetsToRender.map(createGameNetCard).join('');
            }
        }

        function filterGameNets() {
            const genderFilter = document.getElementById('genderFilter').value;
            const areaFilter = document.getElementById('areaFilter').value;
            const deviceFilter = document.getElementById('deviceFilter').value;
            const priceFilter = document.getElementById('priceFilter').value;

            filteredGameNets = gameNets.filter(gameNet => {
                const genderMatch = !genderFilter || gameNet.gender === genderFilter;
                const areaMatch = !areaFilter || gameNet.area === areaFilter;
                const deviceMatch = !deviceFilter || gameNet.devices.includes(deviceFilter);

                let priceMatch = true;
                if (priceFilter === 'low') priceMatch = gameNet.priceValue < 10;
                else if (priceFilter === 'medium') priceMatch = gameNet.priceValue >= 10 && gameNet.priceValue <= 20;
                else if (priceFilter === 'high') priceMatch = gameNet.priceValue > 20;

                return genderMatch && areaMatch && deviceMatch && priceMatch;
            });

            renderGameNets();
        }

        // Initial render
        renderGameNets();
    </script>

</html>
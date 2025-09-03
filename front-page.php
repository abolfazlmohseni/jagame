<?php 

get_header(); 
?>

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

    .card-stagger:nth-child(1) { animation-delay: 0.1s; }
    .card-stagger:nth-child(2) { animation-delay: 0.2s; }
    .card-stagger:nth-child(3) { animation-delay: 0.3s; }
    .card-stagger:nth-child(4) { animation-delay: 0.4s; }
    .card-stagger:nth-child(5) { animation-delay: 0.5s; }
    .card-stagger:nth-child(6) { animation-delay: 0.6s; }

    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #4B3F72;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

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
                        <option value="">همه</option>
                        <option value="مختلط">مختلط</option>
                        <option value="آقایان">آقایان</option>
                        <option value="بانوان">بانوان</option>
                    </select>
                </div>

                <!-- Age Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-muted">شرایط سنی</label>
                    <select id="ageFilter" class="w-full p-2 sm:p-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="">همه</option>
                        <option value="12">12 سال به بالا</option>
                        <option value="15">15 سال به بالا</option>
                        <option value="18">18 سال به بالا</option>
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

                <!-- Price Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-muted">قیمت (هزار تومان)</label>
                    <select id="priceFilter" class="w-full p-2 sm:p-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="">همه قیمت ها</option>
                        <option value="low">کمتر از 40</option>
                        <option value="medium">40 تا 60</option>
                        <option value="high">بیشتر از 60</option>
                    </select>
                </div>

                <!-- Search Button -->
                <div class="flex items-end sm:col-span-2 lg:col-span-1">
                    <button onclick="loadGameNets(1)" class="w-full bg-primary text-text-on-dark py-2 sm:py-3 rounded-lg font-semibold text-sm sm:text-base hover:bg-opacity-90 transition-colors">
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
        
        <!-- Loading -->
        <div id="loading" class="text-center py-8">
            <div class="loading-spinner"></div>
            <p class="mt-2 text-gray-600">در حال بارگذاری گیم نت‌ها...</p>
        </div>

        <!-- Game Nets List -->
        <div id="gameNetsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <!-- محتوای AJAX اینجا لود میشه -->
        </div>

        <!-- No Results -->
        <div id="noResults" class="text-center py-8 hidden">
            <div class="text-6xl mb-4">😔</div>
            <h3 class="text-xl font-semibold mb-2">هیچ گیم نتی پیدا نشد</h3>
            <p class="text-muted">لطفاً فیلترهای خود را تغییر دهید</p>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="flex justify-center items-center gap-x-2 mt-8">
            <!-- pagination buttons -->
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

<script>
// تعریف آبجکت AJAX
const gameNetsAjax = {
    ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
    nonce: "<?php echo wp_create_nonce('game_nets_list_nonce'); ?>",
    current_page: 1,
    per_page: 8
};

// تابع برای لود گیم نت‌ها
function loadGameNets(page = 1) {
    const loading = document.getElementById('loading');
    const gameNetsGrid = document.getElementById('gameNetsGrid');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');

    // نمایش loading
    loading.classList.remove('hidden');
    gameNetsGrid.innerHTML = '';
    noResults.classList.add('hidden');
    pagination.innerHTML = '';

    // آماده کردن داده‌ها
    const formData = new FormData();
    formData.append('action', 'get_game_nets_list');
    formData.append('security', gameNetsAjax.nonce);
    formData.append('page', page);
    formData.append('per_page', gameNetsAjax.per_page);
    
    // اضافه کردن فیلترها
    const genderFilter = document.getElementById('genderFilter').value;
    const ageFilter = document.getElementById('ageFilter').value;
    const areaFilter = document.getElementById('areaFilter').value;
    const priceFilter = document.getElementById('priceFilter').value;
    
    if (genderFilter) formData.append('gender', genderFilter);
    if (ageFilter) formData.append('age', ageFilter);
    if (areaFilter) formData.append('area', areaFilter);
    if (priceFilter) formData.append('price', priceFilter);

    // ارسال درخواست
    fetch(gameNetsAjax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayGameNets(data.data.game_nets);
            updatePagination(data.data.pagination);
        } else {
            showNoResults();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNoResults();
    })
    .finally(() => {
        loading.classList.add('hidden');
    });
}

// تابع برای نمایش گیم نت‌ها
function escapeHtml(unsafe) {
    if (!unsafe && unsafe !== 0) return '';
    return String(unsafe)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function displayGameNets(gameNets) {
    const gameNetsGrid = document.getElementById('gameNetsGrid');
    const noResults = document.getElementById('noResults');

    if (!Array.isArray(gameNets) || gameNets.length === 0) {
        showNoResults();
        return;
    }

    let html = '';
    gameNets.forEach((gameNet, index) => {
        // permalink safe fallback: از مسیر پیش‌فرض استفاده کن اگر سرور فرستاده نباشه
        const permalink = (gameNet.permalink && gameNet.permalink !== 'undefined')
            ? gameNet.permalink
            : "<?php echo esc_url( home_url('/game-net/') ); ?>" + encodeURIComponent(gameNet.id) + "/";

        const name = escapeHtml(gameNet.name);
        const phone = escapeHtml(gameNet.phone || 'ثبت نشده');
        const gender = escapeHtml(gameNet.gender || 'ثبت نشده');
        const age = escapeHtml(gameNet.age || 'ثبت نشده');
        const hours = escapeHtml(gameNet.hours || 'ثبت نشده');
        const holiday = escapeHtml(gameNet.holiday || 'بدون تعطیلی');
        const bio = escapeHtml(gameNet.bio || '');

        html += `
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover card-stagger" style="animation-delay: ${index * 0.1}s">
                <div class="p-4 sm:p-6">
                    <div class="mb-3 sm:mb-4">
                        <h4 class="text-lg sm:text-xl font-bold text-text-dark">${name}</h4>
                    </div>
                    
                    <div class="space-y-2 mb-3 sm:mb-4">
                        <div class="flex items-center space-x-2 space-x-reverse text-xs sm:text-sm text-muted">
                            <span>📞</span>
                            <span class="line-clamp-1">${phone}</span>
                        </div>
                        <div class="flex items-center space-x-3 sm:space-x-4 space-x-reverse text-xs sm:text-sm">
                            <div class="flex items-center space-x-1 space-x-reverse">
                                <span>👥</span>
                                <span>${gender}</span>
                            </div>
                            <div class="flex items-center space-x-1 space-x-reverse">
                                <span>🎮</span>
                                <span>${age}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse text-xs sm:text-sm text-muted">
                            <span>🕒</span>
                            <span>${hours}</span>
                        </div>
                        <div class="flex items-center space-x-2 space-x-reverse text-xs sm:text-sm text-muted">
                            <span>📅</span>
                            <span>${holiday}</span>
                        </div>
                    </div>
                    
                    ${bio ? `
                    <div class="mb-3 sm:mb-4">
                        <p class="text-sm text-gray-600 line-clamp-2">${bio}</p>
                    </div>
                    ` : ''}

                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:gap-x-2 sm:space-x-reverse">
                        <a href="${permalink}" 
                            class="flex-1 bg-primary text-text-on-dark py-2 sm:py-2 rounded-lg font-semibold text-sm sm:text-base hover:bg-opacity-90 transition-colors text-center">
                            مشاهده جزئیات
                        </a>

                        <button class="flex-1 bg-accent text-text-dark py-2 sm:py-2 rounded-lg font-semibold text-sm sm:text-base hover:bg-yellow-400 transition-colors">
                            تماس سریع
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    gameNetsGrid.innerHTML = html;
    noResults.classList.add('hidden');
}


// تابع برای pagination
function updatePagination(pagination) {
    const paginationContainer = document.getElementById('pagination');
    let html = '';

    if (pagination.total_pages > 1) {
        // دکمه قبلی
        if (pagination.current_page > 1) {
            html += `<button onclick="loadGameNets(${pagination.current_page - 1})" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">قبلی</button>`;
        }

        // صفحات
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.total_pages, startPage + 4);

        for (let i = startPage; i <= endPage; i++) {
            html += `<button onclick="loadGameNets(${i})" class="px-4 py-2 ${i === pagination.current_page ? 'bg-primary text-white' : 'bg-gray-200'} rounded-lg hover:bg-gray-300 transition-colors">${i}</button>`;
        }

        // دکمه بعدی
        if (pagination.current_page < pagination.total_pages) {
            html += `<button onclick="loadGameNets(${pagination.current_page + 1})" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">بعدی</button>`;
        }
    }

    paginationContainer.innerHTML = html;
}

// تابع برای زمانی که نتیجه‌ای نیست
function showNoResults() {
    const gameNetsGrid = document.getElementById('gameNetsGrid');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');

    gameNetsGrid.innerHTML = '';
    noResults.classList.remove('hidden');
    pagination.innerHTML = '';
}
// تابع برای مشاهده جزئیات
function showGameNetDetails(gameNetId) {
    // هدایت به صفحه single-game_net با پارامتر GET
    window.location.href = "<?php echo home_url('/single-game_net/'); ?>?game_net_id=" + gameNetId;
}
// لود اولیه وقتی صفحه loaded شد
document.addEventListener('DOMContentLoaded', function() {
    loadGameNets();
});

// event listener برای فیلترها
document.getElementById('genderFilter').addEventListener('change', function() {
    loadGameNets(1);
});
document.getElementById('ageFilter').addEventListener('change', function() {
    loadGameNets(1);
});
document.getElementById('areaFilter').addEventListener('change', function() {
    loadGameNets(1);
});
document.getElementById('priceFilter').addEventListener('change', function() {
    loadGameNets(1);
});
</script>

<?php get_footer(); ?>
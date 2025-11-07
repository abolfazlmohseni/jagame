<?php

/**
 * Template Name: Game Net Single
 * Template Post Type: game_net
 */

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();

        // Get post meta values
        $phone = get_post_meta($post_id, '_phone', true);
        $password = get_post_meta($post_id, '_password', true);
        $gender = get_post_meta($post_id, '_gender', true);
        $age = get_post_meta($post_id, '_age', true);
        $hours = get_post_meta($post_id, '_hours', true);
        $holiday = get_post_meta($post_id, '_holiday', true);
        $bio = get_post_meta($post_id, '_bio', true);
        $gallery_raw = get_post_meta($post_id, '_gallery_images', true);

        // Process gallery images
        $images = array();
        if (!empty($gallery_raw)) {
            $images = array_filter(array_map('intval', explode(',', $gallery_raw)));
        }

        // Get featured image as fallback
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');

        // Process phone number for tel link
        $tel_digits = preg_replace('/\D+/', '', $phone);
        $tel_url = !empty($tel_digits) ? 'tel:' . $tel_digits : '';
        $whatsapp_url = !empty($tel_digits) ? 'https://wa.me/' . $tel_digits : '';

        // Query devices
        $devices = new WP_Query(array(
            'post_type' => 'device',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_game_net_id',
                    'value' => $post_id,
                    'compare' => '='
                )
            )
        ));
        ?>

        <!DOCTYPE html>
        <html dir="rtl" lang="fa-IR">

        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php the_title(); ?> - <?php bloginfo('name'); ?></title>
            <?php wp_head(); ?>
            <style>
                .gradient-bg {
                    background: linear-gradient(135deg, #4B3F72 0%, #8E7CC3 100%);
                }

                .bg-primary {
                    background-color: #4B3F72;
                }

                .text-primary {
                    color: #4B3F72;
                }

                .bg-secondary {
                    background-color: #8E7CC3;
                }

                .text-secondary {
                    color: #8E7CC3;
                }

                .bg-accent {
                    background-color: #FFD447;
                }

                .text-accent {
                    color: #FFD447;
                }

                .bg-surface {
                    background-color: #FFFFFF;
                }

                .text-dark {
                    color: #111827;
                }

                .text-light {
                    color: #FFFFFF;
                }

                .text-muted {
                    color: #6B7280;
                }

                .lightbox {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.9);
                    z-index: 1000;
                    justify-content: center;
                    align-items: center;
                }

                .lightbox img {
                    max-width: 90%;
                    max-height: 80%;
                    object-fit: contain;
                }

                .lightbox-nav {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                    border: none;
                    padding: 1rem;
                    cursor: pointer;
                    font-size: 2rem;
                }

                .lightbox-prev {
                    right: 10px;
                }

                .lightbox-next {
                    left: 10px;
                }

                .lightbox-close {
                    position: absolute;
                    top: 20px;
                    left: 20px;
                    color: white;
                    font-size: 2rem;
                    background: none;
                    border: none;
                    cursor: pointer;
                }

                @media (min-width: 1024px) {
                    .sticky-sidebar {
                        position: sticky;
                        top: 2rem;
                        align-self: start;
                    }
                }

                .device-checkbox {
                    width: 18px;
                    height: 18px;
                    accent-color: #4B3F72;
                }

                .step {
                    display: none;
                }

                .step.active {
                    display: block;
                }

                .btn-next,
                .btn-prev {
                    background-color: #4B3F72;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    margin: 10px 5px;
                }

                .btn-next:hover,
                .btn-prev:hover {
                    background-color: #3A2F5A;
                }
            </style>
        </head>

        <body class="bg-gray-100 text-dark">
            <!-- Hero Section -->
            <section class="gradient-bg text-text-on-dark shadow-lg from-primary to-secondary py-8">
                <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center gap-6">
                    <!-- Avatar -->
                    <div class="lg:order-2">
                        <img src="<?php echo $featured_image ? esc_url($featured_image) : esc_url('https://via.placeholder.com/150'); ?>"
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            class="w-32 h-32 rounded-full ring-4 ring-white object-cover">
                    </div>

                    <!-- Title & Info -->
                    <div class="lg:order-1 text-center lg:text-right flex-1">
                        <h1 class="text-3xl font-bold mb-2"><?php the_title(); ?></h1>
                        <div class="flex items-center justify-center lg:justify-start gap-2 mb-3">
                            <span class="bg-accent text-dark px-3 py-1 rounded-full text-sm">۴.۲</span>
                            <span>امتیاز</span>
                        </div>
                        <p class="text-lg">
                            <?php echo $bio ? nl2br(esc_html($bio)) : 'توضیحی ثبت نشده است'; ?>
                        </p>
                    </div>
                </div>
            </section>

            <!-- دکمه رزرو اصلی -->
            <section class="container mx-auto px-4 py-4">
                <div class="text-center">
                    <button onclick="openReservationModal()"
                        class="bg-primary hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-lg font-bold transition-colors">
                        رزرو دستگاه در این گیم‌نت
                    </button>
                </div>
            </section>

            <main class="container mx-auto px-4 py-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Main Content -->
                    <div class="w-full lg:w-2/3">
                        <!-- About Section -->
                        <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-2xl font-bold mb-4 border-b-2 border-primary pb-2">درباره ما</h2>
                            <p class="text-gray-700 leading-relaxed">
                                <?php echo $bio ? nl2br(esc_html($bio)) : 'توضیحی ثبت نشده است'; ?>
                            </p>
                        </section>

                        <!-- Gallery Section -->
                        <?php if (!empty($images)) : ?>
                            <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                                <h2 class="text-2xl font-bold mb-4 border-b-2 border-primary pb-2">گالری تصاویر</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 game-gallery">
                                    <?php foreach ($images as $index => $img_id) : ?>
                                        <div class="cursor-pointer">
                                            <?php echo wp_get_attachment_image($img_id, 'medium', false, [
                                                'class' => 'rounded-lg w-full h-48 object-cover',
                                                'loading' => 'lazy',
                                                'data-index' => $index
                                            ]); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <!-- Devices Section -->
                        <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-2xl font-bold mb-4 border-b-2 border-primary pb-2">دستگاه ها</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php if ($devices->have_posts()) : ?>
                                    <?php while ($devices->have_posts()) : $devices->the_post(); ?>
                                        <?php
                                        $device_id = get_the_ID();
                                        $type = get_post_meta($device_id, '_type', true);
                                        $specs = get_post_meta($device_id, '_specs', true);
                                        $price = get_post_meta($device_id, '_price', true);
                                        $status = get_post_meta($device_id, '_status', true);

                                        // Status badge colors
                                        $status_colors = [
                                            'قابل استفاده' => 'bg-green-100 text-green-800',
                                            'available' => 'bg-green-100 text-green-800',
                                            'در حال تعمیر' => 'bg-red-100 text-red-800',
                                            'maintenance' => 'bg-red-100 text-red-800',
                                            'غیرفعال' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $status_class = $status_colors[$status] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                            <h3 class="text-xl font-bold mb-2"><?php the_title(); ?></h3>
                                            <div class="flex gap-2 mb-3">
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm"><?php echo esc_html($type); ?></span>
                                                <span class="<?php echo esc_attr($status_class); ?> px-2 py-1 rounded text-sm"><?php echo esc_html($status); ?></span>
                                            </div>
                                            <p class="text-muted text-sm mb-3"><?php echo esc_html($specs); ?></p>
                                            <p class="text-lg font-bold mb-3"><?php echo number_format((float)$price); ?> تومان/ساعت</p>
                                        </div>
                                    <?php endwhile;
                                    wp_reset_postdata(); ?>
                                <?php else : ?>
                                    <p class="text-muted col-span-full text-center py-8">هیچ دستگاهی ثبت نشده است</p>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>

                    <!-- Sidebar -->
                    <div class="w-full lg:w-1/3">
                        <div class="sticky-sidebar">
                            <!-- Contact Card -->
                            <div class="bg-surface rounded-lg shadow-md p-6 mb-6">
                                <h3 class="text-xl font-bold mb-4">اطلاعات تماس</h3>
                                <?php if ($phone) : ?>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-muted">تلفن:</span>
                                        <span class="font-bold"><?php echo esc_html($phone); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($hours) : ?>
                                    <div class="mb-3">
                                        <h4 class="font-bold mb-1">ساعات کاری:</h4>
                                        <p class="text-muted"><?php echo esc_html($hours); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($holiday) : ?>
                                    <div class="mb-3">
                                        <h4 class="font-bold mb-1">تعطیلی هفتگی:</h4>
                                        <p class="text-muted"><?php echo esc_html($holiday); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-surface rounded-lg shadow-md p-6">
                                <h3 class="text-xl font-bold mb-4">دسترسی سریع</h3>
                                <div class="space-y-3">
                                    <?php if ($tel_url) : ?>
                                        <a href="<?php echo esc_url($tel_url); ?>" class="block w-full bg-accent hover:bg-yellow-400 text-dark text-center py-2 rounded-lg transition-colors">
                                            تماس تلفنی
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Lightbox -->
            <div class="lightbox" id="lightbox">
                <button class="lightbox-close" onclick="closeLightbox()">×</button>
                <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)">‹</button>
                <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)">›</button>
                <img id="lightbox-img" src="" alt="">
            </div>

            <!-- Reservation Modal -->
            <div id="reservationModal" class="fixed inset-0 bg-black/25 bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">رزرو دستگاه</h3>
                        <button onclick="closeReservationModal()" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div id="loginRequiredMessage" class=" hidden">
                        <p class="text-yellow-800 bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">برای رزرو دستگاه وارد حساب کاربری خود شوید.</p>
                        <div class="mt-2">
                            <a href="<?php echo home_url('/index.php/login-page'); ?>"
                                class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                ورود به حساب کاربری
                            </a>
                        </div>
                    </div>

                    <div id="reservationFormContainer" class="hidden">
                        <!-- Step 1: انتخاب نوع دستگاه -->
                        <div id="step1" class="step active">
                            <h4 class="text-lg font-semibold mb-4">مرحله 1: انتخاب نوع دستگاه</h4>
                            <select name="device_type" id="deviceType" class="w-full p-3 border rounded-lg text-lg" required>
                                <option value="">-- لطفاً نوع دستگاه را انتخاب کنید --</option>
                                <option value="pc">PC</option>
                                <option value="xbox">XBOX</option>
                                <option value="ps4">PS4</option>
                                <option value="ps5">PS5</option>
                                <option value="vr">VR</option>
                                <option value="other">سایر</option>
                            </select>
                            <div class="mt-6 text-right">
                                <button type="button" onclick="nextStep(2)" class="btn-next">بعدی →</button>
                            </div>
                        </div>

                        <!-- Step 2: انتخاب تاریخ -->
                        <div id="step2" class="step">
                            <h4 class="text-lg font-semibold mb-4">مرحله 2: انتخاب تاریخ</h4>
                            <input type="date" name="reservation_date" id="reservationDate"
                                min="<?php echo date('Y-m-d'); ?>" class="w-full p-3 border rounded-lg text-lg" required>
                            <div class="mt-6 flex justify-between">
                                <button type="button" onclick="prevStep(1)" class="btn-prev">← قبلی</button>
                                <button type="button" onclick="nextStep(3)" class="btn-next">بعدی →</button>
                            </div>
                        </div>

                        <!-- Step 3: انتخاب ساعت شروع -->
                        <div id="step3" class="step">
                            <h4 class="text-lg font-semibold mb-4">مرحله 3: انتخاب ساعت شروع</h4>
                            <input type="time" name="start_time" id="startTime" class="w-full p-3 border rounded-lg text-lg" required>
                            <div class="mt-6 flex justify-between">
                                <button type="button" onclick="prevStep(2)" class="btn-prev">← قبلی</button>
                                <button type="button" onclick="nextStep(4)" class="btn-next">بعدی →</button>
                            </div>
                        </div>

                        <!-- Step 4: انتخاب مدت زمان -->
                        <div id="step4" class="step">
                            <h4 class="text-lg font-semibold mb-4">مرحله 4: انتخاب مدت زمان (ساعت)</h4>
                            <input type="number" name="duration" id="duration" min="1" value="1"
                                class="w-full p-3 border rounded-lg text-lg" required>
                            <div class="mt-6 flex justify-between">
                                <button type="button" onclick="prevStep(3)" class="btn-prev">← قبلی</button>
                                <button type="button" onclick="nextStep(5)" class="btn-next">بعدی →</button>
                            </div>
                        </div>

                        <!-- Step 5: انتخاب دستگاه‌های موجود -->
                        <div id="step5" class="step">
                            <h4 class="text-lg font-semibold mb-4">مرحله 5: انتخاب دستگاه‌ها</h4>
                            <div id="availableDevicesContainer">
                                <div id="availableDevicesList" class="grid grid-cols-1 gap-3 max-h-80 overflow-y-auto p-3 border rounded-lg"></div>
                            </div>
                            <div class="mt-4 bg-gray-50 p-3 rounded-lg">
                                <label class="block text-sm font-medium mb-1">قیمت تخمینی:</label>
                                <span id="estimatedPrice" class="text-lg font-bold">0 تومان</span>
                            </div>
                            <div class="mt-6 flex justify-between">
                                <button type="button" onclick="prevStep(4)" class="btn-prev">← قبلی</button>
                                <button type="button" onclick="submitReservation()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                                    تأیید و رزرو
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Lightbox functionality
                const lightbox = document.getElementById('lightbox');
                const lightboxImg = document.getElementById('lightbox-img');
                let currentIndex = 0;
                const images = [];

                // Initialize gallery
                document.querySelectorAll('.game-gallery img').forEach((img, index) => {
                    images.push({
                        src: img.src,
                        alt: img.alt
                    });

                    img.addEventListener('click', () => {
                        openLightbox(index);
                    });
                });

                function openLightbox(index) {
                    currentIndex = index;
                    lightboxImg.src = images[currentIndex].src;
                    lightboxImg.alt = images[currentIndex].alt;
                    lightbox.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }

                function closeLightbox() {
                    lightbox.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }

                function navigateLightbox(direction) {
                    currentIndex = (currentIndex + direction + images.length) % images.length;
                    lightboxImg.src = images[currentIndex].src;
                    lightboxImg.alt = images[currentIndex].alt;
                }

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (lightbox.style.display === 'flex') {
                        if (e.key === 'Escape') closeLightbox();
                        if (e.key === 'ArrowRight') navigateLightbox(1);
                        if (e.key === 'ArrowLeft') navigateLightbox(-1);
                    }
                });

                // Close lightbox when clicking outside image
                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox) closeLightbox();
                });
            </script>

            <script>
                // متغیرهای جهانی
                let availableDevices = [];
                let selectedDevices = [];
                let isUserLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;
                let currentStep = 1;

                // باز کردن مودال رزرو
                function openReservationModal() {
                    const modal = document.getElementById('reservationModal');
                    const loginMessage = document.getElementById('loginRequiredMessage');
                    const formContainer = document.getElementById('reservationFormContainer');

                    modal.classList.remove('hidden');
                    resetForm();

                    if (!isUserLoggedIn) {
                        loginMessage.classList.remove('hidden');
                        formContainer.classList.add('hidden');
                    } else {
                        loginMessage.classList.add('hidden');
                        formContainer.classList.remove('hidden');
                        showStep(1);
                    }
                }

                // بستن مودال رزرو
                function closeReservationModal() {
                    document.getElementById('reservationModal').classList.add('hidden');
                }

                // ریست فرم
                function resetForm() {
                    document.getElementById('deviceType').value = '';
                    document.getElementById('reservationDate').value = '';
                    document.getElementById('startTime').value = '';
                    document.getElementById('duration').value = '1';
                    document.getElementById('estimatedPrice').textContent = '0 تومان';
                    currentStep = 1;
                }

                // نمایش مرحله
                function showStep(stepNumber) {
                    document.querySelectorAll('.step').forEach(step => {
                        step.classList.remove('active');
                    });
                    document.getElementById('step' + stepNumber).classList.add('active');
                    currentStep = stepNumber;
                }

                // رفتن به مرحله بعد
                function nextStep(nextStepNumber) {
                    if (validateStep(currentStep)) {
                        if (currentStep === 4) { // قبل از رفتن به مرحله 5، دستگاه‌ها رو دریافت کن
                            fetchAvailableDevices();
                        }
                        showStep(nextStepNumber);
                    }
                }

                // برگشت به مرحله قبل
                function prevStep(prevStepNumber) {
                    showStep(prevStepNumber);
                }

                // اعتبارسنجی مرحله
                function validateStep(step) {
                    switch (step) {
                        case 1:
                            if (!document.getElementById('deviceType').value) {
                                alert('لطفاً نوع دستگاه را انتخاب کنید');
                                return false;
                            }
                            return true;
                        case 2:
                            if (!document.getElementById('reservationDate').value) {
                                alert('لطفاً تاریخ را انتخاب کنید');
                                return false;
                            }
                            return true;
                        case 3:
                            if (!document.getElementById('startTime').value) {
                                alert('لطفاً ساعت شروع را انتخاب کنید');
                                return false;
                            }
                            return true;
                        case 4:
                            if (!document.getElementById('duration').value || document.getElementById('duration').value < 1) {
                                alert('لطفاً مدت زمان معتبر وارد کنید');
                                return false;
                            }
                            return true;
                        default:
                            return true;
                    }
                }

                // دریافت دستگاه‌های موجود از سرور
                async function fetchAvailableDevices() {
                    const deviceType = document.getElementById('deviceType').value;
                    const gameNetId = <?php echo $post_id; ?>;
                    const date = document.getElementById('reservationDate').value;
                    const time = document.getElementById('startTime').value;
                    const duration = document.getElementById('duration').value;

                    if (!deviceType || !date || !time || !duration) {
                        alert('لطفاً تمام اطلاعات لازم را وارد کنید');
                        return;
                    }

                    try {
                        const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: 'get_available_devices',
                                device_type: deviceType,
                                game_net_id: gameNetId,
                                date: date,
                                start_time: time,
                                duration: duration,
                                security: '<?php echo wp_create_nonce("device_reservation_nonce"); ?>'
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            availableDevices = result.data.devices;
                            updateAvailableDevicesList();
                        } else {
                            console.error('خطا در دریافت دستگاه‌ها:', result.data);
                            alert('خطا در دریافت اطلاعات دستگاه‌ها');
                        }
                    } catch (error) {
                        console.error('خطا در ارتباط با سرور:', error);
                        alert('خطا در ارتباط با سرور');
                    }
                }

                // بروزرسانی لیست دستگاه‌های موجود
                function updateAvailableDevicesList() {
                    const list = document.getElementById('availableDevicesList');
                    list.innerHTML = '';

                    if (availableDevices.length === 0) {
                        list.innerHTML = '<p class="text-center text-gray-500 py-4">هیچ دستگاه موجودی برای این زمان یافت نشد</p>';
                        return;
                    }

                    availableDevices.forEach(device => {
                        const deviceElement = document.createElement('div');
                        deviceElement.className = 'flex items-center p-3 border rounded-lg hover:bg-gray-50';
                        deviceElement.innerHTML = `
                            <input type="checkbox" name="selected_devices[]" value="${device.id}" 
                                   class="device-checkbox mr-3" data-price="${device.price}"
                                   onchange="calculatePrice()">
                            <div class="flex-1">
                                <span class="font-medium text-lg">${device.name}</span>
                                <span class="text-sm text-gray-600 block">${device.specs}</span>
                                <span class="text-green-600 font-bold">${Number(device.price).toLocaleString()} تومان/ساعت</span>
                            </div>
                        `;
                        list.appendChild(deviceElement);
                    });

                    calculatePrice();
                }

                // محاسبه قیمت
                function calculatePrice() {
                    const duration = parseInt(document.getElementById('duration').value) || 0;
                    const selectedCheckboxes = document.querySelectorAll('.device-checkbox:checked');

                    let totalPrice = 0;
                    selectedDevices = [];

                    selectedCheckboxes.forEach(checkbox => {
                        const price = parseFloat(checkbox.dataset.price);
                        totalPrice += price * duration;
                        selectedDevices.push(checkbox.value);
                    });

                    document.getElementById('estimatedPrice').textContent = Math.round(totalPrice).toLocaleString() + ' تومان';
                }

                // ارسال درخواست رزرو
                async function submitReservation() {
                    if (selectedDevices.length === 0) {
                        alert('لطفاً حداقل یک دستگاه انتخاب کنید');
                        return;
                    }

                    const deviceType = document.getElementById('deviceType').value;
                    const date = document.getElementById('reservationDate').value;
                    const time = document.getElementById('startTime').value;
                    const duration = document.getElementById('duration').value;
                    const start_datetime = date + ' ' + time;

                    try {
                        const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: 'reserve_devices',
                                security: '<?php echo wp_create_nonce("device_reservation_nonce"); ?>',
                                game_net_id: '<?php echo $post_id; ?>',
                                device_ids: selectedDevices.join(','),
                                start_time: start_datetime,
                                hours: duration
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert(result.data.message);
                            closeReservationModal();
                            // رفرش صفحه یا هدایت به صفحه دیگر
                            window.location.reload();
                        } else {
                            alert('خطا: ' + result.data);
                        }
                    } catch (error) {
                        console.error('خطا در ارسال درخواست:', error);
                        alert('خطا در ارتباط با سرور');
                    }
                }

                // بستن مودال با کلید ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeReservationModal();
                    }
                });

                // تنظیم مقادیر پیش‌فرض
                document.addEventListener('DOMContentLoaded', function() {
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    document.getElementById('reservationDate').value = tomorrow.toISOString().split('T')[0];
                    document.getElementById('startTime').value = '14:00';
                });
            </script>
        </body>

        </html>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>
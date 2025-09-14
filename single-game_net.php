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
        $address = get_post_meta($post_id, '_address', true);
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
        <style>
            /* استایل‌های مربوط به تقویم شمسی */
            .persian-date-picker .day {
                padding: 8px;
                text-align: center;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .persian-date-picker .day:hover {
                background-color: #e5e7eb;
            }

            .persian-date-picker .day.selected {
                background-color: #3b82f6;
                color: white;
            }

            .persian-date-picker .day.today {
                border: 2px solid #3b82f6;
            }

            .persian-date-picker .day.disabled {
                color: #9ca3af;
                cursor: not-allowed;
            }

            /* استایل‌های مربوط به انتخاب زمان */
            .time-picker-grid .time-slot {
                padding: 8px;
                text-align: center;
                border-radius: 8px;
                border: 1px solid #e5e7eb;
                cursor: pointer;
                transition: all 0.2s;
            }

            .time-picker-grid .time-slot:hover {
                background-color: #e5e7eb;
            }

            .time-picker-grid .time-slot.selected {
                background-color: #3b82f6;
                color: white;
                border-color: #3b82f6;
            }

            .time-picker-grid .time-slot.disabled {
                color: #9ca3af;
                cursor: not-allowed;
            }

            /* استایل‌های مربوط به مراحل */
            .step {
                display: none;
            }

            .step.active {
                display: block;
            }

            /* استایل‌های دکمه‌ها */
            .btn-next,
            .btn-prev {
                padding: 8px 16px;
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.2s;
            }

            .btn-next {
                background-color: #3b82f6;
                color: white;
            }

            .btn-next:hover:not(:disabled) {
                background-color: #2563eb;
            }

            .btn-next:disabled {
                background-color: #9ca3af;
                cursor: not-allowed;
            }

            .btn-prev {
                background-color: #e5e7eb;
                color: #4b5563;
            }

            .btn-prev:hover {
                background-color: #d1d5db;
            }

            /* استایل‌های مربوط به دستگاه‌ها */
            .device-type-option {
                cursor: pointer;
                transition: all 0.2s;
            }

            .device-type-option:hover {
                transform: translateY(-2px);
            }

            .device-type-option.selected .device-icon {
                background-color: #dbeafe;
                border: 2px solid #3b82f6;
            }

            .device-type-option.selected p {
                color: #3b82f6;
                font-weight: bold;
            }

            /* استایل‌های مربوط به دستگاه‌های موجود */
            .device-item {
                padding: 12px;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .device-item:hover {
                background-color: #f9fafb;
            }

            .device-item.selected {
                background-color: #dbeafe;
                border-color: #3b82f6;
            }

            .device-item.disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
        </style>
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
                </div>
            </div>
        </section>

        <!-- دکمه رزرو اصلی -->
        <section class="container mx-auto px-4 py-4">
            <div class="text-center">
                <button onclick="openReservationModal()"
                    class="bg-secondary hover:bg-primary text-white px-6 py-3 rounded-lg text-lg font-bold transition-colors">
                    رزرو در این گیم‌نت
                </button>
            </div>
        </section>

        <main class="container mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="w-full lg:w-2/3">
                    <!-- About Section -->
                    <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-2xl font-bold mb-4 border-b-2 border-secondary/40 pb-2">درباره ما</h2>
                        <p class="text-gray-700 leading-relaxed">
                            <?php echo $bio ? nl2br(esc_html($bio)) : 'توضیحی ثبت نشده است'; ?>
                        </p>
                    </section>

                    <!-- Gallery Section -->
                    <?php if (!empty($images)) : ?>
                        <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-2xl font-bold mb-4 border-b-2 border-secondary/40 pb-2">گالری تصاویر</h2>
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
                        <h2 class="text-2xl font-bold mb-4 border-b-2 border-secondary/40 pb-2">دستگاه ها</h2>
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
                                    <div class="shadow-md rounded-lg p-4 hover:shadow-lg transition-shadow">
                                        <h3 class="text-xl font-bold mb-2"><?php the_title(); ?></h3>
                                        <div class="flex gap-2 mb-3">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm"><?php echo esc_html($type); ?></span>
                                            <span class="<?php echo esc_attr($status_class); ?> px-2 py-1 rounded text-sm"><?php echo esc_html($status); ?></span>
                                        </div>
                                        <p class="text-muted text-sm mb-3"><?php echo esc_html($specs); ?></p>
                                        <p class="text-lg font-bold mb-3"><?php echo number_format((float)$price); ?> تومان/ساعت</p>

                                        <!-- View Games Button -->
                                        <button class="view-games-btn px-3 py-1 text-sm rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                                            data-device-id="<?php echo $device_id; ?>">
                                            مشاهده بازی‌ها
                                        </button>
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

                            <?php if ($address) : ?>
                                <div class="mb-3">
                                    <h4 class="font-bold mb-1">آدرس:</h4>
                                    <p class="text-muted"><?php echo nl2br(esc_html($address)); ?></p>
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
                                <button onclick="openReservationModal()"
                                    class="w-full bg-secondary hover:bg-primary text-white py-2 rounded-lg text-lg transition-colors">
                                    رزرو در این گیم‌نت
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Games Modal -->
        <div id="gamesModal" class="games-modal" role="dialog" aria-modal="true" aria-labelledby="gamesModalTitle">
            <div class="games-modal-content">
                <div class="games-modal-header">
                    <h3 id="gamesModalTitle" class="text-lg font-bold">بازی‌های دستگاه</h3>
                    <button id="closeGamesModal" class="text-gray-500 hover:text-gray-700" aria-label="بستن">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="games-modal-body">
                    <div id="gamesList" aria-live="polite">
                        <!-- Games will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
        <div class="lightbox" id="lightbox">
            <button class="lightbox-close" onclick="closeLightbox()">×</button>
            <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)">‹</button>
            <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)">›</button>
            <img id="lightbox-img" src="" alt="">
        </div>

        <!-- Reservation Modal -->
        <div id="reservationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">رزرو دستگاه</h3>
                    <button onclick="closeReservationModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="loginRequiredMessage" class="hidden">
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
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <div class="device-type-option" data-type="pc">
                                <div class="device-icon bg-blue-100 p-4 rounded-lg text-center">
                                    <svg class="w-12 h-12 mx-auto text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-center mt-2 font-medium">کامپیوتر</p>
                            </div>
                            <div class="device-type-option" data-type="xbox">
                                <div class="device-icon bg-green-100 p-4 rounded-lg text-center">
                                    <svg class="w-12 h-12 mx-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-center mt-2 font-medium">XBOX</p>
                            </div>
                            <div class="device-type-option" data-type="ps5">
                                <div class="device-icon bg-purple-100 p-4 rounded-lg text-center">
                                    <svg class="w-12 h-12 mx-auto text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <p class="text-center mt-2 font-medium">PS5</p>
                            </div>
                            <div class="device-type-option" data-type="ps4">
                                <div class="device-icon bg-indigo-100 p-4 rounded-lg text-center">
                                    <svg class="w-12 h-12 mx-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <p class="text-center mt-2 font-medium">PS4</p>
                            </div>
                            <div class="device-type-option" data-type="vr">
                                <div class="device-icon bg-pink-100 p-4 rounded-lg text-center">
                                    <svg class="w-12 h-12 mx-auto text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-center mt-2 font-medium">VR</p>
                            </div>
                            <div class="device-type-option" data-type="other">
                                <div class="device-icon bg-gray-100 p-4 rounded-lg text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <p class="text-center mt-2 font-medium">سایر</p>
                            </div>
                        </div>
                        <input type="hidden" name="device_type" id="deviceType" value="">
                        <div class="mt-6 text-right">
                            <button type="button" onclick="nextStep(2)" class="btn-next" disabled>بعدی →</button>
                        </div>
                    </div>

                    <!-- Step 2: انتخاب تاریخ -->
                    <div id="step2" class="step">
                        <h4 class="text-lg font-semibold mb-4">مرحله 2: انتخاب تاریخ</h4>
                        <div class="persian-date-picker bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-4">
                                <button type="button" onclick="changePersianMonth(-1)" class="p-2 rounded hover:bg-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h5 id="persianMonthYear" class="font-bold text-lg"></h5>
                                <button type="button" onclick="changePersianMonth(1)" class="p-2 rounded hover:bg-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-7 gap-2 mb-2">
                                <div class="text-center text-sm font-medium text-gray-500">ش</div>
                                <div class="text-center text-sm font-medium text-gray-500">ی</div>
                                <div class="text-center text-sm font-medium text-gray-500">د</div>
                                <div class="text-center text-sm font-medium text-gray-500">س</div>
                                <div class="text-center text-sm font-medium text-gray-500">چ</div>
                                <div class="text-center text-sm font-medium text-gray-500">پ</div>
                                <div class="text-center text-sm font-medium text-gray-500">ج</div>
                            </div>
                            <div id="persianCalendar" class="grid grid-cols-7 gap-2"></div>
                        </div>
                        <input type="hidden" name="reservation_date" id="reservationDate" value="">
                        <div class="mt-6 flex justify-between">
                            <button type="button" onclick="nextStep(3)" class="btn-next" disabled>بعدی →</button>
                            <button type="button" onclick="prevStep(1)" class="btn-prev">← قبلی</button>
                        </div>
                    </div>

                    <!-- Step 3: انتخاب ساعت شروع -->
                    <div id="step3" class="step">
                        <h4 class="text-lg font-semibold mb-4">مرحله 3: انتخاب ساعت شروع</h4>
                        <div class="time-picker-grid bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                                <!-- زمان‌ها توسط جاوااسکریپت پر خواهد شد -->
                            </div>
                        </div>
                        <input type="hidden" name="start_time" id="startTime" value="">
                        <div class="mt-6 flex justify-between">
                            <button type="button" onclick="nextStep(4)" class="btn-next" disabled>بعدی →</button>
                            <button type="button" onclick="prevStep(2)" class="btn-prev">← قبلی</button>
                        </div>
                    </div>

                    <!-- Step 4: انتخاب مدت زمان -->
                    <div id="step4" class="step">
                        <h4 class="text-lg font-semibold mb-4">مرحله 4: انتخاب مدت زمان</h4>
                        <div class="duration-selector bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="duration-option" data-duration="1">
                                    <div class="text-center py-3 px-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer">
                                        <span class="text-lg font-medium">1 ساعت</span>
                                    </div>
                                </div>
                                <div class="duration-option" data-duration="2">
                                    <div class="text-center py-3 px-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer">
                                        <span class="text-lg font-medium">2 ساعت</span>
                                    </div>
                                </div>
                                <div class="duration-option" data-duration="3">
                                    <div class="text-center py-3 px-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer">
                                        <span class="text-lg font-medium">3 ساعت</span>
                                    </div>
                                </div>
                                <div class="duration-option" data-duration="4">
                                    <div class="text-center py-3 px-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer">
                                        <span class="text-lg font-medium">4 ساعت</span>
                                    </div>
                                </div>
                                <div class="duration-option" data-duration="5">
                                    <div class="text-center py-3 px-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer">
                                        <span class="text-lg font-medium">5 ساعت</span>
                                    </div>
                                </div>
                                <div class="duration-option" data-duration="6">
                                    <div class="text-center py-3 px-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer">
                                        <span class="text-lg font-medium">6 ساعت</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="customDuration" class="block text-sm font-medium mb-2">یا مدت زمان دلخواه:</label>
                                <div class="flex items-center">
                                    <input type="number" id="customDuration" min="1" max="12"
                                        class="w-20 p-2 border rounded-lg text-center" placeholder="ساعت">
                                    <span class="mr-2">ساعت</span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="duration" id="duration" value="">
                        <div class="mt-6 flex justify-between">
                            <button type="button" onclick="nextStep(5)" class="btn-next" disabled>بعدی →</button>
                            <button type="button" onclick="prevStep(3)" class="btn-prev">← قبلی</button>
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
                            <button type="button" onclick="submitReservation()" class="bg-secondary hover:bg-primary transition-colors text-white px-6 py-2 rounded-lg">
                                رزرو
                            </button>
                            <button type="button" onclick="prevStep(4)" class="btn-prev">← قبلی</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="alertModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
                <p class="textalert text-lg text-center"></p>
                <div class="flex gap-2 mt-4">
                    <button class="closealert w-full text-center text-white bg-green-600 hover:bg-green-700 transition-colors rounded-md py-2">تایید</button>
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

            // Games Modal functionality
            const gamesModal = document.getElementById('gamesModal');
            const gamesList = document.getElementById('gamesList');
            const closeGamesModalBtn = document.getElementById('closeGamesModal');
            let currentDeviceId = null;
            let isModalOpen = false;

            // Open games modal
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('view-games-btn')) {
                    const deviceId = e.target.getAttribute('data-device-id');
                    openGamesModal(deviceId);
                }
            });

            function openGamesModal(deviceId) {
                currentDeviceId = deviceId;
                isModalOpen = true;
                gamesModal.classList.add('active');
                document.body.style.overflow = 'hidden';

                // Set focus to modal
                gamesModal.setAttribute('aria-hidden', 'false');

                // Load games
                loadDeviceGames(deviceId);
            }

            function closeGamesModal() {
                gamesModal.classList.remove('active');
                document.body.style.overflow = 'auto';
                isModalOpen = false;
                gamesModal.setAttribute('aria-hidden', 'true');

                // Restore focus to the button that opened the modal
                document.querySelector(`.view-games-btn[data-device-id="${currentDeviceId}"]`).focus();
                currentDeviceId = null;
            }

            // Close modal on overlay click
            gamesModal.addEventListener('click', function(e) {
                if (e.target === gamesModal) {
                    closeGamesModal();
                }
            });

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isModalOpen) {
                    closeGamesModal();
                }
            });

            // Close modal via close button
            closeGamesModalBtn.addEventListener('click', closeGamesModal);

            // Load device games via AJAX
            function loadDeviceGames(deviceId) {
                gamesList.innerHTML = `
                        <div class="text-center py-8">
                            <div class="loading-spinner mx-auto"></div>
                            <p class="mt-2 text-gray-600">در حال دریافت بازی‌ها...</p>
                        </div>
                    `;

                // استفاده از URLSearchParams به جای FormData برای سازگاری بهتر
                const params = new URLSearchParams();
                params.append('action', 'get_device_games');
                params.append('device_id', deviceId);
                params.append('security', '<?php echo wp_create_nonce('device_management_nonce'); ?>');

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: params
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('پاسخ سرور معتبر نیست');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            displayGames(data.data.games);
                        } else {
                            gamesList.innerHTML = `
                                <div class="text-center py-8 text-red-500">
                                    <p>خطا در دریافت بازی‌ها: ${data.data || 'خطای نامشخص'}</p>
                                </div>
                            `;
                            console.error('Server error:', data.data);
                        }
                    })
                    .catch(error => {
                        gamesList.innerHTML = `
                            <div class="text-center py-8 text-red-500">
                                <p>خطا در ارتباط با سرور: ${error.message}</p>
                            </div>
                        `;
                        console.error('Fetch error:', error);
                    });
            }

            // Display games in the modal
            function displayGames(games) {
                if (!games || games.length === 0) {
                    gamesList.innerHTML = `
                            <div class="text-center py-8">
                                <p class="text-gray-500">برای این دستگاه بازی‌ای ثبت نشده است</p>
                            </div>
                        `;
                    return;
                }

                let html = '';
                games.forEach(game => {
                    // اطمینان از اینکه game یک آبجکت است
                    const gameTitle = game.title || game;
                    const thumbnailUrl = game.thumbnail_url || null;

                    html += `
                            <div class="game-item" role="button" tabindex="0">
                                <div class="game-thumbnail-container">
                                    ${thumbnailUrl ? 
                                        `<img src="${thumbnailUrl}" alt="${gameTitle}" class="game-thumbnail">` : 
                                        `<div class="game-thumbnail bg-gray-200 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>`
                                    }
                                </div>
                                <div class="game-info">
                                    <h4 class="game-title">${gameTitle}</h4>
                                </div>
                                <div class="game-details">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        `;
                });

                gamesList.innerHTML = html;
            }
        </script>
        <!-- اسکریپت برای رزرو دستگاه -->
        <script>
            const alertmodal = document.querySelector('.alertModal')
            const textalert = document.querySelector('.textalert')
            const closealert = document.querySelector('.closealert')

            function closemodal() {
                alertmodal.classList.add("hidden")
            }

            function openmodal(text) {
                textalert.textContent = text
                alertmodal.classList.remove("hidden")
            }

            closealert.addEventListener("click", () => {
                closemodal()
            })
            // متغیرهای سراسری
            let selectedDeviceType = null;
            let selectedDate = null;
            let selectedStartTime = null;
            let selectedDuration = 1;
            let selectedDevices = [];
            let availableDevices = [];
            let currentStep = 1;
            let isUserLoggedIn = <?php echo is_user_logged_in() ? 'true' : 'false'; ?>;

            // متغیرهای مربوط به تقویم شمسی
            let currentPersianDate = new Date();
            let currentPersianYear = jalaali.toJalaali(currentPersianDate).jy;
            let currentPersianMonth = jalaali.toJalaali(currentPersianDate).jm;

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

                    // مقداردهی اولیه تقویم شمسی
                    renderPersianCalendar();
                }
            }

            // بستن مودال رزرو
            function closeReservationModal() {
                document.getElementById('reservationModal').classList.add('hidden');
            }

            // ریست فرم
            function resetForm() {
                selectedDeviceType = null;
                selectedDate = null;
                selectedStartTime = null;
                selectedDuration = 1;
                selectedDevices = [];

                // ریست انتخاب‌های UI
                document.querySelectorAll('.device-type-option').forEach(option => {
                    option.classList.remove('selected');
                });

                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.classList.remove('selected');
                });

                document.querySelectorAll('.duration-option').forEach(option => {
                    option.classList.remove('selected');
                });

                document.querySelectorAll('.device-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });

                document.getElementById('customDuration').value = '';
                document.getElementById('estimatedPrice').textContent = '0 تومان';

                currentStep = 1;
                showStep(1);

                // بازنشانی تقویم به ماه جاری
                const today = jalaali.toJalaali(new Date());
                currentPersianYear = today.jy;
                currentPersianMonth = today.jm;
                renderPersianCalendar();
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
                    if (currentStep === 4) {
                        // قبل از رفتن به مرحله 5، دستگاه‌های موجود را دریافت کن
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
                        if (!selectedDeviceType) {
                            openmodal("لطفاً نوع دستگاه را انتخاب کنید")
                            return false;
                        }
                        return true;
                    case 2:
                        if (!selectedDate) {
                            openmodal("لطفاً تاریخ را انتخاب کنید")
                            return false;
                        }
                        return true;
                    case 3:
                        if (!selectedStartTime) {
                            openmodal("لطفاً ساعت شروع را انتخاب کنید")
                            return false;
                        }
                        return true;
                    case 4:
                        if (!selectedDuration || selectedDuration < 1) {
                            openmodal("لطفاً مدت زمان معتبر انتخاب کنید")
                            return false;
                        }
                        return true;
                    default:
                        return true;
                }
            }

            // انتخاب نوع دستگاه
            function selectDeviceType(type) {
                selectedDeviceType = type;

                // بروزرسانی UI
                document.querySelectorAll('.device-type-option').forEach(option => {
                    option.classList.remove('selected');
                    if (option.dataset.type === type) {
                        option.classList.add('selected');
                    }
                });

                // فعال کردن دکمه بعدی
                document.querySelector('#step1 .btn-next').disabled = false;
            }

            // انتخاب تاریخ
            function selectDate(date) {
                selectedDate = date;

                // فعال کردن دکمه بعدی
                document.querySelector('#step2 .btn-next').disabled = false;

                // بارگذاری زمان‌های موجود برای تاریخ انتخاب شده
                loadAvailableTimes();
            }

            // انتخاب زمان شروع
            function selectStartTime(time) {
                selectedStartTime = time;

                // بروزرسانی UI
                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.classList.remove('selected');
                    if (slot.dataset.time === time) {
                        slot.classList.add('selected');
                    }
                });

                // فعال کردن دکمه بعدی
                document.querySelector('#step3 .btn-next').disabled = false;
            }

            // انتخاب مدت زمان
            function selectDuration(duration) {
                selectedDuration = parseInt(duration);

                // بروزرسانی UI
                document.querySelectorAll('.duration-option').forEach(option => {
                    option.classList.remove('selected');
                    if (parseInt(option.dataset.duration) === selectedDuration) {
                        option.classList.add('selected');
                    }
                });

                // فعال کردن دکمه بعدی
                document.querySelector('#step4 .btn-next').disabled = false;

                // محاسبه قیمت
                calculatePrice();
            }

            // محاسبه قیمت بر اساس دستگاه‌های انتخاب شده و مدت زمان
            function calculatePrice() {
                let totalPrice = 0;

                selectedDevices.forEach(deviceId => {
                    const device = availableDevices.find(d => d.id == deviceId);
                    if (device) {
                        totalPrice += device.price * selectedDuration;
                    }
                });

                document.getElementById('estimatedPrice').textContent = totalPrice.toLocaleString() + ' تومان';
            }

            // انتخاب/لغو انتخاب دستگاه
            function toggleDeviceSelection(deviceId, element) {
                const index = selectedDevices.indexOf(deviceId);

                if (index === -1) {
                    selectedDevices.push(deviceId);
                    element.checked = true;
                } else {
                    selectedDevices.splice(index, 1);
                    element.checked = false;
                }

                // محاسبه قیمت
                calculatePrice();
            }

            // دریافت دستگاه‌های موجود از سرور
            async function fetchAvailableDevices() {
                try {
                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'get_available_devices',
                            device_type: selectedDeviceType,
                            game_net_id: <?php echo $post_id; ?>,
                            date: selectedDate,
                            start_time: selectedStartTime,
                            duration: selectedDuration,
                            security: '<?php echo wp_create_nonce("device_reservation_nonce"); ?>'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        availableDevices = result.data.devices;
                        updateAvailableDevicesList();
                    } else {
                        openmodal("خطا در دریافت اطلاعات دستگاه‌ها")
                    }
                } catch (error) {
                    console.error('خطا در ارتباط با سرور:', error);
                    openmodal("خطا در ارتباط با سرور")
                }
            }

            // بروزرسانی لیست دستگاه‌های موجود در UI
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
            <input type="checkbox" id="device-${device.id}" 
                   class="device-checkbox mr-3" 
                   onchange="toggleDeviceSelection(${device.id}, this)">
            <div class="flex-1">
                <label for="device-${device.id}" class="cursor-pointer">
                    <span class="font-medium text-lg">${device.name}</span>
                    <span class="text-sm text-gray-600 block">${device.specs}</span>
                    <span class="text-green-600 font-bold">${Number(device.price).toLocaleString()} تومان/ساعت</span>
                </label>
            </div>
        `;
                    list.appendChild(deviceElement);
                });

                // ریست انتخاب‌های قبلی
                selectedDevices = [];
                calculatePrice();
            }

            // ارسال درخواست رزرو
            async function submitReservation() {
                if (selectedDevices.length === 0) {
                    openmodal("لطفاً حداقل یک دستگاه انتخاب کنید")
                    return;
                }

                try {
                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'reserve_devices',
                            security: '<?php echo wp_create_nonce("device_reservation_nonce"); ?>',
                            game_net_id: <?php echo $post_id; ?>,
                            device_ids: selectedDevices.join(','),
                            start_time: selectedDate + ' ' + selectedStartTime,
                            hours: selectedDuration
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        openmodal(result.data.message)
                        closeReservationModal();
                        // رفرش صفحه
                        window.location.reload();
                    } else {
                        openmodal(result.data)
                    }
                } catch (error) {
                    console.error('خطا در ارسال درخواست:', error);
                    openmodal("خطا در ارتباط با سرور")
                }
            }

            // بارگذاری زمان‌های موجود
            function loadAvailableTimes() {
                const timeGrid = document.querySelector('.time-picker-grid .grid');
                timeGrid.innerHTML = '';

                // تولید زمان‌ها از 10 صبح تا 10 شب
                for (let hour = 10; hour <= 22; hour++) {
                    for (let minute = 0; minute < 60; minute += 30) {
                        const timeSlot = document.createElement('div');
                        timeSlot.classList.add('time-slot');

                        const timeFormatted = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                        timeSlot.textContent = timeFormatted;
                        timeSlot.dataset.time = timeFormatted;

                        // بررسی آیا زمان از گذشته است (برای تاریخ امروز)
                        const today = new Date();
                        if (selectedDate === today.toISOString().split('T')[0]) {
                            const now = new Date();
                            const currentHour = now.getHours();
                            const currentMinute = now.getMinutes();

                            if (hour < currentHour || (hour === currentHour && minute < currentMinute)) {
                                timeSlot.classList.add('disabled');
                            }
                        }

                        if (!timeSlot.classList.contains('disabled')) {
                            timeSlot.addEventListener('click', () => {
                                selectStartTime(timeFormatted);
                            });
                        }

                        timeGrid.appendChild(timeSlot);
                    }
                }
            }

            // تابع برای ایجاد تقویم شمسی
            function renderPersianCalendar() {
                const calendarContainer = document.getElementById('persianCalendar');
                const monthYearElement = document.getElementById('persianMonthYear');

                // نام ماه‌های فارسی
                const persianMonths = [
                    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
                    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
                ];

                // روزهای هفته
                const weekDays = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];

                // نمایش نام ماه و سال
                monthYearElement.textContent = `${persianMonths[currentPersianMonth - 1]} ${currentPersianYear}`;

                // پاک کردن تقویم قبلی
                calendarContainer.innerHTML = '';

                // دریافت اولین روز ماه و تعداد روزهای ماه
                const firstDay = jalaali.j2d(currentPersianYear, currentPersianMonth, 1);
                const daysInMonth = jalaali.jalaaliMonthLength(currentPersianYear, currentPersianMonth);

                // محاسبه روز هفته برای اولین روز ماه (0=شنبه، 6=جمعه)
                const startDay = (firstDay + 1) % 7;

                // اضافه کردن خانه‌های خالی برای روزهای قبل از شروع ماه
                for (let i = 0; i < startDay; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.classList.add('day', 'empty');
                    calendarContainer.appendChild(emptyCell);
                }

                // اضافه کردن روزهای ماه
                const today = jalaali.toJalaali(new Date());

                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.classList.add('day');
                    dayCell.textContent = day;

                    // بررسی آیا امروز است
                    if (today.jy === currentPersianYear &&
                        today.jm === currentPersianMonth &&
                        today.jd === day) {
                        dayCell.classList.add('today');
                    }

                    // بررسی آیا تاریخ انتخاب شده است
                    if (selectedDate) {
                        const selectedJalaali = jalaali.toJalaali(new Date(selectedDate));
                        if (selectedJalaali.jy === currentPersianYear &&
                            selectedJalaali.jm === currentPersianMonth &&
                            selectedJalaali.jd === day) {
                            dayCell.classList.add('selected');
                        }
                    }

                    // غیرفعال کردن روزهای گذشته
                    const isPastDate = (
                        currentPersianYear < today.jy ||
                        (currentPersianYear === today.jy && currentPersianMonth < today.jm) ||
                        (currentPersianYear === today.jy && currentPersianMonth === today.jm && day < today.jd)
                    );

                    if (isPastDate) {
                        dayCell.classList.add('disabled');
                    } else {
                        dayCell.addEventListener('click', () => {
                            selectPersianDate(day);
                        });
                    }

                    calendarContainer.appendChild(dayCell);
                }
            }

            // تغییر ماه در تقویم شمسی
            function changePersianMonth(direction) {
                currentPersianMonth += direction;

                if (currentPersianMonth > 12) {
                    currentPersianMonth = 1;
                    currentPersianYear++;
                } else if (currentPersianMonth < 1) {
                    currentPersianMonth = 12;
                    currentPersianYear--;
                }

                renderPersianCalendar();
            }

            // انتخاب تاریخ شمسی
            // انتخاب تاریخ شمسی - نسخه تصحیح شده
            function selectPersianDate(day) {
                // تبدیل تاریخ شمسی به میلادی با استفاده از تابع صحیح
                const gregorianDate = jalaali.toGregorian(
                    currentPersianYear,
                    currentPersianMonth,
                    day
                );

                // فرمت تاریخ به YYYY-MM-DD
                const selectedDateObj = new Date(
                    gregorianDate.gy,
                    gregorianDate.gm - 1, // ماه در JavaScript از 0 شروع می‌شود
                    gregorianDate.gd
                );

                const formattedDate = selectedDateObj.toISOString().split('T')[0];
                selectDate(formattedDate);

                // بروزرسانی UI
                document.querySelectorAll('.day').forEach(dayElement => {
                    dayElement.classList.remove('selected');
                });

                event.target.classList.add('selected');
            }
            // مقداردهی اولیه
            document.addEventListener('DOMContentLoaded', function() {
                // رویدادهای انتخاب نوع دستگاه
                document.querySelectorAll('.device-type-option').forEach(option => {
                    option.addEventListener('click', () => {
                        selectDeviceType(option.dataset.type);
                    });
                });

                // رویدادهای انتخاب مدت زمان
                document.querySelectorAll('.duration-option').forEach(option => {
                    option.addEventListener('click', () => {
                        selectDuration(option.dataset.duration);
                    });
                });

                // رویداد مدت زمان دلخواه
                document.getElementById('customDuration').addEventListener('change', function() {
                    if (this.value && this.value > 0 && this.value <= 12) {
                        selectDuration(parseInt(this.value));
                    }
                });

                // بستن مودال با کلید ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeReservationModal();
                    }
                });

                // رویدادهای تغییر ماه در تقویم شمسی
                document.querySelectorAll('.persian-date-picker button').forEach(button => {
                    button.addEventListener('click', function() {
                        const direction = this.textContent.includes('‹') ? -1 : 1;
                        changePersianMonth(direction);
                    });
                });
            });
        </script>
        </body>

        </html>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>
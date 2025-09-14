<?php
/*
Template Name: User Panel
*/

// اگر کاربر لاگین نکرده باشد، به صفحه لاگین هدایت شود
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

// اطلاعات کاربر فعلی
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// بررسی نقش کاربر - اگر مالک گیم‌نت باشد به پنل مدیریت هدایت شود
if (in_array('game_net_owner', $current_user->roles)) {
    $panel_page = get_page_by_path('overview');
    $redirect_url = $panel_page ? get_permalink($panel_page->ID) : home_url();
    wp_redirect($redirect_url);
    exit;
}

// توابع کمکی برای گرفتن آمار کاربر
function get_user_total_hours($user_id)
{
    // این تابع باید مجموع ساعات بازی کاربر را از CPT رزروها محاسبه کند
    $total_hours = 0;

    $reservations = get_posts(array(
        'post_type' => 'reservation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => 'completed',
                'compare' => '='
            )
        )
    ));

    foreach ($reservations as $reservation) {
        $hours = get_post_meta($reservation->ID, '_hours', true);
        $total_hours += floatval($hours);
    }

    return $total_hours;
}

function get_user_total_reservations($user_id)
{
    // تعداد کل رزروهای کاربر
    $reservations = get_posts(array(
        'post_type' => 'reservation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '='
            )
        )
    ));

    return count($reservations);
}

function get_user_active_reservations($user_id)
{
    // تعداد رزروهای فعال کاربر
    $reservations = get_posts(array(
        'post_type' => 'reservation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => array('pending', 'confirmed'),
                'compare' => 'IN'
            )
        )
    ));

    return count($reservations);
}

function get_user_recent_activities($user_id, $limit = 5)
{
    // آخرین فعالیت‌های کاربر
    $activities = array();

    $reservations = get_posts(array(
        'post_type' => 'reservation',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    foreach ($reservations as $reservation) {
        $device_id = get_post_meta($reservation->ID, '_device_id', true);
        $device_name = $device_id ? get_the_title($device_id) : 'دستگاه ناشناس';
        $hours = get_post_meta($reservation->ID, '_hours', true);
        $status = get_post_meta($reservation->ID, '_status', true);
        $start_time = get_post_meta($reservation->ID, '_start_time', true);

        $activities[] = array(
            'device' => $device_name,
            'hours' => $hours,
            'status' => $status,
            'date' => $start_time ? date('Y-m-d H:i', strtotime($start_time)) : get_the_date('Y-m-d H:i', $reservation->ID)
        );
    }

    return $activities;
}

function get_user_reservation_history($user_id, $page = 1, $per_page = 5)
{
    // سابقه رزروهای کاربر
    $args = array(
        'post_type' => 'reservation',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );

    // فیلتر بر اساس وضعیت
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $args['meta_query'][] = array(
            'key' => '_status',
            'value' => sanitize_text_field($_GET['status']),
            'compare' => '='
        );
    }

    // فیلتر بر اساس بازه زمانی
    if (isset($_GET['timeframe']) && !empty($_GET['timeframe'])) {
        $timeframe = sanitize_text_field($_GET['timeframe']);
        $date_query = array();

        switch ($timeframe) {
            case 'this_month':
                $date_query = array(
                    'year' => date('Y'),
                    'month' => date('m')
                );
                break;
            case 'last_month':
                $last_month = date('m') - 1;
                $year = date('Y');
                if ($last_month == 0) {
                    $last_month = 12;
                    $year = date('Y') - 1;
                }
                $date_query = array(
                    'year' => $year,
                    'month' => $last_month
                );
                break;
            case 'last_3_months':
                $date_query = array(
                    'after' => '3 months ago'
                );
                break;
        }

        if (!empty($date_query)) {
            $args['date_query'] = array($date_query);
        }
    }

    $query = new WP_Query($args);
    $reservations = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $device_id = get_post_meta($post_id, '_device_id', true);
            $game_net_id = get_post_meta($post_id, '_game_net_id', true);
            $start_time = get_post_meta($post_id, '_start_time', true);
            $end_time = get_post_meta($post_id, '_end_time', true);
            $hours = get_post_meta($post_id, '_hours', true);
            $price = get_post_meta($post_id, '_total_price', true);
            $status = get_post_meta($post_id, '_status', true);

            $reservations[] = array(
                'id' => $post_id,
                'date' => $start_time ? date('Y/m/d', strtotime($start_time)) : get_the_date('Y/m/d'),
                'device' => $device_id ? get_the_title($device_id) : 'دستگاه ناشناس',
                'game_net' => $game_net_id ? get_the_title($game_net_id) : 'گیم نت ناشناس',
                'start_time' => $start_time ? date('H:i', strtotime($start_time)) : '',
                'hours' => $hours,
                'price' => $price,
                'status' => $status
            );
        }
        wp_reset_postdata();
    }

    return array(
        'reservations' => $reservations,
        'total' => $query->found_posts,
        'max_num_pages' => $query->max_num_pages
    );
}

function get_user_upcoming_reservations($user_id)
{
    // رزروهای آینده کاربر
    $upcoming_reservations = array();

    $reservations = get_posts(array(
        'post_type' => 'reservation',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_user_id',
                'value' => $user_id,
                'compare' => '='
            ),
            array(
                'key' => '_status',
                'value' => array('pending', 'confirmed'),
                'compare' => 'IN'
            ),
            array(
                'key' => '_start_time',
                'value' => date('Y-m-d H:i:s'),
                'compare' => '>=',
                'type' => 'DATETIME'
            )
        ),
        'orderby' => 'meta_value',
        'meta_key' => '_start_time',
        'order' => 'ASC'
    ));

    foreach ($reservations as $reservation) {
        $device_id = get_post_meta($reservation->ID, '_device_id', true);
        $game_net_id = get_post_meta($reservation->ID, '_game_net_id', true);
        $start_time = get_post_meta($reservation->ID, '_start_time', true);
        $end_time = get_post_meta($reservation->ID, '_end_time', true);
        $hours = get_post_meta($reservation->ID, '_hours', true);
        $price = get_post_meta($reservation->ID, '_total_price', true);
        $status = get_post_meta($reservation->ID, '_status', true);

        $upcoming_reservations[] = array(
            'id' => $reservation->ID,
            'device' => $device_id ? get_the_title($device_id) : 'دستگاه ناشناس',
            'game_net' => $game_net_id ? get_the_title($game_net_id) : 'گیم نت ناشناس',
            'start_time' => $start_time,
            'end_time' => $end_time,
            'hours' => $hours,
            'price' => $price,
            'status' => $status
        );
    }

    return $upcoming_reservations;
}

// گرفتن آمار کاربر
$total_hours = get_user_total_hours($user_id);
$total_reservations = get_user_total_reservations($user_id);
$active_reservations = get_user_active_reservations($user_id);
$recent_activities = get_user_recent_activities($user_id, 4);

// گرفتن سابقه رزروها
$reservation_page = isset($_GET['reservation_page']) ? max(1, intval($_GET['reservation_page'])) : 1;
$reservation_history = get_user_reservation_history($user_id, $reservation_page, 5);

// گرفتن رزروهای آینده
$upcoming_reservations = get_user_upcoming_reservations($user_id);

// گرفتن اطلاعات پروفایل کاربر
$user_info = get_userdata($user_id);
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$phone = get_user_meta($user_id, 'phone', true);
$birthdate = get_user_meta($user_id, 'birthdate', true);
$address = get_user_meta($user_id, 'address', true);
?>
<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل کاربری گیمر</title>
    <?php wp_head() ?>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .progress-bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .status-completed {
            background: linear-gradient(45deg, #10b981, #059669);
        }

        .status-upcoming {
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
        }

        .status-cancelled {
            background: linear-gradient(45deg, #ef4444, #dc2626);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->

    <header class="gradient-bg text-white shadow-lg ">
        <div class="container mx-auto px-4 lg:px-6 py-3">
            <div class="flex items-center justify-between py-2 md:py-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg md:text-xl "><?php echo esc_html($current_user->display_name); ?></span>
                </div>

                <!-- منوی ناوبری در وسط - فقط در دسکتاپ نمایش داده می‌شود -->
                <nav class="hidden md:flex text-lg text-white space-x-reverse space-x-2 lg:space-x-4">
                    <a href="<?php echo home_url('/'); ?>" class="text-white hover:bg-blue-500/20 transition-colors py-2 px-3 rounded-md">صفحه اصلی</a>
                    <a href="<?php echo home_url('/about/'); ?>" class="text-white hover:bg-blue-500/20 transition-colors py-2 px-3 rounded-md">درباره ما</a>
                    <a href="<?php echo home_url('/contact/'); ?>" class="text-white hover:bg-blue-500/20 transition-colors py-2 px-3 rounded-md">تماس با ما</a>
                </nav>

                <!-- بخش کاربر در دسکتاپ -->
                <div class="hidden md:flex items-center gap-4">

                    <button id="logout-btn" class=" bg-red-500 text-white py-2 px-4 rounded-md transition-colors border-0 cursor-pointer hover:bg-red-600">
                        خروج
                    </button>
                </div>

                <!-- دکمه منوی موبایل -->
                <button class="md:hidden block bg-transparent border-0 text-white text-2xl cursor-pointer" id="mobile-menu-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- منوی موبایل -->
        <div class="md:hidden hidden gradient-bg px-6 pb-6 pt-3 shadow-xl" id="mobile-menu">
            <nav class="flex flex-col text-lg text-white space-y-4">
                <a href="<?php echo home_url('/'); ?>" class="text-white py-2 px-3 rounded-md">
                    صفحه اصلی
                </a>
                <a href="<?php echo home_url('/about/'); ?>" class="text-white py-2 px-3 rounded-md">
                    درباره ما
                </a>
                <a href="<?php echo home_url('/contact/'); ?>" class="text-white py-2 px-3 rounded-md">
                    تماس با ما
                </a>

                <!-- بخش کاربر در نسخه موبایل -->
                <div class="flex flex-col gap-3 mt-4 pt-4">
                    <button onclick="window.location.href='<?php echo wp_logout_url(home_url()); ?>" class="bg-red-500 text-white py-2 px-4 rounded-md transition-colors border-0 cursor-pointer hover:bg-red-600 flex items-center justify-center">
                        خروج
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- تأییدیه خروج -->
    <div class="logout-confirm fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden" id="logout-confirm">
        <div class="bg-white rounded-xl p-6 w-11/12 max-w-md">
            <h3 class="text-lg font-bold text-center mb-4">آیا مطمئن هستید؟</h3>
            <p class="text-gray-600 text-center mb-6">می‌خواهید از حساب کاربری خود خارج شوید؟</p>
            <div class="flex justify-center gap-3">
                <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors" id="cancel-logout">لغو</button>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" id="confirm-logout">خروج</button>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Navigation Tabs -->
        <div class="bg-white rounded-xl shadow-md p-2 mb-8">
            <div class="flex flex-wrap gap-2">
                <button class="tab-button active px-6 py-3 rounded-lg font-medium" data-tab="dashboard">
                    داشبورد
                </button>
                <button class="tab-button px-6 py-3 rounded-lg font-medium text-gray-600 hover:text-gray-800" data-tab="history">
                    سابقه رزروها
                </button>
                <button class="tab-button px-6 py-3 rounded-lg font-medium text-gray-600 hover:text-gray-800" data-tab="upcoming">
                    رزروهای آینده
                </button>
                <button class="tab-button px-6 py-3 rounded-lg font-medium text-gray-600 hover:text-gray-800" data-tab="profile">
                    تنظیمات پروفایل
                </button>
            </div>
        </div>

        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content">
            <!-- آمار کلی -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">کل ساعات بازی</p>
                            <p class="text-3xl font-bold text-purple-600"><?php echo esc_html($total_hours); ?></p>
                            <p class="text-sm text-gray-500">ساعت</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">کل رزروها</p>
                            <p class="text-3xl font-bold text-blue-600"><?php echo esc_html($total_reservations); ?></p>
                            <p class="text-sm text-gray-500">رزرو</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">رزروهای فعال</p>
                            <p class="text-3xl font-bold text-green-600"><?php echo esc_html($active_reservations); ?></p>
                            <p class="text-sm text-gray-500">رزرو</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <!-- آخرین فعالیت‌ها -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">آخرین فعالیت‌ها</h3>
                    <div class="space-y-4">
                        <?php foreach ($recent_activities as $activity):
                            $status_class = '';
                            $status_text = '';

                            switch ($activity['status']) {
                                case 'completed':
                                    $status_class = 'status-completed';
                                    $status_text = 'تکمیل شده';
                                    $text_color = 'text-green-600';
                                    break;
                                case 'confirmed':
                                case 'pending':
                                    $status_class = 'status-upcoming';
                                    $status_text = 'رزرو شده';
                                    $text_color = 'text-blue-600';
                                    break;
                                case 'cancelled':
                                    $status_class = 'status-cancelled';
                                    $status_text = 'لغو شده';
                                    $text_color = 'text-red-600';
                                    break;
                                default:
                                    $status_class = 'status-upcoming';
                                    $status_text = $activity['status'];
                                    $text_color = 'text-gray-600';
                            }
                        ?>
                            <div class="flex items-center space-x-4 space-x-reverse p-3 bg-gray-50 rounded-lg">
                                <div class="<?php echo $status_class; ?> w-3 h-3 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="font-medium"><?php echo esc_html($activity['device']); ?> - <?php echo esc_html($activity['hours']); ?> ساعت</p>
                                    <p class="text-sm text-gray-600"><?php echo esc_html(date('Y/m/d H:i', strtotime($activity['date']))); ?></p>
                                </div>
                                <span class="<?php echo $text_color; ?> font-semibold"><?php echo esc_html($status_text); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Tab -->
        <div id="history" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">سابقه رزروها</h2>
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <form method="get" class="flex gap-2">
                            <input type="hidden" name="tab" value="history">
                            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" onchange="this.form.submit()">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="completed" <?php selected(isset($_GET['status']) && $_GET['status'] == 'completed'); ?>>تکمیل شده</option>
                                <option value="cancelled" <?php selected(isset($_GET['status']) && $_GET['status'] == 'cancelled'); ?>>لغو شده</option>
                                <option value="confirmed" <?php selected(isset($_GET['status']) && $_GET['status'] == 'confirmed'); ?>>تایید شده</option>
                                <option value="pending" <?php selected(isset($_GET['status']) && $_GET['status'] == 'pending'); ?>>در انتظار</option>
                            </select>
                            <select name="timeframe" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" onchange="this.form.submit()">
                                <option value="">همه زمان‌ها</option>
                                <option value="this_month" <?php selected(isset($_GET['timeframe']) && $_GET['timeframe'] == 'this_month'); ?>>این ماه</option>
                                <option value="last_month" <?php selected(isset($_GET['timeframe']) && $_GET['timeframe'] == 'last_month'); ?>>ماه گذشته</option>
                                <option value="last_3_months" <?php selected(isset($_GET['timeframe']) && $_GET['timeframe'] == 'last_3_months'); ?>>۳ ماه گذشته</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">تاریخ</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">دستگاه</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">گیم نت</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">زمان شروع</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">مدت</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">هزینه</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservation_history['reservations'] as $reservation):
                                $status_class = '';
                                $status_text = '';

                                switch ($reservation['status']) {
                                    case 'completed':
                                        $status_class = 'bg-green-100 text-green-800';
                                        $status_text = 'تکمیل شده';
                                        break;
                                    case 'confirmed':
                                        $status_class = 'bg-blue-100 text-blue-800';
                                        $status_text = 'تایید شده';
                                        break;
                                    case 'pending':
                                        $status_class = 'bg-yellow-100 text-yellow-800';
                                        $status_text = 'در انتظار';
                                        break;
                                    case 'cancelled':
                                        $status_class = 'bg-red-100 text-red-800';
                                        $status_text = 'لغو شده';
                                        break;
                                    default:
                                        $status_class = 'bg-gray-100 text-gray-800';
                                        $status_text = $reservation['status'];
                                }
                            ?>
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-4"><?php echo esc_html($reservation['date']); ?></td>
                                    <td class="py-3 px-4"><?php echo esc_html($reservation['device']); ?></td>
                                    <td class="py-3 px-4"><?php echo esc_html($reservation['game_net']); ?></td>
                                    <td class="py-3 px-4"><?php echo esc_html($reservation['start_time']); ?></td>
                                    <td class="py-3 px-4"><?php echo esc_html($reservation['hours']); ?> ساعت</td>
                                    <td class="py-3 px-4"><?php echo esc_html(number_format($reservation['price'])); ?> تومان</td>
                                    <td class="py-3 px-4">
                                        <span class="<?php echo $status_class; ?> px-2 py-1 rounded-full text-sm"><?php echo esc_html($status_text); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6">
                    <p class="text-gray-600">
                        نمایش
                        <?php echo esc_html((($reservation_page - 1) * 5) + 1); ?>
                        تا
                        <?php echo esc_html(min($reservation_page * 5, $reservation_history['total'])); ?>
                        از
                        <?php echo esc_html($reservation_history['total']); ?>
                        رزرو
                    </p>
                    <div class="flex space-x-2 space-x-reverse">
                        <?php if ($reservation_page > 1): ?>
                            <a href="?tab=history&reservation_page=<?php echo $reservation_page - 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['timeframe']) ? '&timeframe=' . $_GET['timeframe'] : ''; ?>" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">قبلی</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $reservation_history['max_num_pages']; $i++): ?>
                            <a href="?tab=history&reservation_page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['timeframe']) ? '&timeframe=' . $_GET['timeframe'] : ''; ?>" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 <?php echo $i == $reservation_page ? 'bg-purple-600 text-white' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if ($reservation_page < $reservation_history['max_num_pages']): ?>
                            <a href="?tab=history&reservation_page=<?php echo $reservation_page + 1; ?><?php echo isset($_GET['status']) ? '&status=' . $_GET['status'] : ''; ?><?php echo isset($_GET['timeframe']) ? '&timeframe=' . $_GET['timeframe'] : ''; ?>" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">بعدی</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Reservations Tab -->
        <div id="upcoming" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">رزروهای آینده</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($upcoming_reservations as $reservation):
                        $status_class = '';
                        $status_text = '';

                        switch ($reservation['status']) {
                            case 'confirmed':
                                $status_class = 'bg-blue-100 text-blue-800';
                                $status_text = 'تایید شده';
                                break;
                            case 'pending':
                                $status_class = 'bg-yellow-100 text-yellow-800';
                                $status_text = 'در انتظار';
                                break;
                            default:
                                $status_class = 'bg-gray-100 text-gray-800';
                                $status_text = $reservation['status'];
                        }
                    ?>
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-lg"><?php echo esc_html($reservation['device']); ?></h3>
                                <span class="<?php echo $status_class; ?> px-2 py-1 rounded-full text-sm"><?php echo esc_html($status_text); ?></span>
                            </div>
                            <div class="space-y-2 text-gray-600">
                                <p><span class="font-medium">گیم نت:</span> <?php echo esc_html($reservation['game_net']); ?></p>
                                <p><span class="font-medium">تاریخ:</span> <?php echo esc_html(date('Y/m/d', strtotime($reservation['start_time']))); ?></p>
                                <p><span class="font-medium">ساعت:</span> <?php echo esc_html(date('H:i', strtotime($reservation['start_time']))); ?> - <?php echo esc_html(date('H:i', strtotime($reservation['end_time']))); ?></p>
                                <p><span class="font-medium">مدت:</span> <?php echo esc_html($reservation['hours']); ?> ساعت</p>
                                <p><span class="font-medium">هزینه:</span> <?php echo esc_html(number_format($reservation['price'])); ?> تومان</p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($upcoming_reservations)): ?>
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>هیچ رزرو آینده‌ای ندارید</p>
                            <a href="<?php echo home_url('/reserve'); ?>" class="text-purple-600 hover:text-purple-700 mt-2 inline-block">رزرو جدید ایجاد کنید</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Profile Settings Tab -->
        <div id="profile" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Info -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">اطلاعات شخصی</h2>
                    <form class="space-y-6" id="profile-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام</label>
                                <input type="text" name="first_name" value="<?php echo esc_attr($first_name); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام خانوادگی</label>
                                <input type="text" name="last_name" value="<?php echo esc_attr($last_name); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام کاربری</label>
                            <input type="text" value="<?php echo esc_attr($user_info->user_login); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            <p class="text-sm text-gray-500 mt-1">نام کاربری قابل تغییر نیست</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره موبایل</label>
                            <input type="tel" name="phone" value="<?php echo esc_attr($phone); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" value="<?php echo esc_attr($user_info->user_email); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تولد</label>
                            <input type="date" name="birthdate" value="<?php echo esc_attr($birthdate); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                            <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"><?php echo esc_textarea($address); ?></textarea>
                        </div>

                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors">
                            ذخیره تغییرات
                        </button>
                    </form>
                </div>

                <!-- Password Change -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">تغییر رمز عبور</h2>
                    <form class="space-y-6" id="password-form">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور فعلی</label>
                            <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور جدید</label>
                            <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور جدید</label>
                            <input type="password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                        </div>

                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors">
                            تغییر رمز عبور
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php wp_footer() ?>
    <?php
    include_once get_template_directory() . '/userpanel/userpanel-script.php';
    ?>
</body>

</html>
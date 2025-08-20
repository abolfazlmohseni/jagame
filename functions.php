<?php
// بارگذاری CSS و Tailwind
function hodcode_enqueue_styles() {
    wp_enqueue_style('hodkode-style', get_stylesheet_uri());
    wp_enqueue_style('tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    wp_enqueue_script('hodkode-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
    
    // Localize script for AJAX
    wp_localize_script('hodkode-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'login_nonce' => wp_create_nonce('ajax_login_nonce'),
        'update_nonce' => wp_create_nonce('update_game_net_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'hodcode_enqueue_styles');

// پشتیبانی قالب
add_action('after_setup_theme', function () {
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
});

// مخفی کردن نوار ادمین
add_filter('show_admin_bar', '__return_false');

// ثبت CPT گیم نت
function register_game_net_cpt() {
    $labels = array(
        'name' => 'گیم نت‌ها',
        'singular_name' => 'گیم نت',
        'add_new' => 'افزودن گیم نت',
        'add_new_item' => 'افزودن گیم نت جدید',
        'edit_item' => 'ویرایش گیم نت',
        'all_items' => 'تمام گیم نت‌ها',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-admin-site',
    );

    register_post_type('game_net', $args);
}
add_action('init', 'register_game_net_cpt');

// ثبت نقش مالک گیم نت
function add_game_net_roles() {
    add_role('game_net_owner', 'مالک گیم نت', array(
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false
    ));
}
add_action('init', 'add_game_net_roles');

// متاباکس‌های گیم نت
function game_net_meta_boxes() {
    add_meta_box('game_net_info', 'اطلاعات گیم نت', 'game_net_meta_box_callback', 'game_net', 'normal', 'high');
    add_meta_box('game_net_additional', 'اطلاعات تکمیلی', 'game_net_additional_meta_box', 'game_net', 'normal', 'high');
}
add_action('add_meta_boxes', 'game_net_meta_boxes');

function game_net_meta_box_callback($post) {
    wp_nonce_field('save_game_net_meta', 'game_net_meta_nonce');

    $phone = get_post_meta($post->ID, '_phone', true);
    $password = get_post_meta($post->ID, '_password', true);
    ?>
    <p>
        <label>شماره موبایل:</label>
        <input type="text" name="game_net_phone" value="<?php echo esc_attr($phone); ?>" class="widefat">
    </p>
    <p>
        <label>رمز عبور:</label>
        <input type="password" name="game_net_password" value="<?php echo esc_attr($password); ?>" class="widefat">
    </p>
    <?php
}

function game_net_additional_meta_box($post) {
    $fields = array(
        'gender' => 'جنسیت',
        'age' => 'شرایط سنی',
        'hours' => 'ساعت کاری',
        'holiday' => 'روز تعطیل',
        'bio' => 'بیوگرافی'
    );

    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, '_' . $key, true);
        echo '<p>';
        echo '<label>' . $label . ':</label>';
        if ($key === 'bio') {
            echo '<textarea name="game_net_' . $key . '" class="widefat" rows="4">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input type="text" name="game_net_' . $key . '" value="' . esc_attr($value) . '" class="widefat">';
        }
        echo '</p>';
    }
}

function save_game_net_meta($post_id) {
    if (!isset($_POST['game_net_meta_nonce']) || !wp_verify_nonce($_POST['game_net_meta_nonce'], 'save_game_net_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = array('phone', 'password', 'gender', 'age', 'hours', 'holiday', 'bio');
    
    foreach ($fields as $field) {
        if (isset($_POST['game_net_' . $field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST['game_net_' . $field]));
        }
    }
}
add_action('save_post', 'save_game_net_meta');

// AJAX Login
// AJAX Login
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login_handler');
add_action('wp_ajax_ajax_login', 'ajax_login_handler');

function ajax_login_handler() {
    // بررسی وجود security field
    if (!isset($_POST['security'])) {
        wp_send_json_error(array('message' => 'فیلد امنیتی وجود ندارد'));
        wp_die();
    }

    // بررسی nonce
    if (!wp_verify_nonce($_POST['security'], 'ajax_login_nonce')) {
        wp_send_json_error(array('message' => 'لطفاً صفحه را رفرش کرده و مجدد تلاش کنید'));
        wp_die();
    }

    $username = sanitize_text_field($_POST['username'] ?? '');
    $password = sanitize_text_field($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        wp_send_json_error(array('message' => 'لطفاً شماره موبایل و رمز عبور را وارد کنید'));
        wp_die();
    }

    // جستجوی گیم نت
    global $wpdb;
    $game_net_id = $wpdb->get_var($wpdb->prepare(
        "SELECT p.ID FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
        WHERE p.post_type = 'game_net'
        AND p.post_status = 'publish'
        AND pm1.meta_key = '_phone' AND pm1.meta_value = %s
        AND pm2.meta_key = '_password' AND pm2.meta_value = %s",
        $username,
        $password
    ));

    if ($game_net_id) {
        $user_id = get_current_user_id();
        
        // اگر کاربر لاگین نبوده، یک کاربر ایجاد کن
        if (!$user_id) {
            $user_id = wp_create_user('user_' . $game_net_id . '_' . time(), wp_generate_password(), $username . '@gamenet.com');
            
            if (is_wp_error($user_id)) {
                wp_send_json_error(array('message' => 'خطا در ایجاد حساب کاربری: ' . $user_id->get_error_message()));
                wp_die();
            }
        }

        // بروزرسانی نقش کاربر
        $user = new WP_User($user_id);
        $user->set_role('game_net_owner');
        
        // ذخیره game_net_id
        update_user_meta($user_id, '_game_net_id', $game_net_id);
        
        // لاگین کاربر
        wp_clear_auth_cookie();
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        // پیدا کردن صفحه پنل
        $panel_page = get_page_by_path('information-panel');
        $redirect_url = $panel_page ? get_permalink($panel_page) : home_url();
        
        wp_send_json_success(array('redirect' => $redirect_url));
    } else {
        wp_send_json_error(array('message' => 'شماره موبایل یا رمز عبور اشتباه است'));
    }

    wp_die();
}

// AJAX برای ذخیره اطلاعات گیم نت
add_action('wp_ajax_update_game_net_info', 'update_game_net_info_handler');

function update_game_net_info_handler() {
    // بررسی nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'update_game_net_nonce')) {
        wp_send_json_error('امنیت نامعتبر است');
        wp_die();
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('لطفاً ابتدا وارد شوید');
        wp_die();
    }

    $game_net_id = get_user_meta($user_id, '_game_net_id', true);
    if (!$game_net_id) {
        wp_send_json_error('گیم نت پیدا نشد');
        wp_die();
    }

    // بروزرسانی عنوان
    if (isset($_POST['gamenet_name'])) {
        wp_update_post(array(
            'ID' => $game_net_id,
            'post_title' => sanitize_text_field($_POST['gamenet_name'])
        ));
    }

    // بروزرسانی فیلدها
    $fields = array('gender', 'age', 'hours', 'holiday', 'bio');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($game_net_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    wp_send_json_success('اطلاعات با موفقیت بروزرسانی شد');
    wp_die();
}

// کوتاه‌نمای اطلاعات کاربر
function get_current_user_game_net_info() {
    $user_id = get_current_user_id();
    if (!$user_id) return false;

    $game_net_id = get_user_meta($user_id, '_game_net_id', true);
    if (!$game_net_id) return false;

    $info = array(
        'id' => $game_net_id,
        'name' => get_the_title($game_net_id),
        'phone' => get_post_meta($game_net_id, '_phone', true),
        'gender' => get_post_meta($game_net_id, '_gender', true),
        'age' => get_post_meta($game_net_id, '_age', true),
        'hours' => get_post_meta($game_net_id, '_hours', true),
        'holiday' => get_post_meta($game_net_id, '_holiday', true),
        'bio' => get_post_meta($game_net_id, '_bio', true)
    );

    return $info;
}

// Enable error logging for debugging
function log_ajax_errors() {
    if (WP_DEBUG && WP_DEBUG_LOG) {
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', WP_CONTENT_DIR . '/debug.log');
    }
}
add_action('init', 'log_ajax_errors');

// Debug function to check what's happening
function debug_ajax_login() {
    if (isset($_POST['action']) && $_POST['action'] === 'ajax_login') {
        error_log('AJAX Login Request: ' . print_r($_POST, true));
        
        // Check if nonce exists
        if (!isset($_POST['security'])) {
            error_log('Security field missing');
        } else {
            error_log('Nonce received: ' . $_POST['security']);
            error_log('Nonce verification: ' . (wp_verify_nonce($_POST['security'], 'ajax_login_nonce') ? 'VALID' : 'INVALID'));
        }
    }
}
add_action('wp_ajax_nopriv_ajax_login', 'debug_ajax_login', 1);
add_action('wp_ajax_ajax_login', 'debug_ajax_login', 1);

// تابع helper برای selected option
function theme_selected($value, $compare) {
    return selected($value, $compare, false);
}

// اگر تابع selected وجود ندارد، خودمون تعریفش کنیم
if (!function_exists('selected')) {
    function selected($selected, $current = true, $echo = true) {
        $result = ((string) $selected === (string) $current) ? ' selected="selected"' : '';
        if ($echo) {
            echo $result;
        }
        return $result;
    }
}

// Debug function for update game net info
function debug_update_game_net_info() {
    if (isset($_POST['action']) && $_POST['action'] === 'update_game_net_info') {
        error_log('Update Game Net Info Request: ' . print_r($_POST, true));
        
        if (!isset($_POST['security'])) {
            error_log('Security field missing in update request');
        } else {
            error_log('Update Nonce received: ' . $_POST['security']);
            error_log('Update Nonce verification: ' . (wp_verify_nonce($_POST['security'], 'update_game_net_nonce') ? 'VALID' : 'INVALID'));
        }
    }
}
add_action('wp_ajax_update_game_net_info', 'debug_update_game_net_info', 1);
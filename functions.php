<?php
// بارگذاری CSS و Tailwind
function hodcode_enqueue_styles()
{
    wp_enqueue_style(
        'jagame-tailwind',
        get_template_directory_uri() . '/asset/css/tailwind.css',
    );
    wp_enqueue_style('hodkode-style', get_stylesheet_uri());

    wp_enqueue_script('hodkode-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
    wp_localize_script('hodkode-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'login_nonce' => wp_create_nonce('ajax_login_nonce'),
        'update_nonce' => wp_create_nonce('update_game_net_nonce'),
        'device_nonce' => wp_create_nonce('device_management_nonce')
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
function register_game_net_cpt()
{
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

// ثبت CPT برای دستگاه‌ها
function register_devices_cpt()
{
    $labels = array(
        'name' => 'دستگاه‌ها',
        'singular_name' => 'دستگاه',
        'add_new' => 'افزودن دستگاه',
        'add_new_item' => 'افزودن دستگاه جدید',
        'edit_item' => 'ویرایش دستگاه',
        'all_items' => 'تمام دستگاه‌ها',
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'edit.php?post_type=game_net',
        'supports' => array('title'),
        'menu_icon' => 'dashicons-desktop',
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap' => true,
    );

    register_post_type('device', $args);
}
add_action('init', 'register_devices_cpt');

// ثبت نقش مالک گیم نت
function add_game_net_roles()
{
    add_role('game_net_owner', 'مالک گیم نت', array(
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false
    ));
}
add_action('init', 'add_game_net_roles');

// متاباکس‌های گیم نت
function game_net_meta_boxes()
{
    add_meta_box('game_net_info', 'اطلاعات گیم نت', 'game_net_meta_box_callback', 'game_net', 'normal', 'high');
    add_meta_box('game_net_additional', 'اطلاعات تکمیلی', 'game_net_additional_meta_box', 'game_net', 'normal', 'high');
    add_meta_box('game_net_gallery', 'گالری تصاویر', 'game_net_gallery_meta_box', 'game_net', 'normal', 'high');
}
add_action('add_meta_boxes', 'game_net_meta_boxes');

function game_net_meta_box_callback($post)
{
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

function game_net_additional_meta_box($post)
{
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

function game_net_gallery_meta_box($post)
{
    wp_nonce_field('save_game_net_gallery', 'game_net_gallery_nonce');

    // دریافت عکس‌های موجود
    $gallery_images = get_post_meta($post->ID, '_gallery_images', true);
    $gallery_images = !empty($gallery_images) ? explode(',', $gallery_images) : array();
?>

    <div id="game_net_gallery_container">
        <div id="game_net_gallery_images">
            <?php foreach ($gallery_images as $image_id): ?>
                <?php if ($image_url = wp_get_attachment_image_url($image_id, 'thumbnail')): ?>
                    <div class="gallery-image" data-image-id="<?php echo $image_id; ?>">
                        <img src="<?php echo $image_url; ?>" style="width: 100px; height: 100px; object-fit: cover;">
                        <button type="button" class="remove-image">حذف</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <input type="hidden" id="game_net_gallery_ids" name="game_net_gallery_ids" value="<?php echo implode(',', $gallery_images); ?>">

        <button type="button" id="game_net_add_image" class="button" style="margin-top: 10px;">
            افزودن تصویر
        </button>

        <p class="description">حداکثر ۱۰ تصویر قابل آپلود است</p>
    </div>

    <style>
        .gallery-image {
            display: inline-block;
            margin: 5px;
            text-align: center;
            position: relative;
        }

        .remove-image {
            display: block;
            margin-top: 5px;
            background: #dc3232;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>

    <script>
        jQuery(document).ready(function($) {
            var frame;
            var maxImages = 10;

            // افزودن تصویر
            $('#game_net_add_image').click(function(e) {
                e.preventDefault();

                var currentImages = $('#game_net_gallery_ids').val().split(',').filter(Boolean);
                if (currentImages.length >= maxImages) {
                    alert('حداکثر ' + maxImages + ' تصویر قابل آپلود است');
                    return;
                }

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'انتخاب تصویر',
                    button: {
                        text: 'استفاده از تصویر'
                    },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    var currentIds = $('#game_net_gallery_ids').val().split(',').filter(Boolean);

                    attachments.forEach(function(attachment) {
                        if (currentIds.length < maxImages) {
                            currentIds.push(attachment.id);

                            $('#game_net_gallery_images').append(
                                '<div class="gallery-image" data-image-id="' + attachment.id + '">' +
                                '<img src="' + attachment.sizes.thumbnail.url + '" style="width: 100px; height: 100px; object-fit: cover;">' +
                                '<button type="button" class="remove-image">حذف</button>' +
                                '</div>'
                            );
                        }
                    });

                    $('#game_net_gallery_ids').val(currentIds.join(','));
                });

                frame.open();
            });

            // حذف تصویر
            $(document).on('click', '.remove-image', function() {
                var imageDiv = $(this).closest('.gallery-image');
                var imageId = imageDiv.data('image-id');
                var currentIds = $('#game_net_gallery_ids').val().split(',').filter(Boolean);

                currentIds = currentIds.filter(function(id) {
                    return id != imageId;
                });

                $('#game_net_gallery_ids').val(currentIds.join(','));
                imageDiv.remove();
            });
        });
    </script>
<?php
}

function save_game_net_meta($post_id)
{
    if (!isset($_POST['game_net_meta_nonce']) || !wp_verify_nonce($_POST['game_net_meta_nonce'], 'save_game_net_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['game_net_gallery_nonce']) || !wp_verify_nonce($_POST['game_net_gallery_nonce'], 'save_game_net_gallery')) return;

    $fields = array('phone', 'password', 'gender', 'age', 'hours', 'holiday', 'bio');

    foreach ($fields as $field) {
        if (isset($_POST['game_net_' . $field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST['game_net_' . $field]));
        }
    }

    // ذخیره گالری تصاویر
    if (isset($_POST['game_net_gallery_ids'])) {
        $gallery_ids = array_filter(explode(',', $_POST['game_net_gallery_ids']));
        $gallery_ids = array_slice($gallery_ids, 0, 10); // محدودیت ۱۰ تصویر
        update_post_meta($post_id, '_gallery_images', implode(',', $gallery_ids));
    }
}
add_action('save_post', 'save_game_net_meta');

function game_net_admin_styles()
{
    echo '<style>
        .gallery-image {
            display: inline-block;
            margin: 5px;
            text-align: center;
            position: relative;
        }
        .remove-image {
            display: block;
            margin-top: 5px;
            background: #dc3232;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .remove-image:hover {
            background: #a00;
        }
    </style>';
}
add_action('admin_head', 'game_net_admin_styles');
// متاباکس‌های دستگاه
function device_meta_boxes()
{
    add_meta_box('device_info', 'اطلاعات دستگاه', 'device_meta_box_callback', 'device', 'normal', 'high');
}
add_action('add_meta_boxes', 'device_meta_boxes');

function device_meta_box_callback($post)
{
    wp_nonce_field('save_device_meta', 'device_meta_nonce');

    $type = get_post_meta($post->ID, '_type', true);
    $specs = get_post_meta($post->ID, '_specs', true);
    $price = get_post_meta($post->ID, '_price', true);
    $status = get_post_meta($post->ID, '_status', true);
    $game_net_id = get_post_meta($post->ID, '_game_net_id', true);

    // فقط برای ادمین قابل مشاهده باشد
    if (current_user_can('manage_options')) {
        echo '<p>';
        echo '<label>گیم نت مرتبط:</label>';
        echo '<select name="device_game_net_id" class="widefat">';

        $game_nets = get_posts(array(
            'post_type' => 'game_net',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));

        foreach ($game_nets as $game_net) {
            echo '<option value="' . $game_net->ID . '" ' . selected($game_net_id, $game_net->ID, false) . '>' . $game_net->post_title . '</option>';
        }
        echo '</select>';
        echo '</p>';
    } else {
        echo '<input type="hidden" name="device_game_net_id" value="' . esc_attr($game_net_id) . '">';
    }
?>
    <p>
        <label>نوع دستگاه:</label>
        <select name="device_type" class="widefat">
            <option value="pc" <?php selected($type, 'pc') ?>>PC</option>
            <option value="xbox" <?php selected($type, 'xbox') ?>>XBOX</option>
            <option value="ps4" <?php selected($type, 'ps4') ?>>PS4</option>
            <option value="ps5" <?php selected($type, 'ps5') ?>>PS5</option>
            <option value="vr" <?php selected($type, 'vr') ?>>VR</option>
            <option value="other" <?php selected($type, 'other') ?>>سایر</option>
        </select>
    </p>
    <p>
        <label>مشخصات فنی:</label>
        <textarea name="device_specs" class="widefat" rows="4"><?php echo esc_textarea($specs); ?></textarea>
    </p>
    <p>
        <label>قیمت ساعتی (تومان):</label>
        <input type="number" name="device_price" value="<?php echo esc_attr($price); ?>" class="widefat">
    </p>
    <p>
        <label>وضعیت:</label>
        <select name="device_status" class="widefat">
            <option value="قابل استفاده" <?php selected($status, 'قابل استفاده'); ?>>قابل استفاده</option>
            <option value="در حال تعمیر" <?php selected($status, 'در حال تعمیر'); ?>>در حال تعمیر</option>
            <option value="رزومه شده" <?php selected($status, 'رزومه شده'); ?>>رزرو شده</option>
            <option value="غیرفعال" <?php selected($status, 'غیرفعال'); ?>>غیرفعال</option>
        </select>
    </p>
<?php
}

function save_device_meta($post_id)
{
    if (!isset($_POST['device_meta_nonce']) || !wp_verify_nonce($_POST['device_meta_nonce'], 'save_device_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = array('type', 'specs', 'price', 'status', 'game_net_id');

    foreach ($fields as $field) {
        if (isset($_POST['device_' . $field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST['device_' . $field]));
        }
    }
}
add_action('save_post', 'save_device_meta');

// AJAX Login
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login_handler');
add_action('wp_ajax_ajax_login', 'ajax_login_handler');

function ajax_login_handler()
{
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
        $panel_page = get_page_by_path('Overview');
        $redirect_url = $panel_page ? get_permalink($panel_page) : home_url();

        wp_send_json_success(array('redirect' => $redirect_url));
    } else {
        wp_send_json_error(array('message' => 'شماره موبایل یا رمز عبور اشتباه است'));
    }

    wp_die();
}
// AJAX برای ذخیره اطلاعات گیم نت و آپلود عکس‌ها
add_action('wp_ajax_update_game_net_info', 'update_game_net_info_handler');

function update_game_net_info_handler()
{


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

    // پردازش آپلود عکس‌ها
    if (!empty($_FILES['gallery_images'])) {
        $uploaded_images = handle_gallery_upload($game_net_id, $_FILES['gallery_images']);

        if (is_wp_error($uploaded_images)) {
            wp_send_json_error('خطا در آپلود عکس‌ها: ' . $uploaded_images->get_error_message());
            wp_die();
        }
    }

    wp_send_json_success('اطلاعات با موفقیت بروزرسانی شد');
    wp_die();
}

// تابع برای پردازش آپلود عکس‌های گالری
function handle_gallery_upload($post_id, $files)
{
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $uploaded_ids = array();
    $existing_images = get_post_meta($post_id, '_gallery_images', true);
    $existing_images = !empty($existing_images) ? explode(',', $existing_images) : array();

    // بررسی تعداد کل عکس‌ها (موجود + جدید)
    $total_images = count($existing_images);
    $max_images = 10;

    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            // بررسی محدودیت تعداد عکس‌ها
            if ($total_images >= $max_images) {
                return new WP_Error('limit_exceeded', 'حداکثر ' . $max_images . ' تصویر قابل آپلود است');
            }

            $file = array(
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error'    => $files['error'][$key],
                'size'     => $files['size'][$key]
            );

            // آپلود فایل
            $upload = wp_handle_upload($file, array('test_form' => false));

            if (isset($upload['error'])) {
                return new WP_Error('upload_error', $upload['error']);
            }

            // ایجاد attachment
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($upload['file'])),
                'post_content'   => '',
                'post_status'    => 'inherit',
                'guid'           => $upload['url']
            );

            $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

            if (is_wp_error($attach_id)) {
                return $attach_id;
            }

            // ایجاد متادیتاهای attachment
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);

            $uploaded_ids[] = $attach_id;
            $total_images++;
        }
    }

    // ادغام عکس‌های جدید با عکس‌های موجود
    $all_images = array_merge($existing_images, $uploaded_ids);

    // محدود کردن به حداکثر ۱۰ عکس
    $all_images = array_slice($all_images, 0, $max_images);

    // ذخیره در متا
    update_post_meta($post_id, '_gallery_images', implode(',', $all_images));

    return $uploaded_ids;
}

// AJAX برای حذف عکس از گالری
add_action('wp_ajax_delete_game_net_image', 'delete_game_net_image_handler');

function delete_game_net_image_handler()
{
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

    if (!isset($_POST['image_id'])) {
        wp_send_json_error('شناسه تصویر ارسال نشده است');
        wp_die();
    }

    $image_id = intval($_POST['image_id']);

    // دریافت عکس‌های موجود
    $gallery_images = get_post_meta($game_net_id, '_gallery_images', true);
    $gallery_images = !empty($gallery_images) ? explode(',', $gallery_images) : array();

    // حذف عکس از آرایه
    $gallery_images = array_filter($gallery_images, function ($id) use ($image_id) {
        return $id != $image_id;
    });

    // حذف فایل فیزیکی
    if (wp_attachment_is_image($image_id)) {
        wp_delete_attachment($image_id, true);
    }

    // ذخیره لیست جدید
    update_post_meta($game_net_id, '_gallery_images', implode(',', $gallery_images));

    wp_send_json_success('تصویر با موفقیت حذف شد');
    wp_die();
}
// AJAX برای اضافه کردن دستگاه جدید
add_action('wp_ajax_add_device', 'add_device_handler');
function add_device_handler()
{
    // بررسی nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'device_management_nonce')) {
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

    // اعتبارسنجی داده‌های ورودی
    $name = sanitize_text_field($_POST['name'] ?? '');
    $type = sanitize_text_field($_POST['type'] ?? '');
    $specs = sanitize_textarea_field($_POST['specs'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $status = sanitize_text_field($_POST['status'] ?? 'available');

    if (empty($name) || empty($type)) {
        wp_send_json_error('نام و نوع دستگاه الزامی است');
        wp_die();
    }

    // ایجاد دستگاه جدید
    $device_id = wp_insert_post(array(
        'post_title' => $name,
        'post_type' => 'device',
        'post_status' => 'publish'
    ));

    if (is_wp_error($device_id)) {
        wp_send_json_error('خطا در ایجاد دستگاه: ' . $device_id->get_error_message());
        wp_die();
    }

    // ذخیره متادیتا
    update_post_meta($device_id, '_type', $type);
    update_post_meta($device_id, '_specs', $specs);
    update_post_meta($device_id, '_price', $price);
    update_post_meta($device_id, '_status', $status);
    update_post_meta($device_id, '_game_net_id', $game_net_id);

    wp_send_json_success(array(
        'message' => 'دستگاه با موفقیت اضافه شد',
        'device_id' => $device_id
    ));
    wp_die();
}

// AJAX برای دریافت لیست دستگاه‌ها
add_action('wp_ajax_get_devices', 'get_devices_handler');
function get_devices_handler()
{
    // بررسی nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'device_management_nonce')) {
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

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
    $offset = ($page - 1) * $per_page;

    // دریافت دستگاه‌های مربوط به این گیم نت
    $args = array(
        'post_type' => 'device',
        'posts_per_page' => $per_page,
        'offset' => $offset,
        'meta_query' => array(
            array(
                'key' => '_game_net_id',
                'value' => $game_net_id,
                'compare' => '='
            )
        ),
        'orderby' => 'title',
        'order' => 'ASC'
    );

    $query = new WP_Query($args);
    $devices = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $devices[] = array(
                'id' => $post_id,
                'name' => get_the_title(),
                'type' => get_post_meta($post_id, '_type', true),
                'specs' => get_post_meta($post_id, '_specs', true),
                'price' => get_post_meta($post_id, '_price', true),
                'status' => get_post_meta($post_id, '_status', true)
            );
        }
        wp_reset_postdata();
    }

    // اطلاعات pagination
    $total_posts = $query->found_posts;
    $total_pages = ceil($total_posts / $per_page);

    wp_send_json_success(array(
        'devices' => $devices,
        'pagination' => array(
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_items' => $total_posts,
            'per_page' => $per_page
        )
    ));
    wp_die();
}

// AJAX برای دریافت اطلاعات یک دستگاه
add_action('wp_ajax_get_device', 'get_device_handler');
function get_device_handler()
{
    // بررسی nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'device_management_nonce')) {
        wp_send_json_error('امنیت نامعتبر است');
        wp_die();
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('لطفاً ابتدا وارد شوید');
        wp_die();
    }

    $device_id = isset($_POST['device_id']) ? intval($_POST['device_id']) : 0;
    if (!$device_id) {
        wp_send_json_error('دستگاه مشخص نشده است');
        wp_die();
    }

    // بررسی مالکیت دستگاه
    $game_net_id = get_user_meta($user_id, '_game_net_id', true);
    $device_game_net_id = get_post_meta($device_id, '_game_net_id', true);

    if ($device_game_net_id != $game_net_id) {
        wp_send_json_error('شما مجاز به مشاهده این دستگاه نیستید');
        wp_die();
    }

    // دریافت اطلاعات دستگاه
    $device = array(
        'id' => $device_id,
        'name' => get_the_title($device_id),
        'type' => get_post_meta($device_id, '_type', true),
        'specs' => get_post_meta($device_id, '_specs', true),
        'price' => get_post_meta($device_id, '_price', true),
        'status' => get_post_meta($device_id, '_status', true)
    );

    wp_send_json_success($device);
    wp_die();
}

// AJAX برای ویرایش دستگاه
add_action('wp_ajax_update_device', 'update_device_handler');
function update_device_handler()
{
    // بررسی nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'device_management_nonce')) {
        wp_send_json_error('امنیت نامعتبر است');
        wp_die();
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('لطفاً ابتدا وارد شوید');
        wp_die();
    }

    $device_id = isset($_POST['device_id']) ? intval($_POST['device_id']) : 0;
    if (!$device_id) {
        wp_send_json_error('دستگاه مشخص نشده است');
        wp_die();
    }

    // بررسی مالکیت دستگاه
    $game_net_id = get_user_meta($user_id, '_game_net_id', true);
    $device_game_net_id = get_post_meta($device_id, '_game_net_id', true);

    if ($device_game_net_id != $game_net_id) {
        wp_send_json_error('شما مجاز به ویرایش این دستگاه نیستید');
        wp_die();
    }

    // اعتبارسنجی داده‌های ورودی
    $name = sanitize_text_field($_POST['name'] ?? '');
    $type = sanitize_text_field($_POST['type'] ?? '');
    $specs = sanitize_textarea_field($_POST['specs'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $status = sanitize_text_field($_POST['status'] ?? 'available');

    if (empty($name) || empty($type)) {
        wp_send_json_error('نام و نوع دستگاه الزامی است');
        wp_die();
    }

    // به روزرسانی دستگاه
    wp_update_post(array(
        'ID' => $device_id,
        'post_title' => $name
    ));

    // ذخیره متادیتا
    update_post_meta($device_id, '_type', $type);
    update_post_meta($device_id, '_specs', $specs);
    update_post_meta($device_id, '_price', $price);
    update_post_meta($device_id, '_status', $status);

    wp_send_json_success('دستگاه با موفقیت ویرایش شد');
    wp_die();
}

// AJAX برای حذف دستگاه
add_action('wp_ajax_delete_device', 'delete_device_handler');
function delete_device_handler()
{
    // بررسی nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'device_management_nonce')) {
        wp_send_json_error('امنیت نامعتبر است');
        wp_die();
    }

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('لطفاً ابتدا وارد شوید');
        wp_die();
    }

    $device_id = isset($_POST['device_id']) ? intval($_POST['device_id']) : 0;
    if (!$device_id) {
        wp_send_json_error('دستگاه مشخص نشده است');
        wp_die();
    }

    // بررسی مالکیت دستگاه
    $game_net_id = get_user_meta($user_id, '_game_net_id', true);
    $device_game_net_id = get_post_meta($device_id, '_game_net_id', true);

    if ($device_game_net_id != $game_net_id) {
        wp_send_json_error('شما مجاز به حذف این دستگاه نیستید');
        wp_die();
    }


    $result = wp_delete_post($device_id, true);

    if ($result) {
        wp_send_json_success('دستگاه با موفقیت حذف شد');
    } else {
        wp_send_json_error('خطا در حذف دستگاه');
    }
    wp_die();
}

function get_current_user_game_net_info()
{
    $user_id = get_current_user_id();
    if (!$user_id) return false;

    $game_net_id = get_user_meta($user_id, '_game_net_id', true);
    if (!$game_net_id) return false;

    $post = get_post($game_net_id);
    if (!$post) return false;

    return array(
        'name' => $post->post_title,
        'phone' => get_post_meta($game_net_id, '_phone', true),
        'password' => get_post_meta($game_net_id, '_password', true),
        'gender' => get_post_meta($game_net_id, '_gender', true),
        'age' => get_post_meta($game_net_id, '_age', true),
        'hours' => get_post_meta($game_net_id, '_hours', true),
        'holiday' => get_post_meta($game_net_id, '_holiday', true),
        'bio' => get_post_meta($game_net_id, '_bio', true),
        'gallery_images' => get_post_meta($game_net_id, '_gallery_images', true),
        'profile_picture' => get_post_meta($game_net_id, '_profile_picture_id', true) // اضافه شد
    );
}

// Debug function to check what's happening
function debug_ajax_login()
{
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
function theme_selected($value, $compare)
{
    return selected($value, $compare, false);
}

// اگر تابع selected وجود ندارد، خودمون تعریفش کنیم
if (!function_exists('selected')) {
    function selected($selected, $current = true, $echo = true)
    {
        $result = ((string) $selected === (string) $current) ? ' selected="selected"' : '';
        if ($echo) {
            echo $result;
        }
        return $result;
    }
}

// Debug function for update game net info
function debug_update_game_net_info()
{
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

// AJAX برای گرفتن لیست گیم نت‌ها
add_action('wp_ajax_get_game_nets_list', 'get_game_nets_list_handler');
add_action('wp_ajax_nopriv_get_game_nets_list', 'get_game_nets_list_handler');

function get_game_nets_list_handler()
{
    // بررسی nonce برای امنیت
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'game_nets_list_nonce')) {
        wp_send_json_error('امنیت نامعتبر است');
        wp_die();
    }

    // پارامترهای pagination
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 10;
    $offset = ($page - 1) * $per_page;

    // گرفتن گیم نت‌ها
    $args = array(
        'post_type' => 'game_net',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'offset' => $offset,
        'orderby' => 'title',
        'order' => 'ASC'
    );

    // فیلتر بر اساس جنسیت (اگر وجود دارد)
    if (isset($_POST['gender']) && !empty($_POST['gender'])) {
        $args['meta_query'] = array(
            array(
                'key' => '_gender',
                'value' => sanitize_text_field($_POST['gender']),
                'compare' => '='
            )
        );
    }

    // فیلتر بر اساس شرایط سنی (اگر وجود دارد)
    if (isset($_POST['age']) && !empty($_POST['age'])) {
        $args['meta_query'] = array(
            array(
                'key' => '_age',
                'value' => sanitize_text_field($_POST['age']),
                'compare' => 'LIKE'
            )
        );
    }

    $query = new WP_Query($args);
    $game_nets = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            $game_nets[] = array(
                'id' => $post_id,
                'name' => get_the_title(),
                'phone' => get_post_meta($post_id, '_phone', true),
                'gender' => get_post_meta($post_id, '_gender', true),
                'age' => get_post_meta($post_id, '_age', true),
                'hours' => get_post_meta($post_id, '_hours', true),
                'holiday' => get_post_meta($post_id, '_holiday', true),
                'bio' => get_post_meta($post_id, '_bio', true),
                'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium') ?: get_template_directory_uri() . '/images/default-game-net.jpg',
                'gallery_images' => get_post_meta($post_id, '_gallery_images', true),
                'permalink' => esc_url(get_permalink($post_id)),
                'profile_picture' => get_post_meta($post_id, '_profile_picture_id', true)
            );
        }
        wp_reset_postdata();
    }

    // اطلاعات pagination
    $total_posts = $query->found_posts;
    $total_pages = ceil($total_posts / $per_page);

    wp_send_json_success(array(
        'game_nets' => $game_nets,
        'pagination' => array(
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_items' => $total_posts,
            'per_page' => $per_page
        )
    ));

    wp_die();
}

// اضافه کردن rewrite rule برای URL زیباتر
add_action('init', 'custom_game_net_rewrite_rules');
function custom_game_net_rewrite_rules()
{
    add_rewrite_rule(
        '^game-net/([0-9]+)/?$',
        'index.php?p=$matches[1]&post_type=game_net',
        'top'
    );
}

// اضافه کردن query var
add_filter('query_vars', 'custom_game_net_query_vars');
function custom_game_net_query_vars($vars)
{
    $vars[] = 'game_net_id';
    return $vars;
}

// متاباکس برای تصویر پروفایل گیم نت
function add_game_net_profile_picture_meta_box()
{
    add_meta_box(
        'game_net_profile_picture',
        'تصویر پروفایل',
        'game_net_profile_picture_meta_box_callback',
        'game_net',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_game_net_profile_picture_meta_box');

function game_net_profile_picture_meta_box_callback($post)
{
    wp_nonce_field('save_game_net_profile_picture', 'game_net_profile_picture_nonce');

    $profile_picture_id = get_post_meta($post->ID, '_profile_picture_id', true);
    $profile_picture_url = $profile_picture_id ? wp_get_attachment_image_url($profile_picture_id, 'medium') : '';
?>

    <div id="game_net_profile_picture_container">
        <div id="game_net_profile_picture_preview" style="margin-bottom: 10px;">
            <?php if ($profile_picture_url): ?>
                <img src="<?php echo $profile_picture_url; ?>" style="max-width: 100%; height: auto;">
            <?php endif; ?>
        </div>

        <input type="hidden" id="game_net_profile_picture_id" name="game_net_profile_picture_id" value="<?php echo $profile_picture_id; ?>">

        <button type="button" id="game_net_upload_profile_picture" class="button" style="margin-bottom: 10px;">
            <?php echo $profile_picture_id ? 'تغییر تصویر' : 'آپلود تصویر'; ?>
        </button>

        <?php if ($profile_picture_id): ?>
            <button type="button" id="game_net_remove_profile_picture" class="button button-danger" style="background: #dc3232; color: white;">
                حذف تصویر
            </button>
        <?php endif; ?>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var profile_frame;

            // آپلود تصویر پروفایل
            $('#game_net_upload_profile_picture').click(function(e) {
                e.preventDefault();

                if (profile_frame) {
                    profile_frame.open();
                    return;
                }

                profile_frame = wp.media({
                    title: 'انتخاب تصویر پروفایل',
                    button: {
                        text: 'استفاده از تصویر'
                    },
                    multiple: false
                });

                profile_frame.on('select', function() {
                    var attachment = profile_frame.state().get('selection').first().toJSON();

                    $('#game_net_profile_picture_id').val(attachment.id);
                    $('#game_net_profile_picture_preview').html('<img src="' + attachment.sizes.medium.url + '" style="max-width: 100%; height: auto;">');
                    $('#game_net_remove_profile_picture').show();
                    $(this).text('تغییر تصویر');
                });

                profile_frame.open();
            });

            // حذف تصویر پروفایل
            $('#game_net_remove_profile_picture').click(function(e) {
                e.preventDefault();

                $('#game_net_profile_picture_id').val('');
                $('#game_net_profile_picture_preview').html('');
                $(this).hide();
                $('#game_net_upload_profile_picture').text('آپلود تصویر');
            });
        });
    </script>

    <style>
        .button-danger {
            background: #dc3232;
            color: white;
            border-color: #dc3232;
        }

        .button-danger:hover {
            background: #a00;
            border-color: #a00;
            color: white;
        }
    </style>
<?php
}

// ذخیره تصویر پروفایل
function save_game_net_profile_picture($post_id)
{
    if (
        !isset($_POST['game_net_profile_picture_nonce']) ||
        !wp_verify_nonce($_POST['game_net_profile_picture_nonce'], 'save_game_net_profile_picture')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['game_net_profile_picture_id'])) {
        $profile_picture_id = intval($_POST['game_net_profile_picture_id']);
        update_post_meta($post_id, '_profile_picture_id', $profile_picture_id);

        // همچنین به عنوان تصویر شاخص پست تنظیم شود
        if ($profile_picture_id) {
            set_post_thumbnail($post_id, $profile_picture_id);
        } else {
            delete_post_thumbnail($post_id);
        }
    }
}
add_action('save_post', 'save_game_net_profile_picture');

// AJAX برای آپلود تصویر پروفایل از پنل کاربری
add_action('wp_ajax_upload_game_net_profile_picture', 'upload_game_net_profile_picture_handler');

function upload_game_net_profile_picture_handler()
{
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

    if (empty($_FILES['profile_picture'])) {
        wp_send_json_error('هیچ فایلی آپلود نشده است');
        wp_die();
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('profile_picture', $game_net_id);

    if (is_wp_error($attachment_id)) {
        wp_send_json_error('خطا در آپلود تصویر: ' . $attachment_id->get_error_message());
        wp_die();
    }

    update_post_meta($game_net_id, '_profile_picture_id', $attachment_id);
    set_post_thumbnail($game_net_id, $attachment_id);

    wp_send_json_success(array(
        'message' => 'تصویر پروفایل با موفقیت آپلود شد',
        'image_url' => wp_get_attachment_image_url($attachment_id, 'medium')
    ));
    wp_die();
};


add_action("after_setup_theme", function () {
    register_nav_menus([
        'landing header' => 'landing header',

    ]);
    register_nav_menus([
        'footer' => 'footer'
    ]);
});

// ثبت CPT برای درخواست‌های ثبت‌نام گیم‌نت
function register_game_net_requests_cpt()
{
    $labels = array(
        'name' => 'درخواست‌های ثبت‌نام',
        'singular_name' => 'درخواست ثبت‌نام',
        'add_new' => 'افزودن درخواست',
        'add_new_item' => 'افزودن درخواست جدید',
        'edit_item' => 'ویرایش درخواست',
        'all_items' => 'تمام درخواست‌ها',
        'view_item' => 'مشاهده درخواست',
        'search_items' => 'جستجوی درخواست‌ها',
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-clipboard',
        'capability_type' => 'post',
        'capabilities' => array(
            'create_posts' => false, // کاربران نمی‌توانند به صورت دستی درخواست ایجاد کنند
        ),
        'map_meta_cap' => true,
        'supports' => array('title'),
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
    );

    register_post_type('game_net_request', $args);
}
add_action('init', 'register_game_net_requests_cpt');

// متاباکس‌های درخواست ثبت‌نام
function game_net_request_meta_boxes()
{
    add_meta_box('game_net_request_info', 'اطلاعات درخواست', 'game_net_request_info_meta_box', 'game_net_request', 'normal', 'high');
    add_meta_box('game_net_request_status', 'وضعیت درخواست', 'game_net_request_status_meta_box', 'game_net_request', 'side', 'high');
}
add_action('add_meta_boxes', 'game_net_request_meta_boxes');

function game_net_request_info_meta_box($post)
{
    wp_nonce_field('save_game_net_request_meta', 'game_net_request_meta_nonce');

    // دریافت تمام فیلدهای ذخیره شده
    $fields = array(
        'phone' => 'شماره موبایل',
        'address' => 'آدرس کامل',
        'area' => 'منطقه',
        'gender_status' => 'وضعیت جنسیت',
        'start_time' => 'ساعت شروع',
        'end_time' => 'ساعت پایان',
        'off_days' => 'روزهای تعطیل',
        'min_age' => 'حداقل سن',
        'max_age' => 'حداکثر سن',
        'hourly_price' => 'قیمت ساعتی',
        'devices' => 'دستگاه‌های موجود',
        'additional_features' => 'امکانات اضافی'
    );

    echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">';

    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, '_' . $key, true);

        echo '<div style="margin-bottom: 15px;">';
        echo '<strong>' . $label . ':</strong><br>';

        if ($key === 'off_days' || $key === 'devices') {
            // نمایش لیست برای فیلدهای چندتایی
            if (!empty($value)) {
                $items = maybe_unserialize($value);
                if (is_array($items)) {
                    echo '<ul style="margin: 5px 0; padding-right: 20px;">';
                    foreach ($items as $item) {
                        echo '<li>' . esc_html($item) . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo esc_html($value);
                }
            } else {
                echo '<span style="color: #999;">---</span>';
            }
        } else {
            echo esc_html($value ?: '<span style="color: #999;">---</span>');
        }

        echo '</div>';
    }

    echo '</div>';
}

function game_net_request_status_meta_box($post)
{
    $status = get_post_meta($post->ID, '_request_status', true);
    $status = $status ?: 'pending';
?>
    <p>
        <label for="request_status">وضعیت:</label>
        <select name="request_status" id="request_status" style="width: 100%; margin-top: 5px;">
            <option value="pending" <?php selected($status, 'pending'); ?>>در انتظار بررسی</option>
            <option value="approved" <?php selected($status, 'approved'); ?>>تایید شده</option>
            <option value="rejected" <?php selected($status, 'rejected'); ?>>رد شده</option>
            <option value="contacted" <?php selected($status, 'contacted'); ?>>تماس گرفته شده</option>
        </select>
    </p>

    <p>
        <label for="admin_notes">یادداشت ادمین:</label>
        <textarea name="admin_notes" id="admin_notes" rows="4" style="width: 100%; margin-top: 5px;"><?php echo esc_textarea(get_post_meta($post->ID, '_admin_notes', true)); ?></textarea>
    </p>
<?php
}

// ذخیره متادیتای درخواست
function save_game_net_request_meta($post_id)
{
    if (!isset($_POST['game_net_request_meta_nonce']) || !wp_verify_nonce($_POST['game_net_request_meta_nonce'], 'save_game_net_request_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ('game_net_request' !== get_post_type($post_id)) return;

    // ذخیره وضعیت درخواست
    if (isset($_POST['request_status'])) {
        update_post_meta($post_id, '_request_status', sanitize_text_field($_POST['request_status']));
    }

    // ذخیره یادداشت ادمین
    if (isset($_POST['admin_notes'])) {
        update_post_meta($post_id, '_admin_notes', sanitize_textarea_field($_POST['admin_notes']));
    }
}
add_action('save_post', 'save_game_net_request_meta');

// تغییر ستون‌های لیست درخواست‌ها
function game_net_request_columns($columns)
{
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => 'نام گیم نت',
        'phone' => 'شماره تماس',
        'area' => 'منطقه',
        'status' => 'وضعیت',
        'date' => 'تاریخ ثبت'
    );
    return $new_columns;
}
add_filter('manage_game_net_request_posts_columns', 'game_net_request_columns');

function game_net_request_custom_column($column, $post_id)
{
    switch ($column) {
        case 'phone':
            echo esc_html(get_post_meta($post_id, '_phone', true));
            break;
        case 'area':
            echo esc_html(get_post_meta($post_id, '_area', true));
            break;
        case 'status':
            $status = get_post_meta($post_id, '_request_status', true);
            $status_labels = array(
                'pending' => 'در انتظار بررسی',
                'approved' => 'تایید شده',
                'rejected' => 'رد شده',
                'contacted' => 'تماس گرفته شده'
            );
            $status_class = array(
                'pending' => 'style="background: #ffb900; color: #000; padding: 3px 8px; border-radius: 3px;"',
                'approved' => 'style="background: #46b450; color: #fff; padding: 3px 8px; border-radius: 3px;"',
                'rejected' => 'style="background: #dc3232; color: #fff; padding: 3px 8px; border-radius: 3px;"',
                'contacted' => 'style="background: #0073aa; color: #fff; padding: 3px 8px; border-radius: 3px;"'
            );
            echo '<span ' . ($status_class[$status] ?? '') . '>' . ($status_labels[$status] ?? 'نامشخص') . '</span>';
            break;
    }
}
add_action('manage_game_net_request_posts_custom_column', 'game_net_request_custom_column', 10, 2);

// فیلتر وضعیت‌ها در لیست
function game_net_request_status_filter()
{
    global $post_type;
    if ($post_type == 'game_net_request') {
        $status = isset($_GET['request_status']) ? $_GET['request_status'] : '';
        $statuses = array(
            '' => 'همه وضعیت‌ها',
            'pending' => 'در انتظار بررسی',
            'approved' => 'تایید شده',
            'rejected' => 'رد شده',
            'contacted' => 'تماس گرفته شده'
        );

        echo '<select name="request_status">';
        foreach ($statuses as $value => $label) {
            printf('<option value="%s"%s>%s</option>', $value, $value == $status ? ' selected="selected"' : '', $label);
        }
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'game_net_request_status_filter');

function filter_game_net_requests_by_status($query)
{
    global $pagenow;
    $type = 'game_net_request';

    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == $type && isset($_GET['request_status']) && $_GET['request_status'] != '') {
        $query->query_vars['meta_key'] = '_request_status';
        $query->query_vars['meta_value'] = sanitize_text_field($_GET['request_status']);
    }
}
add_filter('parse_query', 'filter_game_net_requests_by_status');

// پردازش فرم ثبت‌نام و ایجاد درخواست
function process_game_net_registration_form()
{
    // بررسی nonce برای امنیت
    if (!isset($_POST['registration_nonce']) || !wp_verify_nonce($_POST['registration_nonce'], 'game_net_registration_nonce')) {
        wp_send_json_error('خطای امنیتی. لطفاً صفحه را رفرش کرده و مجدد تلاش کنید.');
    }

    // اعتبارسنجی فیلدهای اجباری
    $required_fields = array(
        'gamenetName' => 'نام گیم نت',
        'phoneNumber' => 'شماره موبایل',
        'address' => 'آدرس کامل',
        'area' => 'منطقه',
        'genderStatus' => 'وضعیت جنسیت',
        'startTime' => 'ساعت شروع',
        'endTime' => 'ساعت پایان',
        'minAge' => 'حداقل سن',
        'hourlyPrice' => 'قیمت ساعتی'
    );

    $errors = array();
    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[] = 'فیلد ' . $label . ' الزامی است.';
        }
    }

    if (!empty($errors)) {
        wp_send_json_error(implode('<br>', $errors));
    }

    // ایجاد درخواست جدید
    $request_id = wp_insert_post(array(
        'post_title' => sanitize_text_field($_POST['gamenetName']),
        'post_type' => 'game_net_request',
        'post_status' => 'publish'
    ));

    if (is_wp_error($request_id)) {
        wp_send_json_error('خطا در ثبت درخواست. لطفاً مجدد تلاش کنید.');
    }

    // ذخیره فیلدها
    $fields_to_save = array(
        'phoneNumber' => 'phone',
        'address' => 'address',
        'area' => 'area',
        'genderStatus' => 'gender_status',
        'startTime' => 'start_time',
        'endTime' => 'end_time',
        'minAge' => 'min_age',
        'maxAge' => 'max_age',
        'hourlyPrice' => 'hourly_price',
        'additionalFeatures' => 'additional_features'
    );

    foreach ($fields_to_save as $form_field => $meta_field) {
        if (!empty($_POST[$form_field])) {
            update_post_meta($request_id, '_' . $meta_field, sanitize_text_field($_POST[$form_field]));
        }
    }

    // ذخیره فیلدهای چندتایی
    if (!empty($_POST['offDays'])) {
        $off_days = is_array($_POST['offDays']) ? $_POST['offDays'] : array($_POST['offDays']);
        update_post_meta($request_id, '_off_days', maybe_serialize(array_map('sanitize_text_field', $off_days)));
    }

    if (!empty($_POST['devices'])) {
        $devices = is_array($_POST['devices']) ? $_POST['devices'] : array($_POST['devices']);
        update_post_meta($request_id, '_devices', maybe_serialize(array_map('sanitize_text_field', $devices)));
    }

    // وضعیت پیش‌فرض
    update_post_meta($request_id, '_request_status', 'pending');

    // ارسال ایمیل به مدیر
    $admin_email = get_option('admin_email');
    $subject = 'درخواست ثبت‌نام گیم‌نت جدید: ' . sanitize_text_field($_POST['gamenetName']);

    $message = "یک درخواست ثبت‌نام گیم‌نت جدید دریافت شده است:\n\n";
    $message .= "نام گیم نت: " . sanitize_text_field($_POST['gamenetName']) . "\n";
    $message .= "شماره تماس: " . sanitize_text_field($_POST['phoneNumber']) . "\n";
    $message .= "آدرس: " . sanitize_text_field($_POST['address']) . "\n";
    $message .= "منطقه: " . sanitize_text_field($_POST['area']) . "\n\n";
    $message .= "برای مشاهده جزئیات کامل به پنل مدیریت مراجعه کنید:\n";
    $message .= admin_url('edit.php?post_type=game_net_request');

    wp_mail($admin_email, $subject, $message);

    wp_send_json_success('درخواست شما با موفقیت ثبت شد. به زودی با شما تماس خواهیم گرفت.');
}

// ثبت Ajax برای فرم ثبت‌نام
add_action('wp_ajax_game_net_registration', 'process_game_net_registration_form');
add_action('wp_ajax_nopriv_game_net_registration', 'process_game_net_registration_form');

function add_registration_nonce()
{
    wp_nonce_field('game_net_registration_nonce', 'registration_nonce');
}
add_action('wp_footer', 'add_registration_nonce');

// 
// 

// ثبت نقش گیمر(اگر وجود ندارد)
function add_regular_user_role()
{
    if (!get_role('regular_user')) {
        add_role('regular_user', 'گیمر', array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false
        ));
    }
}
add_action('init', 'add_regular_user_role');

// ثبت صفحه پنل کاربری
function create_user_dashboard_page()
{
    $page_exists = get_page_by_path('user-dashboard');
    if (!$page_exists) {
        $page_id = wp_insert_post(array(
            'post_title' => 'پنل کاربری',
            'post_name' => 'user-dashboard',
            'post_content' => '[user_dashboard]',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
    }
}
add_action('init', 'create_user_dashboard_page');

// شورت کد برای پنل کاربری
function user_dashboard_shortcode()
{
    if (is_user_logged_in()) {
        ob_start();
        // محتوای پنل کاربری که بعداً اضافه خواهیم کرد
        include get_template_directory() . '/user-dashboard.php';
        return ob_get_clean();
    } else {
        return '<p>لطفاً برای مشاهده پنل کاربری وارد حساب خود شوید.</p>';
    }
}
add_shortcode('user_dashboard', 'user_dashboard_shortcode');

// Ajax برای ثبت‌نام کاربر
add_action('wp_ajax_nopriv_register_user', 'register_user_handler');
function register_user_handler()
{
    // بررسی nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'user_auth_nonce')) {
        wp_send_json_error('خطای امنیتی. لطفاً صفحه را رفرش کنید.');
    }

    // دریافت و اعتبارسنجی داده‌ها
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // بررسی وجود فیلدهای ضروری
    if (empty($username) || empty($email) || empty($password)) {
        wp_send_json_error('لطفاً تمام فیلدهای ضروری را پر کنید.');
    }

    // بررسی مطابقت رمز عبور
    if ($password !== $confirm_password) {
        wp_send_json_error('رمزهای عبور وارد شده مطابقت ندارند.');
    }

    // بررسی وجود کاربر
    if (username_exists($username)) {
        wp_send_json_error('نام کاربری قبلاً انتخاب شده است.');
    }

    // بررسی وجود ایمیل
    if (email_exists($email)) {
        wp_send_json_error('ایمیل وارد شده قبلاً استفاده شده است.');
    }

    // ایجاد کاربر جدید
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error('خطا در ایجاد حساب کاربری: ' . $user_id->get_error_message());
    }

    // اختصاص نقش به کاربر
    $user = new WP_User($user_id);
    $user->set_role('regular_user');

    // ورود خودکار کاربر بعد از ثبت‌نام
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success('حساب کاربری با موفقیت ایجاد شد. در حال انتقال...');
}

// Ajax برای ورود کاربر
add_action('wp_ajax_nopriv_login_user', 'login_user_handler');
function login_user_handler()
{
    // بررسی nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'user_auth_nonce')) {
        wp_send_json_error('خطای امنیتی. لطفاً صفحه را رفرش کنید.');
    }

    // دریافت داده‌ها
    $credentials = array();
    $credentials['user_login'] = sanitize_user($_POST['username']);
    $credentials['user_password'] = $_POST['password'];
    $credentials['remember'] = isset($_POST['remember']) ? true : false;

    // بررسی وجود فیلدهای ضروری
    if (empty($credentials['user_login']) || empty($credentials['user_password'])) {
        wp_send_json_error('لطفاً نام کاربری و رمز عبور را وارد کنید.');
    }

    // تلاش برای ورود
    $user = wp_signon($credentials, false);

    if (is_wp_error($user)) {
        wp_send_json_error('نام کاربری یا رمز عبور اشتباه است.');
    }

    wp_send_json_success('ورود موفقیت‌آمیز. در حال انتقال...');
}

// Ajax برای دریافت اطلاعات کاربر
add_action('wp_ajax_get_user_data', 'get_user_data_handler');
function get_user_data_handler()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('لطفاً وارد حساب خود شوید.');
    }

    $user_id = get_current_user_id();
    $user_info = get_userdata($user_id);

    $user_data = array(
        'id' => $user_id,
        'username' => $user_info->user_login,
        'email' => $user_info->user_email,
        'display_name' => $user_info->display_name,
        'registered' => date('Y/m/d', strtotime($user_info->user_registered))
    );

    wp_send_json_success($user_data);
}

// Ajax برای خروج کاربر
add_action('wp_ajax_user_logout', 'user_logout_handler');
function user_logout_handler()
{
    wp_logout();
    wp_send_json_success('خروج موفقیت‌آمیز بود.');
}

// اضافه کردن nonce به سایت
function add_user_auth_nonce()
{
    wp_nonce_field('user_auth_nonce', 'user_auth_nonce_field');
}
add_action('wp_footer', 'add_user_auth_nonce');

// localize script برای Ajax
function user_auth_localize_script()
{
    // اول مطمئن شوید اسکریپت ثبت شده است
    wp_enqueue_script('hodkode-script');

    // سپس آن را localize کنید
    wp_localize_script('hodkode-script', 'user_auth_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('user_auth_nonce'),
        'is_logged_in' => is_user_logged_in()
    ));
}
add_action('wp_enqueue_scripts', 'user_auth_localize_script', 20);


?>
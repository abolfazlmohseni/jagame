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

// بررسی نقش کاربر - اگر مالک گیم‌نت باشد به پنل مدیریت هدایت شود
if (in_array('game_net_owner', $current_user->roles)) {
    $panel_page = get_page_by_path('overview');
    $redirect_url = $panel_page ? get_permalink($panel_page->ID) : home_url();
    wp_redirect($redirect_url);
    exit;
}

get_header();
?>

<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">پنل کاربری گیمر</h1>
            <p class="text-gray-600">خوش آمدید <?php echo $current_user->display_name; ?></p>
        </div>
        
        <div class="bg-blue-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">اطلاعات حساب کاربری</h2>
            <div class="space-y-2">
                <p><span class="font-medium">نام کاربری:</span> <?php echo $current_user->user_login; ?></p>
                <p><span class="font-medium">ایمیل:</span> <?php echo $current_user->user_email; ?></p>
                <p><span class="font-medium">تاریخ ثبت‌نام:</span> <?php echo date('Y/m/d', strtotime($current_user->user_registered)); ?></p>
            </div>
        </div>
        
        <div class="flex justify-center">
            <button id="logout-btn" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                خروج از حساب کاربری
            </button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // مدیریت خروج کاربر
    $('#logout-btn').on('click', function() {
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'user_logout',
                security: '<?php echo wp_create_nonce("user_auth_nonce"); ?>'
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?php echo home_url(); ?>';
                } else {
                    alert('خطا در خروج از حساب');
                }
            },
            error: function() {
                alert('خطا در ارتباط با سرور');
            }
        });
    });
});
</script>

<?php
get_footer();
?>
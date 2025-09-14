<?php
/*
Template Name: reservation
*/
include_once "PanelHeader.php";

// دریافت اطلاعات کاربر و گیم‌نت
$user_id = get_current_user_id();
$game_net_id = get_user_meta($user_id, '_game_net_id', true);

// ایجاد nonce برای این صفحه
$reservation_nonce = wp_create_nonce('reservation_management_nonce');
?>

<div class="w-full bg-white rounded-xl drop-shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">رزروهای آینده</h2>
        <button id="refreshReservations" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            بروزرسانی
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-right py-3 px-4 font-semibold text-gray-500 hidden md:table-cell">نام مشتری</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-500">دستگاه</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-500">زمان شروع</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-500">مدت</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-500">وضعیت</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-500">عملیات</th>
                </tr>
            </thead>
            <tbody id="reservationsTable">
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-500">
                        در حال بارگذاری رزروها...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    var reservation_ajax_object = {
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
        reservation_nonce: '<?php echo $reservation_nonce; ?>',
        game_net_id: '<?php echo $game_net_id; ?>'
    };
</script>
<script>
    <?php
    include_once get_template_directory() . '/js/reservation.js';
    ?>
</script>
</body>

</html>
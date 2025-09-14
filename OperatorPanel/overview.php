<?php
/*
Template Name: Overview
*/
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}
include_once "PanelHeader.php"
?>

<div class="w-full lg:w-4/5 p-4">
    <!-- بخش کارت های اطلاعات -->
    <div>
        <p class="font-bold text-2xl">نمای کلی</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4">
        <div class="bg-white p-5 rounded-lg w-full shadow-lg">
            <p class="text-sm">تعداد کل دستگاه‌ها</p>
            <p id="totalDevices" class="text-2xl font-bold mt-2 text-text   -dark">0</p>
        </div>
        <div class="bg-white p-5 rounded-lg w-full shadow-lg">
            <p class="text-sm text-gray-600">دستگاه‌های فعال</p>
            <p id="availableDevices" class="text-2xl font-bold mt-2 text-green-600">0</p>
        </div>
        <div class="bg-white p-5 rounded-lg w-full shadow-lg">
            <p class="text-sm text-gray-600">دستگاه‌های در تعمیر</p>
            <p id="maintenanceDevices" class="text-2xl font-bold mt-2 text-accent">0</p>
        </div>
        <div class="bg-white p-5 rounded-lg w-full shadow-lg">
            <p class="text-sm text-gray-600">دستگاه‌های رزرو شده</p>
            <p id="reservedDevices" class="text-2xl font-bold mt-2 text-secondary">0</p>
        </div>
    </div>

    <!-- جدول دستگاه‌ها -->
    <div class="w-full bg-white rounded-xl shadow-xl p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">مدیریت دستگاه‌ها</h2>
        </div>

        <!-- جدول دستگاه‌ها -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="hidden md:table-header-group">
                    <tr class="border-b border-gray-200">
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">نام دستگاه</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">نوع</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700 hidden lg:table-cell">مشخصات</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">قیمت/ساعت</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">وضعیت</th>
                    </tr>
                </thead>
                <tbody id="devicesTable">




                    <!-- حالت لودینگ -->
                    <tr class="border-b border-gray-100 hover:bg-gray-50 text-center">
                        <td colspan="5" class="py-8 text-gray-500">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                            در حال بارگذاری دستگاه‌ها...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-6 flex justify-center items-center space-x-2 space-x-reverse">

        </div>
    </div>

    <!-- جدول رزرو های آینده
    <div class="mt-5 bg-white rounded-xl drop-shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">رزروهای آینده</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-right py-3 px-4 font-semibold text-gray-500">نام مشتری</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-500">دستگاه</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-500">زمان شروع</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-500">مدت</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-500">وضعیت</th>
                    </tr>
                </thead>
                <tbody id="reservationsTable">
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">علی احمدی</td>
                        <td class="py-3 px-4">PC-01</td>
                        <td class="py-3 px-4">۱۴:۳۰</td>
                        <td class="py-3 px-4">۲ ساعت</td>
                        <td class="py-3 px-4">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">در انتظار</span>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">محمد رضایی</td>
                        <td class="py-3 px-4">PC-05</td>
                        <td class="py-3 px-4">۱۶:۰۰</td>
                        <td class="py-3 px-4">۳ ساعت</td>
                        <td class="py-3 px-4">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">تایید شده</span>
                        </td>

                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">سارا محمدی</td>
                        <td class="py-3 px-4">PC-03</td>
                        <td class="py-3 px-4">۱۸:۳۰</td>
                        <td class="py-3 px-4">۱.۵ ساعت</td>
                        <td class="py-3 px-4">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">شروع شده</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> -->

    <!-- تکرار بشه توی هر صفحه -->
</div>
<script>
    var ajax_object = {
        ajax_url: '<?php echo admin_url("admin-ajax.php"); ?>'
    };
</script>

</body>

</html>
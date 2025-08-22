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

<div class="w-4/5 p-4">
    <!-- بخش کارت های اطلاعات -->
    <div>
        <p class="font-bold text-2xl">نمای کلی</p>
    </div>
    <div class="flex gap-5 mt-4">
        <div class="bg-secondary p-5 rounded-lg w-1/4 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <p class="">تعداد کل دستگاه ها</p>
            <p class="text-2xl font-bold mt-2 text-white">40</p>
        </div>
        <div class="bg-secondary p-5 rounded-lg w-1/4 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <p class="">دستگاه های آزاد</p>
            <p class="text-2xl font-bold mt-2 text-white">23</p>
        </div>
        <div class="bg-secondary p-5 rounded-lg w-1/4 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <p class="">دستگاه های مشغول</p>
            <p class="text-2xl font-bold mt-2 text-white">14</p>
        </div>
        <div class="bg-secondary p-5 rounded-lg w-1/4 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
            <p class="">میانگین هزینه ها</p>
            <p class="text-2xl font-bold mt-2 text-white"><?= number_format(20499) ?></p>
        </div>
    </div>

    <!-- جدول رزرو های آینده -->
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
    </div>
</div>



<!-- تکرار بشه توی هر صفحه -->
</div>
</body>

</html>
<?php
/*
Template Name: reservation
*/
include_once "PanelHeader.php";

// دریافت اطلاعات کاربر و گیم‌نت
$user_id = get_current_user_id();
$game_net_id = get_user_meta($user_id, '_game_net_id', true);
?>

<div class="w-full bg-white rounded-xl drop-shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">رزروهای آینده</h2>
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
                </tr>
            </thead>
            <tbody id="reservationsTable">
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 hidden md:table-cell">علی احمدی</td>
                    <td class="py-3 px-4">PC-01</td>
                    <td class="py-3 px-4">۱۴:۳۰</td>
                    <td class="py-3 px-4">۲ ساعت</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center lg:px-2.5 lg:py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <p class="hidden lg:table-cell">در انتظار</p>
                            <div class="lg:hidden w-6 h-6"></div>
                        </span>
                    </td>
                </tr>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 hidden md:table-cell">محمد رضایی</td>
                    <td class="py-3 px-4">PC-05</td>
                    <td class="py-3 px-4">۱۶:۰۰</td>
                    <td class="py-3 px-4">۳ ساعت</td>
                    <td class="py-3 px-4">
                        <span class="bg-green-100 text-green-800 inline-flex items-center lg:px-2.5 lg:py-0.5 rounded-full text-xs font-medium ${statusClass}">
                            <p class="hidden lg:table-cell">تایید شده</p>
                            <div class="lg:hidden w-6 h-6"></div>
                        </span>
                    </td>

                </tr>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 hidden md:table-cell">سارا محمدی</td>
                    <td class="py-3 px-4">PC-03</td>
                    <td class="py-3 px-4">۱۸:۳۰</td>
                    <td class="py-3 px-4">۱.۵ ساعت</td>
                    <td class="py-3 px-4">
                        <span class="bg-blue-100 text-blue-800 inline-flex items-center lg:px-2.5 lg:py-0.5 rounded-full text-xs font-medium ${statusClass}">
                            <p class="hidden lg:table-cell">شروع شده</p>
                            <div class="lg:hidden w-6 h-6"></div>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>

</html>
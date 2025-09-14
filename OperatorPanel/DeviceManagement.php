<?php
/*
Template Name: Device Management
*/
include_once "PanelHeader.php";

// دریافت اطلاعات کاربر و گیم‌نت
$user_id = get_current_user_id();
$game_net_id = get_user_meta($user_id, '_game_net_id', true);

// اگر کاربر لاگین نکرده یا گیم‌نت مرتبط ندارد، به صفحه لاگین هدایت شود
if (!$user_id || !$game_net_id) {
    wp_redirect(home_url('/login'));
    exit;
}

// دریافت اطلاعات گیم‌نت
$game_net_name = get_the_title($game_net_id);
?>

<!-- بخش مدیریت دستگاه‌ها -->
<div class="w-full bg-white rounded-xl shadow-md p-6 mb-8">
    <div class="flex flex-col gap-3 md:flex-row md:items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">مدیریت دستگاه‌ها</h2>
        <button id="addNewDeviceBtn" class="w-fit bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
            + دستگاه جدید
        </button>
    </div>

    <!-- جدول دستگاه‌ها -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 hidden md:table-row">
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">نام دستگاه</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">نوع</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 hidden lg:table-cell">مشخصات</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">قیمت/ساعت</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">وضعیت</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">عملیات</th>
                </tr>
            </thead>
            <tbody id="devicesTable">
                <!-- محتوای داینامیک توسط JavaScript پر خواهد شد -->
                <tr class="border-b border-gray-100 hover:bg-gray-50 text-center">
                    <td colspan="6" class="py-8 text-gray-500">
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

<!-- مودال اضافه/ویرایش دستگاه -->
<div id="deviceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-4 md:p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h3 id="modalTitle" class="text-lg md:text-xl font-bold text-gray-800">اضافه کردن دستگاه جدید</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="deviceModalForm" class="space-y-3 md:space-y-4">
            <input type="hidden" id="editingDeviceId" value="">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نام دستگاه</label>
                <input type="text" id="modalDeviceName" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="مثال: PC-1" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نوع دستگاه</label>
                <select id="modalDeviceType" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">انتخاب کنید</option>
                    <option value="pc">PC</option>
                    <option value="console">کنسول</option>
                    <option value="vr">VR</option>
                    <option value="other">سایر</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">بازی‌ها</label>
                <div id="modalDeviceGames" class="grid grid-cols-1 gap-1 md:gap-2 max-h-40 overflow-y-auto p-2 border border-gray-300 rounded-lg"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">مشخصات</label>
                <textarea id="modalDeviceSpecs" rows="2" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="CPU، RAM، کارت گرافیک و..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">قیمت ساعتی (تومان)</label>
                <input type="number" id="modalDevicePrice" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="۱۵۰۰۰" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">وضعیت</label>
                <select id="modalDeviceStatus" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available">قابل استفاده</option>
                    <option value="maintenance">در حال تعمیر</option>
                    <option value="reserved">رزرو شده</option>
                    <option value="inactive">غیرفعال</option>
                </select>
            </div>

            <div class="flex gap-2 md:gap-4 pt-3 md:pt-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-3 md:px-4 rounded-lg transition-colors font-medium text-sm md:text-base">
                    ذخیره
                </button>
                <button type="button" id="cancelModal" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 md:px-4 rounded-lg transition-colors font-medium text-sm md:text-base">
                    انصراف
                </button>
            </div>
        </form>
    </div>
</div><!-- مودال اضافه/ویرایش دستگاه -->
<div id="deviceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-4 md:p-6 w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4 md:mb-6">
            <h3 id="modalTitle" class="text-lg md:text-xl font-bold text-gray-800">اضافه کردن دستگاه جدید</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="deviceModalForm" class="space-y-3 md:space-y-4">
            <input type="hidden" id="editingDeviceId" value="">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نام دستگاه</label>
                <input type="text" id="modalDeviceName" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="مثال: PC-1" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">نوع دستگاه</label>
                <select id="modalDeviceType" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">انتخاب کنید</option>
                    <option value="pc">PC</option>
                    <option value="xbox">XBOX</option>
                    <option value="ps4">PS4</option>
                    <option value="ps5">PS5</option>
                    <option value="vr">VR</option>
                    <option value="other">سایر</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">بازی‌ها</label>
                <div id="modalDeviceGames" class="grid grid-cols-1 gap-1 md:gap-2 max-h-40 overflow-y-auto p-2 border border-gray-300 rounded-lg"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">مشخصات</label>
                <textarea id="modalDeviceSpecs" rows="2" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="CPU، RAM، کارت گرافیک و..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">قیمت ساعتی (تومان)</label>
                <input type="number" id="modalDevicePrice" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="۱۵۰۰۰" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 md:mb-2">وضعیت</label>
                <select id="modalDeviceStatus" class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available">قابل استفاده</option>
                    <option value="maintenance">در حال تعمیر</option>
                    <option value="reserved">رزرو شده</option>
                    <option value="inactive">غیرفعال</option>
                </select>
            </div>

            <div class="flex gap-2 md:gap-4 pt-3 md:pt-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-3 md:px-4 rounded-lg transition-colors font-medium text-sm md:text-base">
                    ذخیره
                </button>
                <button type="button" id="cancelModal" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 md:px-4 rounded-lg transition-colors font-medium text-sm md:text-base">
                    انصراف
                </button>
            </div>
        </form>
    </div>
</div>

<!-- مودال تأیید حذف -->
<div id="confirmModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
        <p class="text-lg text-center">آیا از حذف این دستگاه اطمینان دارید؟</p>
        <div class="flex gap-2 mt-4">
            <button id="cancelDelete" class="block rounded-md px-4 py-2 text-white w-1/2 bg-gray-500">لغو</button>
            <button id="confirmDelete" class="block rounded-md px-4 py-2 text-white w-1/2 bg-red-600">حذف</button>
        </div>
    </div>
</div>
<!-- مودال اعلان دستگاه ها -->

<div id="alertModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
        <p class="textalert text-lg text-center"></p>
        <div class="flex gap-2 mt-4">
            <button class="closealert w-full text-center text-white bg-green-600 hover:bg-green-700 transition-colors rounded-md py-2">تایید</button>
        </div>
    </div>
</div>

</body>

</html>
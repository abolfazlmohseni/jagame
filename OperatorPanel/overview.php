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
<script>
    jQuery(document).ready(function($) {
        // متغیرهای global
        let currentPage = 1;
        const perPage = 10;
        const nonce = '<?php echo wp_create_nonce("device_management_nonce"); ?>';

        // بارگذاری دستگاه‌ها و آمار
        function loadDevicesAndStats(page = 1) {
            currentPage = page;

            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_devices',
                    security: nonce,
                    page: page,
                    per_page: perPage
                },
                beforeSend: function() {
                    $('#devicesTable').html(`
                    <tr class="border-b border-gray-100 hover:bg-gray-50 text-center">
                        <td colspan="6" class="py-8 text-gray-500">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                            در حال بارگذاری دستگاه‌ها...
                        </td>
                    </tr>
                `);

                    // نمایش وضعیت loading برای آمار
                    $('#totalDevices, #availableDevices, #maintenanceDevices, #reservedDevices').html(`
                    <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-current"></div>
                `);
                },
                success: function(response) {
                    if (response.success) {
                        renderDevicesTable(response.data.devices);
                        renderPagination(response.data.pagination);
                        updateStats(response.data.devices, response.data.pagination.total_items);
                    } else {
                        $('#devicesTable').html(`
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td colspan="6" class="py-4 px-4 text-center text-red-500">
                                خطا در بارگذاری دستگاه‌ها: ${response.data}
                            </td>
                        </tr>
                    `);

                        // نمایش خطا در آمار
                        $('#totalDevices, #availableDevices, #maintenanceDevices, #reservedDevices').text('خطا');
                    }
                },
                error: function() {
                    $('#devicesTable').html(`
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td colspan="6" class="py-4 px-4 text-center text-red-500">
                            خطا در ارتباط با سرور
                        </td>
                    </tr>
                `);

                    // نمایش خطا در آمار
                    $('#totalDevices, #availableDevices, #maintenanceDevices, #reservedDevices').text('خطا');
                }
            });
        }

        // نمایش دستگاه‌ها در جدول
        function renderDevicesTable(devices) {
            if (devices.length === 0) {
                $('#devicesTable').html(`
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                        هیچ دستگاهی یافت نشد. اولین دستگاه را اضافه کنید.
                    </td>
                </tr>
            `);
                return;
            }

            let tableContent = '';
            devices.forEach(device => {
                const statusClass = getStatusClass(device.status);
                const statusText = getStatusText(device.status);
                const deviceTypeText = getDeviceTypeText(device.type);

                tableContent += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 device-row">
                    <td class="py-3 px-4 font-medium">${device.name}</td>
                    <td class="py-3 px-4">${deviceTypeText}</td>
                    <td class="py-3 px-4 text-sm hidden lg:table-cell">${device.specs}</td>
                    <td class="py-3 px-4">${parseInt(device.price).toLocaleString('fa-IR')} تومان</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center lg:px-2.5 lg:py-0.5 rounded-full text-xs font-medium ${statusClass}">
                          <p class="hidden lg:table-cell">${statusText}</p>
                          <div class="lg:hidden w-6 h-6"></div>
                        </span>
                    </td>
                  
                </tr>
            `;
            });

            $('#devicesTable').html(tableContent);
        }

        // به روزرسانی آمار دستگاه‌ها
        function updateStats(devices, totalItems) {
            // محاسبه آمار بر اساس وضعیت دستگاه‌ها
            const availableDevices = devices.filter(d => d.status === 'available').length;
            const maintenanceDevices = devices.filter(d => d.status === 'maintenance').length;
            const reservedDevices = devices.filter(d => d.status === 'reserved').length;
            const inactiveDevices = devices.filter(d => d.status === 'inactive').length;

            // به روزرسانی مقادیر در کارت‌ها
            $('#totalDevices').text(totalItems);
            $('#availableDevices').text(availableDevices);
            $('#maintenanceDevices').text(maintenanceDevices);
            $('#reservedDevices').text(reservedDevices);

            // همچنین می‌توانید آمار غیرفعال را هم نمایش دهید اگر نیاز دارید
            // $('#inactiveDevices').text(inactiveDevices);
        }

        // نمایش pagination
        function renderPagination(pagination) {
            const paginationContainer = $('#pagination');

            if (pagination.total_pages <= 1) {
                paginationContainer.html('');
                return;
            }

            let paginationHtml = '';

            // دکمه قبلی
            if (pagination.current_page > 1) {
                paginationHtml += `
                <button class="pagination-btn bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 px-3 py-2 rounded-md" data-page="${pagination.current_page - 1}">
                    <i class="fas fa-chevron-right ml-1"></i>
                    قبلی
                </button>
            `;
            }

            // صفحات
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.total_pages, startPage + 4);

            for (let i = startPage; i <= endPage; i++) {
                if (i === pagination.current_page) {
                    paginationHtml += `
                    <button class="pagination-btn bg-blue-500 border border-blue-500 text-white px-3 py-2 rounded-md" data-page="${i}">
                        ${i}
                    </button>
                `;
                } else {
                    paginationHtml += `
                    <button class="pagination-btn bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 px-3 py-2 rounded-md" data-page="${i}">
                        ${i}
                    </button>
                `;
                }
            }

            // دکمه بعدی
            if (pagination.current_page < pagination.total_pages) {
                paginationHtml += `
                <button class="pagination-btn bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 px-3 py-2 rounded-md" data-page="${pagination.current_page + 1}">
                    بعدی
                    <i class="fas fa-chevron-left mr-1"></i>
                </button>
            `;
            }

            paginationContainer.html(paginationHtml);
        }

        // کلاس وضعیت بر اساس مقدار
        function getStatusClass(status) {
            switch (status) {
                case 'available':
                    return 'bg-green-100 text-green-800';
                case 'maintenance':
                    return 'bg-yellow-100 text-yellow-800';
                case 'reserved':
                    return 'bg-blue-100 text-blue-800';
                case 'inactive':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // متن وضعیت بر اساس مقدار
        function getStatusText(status) {
            switch (status) {
                case 'available':
                    return 'قابل استفاده';
                case 'maintenance':
                    return 'در حال تعمیر';
                case 'reserved':
                    return 'رزرو شده';
                case 'inactive':
                    return 'غیرفعال';
                default:
                    return status;
            }
        }

        // متن نوع دستگاه بر اساس مقدار
        function getDeviceTypeText(type) {
            switch (type) {
                case 'pc':
                    return 'کامپیوتر';
                case 'console':
                    return 'کنسول';
                case 'vr':
                    return 'VR';
                case 'other':
                    return 'سایر';
                default:
                    return type;
            }
        }

        // کلیک روی pagination
        $(document).on('click', '.pagination-btn', function() {
            const page = $(this).data('page');
            loadDevicesAndStats(page);
        });

        // بارگذاری اولیه دستگاه‌ها و آمار
        loadDevicesAndStats();
    });
</script>
</body>

</html>
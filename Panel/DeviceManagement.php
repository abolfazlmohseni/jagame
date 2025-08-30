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
<div id="deviceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">اضافه کردن دستگاه جدید</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="deviceModalForm" class="space-y-4">
            <input type="hidden" id="editingDeviceId" value="">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نام دستگاه</label>
                <input type="text" id="modalDeviceName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="مثال: PC-1" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع دستگاه</label>
                <select id="modalDeviceType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">مشخصات</label>
                <textarea id="modalDeviceSpecs" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="CPU، RAM، کارت گرافیک و..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">قیمت ساعتی (تومان)</label>
                <input type="number" id="modalDevicePrice" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="۱۵۰۰۰" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                <select id="modalDeviceStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available">قابل استفاده</option>
                    <option value="maintenance">در حال تعمیر</option>
                    <option value="reserved">رزرو شده</option>
                    <option value="inactive">غیرفعال</option>
                </select>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition-colors font-medium">
                    ذخیره
                </button>
                <button type="button" id="cancelModal" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors font-medium">
                    انصراف
                </button>
            </div>
        </form>
    </div>
</div>

<!-- مودال تأیید حذف -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
        <p class="text-lg text-center">آیا از حذف این دستگاه اطمینان دارید؟</p>
        <div class="flex gap-2 mt-4">
            <button id="cancelDelete" class="block rounded-md px-4 py-2 text-white w-1/2 bg-gray-500">لغو</button>
            <button id="confirmDelete" class="block rounded-md px-4 py-2 text-white w-1/2 bg-red-600">حذف</button>
        </div>
    </div>
</div>
<!-- مودال اعلان دستگاه ها -->

<div id="alertModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
        <p class="textalert text-lg text-center"></p>
        <div class="flex gap-2 mt-4">
            <button class="closealert w-full text-center text-white bg-green-600 hover:bg-green-700 transition-colors rounded-md py-2">تایید</button>
        </div>
    </div>
</div>
<script>
    // تعریف متغیر ajax_object برای دسترسی به آدرس AJAX
    var ajax_object = {
        ajax_url: '<?php echo admin_url("admin-ajax.php"); ?>'
    };

    jQuery(document).ready(function($) {
        // متغیر های کلی
        let currentPage = 1;
        let perPage = 10;
        let currentDeviceId = null;
        const nonce = '<?php echo wp_create_nonce("device_management_nonce"); ?>';

        // مدیریت مودال
        const deviceModal = $('#deviceModal');
        const confirmModal = $('#confirmModal');
        const modalTitle = $('#modalTitle');
        const deviceModalForm = $('#deviceModalForm');

        // باز کردن مودال برای اضافه کردن دستگاه جدید
        $('#addNewDeviceBtn').on('click', function() {
            modalTitle.text('اضافه کردن دستگاه جدید');
            $('#editingDeviceId').val('');
            deviceModalForm[0].reset();
            deviceModal.removeClass('hidden');
        });

        // بستن مودال
        function closeDeviceModal() {
            deviceModal.addClass('hidden');
            deviceModalForm[0].reset();
        }

        $('#closeModal, #cancelModal').on('click', closeDeviceModal);

        // بستن مودال با کلیک روی پس‌زمینه
        deviceModal.on('click', function(e) {
            if (e.target === deviceModal[0]) {
                closeDeviceModal();
            }
        });

        // مدیریت مودال تأیید حذف
        $('#cancelDelete').on('click', function() {
            confirmModal.addClass('hidden');
        });

        // بارگذاری دستگاه‌ها
        function loadDevices(page = 1) {
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
                },
                success: function(response) {
                    if (response.success) {
                        renderDevicesTable(response.data.devices);
                        renderPagination(response.data.pagination);
                    } else {
                        $('#devicesTable').html(`
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td colspan="6" class="py-4 px-4 text-center text-red-500">
                                خطا در بارگذاری دستگاه‌ها: ${response.data}
                            </td>
                        </tr>
                    `);
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

                tableContent += `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">${device.name}</td>
                    <td class="py-3 px-4">${getDeviceTypeText(device.type)}</td>
                    <td class="py-3 px-4 text-sm hidden lg:table-cell">${device.specs}</td>
                    <td class="py-3 px-4">${parseInt(device.price).toLocaleString('fa-IR')} <span class="hidden md:inline">تومان</span></td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center lg:px-2.5 lg:py-0.5 rounded-full text-xs font-medium ${statusClass}">
                          <p class="hidden lg:table-cell">${statusText}</p>
                          <div class="lg:hidden w-6 h-6"></div>
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <button class="edit-device text-blue-600 hover:text-blue-800 ml-2" data-id="${device.id}">ویرایش</button>
                        <button class="delete-device text-red-600 hover:text-red-800" data-id="${device.id}">حذف</button>
                    </td>
                </tr>
            `;
            });

            $('#devicesTable').html(tableContent);
        }

        // نمایش pagination
        function renderPagination(pagination) {
            if (pagination.total_pages <= 1) {
                $('#pagination').html('');
                return;
            }

            let paginationHtml = '';

            // دکمه قبلی
            if (pagination.current_page > 1) {
                paginationHtml += `
                <button class="pagination-btn bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 px-3 py-2 rounded-md" data-page="${pagination.current_page - 1}">
                    قبلی
                </button>
            `;
            }

            // صفحات
            for (let i = 1; i <= pagination.total_pages; i++) {
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
                </button>
            `;
            }

            $('#pagination').html(paginationHtml);
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
                    return 'PC';
                case 'xbox':
                    return 'XBOX';
                case 'vr':
                    return 'VR';
                case 'ps4':
                    return 'PS4';
                case 'ps5':
                    return 'PS5';
                case 'other':
                    return 'سایر';
                default:
                    return type;
            }
        }

        // کلیک روی pagination
        $(document).on('click', '.pagination-btn', function() {
            const page = $(this).data('page');
            loadDevices(page);
        });

        // ویرایش دستگاه
        $(document).on('click', '.edit-device', function() {
            const deviceId = $(this).data('id');

            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_device',
                    security: nonce,
                    device_id: deviceId
                },
                success: function(response) {
                    if (response.success) {
                        const device = response.data;
                        modalTitle.text('ویرایش دستگاه');
                        $('#editingDeviceId').val(device.id);
                        $('#modalDeviceName').val(device.name);
                        $('#modalDeviceType').val(device.type);
                        $('#modalDeviceSpecs').val(device.specs);
                        $('#modalDevicePrice').val(device.price);
                        $('#modalDeviceStatus').val(device.status);
                        deviceModal.removeClass('hidden');
                    }
                }
            });
        });
        const alertModal = document.querySelector("#alertModal");
        const closealert = document.querySelector(".closealert");
        const textalert = document.querySelector(".textalert");

        closealert.addEventListener("click", () => {
            alertModal.classList.add("hidden")
        })

        // حذف دستگاه
        $(document).on('click', '.delete-device', function() {
            currentDeviceId = $(this).data('id');
            confirmModal.removeClass('hidden');
        });

        // تأیید حذف
        $('#confirmDelete').on('click', function() {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_device',
                    security: nonce,
                    device_id: currentDeviceId
                },
                success: function(response) {
                    if (response.success) {
                        alertModal.classList.remove('hidden')
                        textalert.textContent = 'دستگاه با موفقیت حذف شد'
                        loadDevices(currentPage);
                    }
                    confirmModal.addClass('hidden');
                }
            });
        });

        // ذخیره دستگاه (اضافه یا ویرایش)
        deviceModalForm.on('submit', function(e) {
            e.preventDefault();

            const editingDeviceId = $('#editingDeviceId').val();
            const deviceData = {
                name: $('#modalDeviceName').val(),
                type: $('#modalDeviceType').val(),
                specs: $('#modalDeviceSpecs').val(),
                price: $('#modalDevicePrice').val(),
                status: $('#modalDeviceStatus').val(),
                security: nonce
            };

            const action = editingDeviceId ? 'update_device' : 'add_device';
            if (editingDeviceId) {
                deviceData.device_id = editingDeviceId;
            }

            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    ...deviceData
                },
                success: function(response) {
                    if (response.success) {
                        closeDeviceModal();
                        textalert.textContent = editingDeviceId ? 'دستگاه با موفقیت ویرایش شد' : 'دستگاه با موفقیت اضافه شد'
                        alertModal.classList.remove("hidden");
                        loadDevices(currentPage);
                    } else {
                        alert('خطا: ' + response.data);
                    }
                },
                error: function() {
                    alert('خطا در ارتباط با سرور');
                }
            });

        });
        // بارگذاری اولیه دستگاه‌ها
        loadDevices();

    });
</script>
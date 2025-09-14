jQuery(document).ready(function ($) {
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
            beforeSend: function () {
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
            success: function (response) {
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
            error: function () {
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
    $(document).on('click', '.pagination-btn', function () {
        const page = $(this).data('page');
        loadDevicesAndStats(page);
    });

    // بارگذاری اولیه دستگاه‌ها و آمار
    loadDevicesAndStats();
});
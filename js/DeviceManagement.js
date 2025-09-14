// تعریف متغیر ajax_object برای دسترسی به آدرس AJAX
var ajax_object = {
    ajax_url: '<?php echo admin_url("admin-ajax.php"); ?>'
};
const deviceGamesMap = {
    pc: ["Counter-Strike: Global Offensive", "Valorant", "Dota 2", "League of Legends", "PUBG"],
    console: ["FIFA", "NBA 2K", "Call of Duty", "Gran Turismo", "God of War"],
    vr: ["Beat Saber", "Half-Life: Alyx", "Superhot VR", "The Walking Dead: Saints & Sinners"],
    other: ["تخته‌ای/محلی (Custom Game)", "سایر"]
};


function renderModalGames(type, selectedGames = []) {
    const container = document.getElementById('modalDeviceGames');
    const list = deviceGamesMap[type] || [];

    if (!list || list.length === 0) {
        container.innerHTML = '<p class="text-sm text-gray-500">هیچ بازی‌ای تعریف نشده است</p>';
        return;
    }

    // make sure selectedGames is an array
    if (!Array.isArray(selectedGames)) {
        selectedGames = selectedGames ? [selectedGames] : [];
    }

    let html = '';
    html += '<div class="space-y-2">';
    list.forEach((game, idx) => {
        const id = `modalGame_${type}_${idx}`;
        const checked = selectedGames.indexOf(game) !== -1 ? 'checked' : '';
        html += `
            <label for="${id}" class="flex items-center justify-between gap-3 p-2 border rounded-lg">
                <div class="flex items-center gap-3">
                    <input id="${id}" type="checkbox" class="game-checkbox" value="${game.replace(/"/g, '&quot;')}" ${checked}>
                    <span class="text-sm font-medium">${game}</span>
                </div>
                <span class="text-xs text-gray-500">انتخاب</span>
            </label>
        `;
    });
    html += '</div>';

    container.innerHTML = html;
}



jQuery(document).ready(function ($) {
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
    $('#addNewDeviceBtn').on('click', function () {
        modalTitle.text('اضافه کردن دستگاه جدید');
        $('#editingDeviceId').val('');
        deviceModalForm[0].reset();
        renderModalGames($('#modalDeviceType').val(), []);
        deviceModal.removeClass('hidden');
    });

    // بستن مودال
    function closeDeviceModal() {
        deviceModal.addClass('hidden');
        deviceModalForm[0].reset();
    }

    $('#closeModal, #cancelModal').on('click', closeDeviceModal);

    // بستن مودال با کلیک روی پس‌زمینه
    deviceModal.on('click', function (e) {
        if (e.target === deviceModal[0]) {
            closeDeviceModal();
        }
    });

    // مدیریت مودال تأیید حذف
    $('#cancelDelete').on('click', function () {
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
            beforeSend: function () {
                $('#devicesTable').html(`
                    <tr class="border-b border-gray-100 hover:bg-gray-50 text-center">
                        <td colspan="6" class="py-8 text-gray-500">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-2"></div>
                            در حال بارگذاری دستگاه‌ها...
                        </td>
                    </tr>
                `);
            },
            success: function (response) {
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
            error: function () {
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

    $(document).on('change', '#modalDeviceType', function () {
        renderModalGames(this.value, []);
    });

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
    $(document).on('click', '.pagination-btn', function () {
        const page = $(this).data('page');
        loadDevices(page);
    });

    // ویرایش دستگاه
    $(document).on('click', '.edit-device', function () {
        const deviceId = $(this).data('id');

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_device',
                security: nonce,
                device_id: deviceId
            },
            success: function (response) {
                if (response.success) {
                    const device = response.data;
                    modalTitle.text('ویرایش دستگاه');
                    $('#editingDeviceId').val(device.id);
                    $('#modalDeviceName').val(device.name);
                    $('#modalDeviceType').val(device.type);
                    $('#modalDeviceSpecs').val(device.specs);
                    $('#modalDevicePrice').val(device.price);
                    $('#modalDeviceStatus').val(device.status);
                    renderModalGames(device.type || $('#modalDeviceType').val(), device.games || []);

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
    $(document).on('click', '.delete-device', function () {
        currentDeviceId = $(this).data('id');
        confirmModal.removeClass('hidden');
    });

    // تأیید حذف
    $('#confirmDelete').on('click', function () {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_device',
                security: nonce,
                device_id: currentDeviceId
            },
            success: function (response) {
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
    // ذخیره دستگاه (اضافه یا ویرایش)
    deviceModalForm.on('submit', function (e) {
        e.preventDefault();

        const editingDeviceId = $('#editingDeviceId').val();
        console.log('Editing device ID:', editingDeviceId);

        // collect checked games from checkboxes
        const selectedGames = [];
        $('#modalDeviceGames .game-checkbox:checked').each(function () {
            selectedGames.push($(this).val());
        });
        console.log('Selected games:', selectedGames);

        const deviceData = {
            action: editingDeviceId ? 'update_device' : 'add_device',
            name: $('#modalDeviceName').val(),
            type: $('#modalDeviceType').val(),
            specs: $('#modalDeviceSpecs').val(),
            price: $('#modalDevicePrice').val(),
            status: $('#modalDeviceStatus').val(),
            device_games: JSON.stringify(selectedGames),
            security: nonce
        };

        if (editingDeviceId) {
            deviceData.device_id = editingDeviceId;
        }

        console.log('Sending data to server:', deviceData);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: deviceData,
            success: function (response) {
                console.log('Server response:', response);
                if (response.success) {
                    closeDeviceModal();
                    textalert.textContent = editingDeviceId ? 'دستگاه با موفقیت ویرایش شد' : 'دستگاه با موفقیت اضافه شد';
                    alertModal.classList.remove("hidden");
                    loadDevices(currentPage);
                } else {
                    alert('خطا: ' + (response.data || 'خطای نامشخص'));
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                console.error('Response text:', xhr.responseText);
                alert('خطا در ارتباط با سرور');
            }
        });
    });

    // بارگذاری اولیه دستگاه‌ها
    loadDevices();

});
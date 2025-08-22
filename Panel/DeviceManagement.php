<?php
/*
Template Name:Device Management
*/
include_once "PanelHeader.php"
?>



<!-- بخش مدیریت دستگاه‌ها -->
<div class="w-full bg-white rounded-xl shadow-md p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">مدیریت دستگاه‌ها</h2>
        <button id="addNewDeviceBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
            + دستگاه جدید
        </button>
    </div>

    <!-- جدول دستگاه‌ها -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">نام دستگاه</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">نوع</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">مشخصات</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">قیمت/ساعت</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">وضعیت</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700">عملیات</th>
                </tr>
            </thead>
            <tbody id="devicesTable">
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">PC-1</td>
                    <td class="py-3 px-4">PC</td>
                    <td class="py-3 px-4 text-sm">i7-12700K, RTX 3070, 32GB RAM</td>
                    <td class="py-3 px-4">۲۰,۰۰۰ تومان</td>
                    <td class="py-3 px-4">
                        <select class="status-select bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm border-none" data-device="PC-1">
                            <option value="free" selected>آزاد</option>
                            <option value="busy">اشغال</option>
                            <option value="maintenance">در تعمیر</option>
                            <option value="offline">خاموش</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <button class="edit-device text-blue-600 hover:text-blue-800 ml-2" data-device="PC-1">ویرایش</button>
                        <button class="delete-device text-red-600 hover:text-red-800" data-device="PC-1">حذف</button>
                    </td>
                </tr>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">PS5-1</td>
                    <td class="py-3 px-4">PS5</td>
                    <td class="py-3 px-4 text-sm">کنسول PS5، دسته DualSense، 4K Gaming</td>
                    <td class="py-3 px-4">۳۵,۰۰۰ تومان</td>
                    <td class="py-3 px-4">
                        <select class="status-select bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-sm border-none" data-device="PS5-1">
                            <option value="free">آزاد</option>
                            <option value="busy" selected>اشغال</option>
                            <option value="maintenance">در تعمیر</option>
                            <option value="offline">خاموش</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <button class="edit-device text-blue-600 hover:text-blue-800 ml-2" data-device="PS5-1">ویرایش</button>
                        <button class="delete-device text-red-600 hover:text-red-800" data-device="PS5-1">حذف</button>
                    </td>
                </tr>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">PS4-1</td>
                    <td class="py-3 px-4">PS4</td>
                    <td class="py-3 px-4 text-sm">کنسول PS4 Pro، دسته DualShock 4</td>
                    <td class="py-3 px-4">۱۲,۰۰۰ تومان</td>
                    <td class="py-3 px-4">
                        <select class="status-select bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm border-none" data-device="PS4-1">
                            <option value="free">آزاد</option>
                            <option value="busy">اشغال</option>
                            <option value="maintenance" selected>در تعمیر</option>
                            <option value="offline">خاموش</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <button class="edit-device text-blue-600 hover:text-blue-800 ml-2" data-device="PS4-1">ویرایش</button>
                        <button class="delete-device text-red-600 hover:text-red-800" data-device="PS4-1">حذف</button>
                    </td>
                </tr>
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">Xbox-1</td>
                    <td class="py-3 px-4">Xbox</td>
                    <td class="py-3 px-4 text-sm">Xbox Series X، دسته Wireless Controller</td>
                    <td class="py-3 px-4">۱۸,۰۰۰ تومان</td>
                    <td class="py-3 px-4">
                        <select class="status-select bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm border-none" data-device="Xbox-1">
                            <option value="free" selected>آزاد</option>
                            <option value="busy">اشغال</option>
                            <option value="maintenance">در تعمیر</option>
                            <option value="offline">خاموش</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <button class="edit-device text-blue-600 hover:text-blue-800 ml-2" data-device="Xbox-1">ویرایش</button>
                        <button class="delete-device text-red-600 hover:text-red-800" data-device="Xbox-1">حذف</button>
                    </td>
                </tr>
            </tbody>
        </table>
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
            <input type="hidden" id="editingDevice" value="">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع دستگاه</label>
                <select id="modalDeviceName" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <option value="">انتخاب کنید</option>
                    <option value="PS4">PS4</option>
                    <option value="PS5">PS5</option>
                    <option value="PC">PC</option>
                    <option value="Xbox">Xbox</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">شماره دستگاه</label>
                <input type="number" id="modalDeviceType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="مثال: 1" min="1" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مشخصات</label>
                <textarea id="modalDeviceSpecs" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="CPU، RAM، کارت گرافیک و..." required></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">قیمت ساعتی (تومان)</label>
                <input type="number" id="modalDevicePrice" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="۱۵۰۰۰" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                <select id="modalDeviceStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="free">آزاد</option>
                    <option value="busy">اشغال</option>
                    <option value="maintenance">در تعمیر</option>
                    <option value="offline">خاموش</option>
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

<div class="confirm fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
        <p class="text-lg text-center">آیا از پاک کردن این دستگاه اطمینان دارید؟</p>
        <div class="flex gap-2  mt-4">
            <button class="cansel block rounded-md px-4 py-2 text-white w-1/2 bg-gray-500">لغو</button>
            <button class="delete block rounded-md px-4 py-2 text-white w-1/2 bg-red-600">حذف</button>
        </div>
    </div>
</div>

<script>
    // مدیریت دستگاه‌ها
    const deviceModal = document.getElementById('deviceModal');
    const modalTitle = document.getElementById('modalTitle');
    const deviceModalForm = document.getElementById('deviceModalForm');
    const addNewDeviceBtn = document.getElementById('addNewDeviceBtn');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');

    // باز کردن مودال برای اضافه کردن دستگاه جدید
    addNewDeviceBtn.addEventListener('click', function() {
        modalTitle.textContent = 'اضافه کردن دستگاه جدید';
        document.getElementById('editingDevice').value = '';
        deviceModalForm.reset();
        deviceModal.classList.remove('hidden');
    });

    // بستن مودال
    function closeDeviceModal() {
        deviceModal.classList.add('hidden');
        deviceModalForm.reset();
    }

    closeModal.addEventListener('click', closeDeviceModal);
    cancelModal.addEventListener('click', closeDeviceModal);

    // بستن مودال با کلیک روی پس‌زمینه
    deviceModal.addEventListener('click', function(e) {
        if (e.target === deviceModal) {
            closeDeviceModal();
        }
    });

    // تابع به‌روزرسانی رنگ وضعیت
    function updateStatusColor(selectElement, status) {
        selectElement.className = 'status-select px-2 py-1 rounded-full text-sm border-none ';
        switch (status) {
            case 'free':
                selectElement.className += 'bg-green-100 text-green-800';
                break;
            case 'busy':
                selectElement.className += 'bg-orange-100 text-orange-800';
                break;
            case 'maintenance':
                selectElement.className += 'bg-red-100 text-red-800';
                break;
            case 'offline':
                selectElement.className += 'bg-gray-100 text-gray-800';
                break;
        }
    }

    // تغییر وضعیت دستگاه
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('status-select')) {
            const deviceName = e.target.dataset.device;
            const newStatus = e.target.value;
            updateStatusColor(e.target, newStatus);

            alert(`وضعیت ${deviceName} به "${e.target.options[e.target.selectedIndex].text}" تغییر یافت`);
        }
    });

    // ویرایش دستگاه
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-device')) {
            const deviceName = e.target.dataset.device;
            const row = e.target.closest('tr');
            const cells = row.querySelectorAll('td');

            modalTitle.textContent = 'ویرایش دستگاه';
            document.getElementById('editingDevice').value = deviceName;

            // تجزیه نام دستگاه (مثال: PC-1 -> PC و 1)
            const deviceParts = cells[0].textContent.split('-');
            document.getElementById('modalDeviceName').value = deviceParts[0];
            document.getElementById('modalDeviceType').value = deviceParts[1] || '';
            document.getElementById('modalDeviceSpecs').value = cells[2].textContent;
            document.getElementById('modalDevicePrice').value = cells[3].textContent.replace(/[^\d]/g, '');

            const statusSelect = row.querySelector('.status-select');
            document.getElementById('modalDeviceStatus').value = statusSelect.value;

            deviceModal.classList.remove('hidden');
        }
    });
    const canselBtn = document.querySelector('.cansel')
    const deleteBtn = document.querySelector('.delete')
    const confirmBox = document.querySelector('.confirm')

    // حذف دستگاه
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-device')) {
            let deviceName = e.target.dataset.device;
            confirmBox.classList.remove("hidden")
            deleteBtn.addEventListener("click", () => {
                const row = e.target.closest('tr');
                row.remove();
                confirmBox.classList.add("hidden")

            })
            canselBtn.addEventListener("click", () => {
                confirmBox.classList.add("hidden")
                const row = null

            })
        }
    });

    // ذخیره دستگاه (اضافه یا ویرایش)
    deviceModalForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const editingDevice = document.getElementById('editingDevice').value;
        const deviceType = document.getElementById('modalDeviceName').value;
        const deviceNumber = document.getElementById('modalDeviceType').value;
        const deviceSpecs = document.getElementById('modalDeviceSpecs').value;
        const devicePrice = document.getElementById('modalDevicePrice').value;
        const deviceStatus = document.getElementById('modalDeviceStatus').value;

        const deviceName = deviceType + '-' + deviceNumber;

        const statusText = {
            'free': 'آزاد',
            'busy': 'اشغال',
            'maintenance': 'در تعمیر',
            'offline': 'خاموش'
        };

        if (editingDevice) {
            // ویرایش دستگاه موجود
            const rows = document.querySelectorAll('#devicesTable tr');
            for (let row of rows) {
                const editBtn = row.querySelector('.edit-device');
                if (editBtn && editBtn.dataset.device === editingDevice) {
                    const cells = row.querySelectorAll('td');
                    cells[0].textContent = deviceName;
                    cells[1].textContent = deviceType;
                    cells[2].textContent = deviceSpecs;
                    cells[3].textContent = parseInt(devicePrice).toLocaleString('fa-IR') + ' تومان';

                    const statusSelect = row.querySelector('.status-select');
                    statusSelect.value = deviceStatus;
                    statusSelect.dataset.device = deviceName;
                    updateStatusColor(statusSelect, deviceStatus);

                    // به‌روزرسانی data-device در دکمه‌ها
                    editBtn.dataset.device = deviceName;
                    row.querySelector('.delete-device').dataset.device = deviceName;

                    break;
                }
            }
            alert(`${deviceName} با موفقیت ویرایش شد!`);
        } else {
            // اضافه کردن دستگاه جدید
            const devicesTable = document.getElementById('devicesTable');
            const newRow = document.createElement('tr');
            newRow.className = 'border-b border-gray-100 hover:bg-gray-50';

            let statusClass = '';
            switch (deviceStatus) {
                case 'free':
                    statusClass = 'bg-green-100 text-green-800';
                    break;
                case 'busy':
                    statusClass = 'bg-orange-100 text-orange-800';
                    break;
                case 'maintenance':
                    statusClass = 'bg-red-100 text-red-800';
                    break;
                case 'offline':
                    statusClass = 'bg-gray-100 text-gray-800';
                    break;
            }

            newRow.innerHTML = `
                    <td class="py-3 px-4 font-medium">${deviceName}</td>
                    <td class="py-3 px-4">${deviceType}</td>
                    <td class="py-3 px-4 text-sm">${deviceSpecs}</td>
                    <td class="py-3 px-4">${parseInt(devicePrice).toLocaleString('fa-IR')} تومان</td>
                    <td class="py-3 px-4">
                        <select class="status-select ${statusClass} px-2 py-1 rounded-full text-sm border-none" data-device="${deviceName}">
                            <option value="free" ${deviceStatus === 'free' ? 'selected' : ''}>آزاد</option>
                            <option value="busy" ${deviceStatus === 'busy' ? 'selected' : ''}>اشغال</option>
                            <option value="maintenance" ${deviceStatus === 'maintenance' ? 'selected' : ''}>در تعمیر</option>
                            <option value="offline" ${deviceStatus === 'offline' ? 'selected' : ''}>خاموش</option>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <button class="edit-device text-blue-600 hover:text-blue-800 ml-2" data-device="${deviceName}">ویرایش</button>
                        <button class="delete-device text-red-600 hover:text-red-800" data-device="${deviceName}">حذف</button>
                    </td>
                `;

            devicesTable.appendChild(newRow);
            // دستگاه با  موفقعیت اضافه شده
            closeDeviceModal()
        }



        // بستن مودال
        closeDeviceModal();
    });
</script>
<!-- تکرار بشه توی هر صفحه -->
</div>
</body>

</html>
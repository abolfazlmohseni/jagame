<?php
/*
Template Name: Information panel
*/
include_once "PanelHeader.php";

$user_info = get_current_user_game_net_info();

if (!$user_info) {
    echo '<div class="container mx-auto p-4">';
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">لطفاً ابتدا وارد شوید</div>';
    echo '</div>';
    return;
}

// ایجاد nonce برای امنیت
$update_nonce = wp_create_nonce('update_game_net_nonce');

// دریافت عکس‌های گالری
$gallery_images = array();
if (!empty($user_info['gallery_images'])) {
    $gallery_images = array_filter(explode(',', $user_info['gallery_images']));
}
// دریافت تصویر پروفایل
$profile_picture_id = get_post_meta(get_user_meta(get_current_user_id(), '_game_net_id', true), '_profile_picture_id', true);
$profile_picture_url = $profile_picture_id ? wp_get_attachment_image_url($profile_picture_id, 'medium') : '';

    ?>

    <div class="pb-[6rem] w-full mx-auto p-4 max-w-6xl overflow-auto h-screen">
        <!-- پیام موفقیت -->
        <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"></div>

        <!-- بخش اطلاعات گیم نت -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">اطلاعات گیم نت</h2>
                <button id="editInfoBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    ویرایش اطلاعات
                </button>
            </div>

            <!-- نمایش اطلاعات -->
            <div id="infoDisplay" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">نام گیم نت</h3>
                    <p class="text-gray-800" id="displayName"><?= esc_html($user_info['name']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">شماره موبایل</h3>
                    <p class="text-gray-800" id="displayPhone"><?= esc_html($user_info['phone']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">نوع جنسیت</h3>
                    <p class="text-gray-800" id="displayGender"><?= esc_html($user_info['gender']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">شرایط سنی</h3>
                    <p class="text-gray-800" id="displayAge"><?= esc_html($user_info['age']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">ساعت کاری</h3>
                    <p class="text-gray-800" id="displayHours"><?= esc_html($user_info['hours']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-700 mb-2">روز تعطیل</h3>
                    <p class="text-gray-800" id="displayHoliday"><?= esc_html($user_info['holiday']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg md:col-span-2 lg:col-span-3">
                    <h3 class="font-semibold text-gray-700 mb-2">آدرس</h3>
                    <p class="text-gray-800 text-sm" id="displayAddress"><?= esc_html($user_info['address']) ?></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg md:col-span-2 lg:col-span-3">
                    <h3 class="font-semibold text-gray-700 mb-2">بیوگرافی</h3>
                    <p class="text-gray-800 text-sm" id="displayBio"><?= esc_html($user_info['bio']) ?></p>
                </div>
            </div>

            <!-- فرم ویرایش -->
            <div id="editForm" class="hidden mt-6">
                <form id="gameNetInfoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام گیم نت *</label>
                        <input type="text" name="gamenet_name" id="gamenetName" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            value="<?= esc_attr($user_info['name']) ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع جنسیت *</label>
                        <select name="gender" id="genderType" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                            <option value="">انتخاب کنید</option>
                            <option value="مختلط" <?= $user_info['gender'] === 'مختلط' ? 'selected' : '' ?>>مختلط</option>
                            <option value="آقایان" <?= $user_info['gender'] === 'آقایان' ? 'selected' : '' ?>>آقایان</option>
                            <option value="بانوان" <?= $user_info['gender'] === 'بانوان' ? 'selected' : '' ?>>بانوان</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شرایط سنی *</label>
                        <select name="age" id="ageLimit" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                            <option value="">انتخاب کنید</option>
                            <option value="12" <?= $user_info['age'] === '12' ? 'selected' : '' ?>>12 سال به بالا</option>
                            <option value="15" <?= $user_info['age'] === '15' ? 'selected' : '' ?>>15 سال به بالا</option>
                            <option value="18" <?= $user_info['age'] === '18' ? 'selected' : '' ?>>18 سال به بالا</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ساعت کاری *</label>
                        <input type="text" name="hours" id="workingHours" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            value="<?= esc_attr($user_info['hours']) ?>" placeholder="مثال: ۹:００ - ۲۳:۰۰">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">روز تعطیل *</label>
                        <select name="holiday" id="holidayDay" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                            <option value="بدون تعطیلی" <?= $user_info['holiday'] === 'بدون تعطیلی' ? 'selected' : '' ?>>بدون تعطیلی</option>
                            <option value="جمعه" <?= $user_info['holiday'] === 'جمعه' ? 'selected' : '' ?>>جمعه</option>
                            <option value="پنج‌شنبه" <?= $user_info['holiday'] === 'پنج‌شنبه' ? 'selected' : '' ?>>پنج‌شنبه</option>
                            <option value="پنج‌شنبه و جمعه" <?= $user_info['holiday'] === 'پنج‌شنبه و جمعه' ? 'selected' : '' ?>>پنج‌شنبه و جمعه</option>
                            <option value="شنبه" <?= $user_info['holiday'] === 'شنبه' ? 'selected' : '' ?>>شنبه</option>
                            <option value="یکشنبه" <?= $user_info['holiday'] === 'یکشنبه' ? 'selected' : '' ?>>یکشنبه</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            placeholder="آدرس کامل گیم نت..."><?= esc_textarea($user_info['address']) ?></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">بیوگرافی</label>
                        <textarea name="bio" id="biography" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            placeholder="توضیحات کامل درباره گیم نت شما..."><?= esc_textarea($user_info['bio']) ?></textarea>
                    </div>

                    <div class="md:col-span-2 flex gap-4">
                        <button type="button" id="cancelEditBtn"
                            class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-lg transition-colors font-medium">
                            انصراف
                        </button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg transition-colors font-medium">
                            ذخیره تغییرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- بخش تصویر پروفایل -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">تصویر پروفایل</h2>
            </div>

            <div class="flex flex-col md:flex-row items-start gap-6">
                <!-- نمایش تصویر فعلی -->
                <div class="flex-shrink-0">
                    <div id="profilePicturePreview" class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 shadow-md">
                        <?php if ($profile_picture_url): ?>
                            <img src="<?php echo esc_url($profile_picture_url); ?>"
                                class="w-full h-full object-cover"
                                alt="تصویر پروفایل">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- اپلود پروفایل -->
                <div class="flex-1">
                    <form id="profilePictureForm" class="space-y-4" enctype="multipart/form-data">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">انتخاب تصویر جدید</label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 outline-none">
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: JPG, PNG, GIF - حداکثر سایز: 2MB</p>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium">
                                آپلود تصویر
                            </button>

                            <?php if ($profile_picture_url): ?>
                                <button type="button" id="removeProfilePicture"
                                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors font-medium">
                                    حذف تصویر
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- بخش گالری تصاویر -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">گالری تصاویر</h2>
                <span class="text-sm text-gray-500">حداکثر ۱۰ تصویر</span>
            </div>

            <!-- فرم آپلود عکس -->
            <form id="galleryUploadForm" class="mb-6" enctype="multipart/form-data">
                <div class="flex flex-col md:flex-row gap-4 items-start">
                    <div class="flex-1 w-full">
                        <input type="file" id="gallery_images" name="gallery_images[]" multiple accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 outline-none">
                        <p class="text-xs text-gray-500 mt-1">می‌توانید چندین تصویر را انتخاب کنید</p>
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium whitespace-nowrap">
                        آپلود تصاویر
                    </button>
                </div>
            </form>

            <!-- نمایش گالری -->
            <div id="galleryContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <?php if (!empty($gallery_images) && count($gallery_images) > 0): ?>
                    <?php foreach ($gallery_images as $image_id): ?>
                        <?php
                        $image_url = wp_get_attachment_image_url($image_id, 'medium');
                        if ($image_url): ?>
                            <div class="relative group" data-image-id="<?php echo esc_attr($image_id); ?>">
                                <img src="<?php echo esc_url($image_url); ?>"
                                    class="w-full h-48 object-cover rounded-lg shadow-md transition-transform group-hover:scale-105"
                                    alt="تصویر گیم نت">
                                <button type="button"
                                    class="absolute top-2 left-2 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity remove-image"
                                    data-image-id="<?php echo esc_attr($image_id); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">هنوز تصویری آپلود نشده است</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



    <div id="alertModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-lg mx-4">
            <p class="textalert text-lg text-center"></p>
            <div class="flex gap-2 mt-4">
                <button class="closealert w-full text-center text-white bg-green-600 hover:bg-green-700 transition-colors rounded-md py-2">تایید</button>
            </div>
        </div>
    </div>



    <script>
        // تعریف ajax_object به صورت مستقیم
        const ajax_object = {
            ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
            update_nonce: "<?php echo $update_nonce; ?>"
        };

        const alertModal = document.querySelector("#alertModal");
        const closealert = document.querySelector(".closealert");
        const textalert = document.querySelector(".textalert");

        document.addEventListener('DOMContentLoaded', function() {
            // مدیریت نمایش/پنهان فرم ویرایش
            const editInfoBtn = document.getElementById('editInfoBtn');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const infoDisplay = document.getElementById('infoDisplay');
            const editForm = document.getElementById('editForm');
            const gameNetInfoForm = document.getElementById('gameNetInfoForm');
            const successMessage = document.getElementById('successMessage');
            const galleryUploadForm = document.getElementById('galleryUploadForm');
            closealert.addEventListener("click", () => {
                alertModal.classList.add("hidden")
            })
            if (editInfoBtn && cancelEditBtn && infoDisplay && editForm) {
                // نمایش فرم ویرایش
                editInfoBtn.addEventListener('click', function() {
                    infoDisplay.classList.add('hidden');
                    editForm.classList.remove('hidden');
                    editInfoBtn.textContent = 'در حال ویرایش...';
                    editInfoBtn.disabled = true;
                });

                // انصراف از ویرایش
                cancelEditBtn.addEventListener('click', function() {
                    editForm.classList.add('hidden');
                    infoDisplay.classList.remove('hidden');
                    editInfoBtn.textContent = 'ویرایش اطلاعات';
                    editInfoBtn.disabled = false;
                });
            }

            // مدیریت فرم ویرایش اطلاعات
            if (gameNetInfoForm) {
                gameNetInfoForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // اعتبارسنجی فرم
                    const requiredFields = gameNetInfoForm.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        textalert.textContent = 'لطفاً فیلدهای ضروری را پر کنید'
                        alertModal.classList.remove("hidden")
                        return;
                    }

                    const formData = new FormData(this);
                    formData.append('action', 'update_game_net_info');
                    formData.append('security', ajax_object.update_nonce);

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;

                    // نمایش حالت لودینگ
                    submitBtn.textContent = 'در حال ذخیره...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50');

                    fetch(ajax_object.ajax_url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => {
                            if (!res.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return res.json();
                        })
                        .then(res => {
                            if (res.success) {
                                // نمایش پیام موفقیت
                                if (successMessage) {
                                    successMessage.textContent = 'اطلاعات با موفقیت بروزرسانی شد';
                                    successMessage.classList.remove('hidden');
                                    successMessage.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
                                    successMessage.classList.remove('bg-red-100', 'border-red-400', 'text-red-700');

                                    // مخفی کردن پیام بعد از 3 ثانیه
                                    setTimeout(() => {
                                        successMessage.classList.add('hidden');
                                    }, 3000);
                                }

                                // رفرش صفحه بعد از 1 ثانیه
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                throw new Error(res.data || 'خطا در ذخیره اطلاعات');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);

                            // نمایش پیام خطا
                            if (successMessage) {
                                successMessage.textContent = err.message;
                                successMessage.classList.remove('hidden');
                                successMessage.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                                successMessage.classList.remove('bg-green-100', 'border-green-400', 'text-green-700');
                            }
                        })
                        .finally(() => {
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50');
                        });
                });
            }

            // مدیریت آپلود گالری
            if (galleryUploadForm) {
                galleryUploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const fileInput = document.getElementById('gallery_images');
                    if (!fileInput.files.length) {
                        textalert.textContent = 'لطفاً حداقل یک تصویر انتخاب کنید'
                        alertModal.classList.remove("hidden")
                        return;
                    }

                    const formData = new FormData(this);
                    formData.append('action', 'update_game_net_info');
                    formData.append('security', ajax_object.update_nonce);

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;

                    // نمایش حالت لودینگ
                    submitBtn.textContent = 'در حال آپلود...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50');

                    fetch(ajax_object.ajax_url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                successMessage.textContent = 'تصاویر با موفقیت آپلود شدند';
                                successMessage.classList.remove('hidden');
                                successMessage.classList.add('bg-green-100', 'border-green-400', 'text-green-700');

                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                throw new Error(res.data || 'خطا در آپلود تصاویر');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            successMessage.textContent = err.message;
                            successMessage.classList.remove('hidden');
                            successMessage.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                        })
                        .finally(() => {
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50');
                        });
                });
            }

            // مدیریت حذف عکس از گالری
            document.querySelectorAll('.remove-image').forEach(btn => {
                btn.addEventListener('click', function() {
                    const imageId = this.dataset.imageId;
                    if (!confirm('آیا از حذف این تصویر مطمئن هستید؟')) return;

                    const formData = new FormData();
                    formData.append('action', 'delete_game_net_image');
                    formData.append('security', ajax_object.update_nonce);
                    formData.append('image_id', imageId);

                    this.disabled = true;
                    this.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>';

                    fetch(ajax_object.ajax_url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                this.closest('.relative').remove();
                                successMessage.textContent = 'تصویر با موفقیت حذف شد';
                                successMessage.classList.remove('hidden');
                                successMessage.classList.add('bg-green-100', 'border-green-400', 'text-green-700');
                            } else {
                                throw new Error(res.data || 'خطا در حذف تصویر');
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            successMessage.textContent = err.message;
                            successMessage.classList.remove('hidden');
                            successMessage.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        });
                });
            });

            // حذف خطای border وقتی کاربر شروع به تایپ می‌کند
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                });
            });
        });
        // مدیریت آپلود تصویر پروفایل
        const profilePictureForm = document.getElementById('profilePictureForm');
        if (profilePictureForm) {
            profilePictureForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const fileInput = document.getElementById('profile_picture');
                if (!fileInput.files.length) {
                    textalert.textContent = 'لطفاً یک تصویر انتخاب کنید'

                    alertModal.classList.remove("hidden")
                    return;
                }

                const formData = new FormData(this);
                formData.append('action', 'upload_game_net_profile_picture');
                formData.append('security', ajax_object.update_nonce);

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                // نمایش حالت لودینگ
                submitBtn.textContent = 'در حال آپلود...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');

                fetch(ajax_object.ajax_url, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            successMessage.textContent = 'تصویر پروفایل با موفقیت آپلود شد';
                            successMessage.classList.remove('hidden');
                            successMessage.classList.add('bg-green-100', 'border-green-400', 'text-green-700');

                            // آپدیت پیش‌نمایش تصویر
                            if (res.data && res.data.image_url) {
                                const previewDiv = document.getElementById('profilePicturePreview');
                                previewDiv.innerHTML = `<img src="${res.data.image_url}" class="w-full h-full object-cover" alt="تصویر پروفایل">`;

                                // نمایش دکمه حذف
                                if (!document.getElementById('removeProfilePicture')) {
                                    const removeBtn = document.createElement('button');
                                    removeBtn.id = 'removeProfilePicture';
                                    removeBtn.type = 'button';
                                    removeBtn.className = 'bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors font-medium';
                                    removeBtn.textContent = 'حذف تصویر';
                                    removeBtn.addEventListener('click', removeProfilePictureHandler);
                                    submitBtn.parentNode.appendChild(removeBtn);
                                }
                            }

                            setTimeout(() => {
                                successMessage.classList.add('hidden');
                            }, 3000);
                        } else {
                            throw new Error(res.data || 'خطا در آپلود تصویر');
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        successMessage.textContent = err.message;
                        successMessage.classList.remove('hidden');
                        successMessage.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                    })
                    .finally(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50');
                    });
            });
        }

        // مدیریت حذف تصویر پروفایل
        function removeProfilePictureHandler() {
            if (!confirm('آیا از حذف تصویر پروفایل مطمئن هستید؟')) return;

            const formData = new FormData();
            formData.append('action', 'upload_game_net_profile_picture');
            formData.append('security', ajax_object.update_nonce);
            formData.append('remove', 'true');

            const removeBtn = document.getElementById('removeProfilePicture');
            const originalText = removeBtn.textContent;

            removeBtn.textContent = 'در حال حذف...';
            removeBtn.disabled = true;
            removeBtn.classList.add('opacity-50');

            fetch(ajax_object.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        successMessage.textContent = 'تصویر پروفایل با موفقیت حذف شد';
                        successMessage.classList.remove('hidden');
                        successMessage.classList.add('bg-green-100', 'border-green-400', 'text-green-700');

                        // آپدیت پیش‌نمایش
                        const previewDiv = document.getElementById('profilePicturePreview');
                        previewDiv.innerHTML = `
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                `;

                        // حذف دکمه حذف
                        removeBtn.remove();
                    } else {
                        throw new Error(res.data || 'خطا در حذف تصویر');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    successMessage.textContent = err.message;
                    successMessage.classList.remove('hidden');
                    successMessage.classList.add('bg-red-100', 'border-red-400', 'text-red-700');
                })
                .finally(() => {
                    removeBtn.textContent = originalText;
                    removeBtn.disabled = false;
                    removeBtn.classList.remove('opacity-50');
                });

        }

        // اضافه کردن event listener برای دکمه حذف اگر وجود دارد
        const removeProfilePictureBtn = document.getElementById('removeProfilePicture');
        if (removeProfilePictureBtn) {
            removeProfilePictureBtn.addEventListener('click', removeProfilePictureHandler);
        }
    </script>

    </body>

    </html>
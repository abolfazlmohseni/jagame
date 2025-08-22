<?php
/*
Template Name: Information panel
*/

// استفاده از مسیر کامل برای فایل‌های include
$panel_header_path = get_template_directory() . '/Panel/PanelHeader.php';
if (file_exists($panel_header_path)) {
    include_once $panel_header_path;
} else {
    // اگر فایل وجود ندارد، هدر ساده‌ای نمایش بده
?>
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">

    <head>
        <meta charset="<?php bloginfo('charset') ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>پنل اطلاعات گیم نت</title>
        <?php wp_head() ?>
        <style>
            .hidden {
                display: none;
            }

            .loading {
                opacity: 0.6;
                pointer-events: none;
            }

            .alert-success {
                background-color: #d1fae5;
                border-color: #34d399;
                color: #065f46;
            }

            .alert-error {
                background-color: #fee2e2;
                border-color: #f87171;
                color: #b91c1c;
            }
        </style>
    </head>

    <body class="bg-gray-100">
    <?php
}

$user_info = get_current_user_game_net_info();

if (!$user_info) {
    echo '<div class="container mx-auto p-4">';
    echo '<div class="alert-error px-4 py-3 rounded border">لطفاً ابتدا وارد شوید</div>';
    echo '</div>';
    return;
}

// ایجاد nonce برای امنیت
$update_nonce = wp_create_nonce('update_game_net_nonce');
    ?>

    <div class="container mx-auto p-4">
        <!-- پیام موفقیت -->
        <div id="successMessage" class="alert-success px-4 py-3 rounded border mb-4 hidden"></div>

        <!-- بخش اطلاعات گیم نت -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">اطلاعات گیم نت</h2>
                <button id="editInfoBtn" class="bg-secondary hover:bg-primary text-white px-4 py-2 rounded-lg transition-colors">
                    ویرایش اطلاعات
                </button>
            </div>

            <!-- نمایش اطلاعات -->
            <div id="infoDisplay" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <h3 class="font-semibold text-gray-700 mb-2">بیوگرافی</h3>
                    <p class="text-gray-800 text-sm" id="displayBio"><?= esc_html($user_info['bio']) ?></p>
                </div>
            </div>

            <!-- فرم ویرایش -->
            <div id="editForm" class="hidden">
                <form id="gameNetInfoForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام گیم نت *</label>
                        <input type="text" name="gamenet_name" id="gamenetName" required
                            class="outline-0 w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-secondary"
                            value="<?= esc_attr($user_info['name']) ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نوع جنسیت *</label>
                        <select name="gender" id="genderType" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary outline-0">
                            <option value="">-- انتخاب کنید --</option>
                            <option value="مختلط" <?= $user_info['gender'] === 'مختلط' ? 'selected' : '' ?>>مختلط</option>
                            <option value="آقایان" <?= $user_info['gender'] === 'آقایان' ? 'selected' : '' ?>>آقایان</option>
                            <option value="بانوان" <?= $user_info['gender'] === 'بانوان' ? 'selected' : '' ?>>بانوان</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">شرایط سنی *</label>
                        <input type="text" name="age" id="ageLimit" required
                            class="outline-0 w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-secondary"
                            value="<?= esc_attr($user_info['age']) ?>" placeholder="مثال: ۱۸ سال به بالا">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ساعت کاری *</label>
                        <input type="text" name="hours" id="workingHours" required
                            class="outline-0 w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-secondary"
                            value="<?= esc_attr($user_info['hours']) ?>" placeholder="مثال: ۹:۰۰ - ۲۳:۰۰">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">روز تعطیل *</label>
                        <select name="holiday" id="holidayDay" required
                            class="outline-0 w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-secondary">
                            <option value="بدون تعطیلی" <?= $user_info['holiday'] === 'بدون تعطیلی' ? 'selected' : '' ?>>بدون تعطیلی</option>
                            <option value="جمعه" <?= $user_info['holiday'] === 'جمعه' ? 'selected' : '' ?>>جمعه</option>
                            <option value="پنج‌شنبه" <?= $user_info['holiday'] === 'پنج‌شنبه' ? 'selected' : '' ?>>پنج‌شنبه</option>
                            <option value="پنج‌شنبه و جمعه" <?= $user_info['holiday'] === 'پنج‌شنبه و جمعه' ? 'selected' : '' ?>>پنج‌شنبه و جمعه</option>
                            <option value="شنبه" <?= $user_info['holiday'] === 'شنبه' ? 'selected' : '' ?>>شنبه</option>
                            <option value="یکشنبه" <?= $user_info['holiday'] === 'یکشنبه' ? 'selected' : '' ?>>یکشنبه</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">بیوگرافی</label>
                        <textarea name="bio" id="biography" rows="4"
                            class="outline-0 w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-secondary"
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
    </div>

    <script>
        // تعریف ajax_object به صورت مستقیم
        const ajax_object = {
            ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
            update_nonce: "<?php echo $update_nonce; ?>"
        };

        // مدیریت نمایش/پنهان فرم ویرایش
        document.addEventListener('DOMContentLoaded', function() {
            const editInfoBtn = document.getElementById('editInfoBtn');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const infoDisplay = document.getElementById('infoDisplay');
            const editForm = document.getElementById('editForm');
            const gameNetInfoForm = document.getElementById('gameNetInfoForm');
            const successMessage = document.getElementById('successMessage');

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

            // مدیریت فرم ویرایش
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
                        alert('لطفاً فیلدهای ضروری را پر کنید');
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
                    this.classList.add('loading');

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
                                    successMessage.classList.add('alert-success');
                                    successMessage.classList.remove('alert-error');

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
                                successMessage.classList.add('alert-error');
                                successMessage.classList.remove('alert-success');
                            }
                        })
                        .finally(() => {
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                            this.classList.remove('loading');
                        });
                });
            }

            // حذف خطای border وقتی کاربر شروع به تایپ می‌کند
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('border-red-500');
                });
            });
        });
    </script>

    </body>

    </html>
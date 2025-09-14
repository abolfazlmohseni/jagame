
// تعریف ajax_object به صورت مستقیم
const ajax_object = {
    ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
    update_nonce: "<?php echo $update_nonce; ?>"
};

const alertModal = document.querySelector("#alertModal");
const closealert = document.querySelector(".closealert");
const textalert = document.querySelector(".textalert");

document.addEventListener('DOMContentLoaded', function () {
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
        editInfoBtn.addEventListener('click', function () {
            infoDisplay.classList.add('hidden');
            editForm.classList.remove('hidden');
            editInfoBtn.textContent = 'در حال ویرایش...';
            editInfoBtn.disabled = true;
        });

        // انصراف از ویرایش
        cancelEditBtn.addEventListener('click', function () {
            editForm.classList.add('hidden');
            infoDisplay.classList.remove('hidden');
            editInfoBtn.textContent = 'ویرایش اطلاعات';
            editInfoBtn.disabled = false;
        });
    }

    // مدیریت فرم ویرایش اطلاعات
    if (gameNetInfoForm) {
        gameNetInfoForm.addEventListener('submit', function (e) {
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
        galleryUploadForm.addEventListener('submit', function (e) {
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
        btn.addEventListener('click', function () {
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
        input.addEventListener('input', function () {
            this.classList.remove('border-red-500');
        });
    });
});
// مدیریت آپلود تصویر پروفایل
const profilePictureForm = document.getElementById('profilePictureForm');
if (profilePictureForm) {
    profilePictureForm.addEventListener('submit', function (e) {
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
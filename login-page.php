<?php
/*
Template Name: Unified Login Page
*/

// اگر کاربر قبلاً لاگین کرده، به صفحه مناسب منتقل شود
if (is_user_logged_in()) {
    $user = wp_get_current_user();

    if (in_array('game_net_owner', $user->roles)) {
        $panel_page = get_page_by_path('overview');
        $redirect_url = $panel_page ? get_permalink($panel_page->ID) : home_url();
    } else {
        $panel_page = get_page_by_path('userpanel');
        $redirect_url = $panel_page ? get_permalink($panel_page->ID) : home_url();
    }

    wp_redirect($redirect_url);
    exit;
}

wp_head();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center p-4">
    <div class="w-full max-w-md auth-container">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">جاگیم</h1>
            <p class="text-white text-lg">پلتفرم جامع گیمینگ ایران</p>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-2xl">
            <!-- انتخاب نوع کاربر -->
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">من:</p>
                <div class="flex rounded-lg overflow-hidden">
                    <button class="user-type-btn bg-blue-600 text-white py-2 px-4 w-1/2 transition-colors" data-type="gamer">
                        گیمر هستم
                    </button>
                    <button class="user-type-btn bg-gray-200 text-gray-700 py-2 px-4 w-1/2 transition-colors" data-type="owner">
                        مالک گیم‌نت هستم
                    </button>
                </div>
                <input type="hidden" id="user_type" value="gamer">
            </div>

            <!-- تب‌های ورود و ثبت‌نام -->
            <div class="flex mb-6 rounded-lg overflow-hidden">
                <button class="auth-tab active bg-blue-600 text-white py-3 px-4 w-1/2 transition-colors" data-tab="login">
                    ورود
                </button>
                <button class="auth-tab bg-gray-200 text-gray-700 py-3 px-4 w-1/2 transition-colors" data-tab="register">
                    ثبت‌نام
                </button>
            </div>

            <!-- فرم ورود -->
            <form id="login-form" class="auth-form space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" id="login-username-label">نام کاربری</label>
                    <input id="login-username" type="text" placeholder="نام کاربری خود را وارد کنید" dir="rtl"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                    <div class="password-container flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200 overflow-hidden">
                        <input id="login-password" type="password" placeholder="رمز عبور خود را وارد کنید"
                            class="password-input w-full px-4 py-3 text-gray-700 focus:outline-none">
                        <div class="flex">
                            <svg class="show-pass password-toggle size-6 ml-2 cursor-pointer text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg class="hide-pass password-toggle size-6 ml-2 cursor-pointer text-gray-500 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button id="login-submit" type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg hover:shadow-xl">
                    ورود به حساب کاربری
                </button>
            </form>

            <!-- فرم ثبت‌نام -->
            <form id="register-form" class="auth-form hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام کاربری</label>
                    <input id="reg-username" type="text" placeholder="یک نام کاربری انتخاب کنید" dir="rtl"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                    <input id="reg-email" type="email" placeholder="ایمیل خود را وارد کنید" dir="rtl"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                    <div class="password-container flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200 overflow-hidden">
                        <input id="reg-password" type="password" placeholder="یک رمز عبور انتخاب کنید"
                            class="password-input w-full px-4 py-3 text-gray-700 focus:outline-none">
                        <div class="flex">
                            <svg class="show-pass password-toggle size-6 ml-2 cursor-pointer text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C極端3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 極端12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 極端1 1-6 0 3 3 0 0 1 6 極端0Z" />
                            </svg>
                            <svg class="hide-pass password-toggle size-6 ml-2 cursor-pointer text-gray-500 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور</label>
                    <div class="password-container flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200 overflow-hidden">
                        <input id="reg-confirm-password" type="password" placeholder="رمز عبور خود را تکرار کنید"
                            class="password-input w-full px-4 py-3 text-gray-700 focus:outline-none">
                        <div class="flex">
                            <svg class="show-pass password-toggle size-6 ml-2 cursor-pointer text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="極端0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg class="hide-pass password-toggle size-6 ml-2 cursor-pointer text-gray-500 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 極端1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 極端0 0 1-極端4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </div>
                    </div>
                </div>

                <button id="register-submit" type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 shadow-lg hover:shadow-xl">
                    ثبت‌نام
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        // تغییر بین تب‌های ورود و ثبت‌نام
        $('.auth-tab').on('click', function() {
            var tab = $(this).data('tab');

            // تغییر وضعیت تب‌ها
            $('.auth-tab').removeClass('bg-blue-600 text-white').addClass('bg-gray-200 text-gray-700');
            $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-600 text-white');

            // نمایش فرم مربوطه
            $('.auth-form').addClass('hidden');
            $('#' + tab + '-form').removeClass('hidden');
        });

        // تغییر بین نوع کاربر (گیمر یا مالک)
        $('.user-type-btn').on('click', function() {
            var userType = $(this).data('type');

            $('.user-type-btn').removeClass('bg-blue-600 text-white').addClass('bg-gray-200 text-gray-700');
            $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-600 text-white');

            $('#user_type').val(userType);

            // تغییر متن دکمه‌ها و placeholder بر اساس نوع کاربر
            if (userType === 'owner') {
                $('#login-username-label').text('شماره موبایل');
                $('#login-username').attr('placeholder', 'شماره موبایل گیم نت را وارد کنید');
                $('#login-submit').text('ورود به پنل مدیریت');
                $('#register-submit').text('ثبت‌نام مالک گیم نت');
            } else {
                $('#login-username-label').text('نام کاربری');
                $('#login-username').attr('placeholder', 'نام کاربری خود را وارد کنید');
                $('#login-submit').text('ورود به حساب کاربری');
                $('#register-submit').text('ثبت‌نام گیمر');
            }
        });

        // نمایش/مخفی کردن رمز عبور
        $(document).on('click', '.password-toggle', function() {
            var container = $(this).closest('.password-container');
            var input = container.find('.password-input');
            var showIcon = container.find('.show-pass');
            var hideIcon = container.find('.hide-pass');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                showIcon.addClass('hidden');
                hideIcon.removeClass('hidden');
            } else {
                input.attr('type', 'password');
                hideIcon.addClass('hidden');
                showIcon.removeClass('hidden');
            }
        });

        // فرم ورود
        $('#login-form').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                action: 'unified_login',
                security: '<?php echo wp_create_nonce("unified_auth_nonce"); ?>',
                username: $('#login-username').val(),
                password: $('#login-password').val(),
                user_type: $('#user_type').val()
            };

            // اعتبارسنجی
            if (!formData.username || !formData.password) {
                showMessage('لطفاً نام کاربری و رمز عبور را وارد کنید.', 'error');
                return;
            }

            // نمایش حالت لودینگ
            setLoadingState('login', true);

            // ارسال درخواست
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.data.redirect;
                    } else {
                        showMessage(response.data, 'error');
                        setLoadingState('login', false);
                    }
                },
                error: function() {
                    showMessage('خطا در ارتباط با سرور.', 'error');
                    setLoadingState('login', false);
                }
            });
        });

        // فرم ثبت‌نام
        $('#register-form').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                action: 'unified_register',
                security: '<?php echo wp_create_nonce("unified_auth_nonce"); ?>',
                username: $('#reg-username').val(),
                email: $('#reg-email').val(),
                password: $('#reg-password').val(),
                confirm_password: $('#reg-confirm-password').val(),
                user_type: $('#user_type').val()
            };

            // اعتبارسنجی
            if (!formData.username || !formData.email || !formData.password || !formData.confirm_password) {
                showMessage('لطفاً تمام فیلدهای ضروری را پر کنید.', 'error');
                return;
            }

            if (formData.password !== formData.confirm_password) {
                showMessage('رمزهای عبور مطابقت ندارند.', 'error');
                return;
            }

            // نمایش حالت لودینگ
            setLoadingState('register', true);

            // ارسال درخواست
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showMessage(response.data.message, 'success');
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 1500);
                    } else {
                        showMessage(response.data, 'error');
                        setLoadingState('register', false);
                    }
                },
                error: function() {
                    showMessage('خطا در ارتباط با سرور.', 'error');
                    setLoadingState('register', false);
                }
            });
        });

        // توابع کمکی
        function showMessage(message, type) {
            var messageDiv = $('<div class="p-3 rounded-lg mb-4 text-center"></div>');

            if (type === 'error') {
                messageDiv.addClass('bg-red-100 text-red-700');
            } else {
                messageDiv.addClass('bg-green-100 text-green-700');
            }

            messageDiv.text(message);

            // حذف پیام‌های قبلی
            $('.auth-message').remove();

            // اضافه کردن پیام جدید
            $('.auth-container').prepend(messageDiv);
            messageDiv.addClass('auth-message');

            // حذف خودکار پس از 5 ثانیه
            setTimeout(function() {
                messageDiv.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        }

        function setLoadingState(formType, isLoading) {
            var button = $('#' + formType + '-submit');

            if (isLoading) {
                button.prop('disabled', true);
                button.data('original-text', button.text());
                button.text('در حال پردازش...');
            } else {
                button.prop('disabled', false);
                button.text(button.data('original-text'));
            }
        }
    });
</script>
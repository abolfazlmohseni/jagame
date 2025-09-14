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
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به حساب</title>
    <?php wp_head(); ?>
    <style>
        .auth-container {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-tab.disabled {
            background: #E5E7EB;
            color: #9CA3AF;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .password-toggle:hover {
            color: #8B5CF6;
        }

        input:focus {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .form-disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md auth-container">
            <!-- هدر -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">جاگیم</h1>
                <p class="text-white/90 text-lg">پلتفرم جامع گیمینگ مشهد</p>
            </div>

            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-white/20">

                <!-- تب‌های ورود و ثبت‌نام -->
                <div class="flex mb-6 rounded-xl overflow-hidden shadow-sm" id="auth-tabs">
                    <button class="auth-tab active bg-secondary text-white py-3 px-4 w-1/2 transition-all duration-300 font-medium" data-tab="login">
                        ورود
                    </button>
                    <button class="auth-tab bg-gray-100 text-gray-700 py-3 px-4 w-1/2 transition-all duration-300 font-medium" data-tab="register" id="register-tab">
                        ثبت‌نام
                    </button>
                </div>

                <!-- فرم ورود -->
                <form id="login-form" class="auth-form space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2" id="login-username-label">نام کاربری</label>
                        <input id="login-username" type="text" placeholder="نام کاربری خود را وارد کنید"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                        <div class="relative">
                            <input id="login-password" type="password" placeholder="رمز عبور خود را وارد کنید"
                                class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                            <button type="button" class="password-toggle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="bg-secondary hover:bg-primary w-full py-3 px-4 text-white font-medium rounded-xl transition-all duration-300">
                        ورود به حساب
                    </button>
                </form>

                <!-- فرم ثبت‌نام -->
                <form id="register-form" class="auth-form space-y-5 hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نام کاربری</label>
                        <input id="reg-username" type="text" placeholder="نام کاربری مورد نظر را وارد کنید"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                        <input id="reg-email" type="email" placeholder="آدرس ایمیل خود را وارد کنید"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                        <div class="relative">
                            <input id="reg-password" type="password" placeholder="رمز عبور قوی انتخاب کنید"
                                class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                            <button type="button" class="password-toggle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور</label>
                        <div class="relative">
                            <input id="reg-confirm-password" type="password" placeholder="رمز عبور را مجدداً وارد کنید"
                                class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                            <button type="button" class="password-toggle absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="bg-secondary hover:bg-primary w-full py-3 px-4 text-white font-medium rounded-xl transition-all duration-300">
                        ایجاد حساب کاربری
                    </button>
                </form>

                <!-- پیام اضافی برای مالکان گیم‌نت -->
                <!-- <div id="owner-message" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">

                </div> -->
            </div>

            <!-- لینک‌های اضافی -->
            <div class="text-center mt-6">

            </div>
        </div>
    </div>


    <script>
        // عناصر DOM
        const userTypeButtons = document.querySelectorAll('.user-type-btn');

        const authTabs = document.querySelectorAll('.auth-tab');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const registerTab = document.getElementById('register-tab');
        const ownerMessage = document.getElementById('owner-message');

        const passwordToggles = document.querySelectorAll('.password-toggle');

        // نمایش/مخفی کردن رمز عبور
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const passwordInput = this.parentElement.querySelector('input[type="password"], input[type="text"]');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = `
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        `;
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = `
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        `;
                }
            });
        });

        jQuery(document).ready(function($) {
            // تغییر بین تب‌های ورود و ثبت‌نام
            $('.auth-tab').on('click', function() {
                var tab = $(this).data('tab');

                // تغییر وضعیت تب‌ها
                $('.auth-tab').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
                $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-secondary text-white');

                // نمایش فرم مربوطه
                $('.auth-form').addClass('hidden');
                $('#' + tab + '-form').removeClass('hidden');
            });

            // تغییر بین نوع کاربر (گیمر یا مالک)
            // $('.user-type-btn').on('click', function() {
            //     var userType = $(this).data('type');

            //     $('.user-type-btn').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
            //     $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-secondary text-white');

            //     $('#user_type').val(userType);

            //     // تغییر متن دکمه‌ها و placeholder بر اساس نوع کاربر
            //     if (userType === 'owner') {
            //         $('#login-username-label').text('شماره موبایل');
            //         $('#login-username').attr('placeholder', 'شماره موبایل گیم نت را وارد کنید');
            //         $('#login-submit').text('ورود به پنل مدیریت');
            //         $('#register-submit').text('ثبت‌نام مالک گیم نت');
            //     } else {
            //         $('#login-username-label').text('نام کاربری');
            //         $('#login-username').attr('placeholder', 'نام کاربری خود را وارد کنید');
            //         $('#login-submit').text('ورود به حساب کاربری');
            //         $('#register-submit').text('ثبت‌نام گیمر');
            //     }
            // });
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


</body>
<?php
/*
Template Name: login Page
*/
wp_head();
get_header();
?>

<script>
    var user_auth_object = {
        ajax_url: '<?php echo admin_url("admin-ajax.php"); ?>',
        nonce: '<?php echo wp_create_nonce("user_auth_nonce"); ?>',
        is_logged_in: <?php echo is_user_logged_in() ? 'true' : 'false'; ?>
    };
</script>

<div class="bg-white rounded-lg shadow-md p-6 max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-bold text-center mb-6">ورود / ثبت‌نام کاربران</h2>

    <div id="loginForm">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="username">نام کاربری یا ایمیل</label>
            <input type="text" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2" for="password">رمز عبور</label>
            <input type="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button id="loginBtn" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">ورود</button>
        <p class="text-center mt-4">
            حساب کاربری ندارید؟
            <a href="#" id="showRegister" class="text-blue-600 hover:underline">ثبت‌نام کنید</a>
        </p>
    </div>

    <div id="registerForm" class="hidden">
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="reg_username">نام کاربری</label>
            <input type="text" id="reg_username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="reg_email">ایمیل</label>
            <input type="email" id="reg_email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2" for="reg_password">رمز عبور</label>
            <input type="password" id="reg_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2" for="reg_password_confirm">تکرار رمز عبور</label>
            <input type="password" id="reg_password_confirm" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button id="registerBtn" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">ثبت‌نام</button>
        <p class="text-center mt-4">
            قبلاً حساب دارید؟
            <a href="#" id="showLogin" class="text-blue-600 hover:underline">وارد شوید</a>
        </p>
    </div>
</div>

<div id="userDashboard" class="hidden bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-2xl font-bold text-center mb-6">پنل کاربری</h2>

    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-600">خوش آمدید، <span id="userDisplayName" class="font-semibold"></span></p>
        </div>
        <button id="logoutBtn" class="bg-red-600 text-white py-1 px-3 rounded-md hover:bg-red-700 transition duration-200 text-sm">خروج</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3">اطلاعات کاربری</h3>
            <p><span class="font-medium">نام کاربری:</span> <span id="userUsername"></span></p>
            <p><span class="font-medium">ایمیل:</span> <span id="userEmail"></span></p>
            <p><span class="font-medium">تاریخ عضویت:</span> <span id="userRegistered"></span></p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-3">تاریخچه رزروها</h3>
            <div id="userReservations">
                <p class="text-gray-500 text-center">هنوز رزروی انجام نداده‌اید</p>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold mb-3">گیم‌نت‌های مورد علاقه</h3>
        <div id="userFavorites">
            <p class="text-gray-500 text-center">هنوز گیم‌نتی به علاقه‌مندی‌ها اضافه نکرده‌اید</p>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        // ابتدا مطمئن شوید user_auth_object تعریف شده است
        if (typeof user_auth_object === 'undefined') {
            console.error('user_auth_object is not defined');
            return;
        }

        // تغییر بین فرم ورود و ثبت‌نام
        $('#showRegister').on('click', function(e) {
            e.preventDefault();
            $('#loginForm').addClass('hidden');
            $('#registerForm').removeClass('hidden');
        });

        $('#showLogin').on('click', function(e) {
            e.preventDefault();
            $('#registerForm').addClass('hidden');
            $('#loginForm').removeClass('hidden');
        });

        // ثبت‌نام کاربر
        $('#registerBtn').on('click', function() {
            var formData = {
                action: 'register_user',
                username: $('#reg_username').val(),
                email: $('#reg_email').val(),
                password: $('#reg_password').val(),
                confirm_password: $('#reg_password_confirm').val(),
                nonce: user_auth_object.nonce
            };

            // اعتبارسنجی اولیه
            if (!formData.username || !formData.email || !formData.password) {
                alert('لطفاً تمام فیلدهای ضروری را پر کنید.');
                return;
            }

            if (formData.password !== formData.confirm_password) {
                alert('رمزهای عبور مطابقت ندارند.');
                return;
            }

            $.ajax({
                url: user_auth_object.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        getUserData();
                    } else {
                        alert('خطا: ' + response.data);
                    }
                },
                error: function() {
                    alert('خطا در ارتباط با سرور.');
                }
            });
        });

        // ورود کاربر
        $('#loginBtn').on('click', function() {
            var formData = {
                action: 'login_user',
                username: $('#username').val(),
                password: $('#password').val(),
                nonce: user_auth_object.nonce
            };

            if (!formData.username || !formData.password) {
                alert('لطفاً نام کاربری و رمز عبور را وارد کنید.');
                return;
            }

            $.ajax({
                url: user_auth_object.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        getUserData();
                    } else {
                        alert('خطا: ' + response.data);
                    }
                },
                error: function() {
                    alert('خطا در ارتباط با سرور.');
                }
            });
        });

        // دریافت اطلاعات کاربر
        function getUserData() {
            $.ajax({
                url: user_auth_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_user_data',
                    nonce: user_auth_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('#userDisplayName').text(response.data.display_name);
                        $('#userUsername').text(response.data.username);
                        $('#userEmail').text(response.data.email);
                        $('#userRegistered').text(response.data.registered);

                        $('#loginForm, #registerForm').addClass('hidden');
                        $('#userDashboard').removeClass('hidden');
                    }
                },
                error: function() {
                    alert('خطا در دریافت اطلاعات کاربر.');
                }
            });
        }

        // خروج کاربر
        $('#logoutBtn').on('click', function() {
            $.ajax({
                url: user_auth_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'user_logout',
                    nonce: user_auth_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        $('#userDashboard').addClass('hidden');
                        $('#loginForm').removeClass('hidden');
                    }
                },
                error: function() {
                    alert('خطا در خروج از حساب.');
                }
            });
        });

        // بررسی اگر کاربر قبلاً وارد شده
        if (user_auth_object.is_logged_in) {
            getUserData();
        }
    });
</script>
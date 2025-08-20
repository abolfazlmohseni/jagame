<?php
/* Template Name: Login Page */
// Generate fresh nonce for this page load
$login_nonce = wp_create_nonce('ajax_login_nonce');
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="<?php bloginfo('charset') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جاگیم - ورود به حساب کاربری</title>
    <?php wp_head() ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4B3F72',
                        secondary: '#8E7CC3',
                        accent: '#FFD447',
                        surface: '#FFFFFF',
                        'text-dark': '#111827',
                        'text-on-dark': '#FFFFFF',
                        muted: '#6B7280'
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-primary to-secondary min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-text-on-dark mb-2">کجا گیم</h1>
            <p class="text-secondary text-lg">ورود به حساب کاربری</p>
        </div>

        <div class="bg-surface rounded-3xl p-8 shadow-2xl">
            <form id="login-form" class="space-y-6">
                <div id="login-error" class="text-red-500 text-center hidden"></div>

                <!-- شماره موبایل -->
                <div>
                    <label class="block text-sm font-medium text-text-dark mb-2">شماره موبایل</label>
                    <input name="username" type="tel" placeholder="09123456789"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-text-dark placeholder-muted focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all duration-200">
                </div>

                <!-- رمز عبور -->
                <div>
                    <label class="block text-sm font-medium text-text-dark mb-2">رمز عبور</label>
                    <div class="flex items-center">
                        <input name="password" type="password" placeholder="رمز عبور خود را وارد کنید"
                            class="passwordinput w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-text-dark placeholder-muted focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all duration-200">
                        <button type="button" id="toggle-password" class="mr-2 text-muted">👁</button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-text-on-dark font-medium py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-accent shadow-lg hover:shadow-xl">
                    ورود به حساب کاربری
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function() {
            const input = document.querySelector('.passwordinput');
            input.type = input.type === 'password' ? 'text' : 'password';
        });

        const ajax_login_obj = {
            ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
            nonce: "<?php echo $login_nonce; ?>"
        };

        document.querySelector('#login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const errorDiv = document.querySelector('#login-error');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            // Reset error
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
            
            // Show loading
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'در حال ورود...';
            submitBtn.disabled = true;

            const data = new FormData(form);
            data.append('action', 'ajax_login');
            data.append('security', ajax_login_obj.nonce);

            fetch(ajax_login_obj.ajax_url, {
                method: 'POST',
                body: data
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = res.data.redirect;
                } else {
                    errorDiv.textContent = res.data.message;
                    errorDiv.classList.remove('hidden');
                }
            })
            .catch(err => {
                errorDiv.textContent = 'خطا در ارتباط با سرور';
                errorDiv.classList.remove('hidden');
                console.error(err);
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>

    <?php wp_footer(); ?>
</body>
</html>
<?php
/* Template Name: panel Login-page */
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
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <input name="username" type="tel" placeholder="09123456789" dir="rtl"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-text-dark placeholder-muted focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all duration-200">
                </div>

                <!-- رمز عبور -->
                <div>
                    <label class="block text-sm font-medium text-text-dark mb-2">رمز عبور</label>
                    <div class="flex items-center justify-between w-full bg-gray-50 border border-gray-200 rounded-xl text-text-dark placeholder-muted  focus-within:ring-2 focus-within:ring-accent focus-within:border-accent transition-all duration-200 overflow-hidden">
                        <input name="password" type="password" placeholder="رمز عبور خود را وارد کنید"
                            class="passwordinput w-full px-4 py-3 text-text-dark focus:outline-none">
                        <div>
                            <svg id="showPass" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-2 cursor-pointer">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg id="hidePass" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 ml-2 cursor-pointer hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>

                        </div>

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
        const input = document.querySelector('.passwordinput');
        const showPass = document.querySelector('#showPass')
        const hidePass = document.querySelector('#hidePass')

        const changeInputStatus = () => {
            showPass.classList.toggle("hidden")
            hidePass.classList.toggle("hidden")
            input.type = input.type == 'password' ? 'text' : 'password'
        }

        showPass.addEventListener("click", changeInputStatus)
        hidePass.addEventListener("click", changeInputStatus)

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const logoutBtn = document.getElementById('logout-btn');
        const logoutConfirm = document.getElementById('logout-confirm');
        const cancelLogout = document.getElementById('cancel-logout');
        const confirmLogout = document.getElementById('confirm-logout');

        // مدیریت منوی خروج
        logoutBtn.addEventListener('click', function() {
            logoutConfirm.classList.remove('hidden');
        });

        cancelLogout.addEventListener('click', function() {
            logoutConfirm.classList.add('hidden');
        });

        confirmLogout.addEventListener('click', function() {
            // عملیات خروج واقعی با AJAX
            const formData = new FormData();
            formData.append('action', 'user_logout');
            formData.append('security', '<?php echo wp_create_nonce("user_auth_nonce"); ?>');

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // هدایت به صفحه اصلی پس از خروج موفق
                        window.location.href = '<?php echo home_url(); ?>';
                    } else {
                        alert('خطا در خروج از سیستم: ' + data.data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('خطا در ارتباط با سرور');
                });
        });

        // Tab Switching
        // Check if there's a tab parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'dashboard';

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Update URL without reloading page
                const url = new URL(window.location);
                url.searchParams.set('tab', tabId);
                window.history.pushState({}, '', url);

                // Activate tab
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                tabContents.forEach(content => content.classList.add('hidden'));
                document.getElementById(tabId).classList.remove('hidden');
            });

            // Activate tab from URL parameter
            if (tab.getAttribute('data-tab') === activeTab) {
                tab.click();
            }
        });

        // Profile form submission
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'update_user_profile');
            formData.append('security', user_profile_ajax.nonce);

            fetch(user_profile_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                    } else {
                        alert('خطا: ' + data.data);
                    }
                })
                .catch(error => {
                    alert('خطا در ارتباط با سرور');
                });
        });

        // Password form submission
        document.getElementById('password-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'change_user_password');
            formData.append('security', user_profile_ajax.nonce);

            fetch(user_profile_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                    // 
                        this.reset();
                    } else {
                        alert('خطا: ' + data.data);
                    }
                })
                .catch(error => {
                    alert('خطا در ارتباط با سرور');
                });
        });
    });

    // اسکریپت ساده برای نمایش/مخفی کردن منوی موبایل
    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // بستن منوی موبایل هنگام کلیک روی لینک‌ها
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });
</script>
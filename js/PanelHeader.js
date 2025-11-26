document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const userMenu = document.getElementById('user-menu');
    const dropdownContent = document.getElementById('dropdown-content');
    const dropdownArrow = document.getElementById('dropdown-arrow');
    const logoutBtn = document.getElementById('logout-btn');
    const logoutConfirm = document.getElementById('logout-confirm');
    const cancelLogout = document.getElementById('cancel-logout');
    const confirmLogout = document.getElementById('confirm-logout');

    // مدیریت منوی موبایل
    mobileMenuButton.addEventListener('click', function () {
        sidebar.classList.toggle('-right-full');
        sidebar.classList.toggle('right-0');
        overlay.classList.toggle('hidden');
    });

    overlay.addEventListener('click', function () {
        sidebar.classList.add('-right-full');
        sidebar.classList.remove('right-0');
        overlay.classList.add('hidden');
    });

    // مدیریت باز و بسته شدن منوی کاربر
    userMenu.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownContent.classList.toggle('hidden');
        dropdownArrow.classList.toggle('rotate-180');
    });

    // بستن منو با کلیک بیرون از آن
    document.addEventListener('click', function (e) {
        if (!userMenu.contains(e.target)) {
            dropdownContent.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
        }
    });

    // مدیریت کلیک روی دکمه خروج
    logoutBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownContent.classList.add('hidden');
        logoutConfirm.classList.remove('hidden');
    });

    // مدیریت منوی تأیید خروج
    cancelLogout.addEventListener('click', function () {
        logoutConfirm.classList.add('hidden');
    });

    confirmLogout.addEventListener('click', function () {
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
                  
                }
            })
            .catch(error => {
                console.error('Error:', error);
             
            });
    });
});
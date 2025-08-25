<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اپراتور گیم نت</title>
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

<body class="bg-gray-100">
    <!-- هدر -->
    <header class="p-4 bg-primary text-white flex justify-between items-center shadow-md">
        <!-- دکمه منوی موبایل -->
        <div class="mobile-menu-button p-2 cursor-pointer rounded-full bg-secondary lg:hidden">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </div>

        <div>
            <p class="text-xl lg:text-2xl font-bold header-title">پنل مدیریت</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="p-2 cursor-pointer rounded-full bg-secondary">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div class="flex items-center gap-1 cursor-pointer relative" id="user-menu">
                <p class="hidden md:block">اپراتور</p>
                <svg class="w-4 h-4 transition-transform" id="dropdown-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
                <div class="dropdown-content absolute bg-white flex-col shadow-md text-black rounded-md overflow-hidden top-10 left-0 text-sm hidden" id="dropdown-content">
                    <div class="dropdown-item logout p-3 transition-all hover:bg-red-50 text-red-600 flex items-center gap-2" id="logout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>خروج</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- overlay برای موبایل -->
    <div class="overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" id="overlay"></div>

    <!-- منوی سایدبار -->
    <div class="flex">
        <menu class="sidebar fixed lg:static w-3/4 lg:w-1/5 h-screen pt-5 bg-[#372e53] text-gray-400 z-50 transform -right-full top-0 lg:right-0 transition-transform duration-300" id="sidebar">
            <a href=<?= home_url('/Overview/') ?> class="menu-item flex items-center gap-2 py-4 pr-2 font-light hover:bg-primary transition-colors duration-300 active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <p>داشبورد</p>
            </a>
            <a href=<?= home_url('/information-panel/') ?> class="menu-item flex items-center gap-2 py-4 pr-2 font-light hover:bg-primary transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                <p>اطلاعات گیم نت</p>
            </a>
            <a href=<?= home_url('/device-management/') ?> class="menu-item flex items-center gap-2 py-4 pr-2 font-light hover:bg-primary transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 0 1-.657.643 48.39 48.39 0 0 1-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 0 1-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 0 0-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 0 1-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 0 0 .657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 0 1-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 0 0 5.427-.630 48.05 48.05 0 0 0 .582-4.717.532.532 0 0 0-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 0 0 .658-.663 48.422 48.422 0 0 0-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 0 1-.61-.58v0Z" />
                </svg>
                <p>مدیریت دستگاه ها</p>
            </a>
            <a href="" class="menu-item flex items-center gap-2 py-4 pr-2 font-light hover:bg-primary transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                <p>رزرو ها</p>
            </a>
        </menu>

        <!-- تأییدیه خروج -->
        <div class="logout-confirm fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden" id="logout-confirm">
            <div class="bg-white rounded-xl p-6 w-11/12 max-w-md">
                <h3 class="text-lg font-bold text-center mb-4">آیا مطمئن هستید؟</h3>
                <p class="text-gray-600 text-center mb-6">می‌خواهید از حساب کاربری خود خارج شوید؟</p>
                <div class="flex justify-center gap-3">
                    <button class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors" id="cancel-logout">لغو</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" id="confirm-logout">خروج</button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-right-full');
                    sidebar.classList.toggle('right-0');
                    overlay.classList.toggle('hidden');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-right-full');
                    sidebar.classList.remove('right-0');
                    overlay.classList.add('hidden');
                });

                // مدیریت باز و بسته شدن منوی کاربر
                userMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownContent.classList.toggle('hidden');
                    dropdownArrow.classList.toggle('rotate-180');
                });

                // بستن منو با کلیک بیرون از آن
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        dropdownContent.classList.add('hidden');
                        dropdownArrow.classList.remove('rotate-180');
                    }
                });

                // مدیریت کلیک روی دکمه خروج
                logoutBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownContent.classList.add('hidden');
                    logoutConfirm.classList.remove('hidden');
                });

                // مدیریت منوی تأیید خروج
                cancelLogout.addEventListener('click', function() {
                    logoutConfirm.classList.add('hidden');
                });

                confirmLogout.addEventListener('click', function() {
                    // در اینجا می‌توانید عملیات خروج واقعی را انجام دهید
                    alert('عملیات خروج با موفقیت انجام شد!');
                    logoutConfirm.classList.add('hidden');
                });
            });
        </script>
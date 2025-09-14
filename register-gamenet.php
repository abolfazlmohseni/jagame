<?php
/*
Template Name: register Game Net
*/
get_header()
?>
<section class="py-12 sm:py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h3 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 text-gray-800">ثبت نام گیم نت</h3>
            <p class="text-base sm:text-lg text-primary max-w-2xl mx-auto">گیم نت خود را در جاگیم ثبت کنید و مشتریان بیشتری جذب کنید</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-gray-50 rounded-2xl shadow-xl p-6 sm:p-8">
                <form id="registrationForm" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-secondary">اطلاعات پایه</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">نام گیم نت *</label>
                                <input type="text" id="gamenetName" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="نام گیم نت خود را وارد کنید">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">شماره موبایل *</label>
                                <input type="tel" id="phoneNumber" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="09123456789">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold mb-2 text-primary">آدرس کامل *</label>
                                <textarea id="address" required rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="آدرس کامل گیم نت خود را وارد کنید"></textarea>
                            </div>

                            <!-- منطقه (با div به جای select) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">منطقه *</label>
                                <div class="relative">
                                    <div id="areaDropdown" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all flex justify-between items-center cursor-pointer">
                                        <span id="areaSelected" class="text-gray-400">انتخاب منطقه</span>
                                        <i class="fas fa-chevron-down text-gray-400 transition-transform" id="areaArrow"></i>
                                    </div>
                                    <div id="areaOptions" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                                        <div class="p-2 hover:bg-blue-50 cursor-pointer rounded-lg" data-value="one">منطقه یک</div>
                                        <div class="p-2 hover:bg-blue-50 cursor-pointer rounded-lg" data-value="two">منطقه دو</div>
                                        <div class="p-2 hover:bg-blue-50 cursor-pointer rounded-lg" data-value="tree">منطقه سه</div>
                                     </div>
                                    <input type="hidden" id="area" required>
                                </div>
                            </div>

                            <!-- وضعیت جنسیت (با div به جای select) -->
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">وضعیت جنسیت *</label>
                                <div class="relative">
                                    <div id="genderDropdown" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all flex justify-between items-center cursor-pointer">
                                        <span id="genderSelected" class="text-gray-400">انتخاب کنید</span>
                                        <i class="fas fa-chevron-down text-gray-400 transition-transform" id="genderArrow"></i>
                                    </div>
                                    <div id="genderOptions" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                                        <div class="p-2 hover:bg-blue-50 cursor-pointer rounded-lg" data-value="mixed">مختلط</div>
                                        <div class="p-2 hover:bg-blue-50 cursor-pointer rounded-lg" data-value="male">ویژه آقایان</div>
                                        <div class="p-2 hover:bg-blue-50 cursor-pointer rounded-lg" data-value="female">ویژه بانوان</div>
                                    </div>
                                    <input type="hidden" id="genderStatus" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-secondary">ساعات کاری</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">ساعت شروع *</label>
                                <input type="time" id="startTime" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">ساعت پایان *</label>
                                <input type="time" id="endTime" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold mb-2 text-primary">روزهای تعطیل</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="saturday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">شنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="sunday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">یکشنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="monday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">دوشنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="tuesday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">سه‌شنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="wednesday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">چهارشنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="thursday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">پنج‌شنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="friday" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">جمعه</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Age and Pricing -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-secondary">شرایط سنی </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">حداقل سن *</label>
                                <input type="number" id="minAge" required min="0" max="100" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="مثال: 12">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">حداکثر سن</label>
                                <input type="number" id="maxAge" min="0" max="100" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="مثال: 60">
                            </div>
                        </div>
                    </div>

                    <!-- Devices and Features -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-secondary">دستگاه‌ها و امکانات</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">دستگاه‌های موجود *</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="pc" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">کامپیوتر</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="ps5" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">پلی استیشن 5</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="ps4" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">پلی استیشن 4</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="xbox" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">ایکس باکس</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="nintendo" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">نینتندو</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="vr" class="rounded border-gray-300 text-secondary focus:ring-blue-500">
                                        <span class="text-sm">واقعیت مجازی</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-primary">امکانات اضافی</label>
                                <textarea id="additionalFeatures" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="مثال: کافی شاپ، پارکینگ، اینترنت پرسرعت، صندلی گیمینگ و..."></textarea>
                            </div>
                        </div>
                    </div>

                    <?php wp_nonce_field('game_net_registration_nonce', 'registration_nonce'); ?>
                    <!-- Submit Button -->
                    <div class="text-center pt-4">
                        <button type="submit" class="bg-primary text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-all transform hover:scale-105">
                            ثبت درخواست برای بررسی
                        </button>
                        <p class="text-sm text-primary mt-3 hidden">درخواست شما پس از بررسی تیم ما تایید خواهد شد</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // مدیریت dropdownهای سفارشی
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown منطقه
        const areaDropdown = document.getElementById('areaDropdown');
        const areaOptions = document.getElementById('areaOptions');
        const areaSelected = document.getElementById('areaSelected');
        const areaInput = document.getElementById('area');
        const areaArrow = document.getElementById('areaArrow');

        areaDropdown.addEventListener('click', function() {
            areaOptions.classList.toggle('hidden');
            areaArrow.classList.toggle('rotate-180');
        });

        areaOptions.querySelectorAll('div').forEach(option => {
            option.addEventListener('click', function() {
                areaSelected.textContent = this.textContent;
                areaInput.value = this.getAttribute('data-value');
                areaOptions.classList.add('hidden');
                areaArrow.classList.remove('rotate-180');
            });
        });

        // Dropdown وضعیت جنسیت
        const genderDropdown = document.getElementById('genderDropdown');
        const genderOptions = document.getElementById('genderOptions');
        const genderSelected = document.getElementById('genderSelected');
        const genderInput = document.getElementById('genderStatus');
        const genderArrow = document.getElementById('genderArrow');

        genderDropdown.addEventListener('click', function() {
            genderOptions.classList.toggle('hidden');
            genderArrow.classList.toggle('rotate-180');
        });

        genderOptions.querySelectorAll('div').forEach(option => {
            option.addEventListener('click', function() {
                genderSelected.textContent = this.textContent;
                genderInput.value = this.getAttribute('data-value');
                genderOptions.classList.add('hidden');
                genderArrow.classList.remove('rotate-180');
            });
        });

        // بستن dropdownها با کلیک خارج از آنها
        document.addEventListener('click', function(e) {
            if (!areaDropdown.contains(e.target)) {
                areaOptions.classList.add('hidden');
                areaArrow.classList.remove('rotate-180');
            }
            if (!genderDropdown.contains(e.target)) {
                genderOptions.classList.add('hidden');
                genderArrow.classList.remove('rotate-180');
            }
        });
    });

    jQuery(document).ready(function($) {
        $('#registrationForm').on('submit', function(e) {
            e.preventDefault();

            // جمع‌آوری داده‌های فرم
            var formData = {
                action: 'game_net_registration',
                gamenetName: $('#gamenetName').val(),
                phoneNumber: $('#phoneNumber').val(),
                address: $('#address').val(),
                area: $('#area').val(),
                genderStatus: $('#genderStatus').val(),
                startTime: $('#startTime').val(),
                endTime: $('#endTime').val(),
                minAge: $('#minAge').val(),
                maxAge: $('#maxAge').val(),
                additionalFeatures: $('#additionalFeatures').val(),
                registration_nonce: $('#registration_nonce').val() // این خط مهم است
            };

            // جمع‌آوری روزهای تعطیل
            formData.offDays = [];
            $('input[type="checkbox"]:checked').each(function() {
                if ($(this).closest('.grid').find('span:contains("شنبه"), span:contains("یکشنبه"), span:contains("دوشنبه"), span:contains("سه‌شنبه"), span:contains("چهارشنبه"), span:contains("پنج‌شنبه"), span:contains("جمعه")').length) {
                    formData.offDays.push($(this).val());
                }
            });

            // جمع‌آوری دستگاه‌ها
            formData.devices = [];
            $('input[type="checkbox"]:checked').each(function() {
                if ($(this).closest('.grid').find('span:contains("کامپیوتر"), span:contains("پلی استیشن"), span:contains("ایکس باکس"), span:contains("نینتندو"), span:contains("واقعیت مجازی")').length) {
                    formData.devices.push($(this).val());
                }
            });

            // نمایش loading
            $('button[type="submit"]').prop('disabled', true).text('در حال ارسال...');

            // ارسال درخواست Ajax
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        $('#registrationForm')[0].reset();
                        // بازنشانی dropdownها
                        $('#areaSelected').text('انتخاب منطقه');
                        $('#genderSelected').text('انتخاب کنید');
                    } else {
                        alert('خطا: ' + response.data);
                    }
                },
                error: function(xhr, status, error) {
                    alert('خطا در ارسال درخواست. لطفاً مجدد تلاش کنید.');
                    console.error(error);
                },
                complete: function() {
                    // فعال کردن دکمه
                    $('button[type="submit"]').prop('disabled', false).text('ثبت درخواست برای بررسی');
                }
            });
        });
    });
</script>
<?php
get_footer()
?>
</body>

</html>
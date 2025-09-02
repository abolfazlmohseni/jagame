<?php
/*
Template Name: register Game Net

*/
get_header()
?>

<section class="py-12 sm:py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h3 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">ثبت نام گیم نت</h3>
            <p class="text-base sm:text-lg text-muted max-w-2xl mx-auto">گیم نت خود را در جاگیم ثبت کنید و مشتریان بیشتری جذب کنید</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-gray-50 rounded-2xl shadow-xl p-6 sm:p-8 filter-section">
                <form id="registrationForm" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-primary">اطلاعات پایه</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">نام گیم نت *</label>
                                <input type="text" id="gamenetName" required="" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="نام گیم نت خود را وارد کنید">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">شماره موبایل *</label>
                                <input type="tel" id="phoneNumber" required="" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="09123456789">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold mb-2 text-muted">آدرس کامل *</label>
                                <textarea id="address" required="" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="آدرس کامل گیم نت خود را وارد کنید"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">منطقه *</label>
                                <select id="area" required="" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                    <option value="">انتخاب منطقه</option>
                                    <option value="north">شمال شهر</option>
                                    <option value="south">جنوب شهر</option>
                                    <option value="east">شرق شهر</option>
                                    <option value="west">غرب شهر</option>
                                    <option value="center">مرکز شهر</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">وضعیت جنسیت *</label>
                                <select id="genderStatus" required="" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                    <option value="">انتخاب کنید</option>
                                    <option value="mixed">مختلط</option>
                                    <option value="male">ویژه آقایان</option>
                                    <option value="female">ویژه بانوان</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-primary">ساعات کاری</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">ساعت شروع *</label>
                                <input type="time" id="startTime" required="" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">ساعت پایان *</label>
                                <input type="time" id="endTime" required="" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold mb-2 text-muted">روزهای تعطیل</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="saturday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">شنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="sunday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">یکشنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="monday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">دوشنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="tuesday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">سه&zwnj;شنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="wednesday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">چهارشنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="thursday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">پنج&zwnj;شنبه</span>
                                    </label>
                                    <label class="flex items-center gap-x-3 space-x-reverse">
                                        <input type="checkbox" value="friday" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">جمعه</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Age and Pricing -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-primary">شرایط سنی و قیمت</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">حداقل سن *</label>
                                <input type="number" id="minAge" required="" min="0" max="100" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="مثال: 12">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">حداکثر سن</label>
                                <input type="number" id="maxAge" min="0" max="100" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="مثال: 60">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">قیمت ساعتی (هزار تومان) *</label>
                                <input type="number" id="hourlyPrice" required="" min="1" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="مثال: 15">
                            </div>
                        </div>
                    </div>

                    <!-- Devices and Features -->
                    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm">
                        <h4 class="text-lg sm:text-xl font-semibold mb-4 text-primary">دستگاه&zwnj;ها و امکانات</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">دستگاه&zwnj;های موجود *</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="pc" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">کامپیوتر</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="ps5" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">پلی استیشن 5</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="ps4" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">پلی استیشن 4</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="xbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">ایکس باکس</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="nintendo" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">نینتندو</span>
                                    </label>
                                    <label class="flex items-center gap-x-2 space-x-reverse">
                                        <input type="checkbox" value="vr" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm">واقعیت مجازی</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-muted">امکانات اضافی</label>
                                <textarea id="additionalFeatures" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="مثال: کافی شاپ، پارکینگ، اینترنت پرسرعت، صندلی گیمینگ و..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center pt-4">
                        <button type="submit" class="bg-primary text-text-on-dark px-8 py-4 rounded-lg font-semibold text-lg hover:bg-opacity-90 transition-all transform hover:scale-105">
                            ثبت درخواست برای بررسی
                        </button>
                        <p class="text-sm text-muted mt-3">درخواست شما پس از بررسی تیم ما تایید خواهد شد</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
get_footer()
?>
</body>

</html>
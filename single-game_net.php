<?php
/* Template Name: Single Game Net */
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $post_id = get_the_ID();

        // Get post meta values
        $phone = get_post_meta($post_id, '_phone', true);
        $password = get_post_meta($post_id, '_password', true);
        $gender = get_post_meta($post_id, '_gender', true);
        $age = get_post_meta($post_id, '_age', true);
        $hours = get_post_meta($post_id, '_hours', true);
        $holiday = get_post_meta($post_id, '_holiday', true);
        $bio = get_post_meta($post_id, '_bio', true);
        $gallery_raw = get_post_meta($post_id, '_gallery_images', true);

        // Process gallery images
        $images = array();
        if (!empty($gallery_raw)) {
            $images = array_filter(array_map('intval', explode(',', $gallery_raw)));
        }

        // Get featured image as fallback
        $featured_image = get_the_post_thumbnail_url($post_id, 'large');

        // Process phone number for tel link
        $tel_digits = preg_replace('/\D+/', '', $phone);
        $tel_url = !empty($tel_digits) ? 'tel:' . $tel_digits : '';
        $whatsapp_url = !empty($tel_digits) ? 'https://wa.me/' . $tel_digits : '';

        // Query devices
        $devices = new WP_Query(array(
            'post_type' => 'device',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_game_net_id',
                    'value' => $post_id,
                    'compare' => '='
                )
            )
        ));
        ?>
        <!DOCTYPE html>
        <html dir="rtl" lang="fa-IR">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php the_title(); ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://v1.fontapi.ir/css/Vazir" rel="stylesheet">
            <style> 
                .gradient-bg {
                    background: linear-gradient(135deg, #4B3F72 0%, #8E7CC3 100%);
                }

                .bg-primary {
                    background-color: #4B3F72;
                }

                .text-primary {
                    color: #4B3F72;
                }

                .bg-secondary {
                    background-color: #8E7CC3;
                }

                .text-secondary {
                    color: #8E7CC3;
                }

                .bg-accent {
                    background-color: #FFD447;
                }

                .text-accent {
                    color: #FFD447;
                }

                .bg-surface {
                    background-color: #FFFFFF;
                }

                .text-dark {
                    color: #111827;
                }

                .text-light {
                    color: #FFFFFF;
                }

                .text-muted {
                    color: #6B7280;
                }

                .lightbox {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.9);
                    z-index: 1000;
                    justify-content: center;
                    align-items: center;
                }

                .lightbox img {
                    max-width: 90%;
                    max-height: 80%;
                    object-fit: contain;
                }

                .lightbox-nav {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                    border: none;
                    padding: 1rem;
                    cursor: pointer;
                    font-size: 2rem;
                }

                .lightbox-prev {
                    right: 10px;
                }

                .lightbox-next {
                    left: 10px;
                }

                .lightbox-close {
                    position: absolute;
                    top: 20px;
                    left: 20px;
                    color: white;
                    font-size: 2rem;
                    background: none;
                    border: none;
                    cursor: pointer;
                }

                @media (min-width: 1024px) {
                    .sticky-sidebar {
                        position: sticky;
                        top: 2rem;
                        align-self: start;
                    }
                }
            </style>
        </head>

        <body class="font-[Vazir] bg-gray-100 text-dark">
            <!-- Hero Section -->
            <section class="gradient-bg text-text-on-dark shadow-lg from-primary to-secondary py-8">
                <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center gap-6">
                    <!-- Avatar -->
                    <div class="lg:order-2">
                        <img src="<?php echo $featured_image ? esc_url($featured_image) : esc_url('https://via.placeholder.com/150'); ?>"
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            class="w-32 h-32 rounded-full ring-4 ring-white object-cover">
                    </div>

                    <!-- Title & Info -->
                    <div class="lg:order-1 text-center lg:text-right flex-1">
                        <h1 class="text-3xl font-bold mb-2"><?php the_title(); ?></h1>
                        <div class="flex items-center justify-center lg:justify-start gap-2 mb-3">
                            <span class="bg-accent text-dark px-3 py-1 rounded-full text-sm">۴.۲</span>
                            <span>امتیاز</span>
                        </div>
                    </div>
                </div>
            </section>

            <main class="container mx-auto px-4 py-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Main Content -->
                    <div class="w-full lg:w-2/3">
                        <!-- About Section -->
                        <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-2xl font-bold mb-4 border-b-2 border-primary pb-2">درباره ما</h2>
                            <p class="text-gray-700 leading-relaxed">
                                <?php echo $bio ? nl2br(esc_html($bio)) : 'توضیحی ثبت نشده است'; ?>
                            </p>
                        </section>

                        <!-- Gallery Section -->
                        <?php if (!empty($images)) : ?>
                            <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                                <h2 class="text-2xl font-bold mb-4 border-b-2 border-primary pb-2">گالری تصاویر</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 game-gallery">
                                    <?php foreach ($images as $index => $img_id) : ?>
                                        <div class="cursor-pointer">
                                            <?php echo wp_get_attachment_image($img_id, 'medium', false, [
                                                'class' => 'rounded-lg w-full h-48 object-cover',
                                                'loading' => 'lazy',
                                                'data-index' => $index
                                            ]); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php endif; ?>

                        <!-- Devices Section -->
                        <section class="bg-surface rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-2xl font-bold mb-4 border-b-2 border-primary pb-2">دستگاه ها</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php if ($devices->have_posts()) : ?>
                                    <?php while ($devices->have_posts()) : $devices->the_post(); ?>
                                        <?php
                                        $device_id = get_the_ID();
                                        $type = get_post_meta($device_id, '_type', true);
                                        $specs = get_post_meta($device_id, '_specs', true);
                                        $price = get_post_meta($device_id, '_price', true);
                                        $status = get_post_meta($device_id, '_status', true);
                                        
                                        // رنگ هر وضعیت
                                        $status_colors = [
                                            'available'   => 'bg-green-100 text-green-800',
                                            'reserved'    => 'bg-yellow-100 text-yellow-800',
                                            'maintenance' => 'bg-red-100 text-red-800',
                                            'disabled'    => 'bg-gray-100 text-gray-800'
                                        ];

                                        // برچسب فارسی هر وضعیت
                                        $status_labels = [
                                            'available'   => 'قابل استفاده',
                                            'reserved'    => 'رزرو شده',
                                            'maintenance' => 'در حال تعمیر',
                                            'disabled'    => 'غیرفعال'
                                        ];

                                        $status_class = $status_colors[$status] ?? 'bg-gray-100 text-gray-800';
                                        $status_label = $status_labels[$status] ?? $status;


                                        // Status badge colors
                                        $status_colors = [
                                            'available' => 'bg-green-100 text-green-800',
                                            'reserved' => 'bg-yellow-100 text-yellow-800',
                                            'maintenance' => 'bg-red-100 text-red-800'
                                        ];
                                        $status_class = $status_colors[$status] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                            <h3 class="text-xl font-bold mb-2"><?php the_title(); ?></h3>
                                            <div class="flex gap-2 mb-3">
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm"><?php echo esc_html($type); ?></span>
                                                <span class="<?php echo esc_attr($status_class); ?> px-2 py-1 rounded text-sm">
                                                    <?php echo esc_html($status_label); ?>
                                                </span>

                                            </div>
                                            <p class="text-muted text-sm mb-3"><?php echo esc_html($specs); ?></p>
                                            <p class="text-lg font-bold"><?php echo number_format((float)$price); ?> تومان</p>
                                        </div>
                                    <?php endwhile;
                                    wp_reset_postdata(); ?>
                                <?php else : ?>
                                    <p class="text-muted col-span-full text-center py-8">هیچ دستگاهی ثبت نشده است</p>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>

                    <!-- Sidebar -->
                    <div class="w-full lg:w-1/3">
                        <div class="sticky-sidebar">
                            <!-- Contact Card -->
                            <div class="bg-surface rounded-lg shadow-md p-6 mb-6">
                                <h3 class="text-xl font-bold mb-4">اطلاعات تماس</h3>
                                <?php if ($phone) : ?>
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-muted">تلفن:</span>
                                        <span class="font-bold"><?php echo esc_html($phone); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($hours) : ?>
                                    <div class="mb-3">
                                        <h4 class="font-bold mb-1">ساعات کاری:</h4>
                                        <p class="text-muted"><?php echo esc_html($hours); ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($holiday) : ?>
                                    <div class="mb-3">
                                        <h4 class="font-bold mb-1">تعطیلی هفتگی:</h4>
                                        <p class="text-muted"><?php echo esc_html($holiday); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Quick Actions -->
                            <div class="bg-surface rounded-lg shadow-md p-6">
                                <h3 class="text-xl font-bold mb-4">دسترسی سریع</h3>
                                <div class="space-y-3">
                                    <?php if ($tel_url) : ?>
                                        <a href="<?php echo esc_url($tel_url); ?>" class="block w-full bg-accent hover:bg-yellow-400 text-dark text-center py-2 rounded-lg transition-colors">
                                            تماس تلفنی
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Lightbox -->
            <div class="lightbox" id="lightbox">
                <button class="lightbox-close" onclick="closeLightbox()">×</button>
                <button class="lightbox-nav lightbox-prev" onclick="navigateLightbox(-1)">‹</button>
                <button class="lightbox-nav lightbox-next" onclick="navigateLightbox(1)">›</button>
                <img id="lightbox-img" src="" alt="">
            </div>

            <script>
                // Lightbox functionality
                const lightbox = document.getElementById('lightbox');
                const lightboxImg = document.getElementById('lightbox-img');
                let currentIndex = 0;
                const images = [];

                // Initialize gallery
                document.querySelectorAll('.game-gallery img').forEach((img, index) => {
                    images.push({
                        src: img.src,
                        alt: img.alt
                    });

                    img.addEventListener('click', () => {
                        openLightbox(index);
                    });
                });

                function openLightbox(index) {
                    currentIndex = index;
                    lightboxImg.src = images[currentIndex].src;
                    lightboxImg.alt = images[currentIndex].alt;
                    lightbox.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }

                function closeLightbox() {
                    lightbox.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }

                function navigateLightbox(direction) {
                    currentIndex = (currentIndex + direction + images.length) % images.length;
                    lightboxImg.src = images[currentIndex].src;
                    lightboxImg.alt = images[currentIndex].alt;
                }

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (lightbox.style.display === 'flex') {
                        if (e.key === 'Escape') closeLightbox();
                        if (e.key === 'ArrowRight') navigateLightbox(1);
                        if (e.key === 'ArrowLeft') navigateLightbox(-1);
                    }
                });

                // Close lightbox when clicking outside image
                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox) closeLightbox();
                });
            </script>
        </body>

        </html>

<?php endwhile;
endif; ?>

<?php get_footer(); ?>
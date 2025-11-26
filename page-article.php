<?php
/**
 * Template Name: Article Page
 * Description: قالب مخصوص صفحات مقاله، سازگار با ویرایشگر بلوکی
 */

get_header();
?>

<main id="site-content" role="main">
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
      <?php
        // اینجا محتوا (بلوک‌ها) از دیتابیس خونده میشه
        the_content();
      ?>
    </div>
    
  </article>
</main>

<?php
get_footer();

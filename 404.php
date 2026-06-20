<?php get_header(); ?>

<section class="error-404 not-found prose mx-auto max-w-content text-center">
    <p class="text-6xl font-heading font-bold text-primary-600">404</p>
    <h1 class="font-heading"><?php esc_html_e('This page could not be found', 'brightclick'); ?></h1>
    <p><?php esc_html_e('The page you are looking for may have been moved, removed, or never existed. Try a search instead.', 'brightclick'); ?></p>

    <div class="not-prose mt-6 flex flex-col items-center gap-4">
        <?php get_search_form(); ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700">
            &larr; <?php esc_html_e('Back to home', 'brightclick'); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>

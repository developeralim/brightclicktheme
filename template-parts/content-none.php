<?php
/**
 * Template part shown when no posts are found.
 */
?>
<section class="no-results prose mx-auto max-w-content">
    <h1 class="font-heading"><?php esc_html_e('Nothing found', 'brightclick'); ?></h1>

    <?php if (is_search()) : ?>
        <p><?php esc_html_e('Sorry, nothing matched your search terms. Please try again with different keywords.', 'brightclick'); ?></p>
        <?php get_search_form(); ?>
    <?php else : ?>
        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'brightclick'); ?></p>
        <?php get_search_form(); ?>
    <?php endif; ?>
</section>

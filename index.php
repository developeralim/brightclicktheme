<?php get_header(); ?>

<?php if (have_posts()) : ?>

    <?php if (is_home() && ! is_front_page()) : ?>
        <header class="page-header mb-wp-lg text-center">
            <span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600"><?php esc_html_e('Journal', 'brightclick'); ?></span>
            <h1 class="mt-3 font-serif text-4xl font-normal tracking-tight text-stone-900 sm:text-5xl"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <div class="post-list grid gap-wp-md sm:grid-cols-2 lg:grid-cols-3">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', get_post_type());
        endwhile;
        ?>
    </div>

    <?php
    the_posts_pagination([
        'mid_size'  => 2,
        'prev_text' => __('Previous', 'brightclick'),
        'next_text' => __('Next', 'brightclick'),
        'class'     => 'pagination mt-wp-md',
    ]);
    ?>

<?php else : ?>

    <?php get_template_part('template-parts/content', 'none'); ?>

<?php endif; ?>

<?php get_footer(); ?>
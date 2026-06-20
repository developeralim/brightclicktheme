<?php get_header(); ?>

<?php if (have_posts()) : ?>

    <header class="page-header mb-wp-lg text-center">
        <?php
        the_archive_title('<h1 class="font-serif text-4xl font-normal tracking-tight text-stone-900 sm:text-5xl">', '</h1>');
        the_archive_description('<div class="archive-description prose prose-stone mx-auto mt-4 max-w-content">', '</div>');
        ?>
    </header>

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

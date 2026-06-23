<?php get_header(); ?>

<div class="site-container py-wp-lg">

    <?php if (have_posts()) : ?>

        <header class="page-header mb-wp-lg text-center">
            <span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600">
                <?php
                if (is_category()) {
                    esc_html_e('Category', 'brightclick');
                } elseif (is_tag()) {
                    esc_html_e('Tag', 'brightclick');
                } elseif (is_author()) {
                    esc_html_e('Author', 'brightclick');
                } elseif (is_date()) {
                    esc_html_e('Archive', 'brightclick');
                } else {
                    esc_html_e('Journal', 'brightclick');
                }
                ?>
            </span>
            <?php the_archive_title('<h1 class="mt-3 font-serif text-4xl font-normal tracking-tight text-stone-900 sm:text-5xl">', '</h1>'); ?>
            <?php the_archive_description('<div class="archive-description prose prose-stone mx-auto mt-4 max-w-content text-stone-600">', '</div>'); ?>
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
            'class'     => 'pagination mt-wp-lg',
        ]);
        ?>

    <?php else : ?>

        <?php get_template_part('template-parts/content', 'none'); ?>

    <?php endif; ?>

</div>

<?php get_footer(); ?>

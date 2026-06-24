<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <div class="single-page">

        <header class="page-header site-container py-wp-md text-center">
            <h1 class="entry-title mx-auto max-w-3xl font-serif text-4xl font-normal tracking-tight text-stone-900 sm:text-5xl">
                <?php the_title(); ?>
            </h1>
        </header>

        <div class="site-container">
            <div class="entry-content prose prose-stone mx-auto max-w-content pb-wp-md">
                <?php
                the_content();

                wp_link_pages([
                    'before'      => '<nav class="page-links not-prose mt-8 flex items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-stone-500" aria-label="' . esc_attr__('Page sections', 'brightclick') . '"><span>' . esc_html__('Pages:', 'brightclick') . '</span>',
                    'after'       => '</nav>',
                    'link_before' => '<span class="page-link-number inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-stone-200 px-2">',
                    'link_after'  => '</span>',
                ]);
                ?>
            </div>
        </div>

    </div>

    <?php if (comments_open() || get_comments_number()) : ?>
        <?php comments_template(); ?>
    <?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>

<?php get_header(); ?>

<div class="site-container py-wp-lg">

    <?php if (have_posts()) : ?>

        <?php
        // Category filter — only shown on the blog home page.
        if (is_home()) :
            $filter_cats = get_categories(['hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 8]);
            if (! empty($filter_cats)) :
                $blog_url = get_permalink(get_option('page_for_posts')) ?: home_url('/');
        ?>
            <nav class="category-filter mb-wp-md flex flex-wrap items-center justify-center gap-2"
                 aria-label="<?php esc_attr_e('Filter by category', 'brightclick'); ?>">

                <a href="<?php echo esc_url($blog_url); ?>" class="category-filter__item is-active">
                    <?php esc_html_e('All', 'brightclick'); ?>
                </a>

                <?php foreach ($filter_cats as $cat) : ?>
                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                       class="category-filter__item">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>

            </nav>
        <?php
            endif;
        endif;

        // Collect posts so we can separate the hero from the grid.
        $posts_collected = [];
        while (have_posts()) :
            the_post();
            $posts_collected[] = get_post();
        endwhile;

        $hero_post  = (is_home() && ! is_paged() && ! empty($posts_collected)) ? $posts_collected[0] : null;
        $grid_posts = $hero_post ? array_slice($posts_collected, 1) : $posts_collected;
        ?>

        <?php if ($hero_post) : ?>
            <?php
            global $post;
            $post = $hero_post;
            setup_postdata($post);
            ?>
            <?php get_template_part('template-parts/content', 'featured'); ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <?php if (! empty($grid_posts)) : ?>
            <div class="post-list grid gap-wp-md sm:grid-cols-2 lg:grid-cols-3">
                <?php
                foreach ($grid_posts as $post) :
                    setup_postdata($post);
                    get_template_part('template-parts/content', get_post_type());
                endforeach;
                wp_reset_postdata();
                ?>
            </div>
        <?php endif; ?>

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

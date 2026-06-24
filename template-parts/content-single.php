<?php
/**
 * Template part for displaying a single post (editorial layout).
 */

$cats = get_the_category();
$tags = get_the_tags();
?>

<div class="single-post">

    <div class="single-hero site-container py-wp-md text-center">
        <?php if (! empty($cats)) : ?>
            <span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600">
                <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>" class="transition-colors hover:text-primary-700">
                    <?php echo esc_html($cats[0]->name); ?>
                </a>
            </span>
        <?php endif; ?>

        <h1 class="entry-title mx-auto mt-4 max-w-3xl font-serif text-4xl font-normal leading-tight tracking-tight text-stone-900 sm:text-5xl">
            <?php the_title(); ?>
        </h1>

        <div class="entry-meta mt-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-[11px] uppercase tracking-[0.2em] text-stone-400">
            <?php brightclick_entry_meta(); ?>
        </div>
    </div>

    <?php if (has_post_thumbnail()) : ?>
        <figure class="single-featured site-container mt-wp-sm">
            <?php the_post_thumbnail('hero', [
                'class' => 'w-full rounded-xl object-cover',
            ]); ?>
        </figure>
    <?php endif; ?>

    <div class="site-container">

        <div class="entry-content prose prose-stone mx-auto max-w-content py-wp-md">
            <?php
            the_content();

            wp_link_pages([
                'before'      => '<nav class="page-links not-prose mt-8 flex items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-stone-500" aria-label="' . esc_attr__('Post pages', 'brightclick') . '"><span>' . esc_html__('Pages:', 'brightclick') . '</span>',
                'after'       => '</nav>',
                'link_before' => '<span class="page-link-number inline-flex h-7 min-w-7 items-center justify-center rounded-full border border-stone-200 px-2">',
                'link_after'  => '</span>',
            ]);
            ?>
        </div>

        <?php if (! empty($tags)) : ?>
            <div class="entry-tags mx-auto mt-wp-sm flex max-w-content flex-wrap items-center gap-2 border-t border-stone-100 pt-wp-sm">
                <span class="text-[11px] uppercase tracking-[0.25em] text-stone-400"><?php esc_html_e('Tagged', 'brightclick'); ?></span>
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                       class="rounded-full border border-stone-200 px-3 py-1 text-[11px] uppercase tracking-[0.15em] text-stone-600 transition-colors hover:border-stone-900 hover:text-stone-900">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="share-links mx-auto max-w-content <?php echo ! empty($tags) ? 'mt-6' : 'mt-wp-sm border-t border-stone-100 pt-wp-sm'; ?>">
            <span class="text-[11px] uppercase tracking-[0.25em] text-stone-400"><?php esc_html_e('Share', 'brightclick'); ?></span>
            <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode(get_permalink()); ?>&amp;text=<?php echo rawurlencode(get_the_title()); ?>"
               target="_blank" rel="noopener noreferrer"
               class="share-link" aria-label="<?php esc_attr_e('Share on X / Twitter', 'brightclick'); ?>">
                <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
                <?php esc_html_e('X / Twitter', 'brightclick'); ?>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode(get_permalink()); ?>"
               target="_blank" rel="noopener noreferrer"
               class="share-link" aria-label="<?php esc_attr_e('Share on Facebook', 'brightclick'); ?>">
                <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                <?php esc_html_e('Facebook', 'brightclick'); ?>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode(get_permalink()); ?>"
               target="_blank" rel="noopener noreferrer"
               class="share-link" aria-label="<?php esc_attr_e('Share on LinkedIn', 'brightclick'); ?>">
                <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                <?php esc_html_e('LinkedIn', 'brightclick'); ?>
            </a>
        </div>

        <?php $author_bio = get_the_author_meta('description'); ?>
        <?php if ($author_bio) : ?>
            <aside class="author-bio mx-auto mt-wp-md flex max-w-content flex-col items-center gap-4 border-t border-stone-100 pt-wp-md text-center sm:flex-row sm:items-start sm:text-left">
                <div class="author-bio__avatar shrink-0">
                    <?php echo get_avatar(get_the_author_meta('ID'), 72, '', '', ['class' => 'rounded-full']); ?>
                </div>
                <div class="author-bio__body">
                    <span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600"><?php esc_html_e('Written by', 'brightclick'); ?></span>
                    <h2 class="mt-1 font-serif text-xl font-normal text-stone-900"><?php the_author(); ?></h2>
                    <p class="mt-2 text-sm leading-relaxed text-stone-600"><?php echo esc_html($author_bio); ?></p>
                </div>
            </aside>
        <?php endif; ?>

    </div>
</div>

<?php
$related_query = new WP_Query([
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post__not_in'        => [get_the_ID()],
    'category__in'        => wp_get_post_categories(get_the_ID()),
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => 1,
]);

if ($related_query->have_posts()) :
?>
    <section class="site-container mt-wp-lg border-t border-stone-100 pt-wp-md" aria-labelledby="related-posts-heading">
        <h2 id="related-posts-heading" class="related-posts__divider mb-wp-sm">
            <?php esc_html_e('Related Articles', 'brightclick'); ?>
        </h2>
        <div class="grid gap-wp-md sm:grid-cols-2 lg:grid-cols-3">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                <?php get_template_part('template-parts/content', get_post_type()); ?>
            <?php endwhile; ?>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php
the_post_navigation([
    'prev_text' => '<span class="post-nav__label">' . esc_html__('Previous', 'brightclick') . '</span><span class="post-nav__title">%title</span>',
    'next_text' => '<span class="post-nav__label">' . esc_html__('Next', 'brightclick') . '</span><span class="post-nav__title">%title</span>',
    'class'     => 'post-navigation site-container mt-wp-md border-t border-stone-100 pt-wp-md',
]);
?>

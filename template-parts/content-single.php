<?php
/**
 * Template part for displaying a single post or page (editorial layout).
 */

$is_post = ('post' === get_post_type());
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

    <header class="entry-header mx-auto max-w-content text-center">
        <?php if ($is_post) : ?>
            <?php $cats = get_the_category(); ?>
            <?php if (! empty($cats)) : ?>
                <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"
                   class="entry-eyebrow inline-block text-[11px] font-light uppercase tracking-[0.25em] text-primary-600 hover:text-primary-700">
                    <?php echo esc_html($cats[0]->name); ?>
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <h1 class="entry-title mt-4 font-serif text-4xl font-normal leading-tight tracking-tight text-stone-900 sm:text-5xl">
            <?php the_title(); ?>
        </h1>

        <?php if ($is_post) : ?>
            <div class="entry-meta mt-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-[11px] uppercase tracking-[0.2em] text-stone-400">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                <span aria-hidden="true">&middot;</span>
                <span><?php echo esc_html(get_the_author()); ?></span>
            </div>
        <?php endif; ?>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <figure class="featured-image mt-wp-md">
            <?php the_post_thumbnail('wide', ['class' => 'mx-auto w-full max-w-wide rounded-lg object-cover']); ?>
        </figure>
    <?php endif; ?>

    <div class="entry-content prose prose-stone mx-auto mt-wp-md max-w-content">
        <?php
        the_content();

        wp_link_pages([
            'before' => '<div class="page-links mt-6 flex flex-wrap items-center gap-2 text-sm">' . esc_html__('Pages:', 'brightclick') . ' ',
            'after'  => '</div>',
        ]);
        ?>
    </div>

    <?php if (has_tag()) : ?>
        <footer class="entry-footer mx-auto mt-wp-md max-w-content border-t border-stone-100 pt-6">
            <div class="tags flex flex-wrap items-center gap-2 text-[11px] uppercase tracking-[0.2em] text-stone-400">
                <?php the_tags('', '', ''); ?>
            </div>
        </footer>
    <?php endif; ?>
</article>

<?php if ($is_post) : ?>
    <nav class="post-navigation mx-auto mt-wp-lg flex max-w-content items-stretch justify-between gap-6 border-t border-stone-100 pt-8 text-sm" aria-label="<?php esc_attr_e('Posts', 'brightclick'); ?>">
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        ?>
        <div class="flex-1">
            <?php if ($prev) : ?>
                <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="group block">
                    <span class="block text-[11px] uppercase tracking-[0.25em] text-stone-400">&larr; <?php esc_html_e('Previous', 'brightclick'); ?></span>
                    <span class="mt-1 block font-serif text-lg text-stone-900 group-hover:text-primary-700"><?php echo esc_html(get_the_title($prev)); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <div class="flex-1 text-right">
            <?php if ($next) : ?>
                <a href="<?php echo esc_url(get_permalink($next)); ?>" class="group block">
                    <span class="block text-[11px] uppercase tracking-[0.25em] text-stone-400"><?php esc_html_e('Next', 'brightclick'); ?> &rarr;</span>
                    <span class="mt-1 block font-serif text-lg text-stone-900 group-hover:text-primary-700"><?php echo esc_html(get_the_title($next)); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>

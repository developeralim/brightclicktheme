<?php
/**
 * Template part for displaying a single post or page (editorial layout).
 */

$is_post = is_singular('post');
?>

<?php if ($is_post) : ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

        <header class="single-hero site-container py-wp-md text-center">
            <?php $cats = get_the_category(); ?>
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
        </header>

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

            <?php
            $tags = get_the_tags();
            if (! empty($tags)) :
                ?>
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
    </article>

    <?php
    the_post_navigation([
        'prev_text' => '<span class="post-nav__label">' . esc_html__('Previous', 'brightclick') . '</span><span class="post-nav__title">%title</span>',
        'next_text' => '<span class="post-nav__label">' . esc_html__('Next', 'brightclick') . '</span><span class="post-nav__title">%title</span>',
        'class'     => 'post-navigation site-container mt-wp-md border-t border-stone-100 pt-wp-md',
    ]);
    ?>

<?php elseif (is_front_page()) : ?>

    <?php // Front page: builder-driven, render full-bleed so blocks control their own width. ?>
    <div class="entry-content">
        <?php the_content(); ?>
    </div>

<?php else : ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-page'); ?>>
        <header class="page-header site-container mb-wp-md py-wp-md text-center">
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
    </article>

<?php endif; ?>

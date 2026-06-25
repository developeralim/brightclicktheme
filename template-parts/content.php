<?php

/**
 * Template part for displaying a post in a list/archive context (editorial card).
 */

$is_post = ('post' === get_post_type());
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-card group flex flex-col'); ?>>

    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" class="post-card__media block overflow-hidden rounded-lg bg-stone-100" aria-hidden="true" tabindex="-1">
            <?php the_post_thumbnail('card', [
                'class' => 'aspect-[4/3] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105',
            ]); ?>
        </a>
    <?php endif; ?>

    <div class="post-card__body mt-5 flex flex-1 flex-col">
        <?php if ($is_post) : ?>
            <?php $cats = get_the_category(); ?>
            <?php if (! empty($cats)) : ?>
                <span class="text-[11px] font-light uppercase tracking-[0.25em] text-primary-600">
                    <?php echo esc_html($cats[0]->name); ?>
                </span>
            <?php endif; ?>
        <?php endif; ?>

        <h2 class="entry-title mt-2 font-serif text-2xl font-normal leading-snug text-stone-900">
            <a href="<?php the_permalink(); ?>" class="transition-colors hover:text-primary-700"><?php the_title(); ?></a>
        </h2>

        <?php if ($is_post) : ?>
            <div class="entry-meta mt-2 text-[11px] uppercase tracking-[0.2em] text-stone-400">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            </div>
        <?php endif; ?>

        <div class="entry-summary mt-3 flex-1 text-sm leading-relaxed text-stone-600">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="post-card__more mt-5 inline-flex items-center gap-2 self-start text-[11px] font-medium uppercase tracking-[0.25em] text-stone-900">
            <?php esc_html_e('Read more', 'brightclick'); ?>
            <span aria-hidden="true" class="transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
        </a>
    </div>
</article>
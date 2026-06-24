<?php
/**
 * Template part for displaying the featured/hero post at the top of archive pages.
 */

$cats         = get_the_category();
$reading_time = brightclick_reading_time();
?>
<div class="hero-post group mb-wp-md">

    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>"
           class="hero-post__media block overflow-hidden rounded-xl bg-stone-100"
           aria-hidden="true" tabindex="-1">
            <?php the_post_thumbnail('wide', [
                'class' => 'aspect-[16/7] w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]',
            ]); ?>
        </a>
    <?php endif; ?>

    <div class="mt-7 grid gap-8 lg:grid-cols-5 lg:items-end">

        <div class="lg:col-span-3">
            <?php if (! empty($cats)) : ?>
                <span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600">
                    <a href="<?php echo esc_url(get_category_link($cats[0]->term_id)); ?>"
                       class="transition-colors hover:text-primary-700">
                        <?php echo esc_html($cats[0]->name); ?>
                    </a>
                </span>
            <?php endif; ?>

            <h2 class="mt-3 font-serif text-3xl font-normal leading-tight tracking-tight text-stone-900 sm:text-4xl lg:text-5xl">
                <a href="<?php the_permalink(); ?>" class="transition-colors hover:text-primary-700">
                    <?php the_title(); ?>
                </a>
            </h2>
        </div>

        <div class="flex flex-col gap-5 lg:col-span-2">
            <div class="text-sm leading-relaxed text-stone-600">
                <?php the_excerpt(); ?>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-stone-100 pt-5">
                <div class="text-[11px] uppercase tracking-[0.2em] text-stone-400">
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    <span class="mx-2" aria-hidden="true">&middot;</span>
                    <span><?php echo esc_html($reading_time); ?> <?php esc_html_e('min read', 'brightclick'); ?></span>
                </div>
                <a href="<?php the_permalink(); ?>"
                   class="inline-flex items-center gap-2 text-[11px] font-medium uppercase tracking-[0.25em] text-stone-900 transition-colors hover:text-primary-700">
                    <?php esc_html_e('Read article', 'brightclick'); ?>
                    <span aria-hidden="true" class="transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        </div>

    </div>
</div>

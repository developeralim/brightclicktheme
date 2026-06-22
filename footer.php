</main><!-- #primary -->

<?php
$footer_scheme  = get_theme_mod('footer_scheme', 'light');
$footer_bg      = get_theme_mod('footer_bg_color', '#ffffff');
$footer_logo    = get_theme_mod('header_logo_desktop', '');
$footer_tagline = get_theme_mod('footer_tagline', '');
if ($footer_tagline === '') {
    $footer_tagline = get_bloginfo('description', 'display');
}
?>
<footer class="site-footer footer-scheme-<?php echo esc_attr($footer_scheme); ?>"
    style="background-color: <?php echo esc_attr($footer_bg); ?>;">
    <div class="site-container py-wp-lg">

        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widgets footer-divider mb-wp-md grid gap-8 border-b pb-wp-md sm:grid-cols-2 lg:grid-cols-3">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col items-center gap-8 text-center">
            <?php if (get_theme_mod('footer_show_logo', true)) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="footer-brand group inline-flex flex-col items-center">
                    <?php if ($footer_logo) : ?>
                        <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-10 w-auto" />
                    <?php else : ?>
                        <span class="font-serif text-2xl font-normal tracking-[0.3em] sm:text-3xl"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                    <?php if ($footer_tagline) : ?>
                        <span class="footer-tagline mt-2 font-sans text-[10px] uppercase tracking-[0.4em]"><?php echo esc_html($footer_tagline); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <?php if (has_nav_menu('footer')) : ?>
                <nav class="footer-nav" aria-label="<?php esc_attr_e('Footer', 'brightclick'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

            <?php brightclick_footer_socials('gap-6'); ?>
        </div>

        <div class="footer-divider mt-wp-md flex flex-col items-center justify-between gap-4 border-t pt-8 text-[11px] uppercase tracking-[0.2em] sm:flex-row">
            <p class="footer-copyright"><?php brightclick_footer_copyright(); ?></p>
            <a href="#site-header" class="footer-top opacity-70 transition-opacity hover:opacity-100"><?php esc_html_e('Back to top', 'brightclick'); ?></a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

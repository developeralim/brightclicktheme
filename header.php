<?php
$logo_position = get_theme_mod('header_logo_position', 'left');
$is_center     = ('center' === $logo_position);
$sticky_class  = get_theme_mod('header_sticky', true) ? 'sticky top-0' : 'relative';

$nav_args = [
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => '',
    'fallback_cb'    => false,
    'depth'          => 2,
];
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-body font-body text-gray-800 antialiased'); ?>>
    <?php wp_body_open(); ?>

    <a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[60] focus:rounded focus:bg-white focus:px-4 focus:py-2 focus:shadow"
        href="#primary"><?php esc_html_e('Skip to content', 'brightclick'); ?></a>

    <header id="site-header" class="site-header <?php echo esc_attr($sticky_class); ?> z-50 w-full border-b border-stone-100">
        <div class="site-container relative flex h-24 items-center justify-between gap-6">

            <!-- Left column: mobile toggle + desktop-left content -->
            <div class="flex items-center gap-10">
                <?php brightclick_menu_toggle('lg:hidden'); ?>

                <?php if ($is_center) : ?>
                    <nav class="site-nav hidden lg:block" aria-label="<?php esc_attr_e('Primary', 'brightclick'); ?>">
                        <?php wp_nav_menu($nav_args); ?>
                    </nav>
                <?php else : ?>
                    <div class="site-branding hidden lg:block"><?php brightclick_header_logo(); ?></div>
                <?php endif; ?>
            </div>

            <!-- Center column (logo, when centered — desktop only) -->
            <?php if ($is_center) : ?>
                <div class="site-branding absolute left-1/2 top-1/2 hidden -translate-x-1/2 -translate-y-1/2 lg:block">
                    <?php brightclick_header_logo(); ?>
                </div>
            <?php endif; ?>

            <!-- Right column: desktop nav/buttons + right-aligned mobile logo -->
            <div class="flex items-center gap-6 sm:gap-8">
                <?php if (! $is_center) : ?>
                    <nav class="site-nav hidden lg:block" aria-label="<?php esc_attr_e('Primary', 'brightclick'); ?>">
                        <?php wp_nav_menu($nav_args); ?>
                    </nav>
                <?php endif; ?>

                <?php brightclick_header_buttons('hidden items-center gap-4 lg:flex'); ?>

                <div class="site-branding lg:hidden"><?php brightclick_header_logo(); ?></div>
            </div>
        </div>

        <!-- Mobile menu panel -->
        <div id="mobile-menu" class="hidden border-t border-stone-100 bg-surface lg:hidden">
            <div class="site-container space-y-6 py-6">
                <nav class="site-nav-mobile" aria-label="<?php esc_attr_e('Mobile', 'brightclick'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => '',
                        'fallback_cb'    => false,
                        'depth'          => 2,
                    ]);
                    ?>
                </nav>
                <?php brightclick_header_buttons('flex flex-col gap-3 border-t border-stone-100 pt-6'); ?>
            </div>
        </div>
    </header>

    <main id="primary" class="site-main">

<?php

declare(strict_types=1);

define('THEME_VERSION', wp_get_theme()->get('Version'));
define('THEME_DIR',     get_template_directory());
define('THEME_URI',     get_template_directory_uri());
define('DIST_DIR',      THEME_DIR . '/dist');
define('DIST_URI',      THEME_URI . '/dist');
define('IS_DEV',        defined('WP_DEBUG') && WP_DEBUG && file_exists(DIST_DIR . '/.vite/hot'));
define('VITE_DEV_SERVER', 'http://localhost:5173');

add_action('after_setup_theme', function () {

    load_theme_textdomain('brightclick', THEME_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_theme_support('appearance-tools');
    add_theme_support('block-template-parts');

    register_nav_menus([
        'primary' => __('Primary Navigation', 'brightclick'),
        'footer'  => __('Footer Navigation', 'brightclick'),
    ]);

    add_image_size('card', 640, 480, true);
    add_image_size('hero', 1920, 800, true);
    add_image_size('wide', 1440, 600, true);
});


add_action('wp_head', function () {
    $primary_500 = get_theme_mod('primary_500', '#3b82f6');

    $font_heading = get_theme_mod('font_heading', 'Inter');
    $font_body = get_theme_mod('font_body', 'Roboto');
    $font_mono = get_theme_mod('font_mono', 'Fira Code');

    $web_safe_fonts = ['system-ui', 'Georgia', 'Times New Roman', 'Arial', 'Helvetica', 'monospace'];
    $google_fonts = [];

    if (!in_array($font_heading, $web_safe_fonts) && !empty($font_heading)) {
        $google_fonts[] = $font_heading;
    }
    if (!in_array($font_body, $web_safe_fonts) && !empty($font_body)) {
        $google_fonts[] = $font_body;
    }
    if (!in_array($font_mono, $web_safe_fonts) && !empty($font_mono)) {
        $google_fonts[] = $font_mono;
    }

    if (!empty($google_fonts)) {
        $unique_fonts = array_unique($google_fonts);
        $font_family_string = implode('|', $unique_fonts);
        ?>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=<?php echo esc_attr($font_family_string); ?>:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <?php
    }
    ?>
    <style>
        :root {
            /* COLORS */
            --color-primary-50: <?php echo esc_html(get_theme_mod('primary_50', '#eff6ff')); ?>;
            --color-primary-100: <?php echo esc_html(get_theme_mod('primary_100', '#dbeafe')); ?>;
            --color-primary-500: <?php echo esc_html($primary_500); ?>;
            --color-primary-600: <?php echo esc_html(get_theme_mod('primary_600', '#2563eb')); ?>;
            --color-primary-700: <?php echo esc_html(get_theme_mod('primary_700', '#1d4ed8')); ?>;
            --color-primary-900: <?php echo esc_html(get_theme_mod('primary_900', '#1e3a8a')); ?>;

            --color-accent: <?php echo esc_html(get_theme_mod('accent_color', '#f59e0b')); ?>;
            --color-surface: <?php echo esc_html(get_theme_mod('surface_color', '#ffffff')); ?>;
            --color-muted: <?php echo esc_html(get_theme_mod('muted_color', '#f3f4f6')); ?>;
            --color-body-bg: <?php echo esc_html(get_theme_mod('body_background_color', '#fbfbfb')); ?>;

            /* HEADER */
            --header-bg: <?php echo esc_html(get_theme_mod('header_bg_color', '#ffffff')); ?>;

            /* TYPOGRAPHY */
            --font-heading: <?php echo esc_html($font_heading); ?>;
            --font-body: <?php echo esc_html($font_body); ?>;
            --font-mono: <?php echo esc_html($font_mono); ?>;

            /* LAYOUT */
            --wp--style--global--content-size: <?php echo esc_html(get_theme_mod('content_width', '65ch')); ?>;
            --wp--style--global--wide-size: <?php echo esc_html(get_theme_mod('wide_width', '90rem')); ?>;
            --wp--style--global--site-size: <?php echo esc_html(get_theme_mod('site_width', '1440px')); ?>;
        }
    </style>
<?php
});

require_once THEME_DIR . '/customizer/settings.customizer.php';
require_once THEME_DIR . '/customizer/header.customizer.php';
require_once THEME_DIR . '/customizer/footer.customizer.php';

function theme_vite_asset(string $entry): array
{
    static $manifest = null;

    if ($manifest === null) {
        $manifest_path = DIST_DIR . '/.vite/manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = [];
        }
    }

    $asset_url = isset($manifest[$entry]['file']) 
        ? DIST_URI . '/' . $manifest[$entry]['file'] 
        : '';

    return ['url' => $asset_url];
}

function theme_get_vite_css_files(string $entry): array
{
    static $manifest = null;

    if ($manifest === null) {
        $manifest_path = DIST_DIR . '/.vite/manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = [];
        }
    }

    $css_files = [];
    
    if (isset($manifest[$entry]['css']) && is_array($manifest[$entry]['css'])) {
        foreach ($manifest[$entry]['css'] as $css_file) {
            $css_files[] = DIST_URI . '/' . $css_file;
        }
    }

    return $css_files;
}

/**
 * Enqueue frontend assets
 */
add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style(
        'theme-icons',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css',
        [],
        null
    );

    if (! IS_DEV) {
        $main_js = theme_vite_asset('assets/js/main.js');

        if (!empty($main_js['url'])) {
            wp_enqueue_script_module(
                'theme-main',
                $main_js['url'],
                [],
                null,
                []
            );
        }

        $css_files = theme_get_vite_css_files('assets/js/main.js');

        foreach ($css_files as $index => $css_url) {
            wp_enqueue_style(
                "theme-main-css-{$index}",
                $css_url,
                [],
                THEME_VERSION
            );
        }
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
});

add_action('enqueue_block_editor_assets', function (): void {
    $editor_js = theme_vite_asset('assets/js/editor.js');
    
    if (!empty($editor_js['url'])) {
        wp_enqueue_script_module(
            'theme-editor',
            $editor_js['url'],
            [],
            null,
            []
        );
    }

    $css_files = theme_get_vite_css_files('assets/js/editor.js');
    
    foreach ($css_files as $css_url) {
        add_editor_style($css_url);
    }
});


function theme_vite_manifest_exists(): bool
{
    return file_exists(DIST_DIR . '/.vite/manifest.json');
}

function theme_get_vite_entries(): array
{
    $manifest_path = DIST_DIR . '/.vite/manifest.json';

    if (!file_exists($manifest_path)) {
        return [];
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    return is_array($manifest) ? $manifest : [];
}

add_action('wp_head', function (): void {
    if (! IS_DEV) {
        return;
    }
    $server = esc_url(VITE_DEV_SERVER);
    printf('<script type="module" src="%s/@vite/client"></script>' . "\n", $server);
    printf('<script type="module" src="%s/assets/js/main.js"></script>' . "\n", $server);
}, 1);

/**
 * Register widget areas.
 */
add_action('widgets_init', function (): void {
    register_sidebar([
        'name'          => __('Sidebar', 'brightclick'),
        'id'            => 'sidebar-1',
        'description'   => __('Widgets shown in the primary sidebar.', 'brightclick'),
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title text-lg font-heading font-semibold mb-3">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => __('Footer', 'brightclick'),
        'id'            => 'footer-1',
        'description'   => __('Widgets shown in the footer.', 'brightclick'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title text-base font-heading font-semibold mb-3">',
        'after_title'   => '</h2>',
    ]);
});

/**
 * Trim the excerpt "read more" string to an ellipsis.
 */
add_filter('excerpt_more', fn(): string => '&hellip;');


add_action('customize_controls_enqueue_scripts', function (): void {
    wp_enqueue_style(
        'brightclick-customizer',
        THEME_URI . '/assets/css/customizer.css',
        [],
        THEME_VERSION
    );

    wp_enqueue_script(
        'brightclick-customizer',
        THEME_URI . '/assets/js/customizer.js',
        ['customize-controls'],
        THEME_VERSION,
        true
    );

    wp_localize_script('brightclick-customizer', 'bcCustomizer', [
        'selectPage' => __('— Select a page —', 'brightclick'),
        'button'     => __('Button', 'brightclick'),
        'remove'     => __('Remove', 'brightclick'),
        'buttonText' => __('Button text', 'brightclick'),
        'newTab'     => __('Open in new tab', 'brightclick'),
        'styleLabel' => __('Style', 'brightclick'),
        'styles'     => brightclick_header_button_styles(),
    ]);
});
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


/**
 * Google Font family names selected in the Customizer (web-safe fonts excluded).
 *
 * @return string[]
 */
function brightclick_google_font_families(): array
{
    $web_safe_fonts = ['system-ui', 'Georgia', 'Times New Roman', 'Arial', 'Helvetica', 'monospace'];

    $fonts = [];
    foreach ([
        'font_heading' => 'Bebas Neue',
        'font_body'    => 'Jost',
        'font_mono'    => 'Fira Code',
    ] as $mod => $default) {
        $font = get_theme_mod($mod, $default);
        if (!empty($font) && !in_array($font, $web_safe_fonts, true)) {
            $fonts[] = $font;
        }
    }

    return array_values(array_unique($fonts));
}

/**
 * Build a valid Google Fonts css2 stylesheet URL for the active families.
 *
 * The css2 endpoint requires one `family=` parameter per font (joined with
 * `&`), not the legacy pipe-separated list. A single shared weight range is
 * requested per family; Google clamps it to the weights each font actually
 * ships (e.g. Bebas Neue → 400), so display faces load without a 400 error.
 */
function brightclick_google_fonts_url(): string
{
    $families = brightclick_google_font_families();

    if (empty($families)) {
        return '';
    }

    $weights = 'wght@100;200;300;400;500;600;700;800;900';

    $params = array_map(
        static fn (string $family): string =>
            'family=' . str_replace('%20', '+', rawurlencode($family)) . ':' . $weights,
        $families
    );

    return 'https://fonts.googleapis.com/css2?' . implode('&', $params) . '&display=swap';
}

/**
 * The Design System :root{} custom properties built from the Customizer.
 *
 * Shared by the frontend (wp_head) and the block editor canvas so blocks that
 * read these tokens (via Tailwind) render identically in both places.
 */
function brightclick_design_tokens_css(): string
{
    $font_heading = get_theme_mod('font_heading', 'Bebas Neue');
    $font_body    = get_theme_mod('font_body', 'Jost');
    $font_mono    = get_theme_mod('font_mono', 'Fira Code');

    ob_start();
    ?>
        :root {
            /* COLORS */
            --color-primary-50: <?php echo esc_html(get_theme_mod('primary_50', '#eff6ff')); ?>;
            --color-primary-100: <?php echo esc_html(get_theme_mod('primary_100', '#dbeafe')); ?>;
            --color-primary-500: <?php echo esc_html(get_theme_mod('primary_500', '#3b82f6')); ?>;
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
    <?php
    return (string) ob_get_clean();
}

add_action('wp_head', function () {
    $fonts_url = brightclick_google_fonts_url();

    if (!empty($fonts_url)) {
        ?>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="<?php echo esc_url($fonts_url); ?>" rel="stylesheet">
        <?php
    }
    ?>
    <style><?php echo brightclick_design_tokens_css(); ?></style>
<?php
});

/**
 * Inject the same Design System tokens (and webfonts) into the block editor
 * canvas. Using block_editor_settings_all reaches the editor iframe, which a
 * plain enqueue_block_editor_assets stylesheet does not.
 */
add_filter('block_editor_settings_all', function (array $settings): array {
    $css = brightclick_design_tokens_css();

    $fonts_url = brightclick_google_fonts_url();
    if (!empty($fonts_url)) {
        $css = "@import url('{$fonts_url}');\n" . $css;
    }

    $settings['styles'][] = ['css' => $css];

    return $settings;
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

/**
 * Estimate the reading time of a post in whole minutes (min. 1).
 */
function brightclick_reading_time(?int $post_id = null): int
{
    $content = get_post_field('post_content', $post_id ?: get_the_ID());
    $words   = str_word_count(wp_strip_all_tags((string) $content));

    return max(1, (int) ceil($words / 200));
}

/**
 * Output the editorial meta line for a single post: date, author, reading time.
 */
function brightclick_entry_meta(): void
{
    $items = [];

    $items[] = sprintf(
        '<time datetime="%s">%s</time>',
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );

    $items[] = sprintf(
        /* translators: %s: post author name. */
        esc_html__('By %s', 'brightclick'),
        esc_html(get_the_author())
    );

    $items[] = sprintf(
        /* translators: %d: estimated reading time in minutes. */
        esc_html(_n('%d min read', '%d min read', brightclick_reading_time(), 'brightclick')),
        brightclick_reading_time()
    );

    echo '<span class="entry-meta-sep">' . implode('</span><span aria-hidden="true" class="entry-meta-dot">&middot;</span><span class="entry-meta-sep">', $items) . '</span>';
}


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
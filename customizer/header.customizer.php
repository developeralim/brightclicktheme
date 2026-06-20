<?php 

add_action('customize_register', function ($wp_customize) {
    require_once THEME_DIR . '/inc/class-brightclick-header-buttons-control.php';

    $wp_customize->add_section('header_section', [
        'title'       => __('Header', 'brightclick'),
        'priority'    => 22,
        'description' => __('Logos, layout and call-to-action buttons for the site header.', 'brightclick'),
    ]);

    $wp_customize->add_setting('header_logo_desktop', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_logo_desktop', [
        'label'       => __('Desktop Logo', 'brightclick'),
        'section'     => 'header_section',
        'description' => __('Shown on tablet and desktop. Falls back to the site name if empty.', 'brightclick'),
    ]));

    $wp_customize->add_setting('header_logo_mobile', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_logo_mobile', [
        'label'       => __('Mobile Logo', 'brightclick'),
        'section'     => 'header_section',
        'description' => __('Optional. Shown on small screens; falls back to the desktop logo.', 'brightclick'),
    ]));

    $wp_customize->add_setting('header_logo_position', [
        'default'           => 'left',
        'transport'         => 'refresh',
        'sanitize_callback' => fn($v) => in_array($v, ['left', 'center'], true) ? $v : 'left',
    ]);

    $wp_customize->add_control('header_logo_position', [
        'label'       => __('Logo Position', 'brightclick'),
        'section'     => 'header_section',
        'type'        => 'radio',
        'choices'     => [
            'left'   => __('Left (menu &amp; buttons on the right)', 'brightclick'),
            'center' => __('Center (menu left, buttons right)', 'brightclick'),
        ],
    ]);

    $wp_customize->add_setting('header_sticky', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => fn($v) => (bool) $v,
    ]);
    $wp_customize->add_control('header_sticky', [
        'label'   => __('Sticky Header', 'brightclick'),
        'section' => 'header_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('header_bg_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', [
        'label'       => __('Header Background', 'brightclick'),
        'section'     => 'header_section',
        'description' => __('Solid at the top of the page; becomes a translucent frosted-glass effect on scroll.', 'brightclick'),
    ]));

    $wp_customize->add_setting('header_buttons', [
        'default'           => '[]',
        'transport'         => 'refresh',
        'sanitize_callback' => 'brightclick_sanitize_header_buttons',
    ]);
    $wp_customize->add_control(new Brightclick_Header_Buttons_Control($wp_customize, 'header_buttons', [
        'label'       => __('Header Buttons', 'brightclick'),
        'section'     => 'header_section',
        'description' => __('Add as many call-to-action buttons as you like. Each links to a page you select.', 'brightclick'),
    ]));
});

function brightclick_header_button_styles(): array
{
    return [
        'premium' => __('Premium (outline, fills on hover)', 'brightclick'),
        'solid'   => __('Solid', 'brightclick'),
        'outline' => __('Outline', 'brightclick'),
        'minimal' => __('Minimal (text link)', 'brightclick'),
        'pill'    => __('Pill (primary color)', 'brightclick'),
    ];
}

function brightclick_sanitize_header_buttons($value): string
{
    $decoded = json_decode((string) $value, true);

    if (! is_array($decoded)) {
        return '[]';
    }

    $styles = array_keys(brightclick_header_button_styles());
    $clean  = [];

    foreach ($decoded as $row) {
        if (! is_array($row)) {
            continue;
        }
        $text = isset($row['text']) ? sanitize_text_field((string) $row['text']) : '';
        if ($text === '') {
            continue;
        }

        $style = $row['style'] ?? 'premium';

        $clean[] = [
            'text'    => $text,
            'page'    => isset($row['page']) ? absint($row['page']) : 0,
            'new_tab' => empty($row['new_tab']) ? 0 : 1,
            'style'   => in_array($style, $styles, true) ? $style : 'premium',
        ];
    }

    return wp_json_encode($clean);
}

function brightclick_header_logo(): void
{
    $desktop = get_theme_mod('header_logo_desktop', '');
    $mobile  = get_theme_mod('header_logo_mobile', '');
    $home    = esc_url(home_url('/'));
    $name    = get_bloginfo('name');
    $tagline = get_bloginfo('description', 'display');

    echo '<a href="' . $home . '" rel="home" class="site-logo group inline-flex flex-col items-center text-center" aria-label="' . esc_attr($name) . '">';

    if (! $desktop && ! $mobile) {
        echo '<span class="font-serif text-2xl font-normal tracking-[0.3em] text-stone-900 transition-colors duration-300 group-hover:text-stone-600 sm:text-3xl">' . esc_html($name) . '</span>';
        if ($tagline) {
            echo '<span class="mt-1 pl-1 font-sans text-[8px] uppercase tracking-[0.5em] text-stone-400 transition-colors duration-300 group-hover:text-stone-500">' . esc_html($tagline) . '</span>';
        }
    } else {
        $d = $desktop ?: $mobile;
        $m = $mobile ?: $desktop;

        if ($d === $m) {
            echo '<img src="' . esc_url($d) . '" alt="' . esc_attr($name) . '" class="h-10 w-auto" />';
        } else {
            echo '<img src="' . esc_url($d) . '" alt="' . esc_attr($name) . '" class="hidden h-10 w-auto md:block" />';
            echo '<img src="' . esc_url($m) . '" alt="' . esc_attr($name) . '" class="h-10 w-auto md:hidden" />';
        }
    }

    echo '</a>';
}

function brightclick_header_buttons(string $wrapper_class = ''): void
{
    $rows = json_decode((string) get_theme_mod('header_buttons', '[]'), true);
    if (! is_array($rows)) {
        $rows = [];
    }

    $styles  = array_keys(brightclick_header_button_styles());
    $buttons = [];

    foreach ($rows as $row) {
        $text = isset($row['text']) ? trim((string) $row['text']) : '';
        if ($text === '') {
            continue;
        }
        $page_id = isset($row['page']) ? (int) $row['page'] : 0;
        $url     = $page_id ? get_permalink($page_id) : '';
        $style   = $row['style'] ?? 'premium';

        $buttons[] = [
            'text'    => $text,
            'url'     => $url ?: '#',
            'new_tab' => ! empty($row['new_tab']),
            'style'   => in_array($style, $styles, true) ? $style : 'premium',
        ];
    }

    if (! $buttons) {
        return;
    }

    echo '<div class="' . esc_attr($wrapper_class) . '">';

    foreach ($buttons as $b) {
        $target = $b['new_tab'] ? ' target="_blank" rel="noopener noreferrer"' : '';
        echo '<a href="' . esc_url($b['url'] ?: '#') . '" class="bc-btn bc-btn--' . esc_attr($b['style']) . '"' . $target . '>'
            . '<span>' . esc_html($b['text']) . '</span></a>';
    }
    echo '</div>';
}


function brightclick_menu_toggle(string $extra = 'md:hidden'): void
{
    ?>
    <button type="button" data-menu-toggle aria-controls="mobile-menu" aria-expanded="false"
        class="<?php echo esc_attr($extra); ?> inline-flex items-center justify-center rounded-md p-2 text-gray-700 transition-colors hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500">
        <span class="sr-only"><?php esc_html_e('Toggle menu', 'brightclick'); ?></span>
        <svg data-menu-icon-open class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
        <svg data-menu-icon-close class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>
    <?php
}
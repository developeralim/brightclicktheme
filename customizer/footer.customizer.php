<?php

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('footer_section', [
        'title'       => __('Footer', 'brightclick'),
        'priority'    => 24,
        'description' => __('Branding, navigation, social links and colors for the site footer.', 'brightclick'),
    ]);

    $wp_customize->add_setting('footer_bg_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', [
        'label'   => __('Footer Background', 'brightclick'),
        'section' => 'footer_section',
    ]));

    $wp_customize->add_setting('footer_scheme', [
        'default'           => 'light',
        'transport'         => 'refresh',
        'sanitize_callback' => fn($v) => in_array($v, ['light', 'dark'], true) ? $v : 'light',
    ]);
    $wp_customize->add_control('footer_scheme', [
        'label'       => __('Footer Text Scheme', 'brightclick'),
        'section'     => 'footer_section',
        'type'        => 'radio',
        'choices'     => [
            'light' => __('Dark text (for light backgrounds)', 'brightclick'),
            'dark'  => __('Light text (for dark backgrounds)', 'brightclick'),
        ],
    ]);

    $wp_customize->add_setting('footer_show_logo', [
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => fn($v) => (bool) $v,
    ]);
    $wp_customize->add_control('footer_show_logo', [
        'label'   => __('Show Logo / Site Name', 'brightclick'),
        'section' => 'footer_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('footer_tagline', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('footer_tagline', [
        'label'       => __('Tagline', 'brightclick'),
        'section'     => 'footer_section',
        'type'        => 'text',
        'description' => __('Short line shown under the logo. Falls back to the site tagline.', 'brightclick'),
    ]);

    $wp_customize->add_setting('footer_copyright', [
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('footer_copyright', [
        'label'       => __('Copyright Text', 'brightclick'),
        'section'     => 'footer_section',
        'type'        => 'text',
        'description' => __('Leave empty for an automatic "© year, site name" line.', 'brightclick'),
    ]);

    foreach (brightclick_footer_social_networks() as $key => $network) {
        $wp_customize->add_setting("footer_social_{$key}", [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control("footer_social_{$key}", [
            /* translators: %s: social network name. */
            'label'   => sprintf(__('%s URL', 'brightclick'), $network['label']),
            'section' => 'footer_section',
            'type'    => 'url',
        ]);
    }
});

function brightclick_footer_social_networks(): array
{
    return [
        'instagram' => ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram'],
        'facebook'  => ['label' => 'Facebook',  'icon' => 'fa-brands fa-facebook-f'],
        'x'         => ['label' => 'X (Twitter)', 'icon' => 'fa-brands fa-x-twitter'],
        'youtube'   => ['label' => 'YouTube',   'icon' => 'fa-brands fa-youtube'],
        'tiktok'    => ['label' => 'TikTok',    'icon' => 'fa-brands fa-tiktok'],
        'linkedin'  => ['label' => 'LinkedIn',  'icon' => 'fa-brands fa-linkedin-in'],
    ];
}

/**
 * Echo the footer social icon links for any networks that have a URL set.
 */
function brightclick_footer_socials(string $wrapper_class = ''): void
{
    $links = [];
    foreach (brightclick_footer_social_networks() as $key => $network) {
        $url = get_theme_mod("footer_social_{$key}", '');
        if ($url) {
            $links[] = ['url' => $url, 'icon' => $network['icon'], 'label' => $network['label']];
        }
    }

    if (! $links) {
        return;
    }

    echo '<ul class="footer-socials ' . esc_attr($wrapper_class) . '">';
    foreach ($links as $link) {
        echo '<li><a href="' . esc_url($link['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($link['label']) . '">'
            . '<i class="' . esc_attr($link['icon']) . '" aria-hidden="true"></i></a></li>';
    }
    echo '</ul>';
}

/**
 * Echo the footer copyright line (custom text, or an automatic fallback).
 */
function brightclick_footer_copyright(): void
{
    $custom = get_theme_mod('footer_copyright', '');

    if ($custom !== '') {
        echo wp_kses_post($custom);
        return;
    }

    printf(
        /* translators: 1: current year, 2: site name. */
        esc_html__('© %1$s %2$s. All rights reserved.', 'brightclick'),
        esc_html(date_i18n('Y')),
        esc_html(get_bloginfo('name'))
    );
}

<?php
add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_panel('design_system', [
        'title'     => 'Design System',
        'priority'  => 10,
        'description' => 'Configure your design system settings',
    ]);

    //----------------- Colors -----------------//
    $wp_customize->add_section('color_section', [
        'title' => 'Colors',
        'panel' => 'design_system',
        'description' => 'Configure color palette for your theme',
    ]);

    $colors = ['50', '100', '500', '600', '700', '900'];

    foreach ($colors as $shade) {
        $wp_customize->add_setting("primary_$shade", [
            'default'           => $shade == '500' ? '#3b82f6' : '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                "primary_$shade",
                [
                    'label'       => "Primary $shade",
                    'section'     => 'color_section',
                    'description' => $shade == '500' ? 'Default primary color' : '',
                ]
            )
        );
    }

    $wp_customize->add_setting('accent_color', [
        'default'           => '#f59e0b',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'accent_color',
            [
                'label'   => 'Accent Color',
                'section' => 'color_section',
            ]
        )
    );

    $wp_customize->add_setting('surface_color', [
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'surface_color',
            [
                'label'   => 'Surface Color',
                'section' => 'color_section',
            ]
        )
    );

    $wp_customize->add_setting('muted_color', [
        'default'           => '#f3f4f6',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'muted_color',
            [
                'label'   => 'Muted Color',
                'section' => 'color_section',
            ]
        )
    );

    $wp_customize->add_setting('body_background_color', [
        'default'           => '#fbfbfb',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'body_background_color',
            [
                'label'   => 'Background Color',
                'section' => 'color_section',
            ]
        )
    );

    //------------------------- Typography Section -------------------//
    $wp_customize->add_section('typography_section', [
        'title' => 'Typography',
        'panel' => 'design_system',
        'description' => 'Configure font settings for your theme',
    ]);

    $google_fonts = [
        '' => '— Select Font —',
        'Inter' => 'Inter',
        'Roboto' => 'Roboto',
        'Open Sans' => 'Open Sans',
        'Lato' => 'Lato',
        'Montserrat' => 'Montserrat',
        'Poppins' => 'Poppins',
        'Nunito' => 'Nunito',
        'Raleway' => 'Raleway',
        'Playfair Display' => 'Playfair Display',
        'Merriweather' => 'Merriweather',
        'Source Sans Pro' => 'Source Sans Pro',
        'Oswald' => 'Oswald',
        'Bebas Neue' => 'Bebas Neue',
        'Jost' => 'Jost',
        'Cormorant Garamond' => 'Cormorant Garamond',
        'Roboto Condensed' => 'Roboto Condensed',
        'Noto Sans' => 'Noto Sans',
        'DM Sans' => 'DM Sans',
        'Work Sans' => 'Work Sans',
        'Fira Sans' => 'Fira Sans',
        'Quicksand' => 'Quicksand',
        'Manrope' => 'Manrope',
        'system-ui' => 'System UI',
        'Georgia' => 'Georgia',
        'Times New Roman' => 'Times New Roman',
        'Arial' => 'Arial',
        'Helvetica' => 'Helvetica',
    ];

    $wp_customize->add_setting('font_heading', [
        'default'           => 'Bebas Neue',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('font_heading', [
        'label'       => 'Heading Font',
        'section'     => 'typography_section',
        'type'        => 'select',
        'choices'     => $google_fonts,
        'description' => 'Select font for headings',
    ]);

    $wp_customize->add_setting('font_body', [
        'default'           => 'Jost',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('font_body', [
        'label'       => 'Body Font',
        'section'     => 'typography_section',
        'type'        => 'select',
        'choices'     => $google_fonts,
        'description' => 'Select font for body text',
    ]);

    $wp_customize->add_setting('font_mono', [
        'default'           => 'Fira Code',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('font_mono', [
        'label'       => 'Mono Font',
        'section'     => 'typography_section',
        'type'        => 'select',
        'choices'     => [
            '' => '— Select Font —',
            'Fira Code' => 'Fira Code',
            'JetBrains Mono' => 'JetBrains Mono',
            'Monaco' => 'Monaco',
            'Menlo' => 'Menlo',
            'Consolas' => 'Consolas',
            'Courier New' => 'Courier New',
            'monospace' => 'Monospace',
        ],
        'description' => 'Select font for code and monospace text',
    ]);

    $wp_customize->add_setting('font_weight_heading', [
        'default'           => '700',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('font_weight_heading', [
        'label'       => 'Heading Font Weight',
        'section'     => 'typography_section',
        'type'        => 'select',
        'choices'     => [
            '100' => 'Thin 100',
            '200' => 'Extra Light 200',
            '300' => 'Light 300',
            '400' => 'Regular 400',
            '500' => 'Medium 500',
            '600' => 'Semi Bold 600',
            '700' => 'Bold 700',
            '800' => 'Extra Bold 800',
            '900' => 'Black 900',
        ],
    ]);

    $wp_customize->add_setting('font_weight_body', [
        'default'           => '400',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('font_weight_body', [
        'label'       => 'Body Font Weight',
        'section'     => 'typography_section',
        'type'        => 'select',
        'choices'     => [
            '100' => 'Thin 100',
            '200' => 'Extra Light 200',
            '300' => 'Light 300',
            '400' => 'Regular 400',
            '500' => 'Medium 500',
            '600' => 'Semi Bold 600',
            '700' => 'Bold 700',
            '800' => 'Extra Bold 800',
            '900' => 'Black 900',
        ],
    ]);

    //------------------------------- Layout Section ------------------------//
    $wp_customize->add_section('layout_section', [
        'title' => 'Layout',
        'panel' => 'design_system',
        'description' => 'Configure layout dimensions for your theme',
    ]);

    $wp_customize->add_setting('content_width', [
        'default'           => '65ch',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('content_width', [
        'label'       => 'Content Width',
        'section'     => 'layout_section',
        'type'        => 'text',
        'description' => 'Maximum width for content (e.g., 65ch, 800px, 100%)',
        'input_attrs' => [
            'placeholder' => '65ch',
        ],
    ]);

    $wp_customize->add_setting('wide_width', [
        'default'           => '90rem',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('wide_width', [
        'label'       => 'Wide Width',
        'section'     => 'layout_section',
        'type'        => 'text',
        'description' => 'Maximum width for wide elements (e.g., 90rem, 1200px, 100%)',
        'input_attrs' => [
            'placeholder' => '90rem',
        ],
    ]);

    $wp_customize->add_setting('site_width', [
        'default'           => '1440px',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('site_width', [
        'label'       => 'Site Width',
        'section'     => 'layout_section',
        'type'        => 'text',
        'description' => 'Maximum width for the entire site (e.g., 1440px, 100%)',
        'input_attrs' => [
            'placeholder' => '1440px',
        ],
    ]);
});

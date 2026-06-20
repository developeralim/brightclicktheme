<?php

declare(strict_types=1);

if (! class_exists('WP_Customize_Control')) {
    return;
}

class Brightclick_Header_Buttons_Control extends WP_Customize_Control
{
    public $type = 'brightclick_header_buttons';

    public function render_content(): void
    {
        $pages   = get_pages(['sort_column' => 'menu_order,post_title', 'post_status' => 'publish']);
        $choices = [];

        foreach ($pages as $page) {
            $title     = $page->post_title !== '' ? $page->post_title : sprintf(__('(no title) #%d', 'brightclick'), $page->ID);
            $choices[] = ['id' => (int) $page->ID, 'title' => $title];
        }

        $value = $this->value();
        if (! is_string($value) || $value === '') {
            $value = '[]';
        }
        ?>
        <div class="bc-header-buttons-control" data-pages="<?php echo esc_attr(wp_json_encode($choices)); ?>">
            <?php if (! empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (! empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>

            <ul class="bc-buttons-list"></ul>

            <button type="button" class="button bc-add-button"><?php esc_html_e('+ Add Button', 'brightclick'); ?></button>

            <input type="hidden" class="bc-buttons-value" value="<?php echo esc_attr($value); ?>" <?php $this->link(); ?> />
        </div>
        <?php
    }
}

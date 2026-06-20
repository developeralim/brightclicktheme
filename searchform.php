<?php
/**
 * Custom search form.
 */
$unique_id = wp_unique_id('search-form-');
?>
<form role="search" method="get" class="search-form flex items-stretch gap-2 max-w-md w-full" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>" class="sr-only"><?php esc_html_e('Search for:', 'brightclick'); ?></label>
    <input
        type="search"
        id="<?php echo esc_attr($unique_id); ?>"
        class="search-field flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary-500 focus:ring-primary-500"
        placeholder="<?php esc_attr_e('Search&hellip;', 'brightclick'); ?>"
        value="<?php echo get_search_query(); ?>"
        name="s"
    />
    <button type="submit" class="search-submit rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
        <?php esc_html_e('Search', 'brightclick'); ?>
    </button>
</form>

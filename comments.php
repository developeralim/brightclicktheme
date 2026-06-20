<?php
/**
 * The comments template (editorial styling).
 */

if (post_password_required()) {
    return;
}

$field_classes = 'comment-input mt-2 w-full rounded-md border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:border-primary-500 focus:ring-primary-500';
$label_classes = 'block text-[11px] uppercase tracking-[0.2em] text-stone-500';
$commenter     = wp_get_current_commenter();
$req           = get_option('require_name_email');
?>

<div id="comments" class="comments-area mx-auto mt-wp-lg max-w-content border-t border-stone-100 pt-wp-md">

    <?php if (have_comments()) : ?>
        <header class="comments-header mb-wp-sm">
            <span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600"><?php esc_html_e('Conversation', 'brightclick'); ?></span>
            <h2 class="comments-title mt-2 font-serif text-3xl font-normal text-stone-900">
                <?php
                $comment_count = get_comments_number();
                if ('1' === (string) $comment_count) {
                    esc_html_e('One comment', 'brightclick');
                } else {
                    /* translators: %s: comment count. */
                    printf(esc_html(_n('%s comment', '%s comments', $comment_count, 'brightclick')), number_format_i18n($comment_count));
                }
                ?>
            </h2>
        </header>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
            ]);
            ?>
        </ol>

        <?php
        the_comments_pagination([
            'prev_text' => __('Previous', 'brightclick'),
            'next_text' => __('Next', 'brightclick'),
            'class'     => 'comments-pagination mt-wp-sm',
        ]);
        ?>

    <?php endif; ?>

    <?php if (! comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments text-sm uppercase tracking-[0.2em] text-stone-400"><?php esc_html_e('Comments are closed.', 'brightclick'); ?></p>
    <?php endif; ?>

    <?php
    comment_form([
        'class_form'           => 'comment-form mt-wp-md space-y-5',
        'class_submit'         => 'comment-submit',
        'title_reply_before'   => '<span class="block text-[11px] font-light uppercase tracking-[0.3em] text-primary-600">' . esc_html__('Join in', 'brightclick') . '</span><h3 id="reply-title" class="comment-reply-title mt-2 font-serif text-3xl font-normal text-stone-900">',
        'title_reply_after'    => '</h3>',
        'comment_notes_before' => '<p class="comment-notes mt-2 text-sm text-stone-500">' . esc_html__('Your email address will not be published.', 'brightclick') . '</p>',
        'label_submit'         => __('Post Comment', 'brightclick'),
        'comment_field'        => '<p class="comment-form-comment"><label for="comment" class="' . esc_attr($label_classes) . '">' . esc_html__('Comment', 'brightclick') . '</label>'
            . '<textarea id="comment" name="comment" rows="5" required class="' . esc_attr($field_classes) . '"></textarea></p>',
        'fields'               => [
            'author' => '<p class="comment-form-author"><label for="author" class="' . esc_attr($label_classes) . '">' . esc_html__('Name', 'brightclick') . ($req ? ' *' : '') . '</label>'
                . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" ' . ($req ? 'required' : '') . ' class="' . esc_attr($field_classes) . '" /></p>',
            'email'  => '<p class="comment-form-email"><label for="email" class="' . esc_attr($label_classes) . '">' . esc_html__('Email', 'brightclick') . ($req ? ' *' : '') . '</label>'
                . '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" ' . ($req ? 'required' : '') . ' class="' . esc_attr($field_classes) . '" /></p>',
            'url'    => '<p class="comment-form-url"><label for="url" class="' . esc_attr($label_classes) . '">' . esc_html__('Website', 'brightclick') . '</label>'
                . '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" class="' . esc_attr($field_classes) . '" /></p>',
        ],
    ]);
    ?>

</div>

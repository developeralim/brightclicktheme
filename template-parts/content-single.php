<?php
    /**
     * Template part for displaying a single post or page (editorial layout).
     */

    $is_post = ('post' === get_post_type());
?>

<?php the_content(); ?>
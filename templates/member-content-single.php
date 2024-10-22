<?php
// Template for displaying a single member content post
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content', get_post_format());

            if (is_user_logged_in()) {
                the_content();
            } else {
                echo '<p>You need to <a href="' . esc_url(wp_login_url()) . '">login</a> to view the full content.</p>';
            }

        endwhile;
        ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();

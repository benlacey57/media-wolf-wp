<?php
/**
 * Template Name: Full Width Page
 * Description: A full-width page template without a sidebar.
 */

get_header(); ?>

<div id="primary" class="content-area full-width">
    <main id="main" class="site-main">
        <?php
        // Start the loop.
        while (have_posts()) : the_post();
            // Display the content
            get_template_part('template-parts/content', 'page');
        endwhile; // End the loop.
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();

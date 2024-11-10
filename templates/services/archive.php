<?php
// Custom archive template for Services
get_header(); ?>

<div class="services-archive">
    <h1><?php post_type_archive_title(); ?></h1>

    <?php if (have_posts()) : ?>
        <div class="services">
            <?php while (have_posts()) : the_post(); ?>
                <div class="service">
                    <a href="<?php the_permalink(); ?>">
                        <h2><?php the_title(); ?></h2>
                        <?php the_excerpt(); ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p><?php _e('No services found.', 'media-wolf'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
<main class="cars">
    <div class="sidebar">
        <?php get_template_part('template-parts/sidebar'); ?>
        <div>
            <?php get_header(); ?>
            <div>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/cars/car'); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
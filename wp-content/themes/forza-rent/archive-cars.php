<?php get_header(); ?>
<main class="cars">
    <div class="container">
        <div>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/cars/car'); ?>
            <?php endwhile; ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>
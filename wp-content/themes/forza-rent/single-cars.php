<?php get_header(); ?>

<main>
    <?php
    get_template_part('template-parts/cars/single-car/hero');
    get_template_part('template-parts/cars/single-car/reservation');
    get_template_part('template-parts/cars/single-car/price-list');
    get_template_part('template-parts/cars/related');
    ?>
</main>


<?php get_footer(); ?>
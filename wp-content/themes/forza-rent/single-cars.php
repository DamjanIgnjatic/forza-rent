<?php get_header(); ?>

<main>
    <?php
    get_template_part('template-parts/cars/single-car/hero');
    get_template_part('template-parts/cars/single-car/price-list');
    get_template_part('template-parts/cars/related');
    get_template_part('template-parts/cars/booking-form');
    ?>
</main>


<?php get_footer(); ?>
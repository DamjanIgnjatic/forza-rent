<?php
$cars_query = new WP_Query([
    'post_type'      => 'cars',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
]);
?>
<section class="section-cars">
    <div class="container">
        <div class="section-cars--title">
            <h2><?php post_type_archive_title(); ?></h2>
        </div>
        <div class="grid">
            <?php
            if ($cars_query->have_posts()) :
                while ($cars_query->have_posts()) : $cars_query->the_post();
                    get_template_part('template-parts/cars/car');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No cars found.</p>';
            endif;
            ?>
        </div>
        <div class="cars-actions">
            <button id="loadMoreCars" style="border: none;" class="btn-forza primary">Show more</button>
        </div>

        <p class="no-results" style="display:none;">No cars match your filters.</p>
    </div>
</section>
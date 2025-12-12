<?php
$current_id = get_the_ID();
$terms = wp_get_post_terms($current_id, 'car-category');
$term_ids = [];

if (!empty($terms)) {
    $term_ids = wp_list_pluck($terms, 'term_id');
}

if (!empty($term_ids)) {
    $related_args = [
        'post_type'      => 'cars',
        'posts_per_page' => 4,
        'post__not_in'   => [$current_id],
        'tax_query'      => [
            [
                'taxonomy' => 'car-category',
                'field'    => 'term_id',
                'terms'    => $term_ids,
            ]
        ]
    ];
} else {
    $related_args = [
        'post_type'      => 'cars',
        'posts_per_page' => 4,
        'post__not_in'   => [$current_id],
        'orderby'        => 'rand',
    ];
}

$related_cars = new WP_Query($related_args);
$all_cars_link = get_post_type_archive_link('cars');
?>

<?php if ($related_cars->have_posts()): ?>
    <section class="section-related-cars animated left-to-right">

        <div class="container">
            <div class="section-related-cars--title">
                <h2>Slična Vozila</h2>
                <a href="<?php echo esc_url($all_cars_link); ?>" class="view-all">
                    Pogledaj Sve
                </a>
            </div>
            <div class="section-related-cars--box">
                <?php while ($related_cars->have_posts()): $related_cars->the_post(); ?>

                    <?php
                    get_template_part('template-parts/cars/car');
                    ?>

                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
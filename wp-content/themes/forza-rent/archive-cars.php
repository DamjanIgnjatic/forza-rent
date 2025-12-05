<?php
$all_cars = new WP_Query([
    'post_type'      => 'cars',
    'posts_per_page' => -1,
    'fields'         => 'ids'
]);

$min_price = PHP_INT_MAX;
$max_price = 0;

$car_categories = get_terms([
    'taxonomy' => 'car-model',
    'hide_empty' => false
]);

$fuel_type = get_terms([
    'taxonomy' => 'fuel-type',
    'hide_empty' => false
]);

$gearbox_type = get_terms([
    'taxonomy' => 'gearbox-type',
    'hide_empty' => false
]);

foreach ($all_cars->posts as $car_id) {
    $price          = get_field('price', $car_id);
    $discount_price = get_field('discount_price', $car_id);
    $final_price    = $price ?: $discount_price;

    if ($final_price < $min_price) {
        $min_price = $final_price;
    }
    if ($final_price > $max_price) {
        $max_price = $final_price;
    }
}
?>


<?php get_header(); ?>
<main class="cars">
    <div class="container">
        <div class="class-picker">
            <p class="car-category active" data-filter="all">All</p>

            <?php foreach ($car_categories as $category): ?>
                <p class="car-category" data-filter="<?php echo esc_attr($category->slug); ?>">
                    <?php echo esc_html($category->name); ?>
                </p>
            <?php endforeach; ?>
        </div>

        <div class="advanced-filters">
            <div class="filter-group">
                <label for="fuelFilter">Fuel type</label>
                <select id="fuelFilter">
                    <option value="">Any</option>
                    <?php foreach ($fuel_type as $fuel): ?>
                        <option value="<?php echo esc_attr($fuel->slug); ?>">
                            <?php echo esc_html($fuel->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="gearboxFilter">Gearbox</label>
                <select id="gearboxFilter">
                    <option value="">Any</option>
                    <?php foreach ($gearbox_type as $gearbox): ?>
                        <option value="<?php echo esc_attr($gearbox->slug); ?>">
                            <?php echo esc_html($gearbox->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group price-group">
                <label for="priceRange">Price</label>
                <div class="price-slider">
                    <input type="range"
                        id="priceRange"
                        min="<?php echo esc_attr($min_price); ?>"
                        max="<?php echo esc_attr($max_price); ?>"
                        step="10"
                        value="<?php echo esc_attr($min_price); ?>" />
                    <span class="price-label"
                        data-min="<?php echo $min_price; ?>"
                        data-max="<?php echo $max_price; ?>">Max: €<?php echo $max_price; ?></span>
                </div>
            </div>

        </div>
        <div class="grid">
            <?php
            $cars_query = new WP_Query([
                'post_type'      => 'cars',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]);

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
            <button id="loadMoreCars" class="btn-forza primary">Show more</button>
        </div>
    </div>
</main>
<?php get_footer(); ?>
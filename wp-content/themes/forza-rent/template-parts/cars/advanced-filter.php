<?php

$all_cars = new WP_Query([
    'post_type'      => 'cars',
    'posts_per_page' => -1,
    'fields'         => 'ids'
]);

$gearbox_type = get_terms([
    'taxonomy' => 'gearbox-type',
    'hide_empty' => false
]);

$car_categories = get_terms([
    'taxonomy' => 'car-category',
    'hide_empty' => false
]);

$min_price = PHP_INT_MAX;
$max_price = 0;
$start_year = 1990;
$current_year = date('Y');

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
<section class="section-advanced-filter">
    <div class="container">
        <div class="section-advanced-filter--title">
            <h2>Filter cars</h2>
        </div>

        <div class="advanced-filters section-advanced-filter--box">
            <div class="advanced-filters--filter">
                <div class="filter-group">
                    <label for="category">Category</label>
                    <select id="category">
                        <option value="">Any</option>
                        <?php foreach ($car_categories as $category): ?>
                            <option value="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="yearFilter">Production Year</label>
                    <select id="yearFilter">
                        <option value="">Any</option>
                        <?php
                        for ($y = $current_year; $y >= $start_year; $y--) : ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                        <?php endfor; ?>
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

        </div>
    </div>
</section>
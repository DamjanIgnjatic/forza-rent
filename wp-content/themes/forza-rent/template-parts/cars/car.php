<?php
$car = $args['car'] ?? get_post();
$id  = $car->ID;

// Taxonomies
$gear_terms = wp_get_post_terms($id, 'gearbox-type');
$car_type_terms = wp_get_post_terms($id, 'car-type');
$car_category_terms = wp_get_post_terms($id, 'car-category');

// Taxonomy values
$gearbox    = $gear_terms ? $gear_terms[0]->name : null;
$type       = $car_type_terms ? $car_type_terms[0]->name : null;
$type_slug = $car_type_terms ? $car_type_terms[0]->slug : null;
$car_category_slug = $car_category_terms ? $car_category_terms[0]->slug : null;

// Acf Values
$production_year = get_field('production_year', $id);
$capacity       = get_field('capacity', $id);
$price          = get_field('price', $id);
$discount_price = get_field('discount_price', $id);

// links and images
$link           = get_permalink($id);
$image = get_the_post_thumbnail_url($id, 'large');

$filter_price = $price ?: $discount_price;

?>
<div class="car-card" data-type="<?php echo esc_attr($type_slug); ?>"
    data-year="<?php echo strtolower(($production_year)); ?>"
    data-gearbox="<?php echo strtolower(($gearbox)); ?>"
    data-price="<?php echo $filter_price; ?>"
    data-category="<?php echo esc_attr($car_category_slug); ?>">
    <a class="single-link" href="<?php echo esc_url($link); ?>"></a>
    <h3 class="car-title"><?php echo get_the_title($id); ?></h3>
    <?php if ($type): ?>
        <p class="car-class"><?php echo $type ?></p>
    <?php endif; ?>

    <div class="car-details">
        <?php if ($image): ?>
            <div class="car-image">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo get_the_title($id); ?>" />
            </div>
        <?php endif; ?>
        <div class="specs">
            <div class="spec-icons">
                <span class="fuel-icon">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="8" y="8" width="48" height="48" rx="6" fill="#FFF" stroke="#90a3bf" stroke-width="2" />

                        <rect x="8" y="8" width="48" height="12" fill="#90A3BF" />

                        <circle cx="18" cy="14" r="2" fill="#FFF" />
                        <circle cx="32" cy="14" r="2" fill="#FFF" />
                        <circle cx="46" cy="14" r="2" fill="#FFF" />

                        <g transform="translate(16, 30)">
                            <rect x="4" y="6" width="24" height="8" rx="2" fill="#90A3BF" />
                            <path d="M8 6 L12 2 H20 L24 6 Z" fill="#90A3BF" />
                            <circle cx="8" cy="16" r="3" fill="#90A3BF" />
                            <circle cx="24" cy="16" r="3" fill="#90A3BF" />
                        </g>
                    </svg>

                </span>
                <p><?php echo $production_year  ?></p>
            </div>
            <div class="spec-icons">
                <span class="fuel-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.53 2 12 2Z" fill="#90A3BF" />
                        <rect x="4" y="4" width="16" height="16" rx="8" fill="white" />
                        <path d="M12 6C8.688 6 6 8.688 6 12C6 15.312 8.688 18 12 18C15.312 18 18 15.312 18 12C18 8.688 15.318 6 12 6Z" fill="#90A3BF" />
                        <rect x="8" y="8" width="8" height="8" rx="4" fill="white" />
                        <rect x="11" y="17" width="2" height="4" fill="#90A3BF" />
                        <rect x="17" y="11" width="4" height="2" fill="#90A3BF" />
                        <rect x="3" y="11" width="4" height="2" fill="#90A3BF" />
                    </svg>
                </span>
                <p><?php echo $gearbox ?></p>
            </div>
            <div class="spec-icons">
                <span class="fuel-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2C6.38 2 4.25 4.13 4.25 6.75C4.25 9.32 6.26 11.4 8.88 11.49C8.96 11.48 9.04 11.48 9.1 11.49C9.12 11.49 9.13 11.49 9.15 11.49C9.16 11.49 9.16 11.49 9.17 11.49C11.73 11.4 13.74 9.32 13.75 6.75C13.75 4.13 11.62 2 9 2Z" fill="#90A3BF" />
                        <path d="M14.08 14.15C11.29 12.29 6.73996 12.29 3.92996 14.15C2.65996 15 1.95996 16.15 1.95996 17.38C1.95996 18.61 2.65996 19.75 3.91996 20.59C5.31996 21.53 7.15996 22 8.99996 22C10.84 22 12.68 21.53 14.08 20.59C15.34 19.74 16.04 18.6 16.04 17.36C16.03 16.13 15.34 14.99 14.08 14.15Z" fill="#90A3BF" />
                        <path d="M19.99 7.34C20.15 9.28 18.77 10.98 16.86 11.21C16.85 11.21 16.85 11.21 16.84 11.21H16.81C16.75 11.21 16.69 11.21 16.64 11.23C15.67 11.28 14.78 10.97 14.11 10.4C15.14 9.48 15.73 8.1 15.61 6.6C15.54 5.79 15.26 5.05 14.84 4.42C15.22 4.23 15.66 4.11 16.11 4.07C18.07 3.9 19.82 5.36 19.99 7.34Z" fill="#90A3BF" />
                        <path d="M21.99 16.59C21.91 17.56 21.29 18.4 20.25 18.97C19.25 19.52 17.99 19.78 16.74 19.75C17.46 19.1 17.88 18.29 17.96 17.43C18.06 16.19 17.47 15 16.29 14.05C15.62 13.52 14.84 13.1 13.99 12.79C16.2 12.15 18.98 12.58 20.69 13.96C21.61 14.7 22.08 15.63 21.99 16.59Z" fill="#90A3BF" />
                    </svg>
                </span>
                <p><?php echo $capacity ?></p>
            </div>
        </div>
        <div class="price-container">
            <div class="price">
                <p class="discount-price">€ <?php echo $price ?>/day</p>
                <p class="old-price">€ <?php echo $discount_price ?>/<span class="gray-text">day</span></p>
            </div>
            <a class="btn-forza primary">Rent now</a>
        </div>
    </div>
</div>
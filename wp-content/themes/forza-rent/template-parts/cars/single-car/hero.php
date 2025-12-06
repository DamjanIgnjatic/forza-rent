<?php
$car = $args['car'] ?? get_post();
$id  = $car->ID;

// Taxonomies
$fuel_terms = wp_get_post_terms($id, 'fuel-type');
$gear_terms = wp_get_post_terms($id, 'gearbox-type');
$car_type_terms = wp_get_post_terms($id, 'car-type');
$car_category_terms = wp_get_post_terms($id, 'car-model');

// Taxonomy values
$production_year  = 2020;
$gearbox    = $gear_terms ? $gear_terms[0]->name : null;
$car_type       = $car_type_terms ? $car_type_terms[0]->name : null;

// Acf Values
$capacity       = get_field('capacity', $id);
$price          = get_field('price', $id);
$discount_price = get_field('discount_price', $id);
$car_gallery = get_field('car_gallery', $id);
$discount_price = get_field('discount_price', $id);
$car_description = get_field('car_description', $id);

// links and images
$link           = get_permalink($id);
$image = get_the_post_thumbnail_url($id, 'large');
?>

<section class="section-single-hero">
    <div class="container">
        <div class="section-single-hero--box">
            <div class="car-gallery">
                <div class="car-gallery-featured">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo get_the_title($id); ?>" />
                </div>


                <div class="car-gallery-items">
                    <div class="item featured-image active">
                        <img src="<?php echo esc_url($image); ?>" alt="">
                    </div>
                    <?php if (!empty($car_gallery)): ?>
                        <?php foreach ($car_gallery as $row): ?>
                            <?php $img = $row['car_image'] ?? null; ?>
                            <?php if ($img): ?>
                                <div class="item">
                                    <img src="<?php echo esc_url($img['url']); ?>" alt="">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="car-information <?php echo $discount_price ? "discount" : "" ?>">
                <div class="car-information--title">
                    <h2><?php echo get_the_title($id); ?></h2>
                    <?php echo $car_description ?>
                </div>
                <div class="car-information--box">
                    <div class="info-wrapper">
                        <?php if ($car_type): ?>
                            <div>
                                <span>Car Type:</span>
                                <p class="car-class"><?php echo $car_type ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($capacity): ?>
                            <div>
                                <span>Capacity:</span>
                                <p class="car-class"><?php echo $capacity ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="info-wrapper">
                        <?php if ($gearbox): ?>
                            <div>
                                <span>Gearbox:</span>
                                <p class="car-class"><?php echo $gearbox ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($production_year): ?>
                            <div>
                                <span>Production Year:</span>
                                <p class="car-class"><?php echo $production_year ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="car-information--price">
                    <div>
                        <p class="price-info">€<?php echo $price ?>/<span>day</span></p>
                        <p class="price-discount">€<?php echo $discount_price ?></p>
                    </div>

                    <a class="btn-forza primary">Rent now</a>
                </div>
            </div>


        </div>
    </div>
</section>
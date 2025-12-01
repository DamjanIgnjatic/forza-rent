<?php
// Load data
$name = get_field("name");
$type = get_field("type");
$image = get_field("image");
$gearbox = get_field("gearbox");
$fuel_type = get_field("fuel_type");
$capacity = get_field("capacity");
$price = get_field("price");
$discount_price = get_field("discount_price");
$link = get_field("link");

?>

<div class="car-card">
    <p><?php echo $name ?></p>
    <p><?php echo $type ?></p>
    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
    <p><?php echo $gearbox ?></p>
    <p><?php echo $fuel_type  ?></p>
    <p><?php echo $capacity ?></p>
    <p><?php echo $price ?></p>
    <p><?php echo $discount_price ?></p>
</div>
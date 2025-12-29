<?php
$car = $args['car'] ?? get_post();
$id  = $car->ID;

$reservation_form = get_field('reservation_form', $id);
?>
<?php if ($reservation_form) : ?>
    <section class="section-reservation-form">
        <div class="container">
            <div class="section-reservation-form--box">
                <?php echo $reservation_form ?>
            </div>
        </div>
    </section>
<?php endif; ?>
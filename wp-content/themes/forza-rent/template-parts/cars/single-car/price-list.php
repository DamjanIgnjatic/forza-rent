<?php
$car = $args['car'] ?? get_post();
$id  = $car->ID;

$price_list = get_field('price_list', $id);
?>
<section class="section-price-list">
    <div class="container">
        <div class="section-price-list--title">
            <h2>Price List</h2>
        </div>

        <div class="section-price-list--table">
            <table class="">
                <tr class="days">
                    <th>2–4 days</th>
                    <th>5–7 days</th>
                    <th>8–13 days</th>
                    <th>2 Weeks</th>
                    <th>3 Weeks</th>
                    <th>Monthly</th>
                </tr>

                <tr class="prices">
                    <?php foreach ($price_list as $row): ?>
                        <?php $price = $row['price'] ?? null; ?>
                        <?php if ($price): ?>
                            <td>€ <?php echo esc_html($price); ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
</section>
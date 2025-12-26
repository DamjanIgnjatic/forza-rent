<?php
$car = $args['car'] ?? get_post();
$id  = $car->ID;

$price_list = get_field('price_list', $id);
?>
<?php if (!empty($price_list)) : ?>
    <section class="section-price-list animated top-to-bottom">
        <div class="container">

            <div class="section-price-list--title">
                <h2><?php echo esc_html__( 'Pricing', 'forzarent' ); ?></h2>
            </div>

            <div class="section-price-list--table">
                <table class="">
                    <tr class="days">
                        <th>2–4 <?php echo esc_html__( 'days', 'forzarent' ); ?></th>
                        <th>5–7 <?php echo esc_html__( 'days', 'forzarent' ); ?></th>
                        <th>8–13 <?php echo esc_html__( 'days', 'forzarent' ); ?></th>
                        <th>2 <?php echo esc_html__( 'Weeks', 'forzarent' ); ?></th>
                        <th>3 <?php echo esc_html__( 'Weeks', 'forzarent' ); ?></th>
                        <th><?php echo esc_html__( 'Monthly', 'forzarent' ); ?></th>
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
<?php endif; ?>
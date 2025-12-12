<?php
/* 
* Block Name: Recommended Cars
* Post Type: page, post
*/
?>
<?php if (isset($block['data']['previewImage'])): ?>
    <?php do_action('block_image', __FILE__); ?>
<?php else: ?>

    <?php
    include get_template_directory() . '/template-parts/base/block-helper.php';
    $recommended = get_field('recommended', 'option');
    $recommended_title = get_field("recommended_title", "option");
    $recommended_link = get_field("recommended_link", "option");
    ?>

    <section class="section recommended-section-wrapper block <?php echo $className; ?> animated top-to-bottom">
        <div class="container">
            <div class="section recommended-section-wrapper--title">
                <?php if ($recommended_title) : ?>
                    <h2><?php echo $recommended_title ?></h2>
                <?php endif; ?>

                <?php if ($recommended_link) :
                    $link_url = $recommended_link["url"];
                    $link_title = $recommended_link["title"];
                    $link_target = $recommended_link['target'] ? $recommended_link['target'] : '_self';
                ?>
                    <a class="btn-forza" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                <?php endif; ?>
            </div>

            <div class="recommended-section-wrapper--cars">
                <?php foreach ($recommended as $car): ?>
                    <?php
                    if (is_numeric($car)) {
                        $car = get_post($car);
                    }
                    ?>

                    <?php get_template_part('template-parts/cars/car', null, ['car' => $car]); ?>
                <?php endforeach; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </section>

<?php endif; ?>
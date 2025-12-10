<?php
/* 
* Block Name: Hero
* Post Type: page, post
*/
?>
<?php if (isset($block['data']['previewImage'])): ?>
    <?php do_action('block_image', __FILE__); ?>
<?php else: ?>
    <?php
    // Include helpers
    include get_template_directory() . '/template-parts/base/block-helper.php';

    // Hero fields - left
    $hero_left_title = get_field("hero_left_title");
    $hero_left_description = get_field("hero_left_description");
    $hero_left_link = get_field("hero_left_link");
    $hero_left_background_image = get_field("hero_left_background_image");

    // Hero fields - right
    $hero_right_title = get_field("hero_right_title");
    $hero_right_description = get_field("hero_right_description");
    $hero_right_link = get_field("hero_right_link");
    $hero_right_background_image = get_field("hero_right_background_image");
    ?>
    <section class="section section-hero hero-section-section-wrapper block"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>>
        <div class="container">
            <div>
                <?php if ($hero_left_title) : ?>
                    <h1><?php echo $hero_left_title ?></h1>
                <?php endif; ?>

                <?php if ($hero_left_description) : ?>
                    <?php echo $hero_left_description ?>
                <?php endif; ?>
                <?php
                if ($hero_left_link):
                    $link_url = $hero_left_link['url'];
                    $link_title = $hero_left_link['title'];
                    $link_target = $hero_left_link['target'] ? $hero_left_link['target'] : '_self';
                ?>
                    <a class="btn-forza primary" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </section>
<?php endif; ?>
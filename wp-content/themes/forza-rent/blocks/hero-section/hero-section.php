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

    // Load values and assing defaults
    $title = get_field('title') ?: false;

    // Hero fields - left
    $hero_left_title = get_field("hero_left_title");
    $hero_left_description = get_field("hero_left_description");
    $hero_left_link = get_field("hero_left_link");
    $hero_left_image = get_field("hero_left_image");

    // Hero fields - right
    $hero_right_title = get_field("hero_right_title");
    $hero_right_description = get_field("hero_right_description");
    $hero_right_link = get_field("hero_right_link");
    $hero_right_image = get_field("hero_right_image");
    ?>
    <section class="section hero-section-section-wrapper block <?php echo $className; ?>"
        <?php if ($bgUrl): ?>
        style="background-image: url('<?php echo $bgUrl; ?>');"
        data-desktop-image="<?php echo $bgUrl; ?>"
        <?php endif; ?>
        <?php if ($mobileBgUrl): ?>
        data-mobile-image="<?php echo $mobileBgUrl; ?>"
        <?php endif; ?>
        <?php if ($sectionId): ?>
        id="<?php echo $sectionId; ?>"
        <?php endif; ?>>
        <div class="container">
            <!-- CONTENT -->

            <?php if ($hero_left_description) : ?>
                <?php echo $hero_left_description ?>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
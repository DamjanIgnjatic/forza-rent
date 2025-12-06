<?php
/* 
* Block Name: Image
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
    ?>
    <section class="section hero-global-section-wrapper block <?php echo $className; ?>"
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
            <div class="services-title">
            <h1><?php echo get_the_title() ?></h1>
            <h2>Pored iznajmljivanja automobila, nudimo niz dodatnih usluga.</h2>
            </div>
        </div>
    </section>
<?php endif; ?>
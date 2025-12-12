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
    $form = get_field('form') ?: false;
    ?>
    <section class="section form-section-wrapper block animated left-to-right <?php echo $className; ?>">
        <div class="container">
            <div class="form-section-wrapper--title">
                <h2><?php echo $title ?></h2>
            </div>

            <div class="form">
                <?php echo $form ?>
            </div>
        </div>
    </section>
<?php endif; ?>
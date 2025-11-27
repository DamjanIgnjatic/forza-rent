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
    $sponsors = get_field("sponsors");
    ?>
    <section class="section sponsors-section-wrapper block <?php echo $className; ?>"
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
            <div>
                <?php foreach ($sponsors as $row) :
                    $sponsor_image = $row['sponsor_image'] ?? '';
                ?>
                    <?php
                    if (!empty($sponsor_image)): ?>
                        <div>
                            <img src="<?php echo esc_url($sponsor_image['url']); ?>" alt="<?php echo esc_attr($sponsor_image['alt']); ?>" />
                        </div>
                    <?php endif; ?>

                <?php endforeach;  ?>
            </div>
        </div>
    </section>
<?php endif; ?>
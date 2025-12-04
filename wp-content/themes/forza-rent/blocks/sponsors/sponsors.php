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
            <div class="sponsors-marquee">
                <div class="track">
                    <?php foreach ($sponsors as $row): ?>
                        <?php $img = $row['sponsor_image'] ?? null; ?>
                        <?php if ($img): ?>
                            <div class="item">
                                <img src="<?php echo esc_url($img['url']); ?>" alt="">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach ($sponsors as $row): ?>
                        <?php $img = $row['sponsor_image'] ?? null; ?>
                        <?php if ($img): ?>
                            <div class="item">
                                <img src="<?php echo esc_url($img['url']); ?>" alt="">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
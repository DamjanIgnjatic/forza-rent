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
    $title = get_field("title");
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
            <div class="sponsors-section-wrapper--title">
                <h2><?php echo $title ?></h2>
            </div>

            <div class="sponsors-marquee animated top-to-bottom">
                <div class="track">
                    <?php foreach ($sponsors as $row): ?>
                        <?php $img = $row['sponsor_image'] ?? null; ?>
                        <?php $link = $row['link'] ?? null; ?>
                        <?php if ($img): ?>

                            <a href="<?php echo esc_url($link["url"]) ?>" target="<?php echo esc_attr($link["target"]) ?>" class="item">
                                <img src="<?php echo esc_url($img['url']); ?>" alt="">
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach ($sponsors as $row): ?>
                        <?php $img = $row['sponsor_image'] ?? null; ?>
                        <?php $link = $row['link'] ?? null; ?>
                        <?php if ($img): ?>
                            <a href="<?php echo esc_url($link["url"]) ?>" target="<?php echo esc_attr($link["target"]) ?>" class="item">
                                <img src="<?php echo esc_url($img['url']); ?>" alt="">
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
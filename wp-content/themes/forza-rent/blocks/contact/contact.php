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
    $contact_information = get_field('contact_information') ?: false;
    ?>
    <section class="section contact-section-wrapper block animated top-to-bottom <?php echo $className; ?>">
        <div class="container">
            <div class="contact-section-wrapper--title">
                <h2><?php echo $title ?></h2>
            </div>

            <div class="contact-section-wrapper--cards">
                <?php if (!empty($contact_information)) ?>
                <?php foreach ($contact_information as $row) :
                    $icon = $row["icon"];
                    $card_title = $row["title"];
                    $description = $row["description"];
                    $link = $row["link"];
                ?>
                    <div class="card">
                        <?php if ($icon) : ?>
                            <img src="<?php echo $icon["url"] ?>" alt="<?php echo $icon["alt"] ?>" />
                        <?php endif; ?>

                        <?php if ($card_title) : ?>
                            <h3><?php echo $card_title ?></h3>
                        <?php endif; ?>

                        <?php if ($description) : ?>
                            <?php echo $description ?>
                        <?php endif; ?>

                        <?php if ($link) :
                            $link_url = $link["url"];
                            $link_title = $link["title"];
                            $link_target = $link["target"] ? $link["target"] : "_blank";

                        ?>
                            <p class="btn-forza primary"><?php echo $link_title ?></p>
                            <a href="<?php echo esc_attr($link_url) ?>" target="<?php echo esc_attr($link_target)  ?>"></a>

                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
<?php endif; ?>
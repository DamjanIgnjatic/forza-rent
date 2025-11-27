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
    $faq = get_field('faq') ?: false;
    ?>
    <section class="section faq-section-wrapper block <?php echo $className; ?>"
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
            <?php foreach ($faq as $row) :
                $question = $row["question"];
                $answer = $row["answer"];
            ?>
                <div>
                    <?php if ($question): ?>
                        <h4><?php echo  $question ?></h4>
                    <?php endif; ?>

                    <?php if ($answer): ?>
                        <?php echo  $answer ?>
                    <?php endif; ?>
                </div>


            <?php endforeach;  ?>

        </div>
    </section>
<?php endif; ?>
<div class="navigation">
    <div class="navigation--logo">
        <a href="<?php echo home_url(); ?>" class="home-link" aria-label="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
            <?php if (has_custom_logo()): ?>
                <?php $logoUrl = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full'); ?>
                <link rel="preload" href="<?php echo esc_url($logoUrl); ?>" as="image">
                <?php echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'site-logo skip-lazy no-lazy']); ?>
            <?php else: ?>
                <?php echo bloginfo('name'); ?>
            <?php endif; ?>
        </a>
    </div>

    <div class="navigation--items">
        <?php do_action('theme_navigation'); ?>
        <?php $navBtns = get_field('navigation_buttons', 'option'); ?>
        <?php if ($navBtns && is_array($navBtns)): ?>
            <div class="nav-buttons">
                <?php foreach ($navBtns as $val):
                    $count = 0;
                ?>

                    <?php if (is_array($val) && isset($val['link']) && is_array($val['link'])): $link = $val['link']; ?>
                        <?php $iconId = isset($val['icon']) && $val['icon'] ? $val['icon'] : false; ?>
                        <?php $type = isset($val['type']) && $val['type'] ? $val['type'] : 'btn-first'; ?>
                        <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>"
                            class="<?php echo $type; ?>">
                            <?php
                            if ($count > 0) {
                                echo "<p>a is bigger than b</p>";
                            } elseif ($count == 1) {
                                echo "a is equal to b";
                            } else {
                                echo "a is smaller than b";
                            }
                            ?>

                            <?php echo $link['title']; ?>
                            <?php if ($iconId): ?>
                                <?php echo wp_get_attachment_image($iconId, 'full', false, ['class' => 'icon']); ?>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                <?php $count++;
                endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
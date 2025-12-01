<?php

$faq = get_field('faq_global', 'option') ?: false;

?>
<section class="section faq-section-wrapper">
    <div class="container">
        <?php foreach ($faq as $row):
            $question = $row['faq_question'];
            $answer = $row['faq_answer'];
        ?>
            <div>
                <?php if ($question): ?>
                    <h4><?php echo $question ?></h4>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="icon-sm text-token-text-tertiary">
                        <path d="M12.1338 5.94433C12.3919 5.77382 12.7434 5.80202 12.9707 6.02929C13.1979 6.25656 13.2261 6.60807 13.0556 6.8662L12.9707 6.9707L8.47067 11.4707C8.21097 11.7304 7.78896 11.7304 7.52926 11.4707L3.02926 6.9707L2.9443 6.8662C2.77379 6.60807 2.80199 6.25656 3.02926 6.02929C3.25653 5.80202 3.60804 5.77382 3.86617 5.94433L3.97067 6.02929L7.99996 10.0586L12.0293 6.02929L12.1338 5.94433Z"></path>
                    </svg>
                <?php endif; ?>

                <?php if ($answer): ?>
                    <?php echo $answer; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
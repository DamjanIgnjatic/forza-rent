<?php
$testimonials = get_field('testimonials_global', 'option');
$testimonials_title = get_field('testimonials_title ', 'option');
$testimonials_count = is_array($testimonials) ? count($testimonials) : 0;

?>
<section class="section testimonials-section-wrapper animated top-to-bottom">
    <div class="container">
        <div class="testimonials-section-wrapper--title">
            <h2><?php echo $testimonials_title ?></h2>
        </div>

        <div class="testimonials-section-wrapper--box">
            <div class="testimonials-section-wrapper--box-items <?php echo $testimonials_count > 3 ? 'is-slider' : 'is-static'; ?>">
                <?php
                if ($testimonials && is_array($testimonials) && count($testimonials) > 0) :
                    $first_event = $testimonials[0];
                    $last_event  = end($testimonials);
                ?>

                    <?php foreach ($testimonials as $row):
                        $description = $row['testimonial_description'];
                        $full_name = $row['first_and_last_name'];
                        $company = $row['user_company'];
                    ?>


                        <div class="testimonial">
                            <div class="testimonial-wrapper">
                                <svg xmlns=" http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24px" width="24px" version="1.1" id="_x32_" viewBox="0 0 512 512" xml:space="preserve">
                                    <style type="text/css">
                                        .st0 {
                                            fill: #f1eff0;
                                        }
                                    </style>
                                    <g>
                                        <path class="st0" d="M119.472,66.59C53.489,66.59,0,120.094,0,186.1c0,65.983,53.489,119.487,119.472,119.487   c0,0-0.578,44.392-36.642,108.284c-4.006,12.802,3.135,26.435,15.945,30.418c9.089,2.859,18.653,0.08,24.829-6.389   c82.925-90.7,115.385-197.448,115.385-251.8C238.989,120.094,185.501,66.59,119.472,66.59z" />
                                        <path class="st0" d="M392.482,66.59c-65.983,0-119.472,53.505-119.472,119.51c0,65.983,53.489,119.487,119.472,119.487   c0,0-0.578,44.392-36.642,108.284c-4.006,12.802,3.136,26.435,15.945,30.418c9.089,2.859,18.653,0.08,24.828-6.389   C479.539,347.2,512,240.452,512,186.1C512,120.094,458.511,66.59,392.482,66.59z" />
                                    </g>
                                </svg>

                                <?php if ($description): ?>
                                    <div class="description"> <?php echo $description ?></div>
                                <?php endif; ?>

                                <div class="user">
                                    <?php if ($full_name): ?>
                                        <p><?php echo $full_name; ?></p>
                                    <?php endif; ?>

                                    <?php if ($company): ?>
                                        <p><?php echo $company; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <?php if ($testimonials_count > 3) : ?>
                <div class="arrow-box">
                    <div class="btn-prev arrow">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.41431 14.707L1.41431 7.70703L8.41431 0.707032" stroke="white" stroke-width="2" />
                        </svg>
                    </div>
                    <div class="btn-next arrow">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.707031 0.707031L7.70703 7.70703L0.707029 14.707" stroke="white" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            <?php endif; ?>

        </div>


    </div>
</section>
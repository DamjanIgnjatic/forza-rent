<main role="main">
	<div class="sidebar">
		<?php get_template_part('template-parts/sidebar'); ?>
		<div class="blocks">
			<?php get_header(); ?>
			<?php if (have_posts()): ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php
					// the_content();
					$content = get_the_content();
					echo apply_filters('block_content', $content);
					?>
				<?php endwhile; ?>
			<?php else: ?>
				<?php get_template_part('template-parts/no-content', 'No content'); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="google-site-verification" content="DCDsfXkCOxqa5mNBupgDI-tFiM7H2Pmpb4CT2CxQyaM" />
	<?php if ($iconUrl = get_site_icon_url()): ?>
		<link href="<?php echo $iconUrl; ?>" rel="shortcut icon prefetch dns-prefetch">
		<link href="<?php echo $iconUrl; ?>" rel="apple-touch-icon-precomposed prefetch dns-prefetch">
	<?php endif; ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-PVV4ZCMH');
	</script>
	<!-- End Google Tag Manager -->
	<?php wp_head(); ?>
</head>

<body <?php body_class('forzarent'); ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PVV4ZCMH"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php if (!is_page_template('templates/template-plain.php')): ?>
		<header class="section header section-navigation" id="navbar">
			<div class="container">
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
						<?php echo do_shortcode('[social_media_icons]'); ?>
					</div>

					<div class="hamburger">
						<span>
						</span>
						<span>
						</span>
						<span>
						</span>
					</div>
				</div>
			</div>
		</header>
	<?php endif; ?>
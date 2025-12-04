<!doctype html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<?php if ($iconUrl = get_site_icon_url()): ?>
		<link href="<?php echo $iconUrl; ?>" rel="shortcut icon prefetch dns-prefetch">
		<link href="<?php echo $iconUrl; ?>" rel="apple-touch-icon-precomposed prefetch dns-prefetch">
	<?php endif; ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5">

	<?php wp_head(); ?>
</head>

<body <?php body_class('forzarent'); ?>>
	<?php if (!is_page_template('templates/template-plain.php')): ?>
		<header class="section section-navigation" id="navbar">
			<p>HEADER</p>
		</header>
	<?php endif; ?>
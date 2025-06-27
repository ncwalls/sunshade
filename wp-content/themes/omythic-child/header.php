<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,400;1,700&family=Noto+Serif:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<a class="visually-hidden skip-link" href="#MainContent">Skip to content</a>
	<a href="javascript:void(0)" class="nav-toggle" id="ocn-overlay"></a>
	<?php /* ?>
	<div id="ocn">
		<div id="ocn-inner">
			<div id="ocn-top">
				<a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>" id="ocn-brand">
					<img src="<?php echo get_field( 'default_logo', 'option' )['sizes']['small']; ?>" alt="<?php bloginfo( 'name' ); ?>">
				</a>
				<button name="Mobile navigation toggle" aria-pressed="false" class="nav-toggle" type="button" id="ocn-close" aria-labelledby="ocn-toggle-label">
					<span class="screen-reader-text" id="ocn-toggle-label">Close off canvas navigation</span>
				</button>
			</div>
			<?php 
			// wp_nav_menu( array(
			// 	'container' => 'nav',
			// 	'container_id' => 'ocn-nav-primary',
			// 	'theme_location' => 'primary',
			// 	'before' => '<span class="ocn-link-wrap">',
			// 	'after' => '<button aria-pressed="false" name="Menu item dropdown toggle" class="ocn-sub-menu-button"></button></span>',
			// 	'walker' => new sub_menu_walker
			// ) );
			 ?>
		</div>
	</div>
	*/ ?>
	<header class="site-header" role="banner">
		<div class="inner">
			<div class="site-header-logo">
				<a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>" class="brand">
					<img src="<?php echo get_field( 'default_logo', 'option' )['sizes']['small']; ?>" alt="<?php bloginfo( 'name' ); ?>">
				</a>
			</div>
			<?php 
				$countdown_date = get_field('launch_date', 'option');
				$now = date('Y-m-d H:i:s');
				
				$start = date_create($now);
				$end = date_create($countdown_date);
				
				$start->setTimezone(new DateTimeZone('America/New_York'));
				$end->setTimezone(new DateTimeZone('America/New_York'));

				$interval = date_diff($start, $end);
				$days = $interval->format('%a');
				$hours = $interval->format('%h');
				$mins = $interval->format('%i');
				// $secs = $interval->format('%s');

			?>
			<div class="countdown-timer" data-startdate="<?php echo date_format($start, 'Y-m-d H:i:s'); ?>" data-enddate="<?php echo date_format($end, 'Y-m-d H:i:s'); ?>">
				<span class="countdown-label"><?php echo get_field('launch_date_label', 'option'); ?></span>
				<span class="time days">
					<span class="number" data-abbr="d"><?php echo $days; ?></span>
					<span class="label">Days</span>
				</span>
				<span class="time hrs">
					<span class="number" data-abbr="hr"><?php echo $hours; ?></span>
					<span class="label">Hrs</span>
				</span>
				<span class="time mins">
					<span class="number" data-abbr="min"><?php echo $mins; ?></span>
					<span class="label">Mins</span>
				</span>
			</div>
			<?php /* ?>
			<div class="site-header-menu">
				<?php
					wp_nav_menu( array(
						'container' => 'nav',
						'container_id' => 'large-nav-primary',
						'theme_location' => 'primary'
					) );
				?>
			</div>
			<button name="Mobile navigation toggle" aria-pressed="false" class="nav-toggle" type="button" id="nav-toggle" aria-labelledby="nav-toggle-label">
				<span class="screen-reader-text" id="nav-toggle-label">Open off canvas navigation</span>
				<span class="middle-bar"></span>
			</button>
			*/?>
		</div>

		<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/curve-header.png" alt="" class="curve">
	</header>

	<div id="MainContent" class="wrapper" role="main">

		<?php if( !is_front_page() ) : ?>
			<?php get_template_part( 'template', 'banner' ); ?>
		<?php endif; ?>
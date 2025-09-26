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
<?php
$current_user = wp_get_current_user();
global $woocommerce;
?>

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
			<?php /*
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
			<?php */ ?>
			<div class="site-header-menu">
				<?php
					wp_nav_menu( array(
						'container' => 'nav',
						'container_id' => 'large-nav-primary',
						'theme_location' => 'primary'
					) );
				?>
				<a class="header-cart" href="<?php echo wc_get_cart_url(); ?>" title="Shopping Cart">
					<span class="cart-count"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
					<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M5.70527 19C5.18229 19 4.73458 18.814 4.36216 18.4419C3.98973 18.0698 3.80351 17.6225 3.80351 17.1C3.80351 16.5775 3.98973 16.1302 4.36216 15.7581C4.73458 15.386 5.18229 15.2 5.70527 15.2C6.22825 15.2 6.67596 15.386 7.04839 15.7581C7.42081 16.1302 7.60703 16.5775 7.60703 17.1C7.60703 17.6225 7.42081 18.0698 7.04839 18.4419C6.67596 18.814 6.22825 19 5.70527 19ZM15.2141 19C14.6911 19 14.2434 18.814 13.8709 18.4419C13.4985 18.0698 13.3123 17.6225 13.3123 17.1C13.3123 16.5775 13.4985 16.1302 13.8709 15.7581C14.2434 15.386 14.6911 15.2 15.2141 15.2C15.737 15.2 16.1847 15.386 16.5572 15.7581C16.9296 16.1302 17.1158 16.5775 17.1158 17.1C17.1158 17.6225 16.9296 18.0698 16.5572 18.4419C16.1847 18.814 15.737 19 15.2141 19ZM3.99369 1.9H18.0191C18.3837 1.9 18.661 2.06229 18.8512 2.38687C19.0413 2.71146 19.0493 3.04 18.8749 3.3725L15.4993 9.4525C15.325 9.76917 15.0912 10.0146 14.798 10.1888C14.5049 10.3629 14.1839 10.45 13.8353 10.45H6.75124L5.70527 12.35H17.1158V14.25H5.70527C4.99211 14.25 4.45328 13.9373 4.08878 13.3119C3.72427 12.6865 3.70843 12.065 4.04123 11.4475L5.32492 9.12L1.90176 1.9H0V0H3.09036L3.99369 1.9Z"/>
					</svg>
				</a>
			</div>
			<?php /*
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
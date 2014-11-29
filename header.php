<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<?php /* Use WordPress SEO to change titles: https://wordpress.org/plugins/wordpress-seo/ */?> 
		<title><?php wp_title(''); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<?php
		//Favicon
		$favicon = of_get_option( 'favicon', '' );
		if ( !empty( $favicon ) ) {
			?>
			<link href="<?php echo esc_url( $favicon ); ?>" rel="shortcut icon">
			<?php
		}
        ?>

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<?php 
		/* Use WordPress SEO to add meta descriptions: https://wordpress.org/plugins/wordpress-seo/ 
		*/?> 
		
		<script>
        var opubco_ajax_url = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";
        </script>	
		<?php wp_head(); ?>
		<?php
		global $is_IE;
		if ( $is_IE ) {
			?>
			<!--[if lt IE 9]>
			<link rel='stylesheet' id='ie'  href='<?php echo esc_url( get_stylesheet_directory_uri() . '/css/ie.css' ); ?>' media='all' />
			
			<script type='text/javascript' src='<?php echo esc_url( get_stylesheet_directory_uri() . '/js/html5.js' ); ?>'></script>
			<script type='text/javascript' src='<?php echo esc_url( get_stylesheet_directory_uri() . '/js/respond.js' ); ?>'></script>
			<![endif]-->
			<?php
		}
		
		
		?>
		
	</head>
	<body <?php body_class(); ?>>
		<?php do_action( 'body_open' ); ?>
		<!-- header -->
		<header class="header clear" role="banner">
	
			<!-- logo -->
				<div class="logo">
					<a href="<?php echo esc_url( home_url() ); ?>">
						<img src="<?php if(of_get_option( 'logo_upload' )){ echo esc_url( of_get_option( 'logo_upload' ) );}else{ echo esc_url( get_template_directory_uri().'/img/logo.png' ); } ?>" alt="Logo" class="logo-img">
					</a>
				</div>
				<!-- /logo -->
	
				<!-- nav -->
				<nav class="nav" role="navigation">
					<?php opubco_nav(); ?>
				</nav>
				<!-- /nav -->
				<?php
				/* Social is not used in every theme, so remove if necessary.  Uses Font Awesome - http://fortawesome.github.io/Font-Awesome/examples/ - Styles are included in the main style.css stylesheet
					You can change the order of the icons by modifying the order of the array
				*/
				$opubco_social = opubco_get_social_html( array( 'Facebook', 'Twitter', 'YouTube', 'google', 'Instagram', 'Pinterest', 'LinkedIn' ), $wrapper = 'ul', $item = 'li', $echo = false );
				if ( !empty( $opubco_social ) ) {
					printf( '<div id="social" class="social-icons">%s</div>', $opubco_social );
				}
				?>
	
		</header>
		<!-- /header -->
			<!-- footer -->
			<footer class="footer" role="contentinfo">

				<!-- Address Schema / Copyright -->
				<div itemscope itemtype="http://schema.org/LocalBusiness">	
					<p>
						<span itemprop="name"><?php opubco_copyright( '2014', $echo = true ); ?> <?php bloginfo('name'); ?></span>
					
						<?php if(of_get_option( 'street-address' )){ 
							echo '<br /><span itemprop="streetAddress">' . esc_html( of_get_option( 'street-address' ) ) . '</span><br />';
						} ?>  		
						<?php if(of_get_option( 'city' )){ 
							echo '<span itemprop="addressLocality">' . esc_html( of_get_option( 'city' ) ) . '</span>, ';
						} ?> 							
						<?php if(of_get_option( 'state' )){ 
							echo '<span itemprop="addressRegion">' . esc_html( of_get_option( 'state' ) ) . '</span>';
						} ?>
						<?php if(of_get_option( 'zip' )){ 
							echo '<span itemprop="postalcode">' . esc_html( of_get_option( 'zip' ) ) . '</span>';
						} ?>
					
						<?php if(of_get_option( 'phone' )){ 
							echo '<br /><span itemprop="telephone">' . esc_html( of_get_option( 'phone' ) ) . '</span>';
						} ?>
					</p>
				</div>
				<!-- /Address Schema / Copyright -->

				<!-- Footer Menu -->
				<?php //wp_nav_menu( array('theme_location' => 'footer-menu' )); ?>
				<!-- Footer Menu -->

			</footer>
			<!-- /footer -->

		<?php wp_footer(); ?>
		<?php
		/* Use Google Analytics Plugin for Google Analytics: https://wordpress.org/plugins/google-analytics-for-wordpress/
		*/
		?>
	</body>
</html>
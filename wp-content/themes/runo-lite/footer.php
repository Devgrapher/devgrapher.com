<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=primary div and all content after
 *
 */
?>
	</div> <!-- ends primary -->
	
	<div id="secondary" class="runo-background">
	
	<footer id="main-footer" <?php runo_footer_sidebar_class(); ?>>
	
	<div class="sep"> &para; </div>


			<?php
				if ( ! is_404() )
					get_sidebar( 'footer' );
			?>
			
			<div id="site-generator">
			<!-- It is completely optional, but if you like the Theme I would appreciate it if you keep the credit link at the bottom. -->
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'runo' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'runo' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'runo' ), 'WordPress' ); ?></a> &para; <?php _e( '<a href="http://runo.lala.fi">Theme Runo by La&amp;La</a>', 'runo' ); ?>
			</div>
	
	</footer><!-- #main-footer -->
			
	
	</div><!-- #secondary -->

</div><!-- #container -->

<?php wp_footer(); ?>

</body>
</html>
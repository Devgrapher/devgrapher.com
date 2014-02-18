<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tdMinimal
 */

get_header(); ?>

	<?php if( is_category( tdminimal_get_portfolio_category_id() ) && tdminimal_get_portfolio_category_id() ): ?>	
		<?php get_template_part( 'templates/archive', 'portfolio' ); ?>
	<?php else: ?>				
		<?php get_template_part( 'templates/archive', 'general' ); ?>
	<?php endif; ?>

<?php get_footer(); ?>

<?php
global $APSC;

$APSC_Lists = new APSC_Lists();

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $APSC->PageSlug ,  $this->get_asset_url() . 'manage.js', $ReadedJs , $APSC->Ver );
wp_enqueue_style( $APSC->PageSlug , $this->get_asset_url() . 'manage.css', array() , $APSC->Ver );
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php printf( __( '%1$s %2$s for %3$s %4$s' , $APSC->ltd ) , __( 'Customize' ) ,  __( 'Sort' , $APSC->ltd ) , __( 'Search' ) , __( 'Archives' ) ); ?></h2>
	<p><?php _e( 'Please set your favorite.' , $APSC->ltd ); ?></p>
	
	<div class="metabox-holder columns-2 <?php echo $APSC->ltd; ?>">

		<div id="postbox-container-1" class="postbox-container">

			<form id="<?php echo $APSC->PageSlug; ?>_form" method="post" action="<?php echo remove_query_arg( $APSC->MsgQ ); ?>">
				<input type="hidden" name="<?php echo $APSC->UPFN; ?>" value="Y">
				<?php wp_nonce_field( $APSC->Nonces["value"] , $APSC->Nonces["field"] ); ?>
				<input type="hidden" name="record_field" value="search" />

				<?php $args = array( 'per_setting' => '' , 'archive' => 'search' , 'title' => __( 'Default' ) ); ?>
				<?php $APSC_Lists->get_postbox( $args ); ?>
				
				<p class="submit">
					<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
				</p>

				<p class="submit reset">
					<span class="description"><?php _e( 'Reset all settings?' , $APSC->ltd ); ?></span>
					<input type="submit" class="button-secondary" name="reset" value="<?php _e( 'Reset settings' , $APSC->ltd ); ?>" />
				</p>

			</form>

		</div>

		<div id="postbox-container-2" class="postbox-container">

			<?php include_once $this->get_view_dir() . 'donation.php'; ?>

		</div>

		<div class="clear"></div>

	</div>

</div>

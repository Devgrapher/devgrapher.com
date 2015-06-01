<?php
global $APSC;
?>
<?php if( $this->is_donation() ) : ?>

	<span class="description"><?php _e( 'Thank you for your donation.' , $APSC->ltd ); ?></span>

<?php else: ?>

	<div class="stuffbox" style="border-color: #FFC426; border-width: 3px;">
		<h3 style="background: #FFF2D0; border-color: #FFC426;"><span class="hndle"><?php _e( 'How may I help you?' , $APSC->ltd ); ?></span></h3>
		<div class="inside">
			<p style="float: right;">
				<img src="<?php echo $this->get_gravatar_src( 46 ); ?>" width="46" /><br />
				<a href="<?php echo $this->get_author_url( 'contact' , 'side' ); ?>" target="_blank">gqevu6bsiz</a>
			</p>
			<p><?php _e( 'I am good at Admin Screen Customize.' , $APSC->ltd ); ?></p>
			<p><?php _e( 'Please consider the request to me if it is good.' , $APSC->ltd ); ?></p>
			<p>
				<a href="http://wpadminuicustomize.com/blog/category/example/<?php echo $this->get_query_url(); ?>" target="_blank"><?php _e ( 'Example Customize' , $APSC->ltd ); ?></a> :
				<a href="<?php echo $this->get_author_url( 'contact' , 'side' ); ?>" target="_blank"><?php _e( 'Contact me' , $APSC->ltd ); ?></a></p>
		</div>
	</div>

	<div class="stuffbox" id="donationbox">
		<div class="inside">
			<p style="color: #FFFFFF; font-size: 20px;"><?php _e( 'Please donate.' , $APSC->ltd ); ?></p>
			<p style="color: #FFFFFF;"><?php _e( 'You are contented with this plugin?<br />By the laws of Japan, Japan\'s new paypal user can not make a donate button.<br />So i would like you to buy this plugin as the replacement for the donate.' , $APSC->ltd ); ?></p>
			<p>&nbsp;</p>
			<p style="text-align: center;">
				<a href="<?php echo $this->get_author_url( 'linebreak' , 'side' ); ?>" class="button-primary" target="_blank">Line Break First and End</a>
			</p>
			<p>&nbsp;</p>
			<div class="donation_memo">
				<p><strong><?php _e( 'Features' , $APSC->ltd ); ?></strong></p>
				<p><?php _e( 'Line Break First and End plugin is In the visual editor TinyMCE, It is a plugin that will help when you will not be able to enter a line break.' , $APSC->ltd ); ?></p>
			</div>
			<div class="donation_memo">
				<p><strong><?php _e( 'The primary use of donations' , $APSC->ltd ); ?></strong></p>
				<ul>
					<li>- <?php _e( 'Liquidation of time and value' , $APSC->ltd ); ?></li>
					<li>- <?php _e( 'Additional suggestions feature' , $APSC->ltd ); ?></li>
					<li>- <?php _e( 'Maintain motivation' , $APSC->ltd ); ?></li>
					<li>- <?php _e( 'Ensure time as the father of Sunday' , $APSC->ltd ); ?></li>
				</ul>
			</div>
			<form id="donation_form" method="post" action="<?php echo remove_query_arg( $APSC->MsgQ ); ?>">
				<h4 style="color: #FFF;"><?php _e( 'If you have already donated to.' , $APSC->ltd ); ?></h4>
				<p style="color: #FFF;"><?php _e( 'Please enter the \'Donate delete key\' that have been described in the \'Line Break First and End download page\'.' , $APSC->ltd ); ?></p>
				<input type="hidden" name="<?php echo $APSC->UPFN; ?>" value="Y" />
				<?php wp_nonce_field( $APSC->Nonces["value"] , $APSC->Nonces["field"] ); ?>
				<label for="donate_key"><span style="color: #FFF; "><?php _e( 'Donate delete key' , $APSC->ltd ); ?></span></label>
				<input type="text" name="donate_key" id="donate_key" value="" class="small-text" />
				<input type="submit" class="button-secondary" name="update" value="<?php _e( 'Submit' ); ?>" />
			</form>
		</div>
	</div>

<?php endif; ?>

	<div class="stuffbox" id="aboutbox">
		<h3><span class="hndle"><?php _e( 'About plugin' , $APSC->ltd ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Version checked' , $APSC->ltd ); ?> : 3.6.1 - 4.1</p>
			<ul>
				<li><a href="<?php echo $this->get_author_url( '' , 'side' ); ?>" target="_blank"><?php _e( 'Developer\'s site' , $APSC->ltd ); ?></a></li>
				<li><a href="http://wordpress.org/support/plugin/<?php echo $APSC->PluginSlug; ?>" target="_blank"><?php _e( 'Support Forums' ); ?></a></li>
				<li><a href="http://wordpress.org/support/view/plugin-reviews/<?php echo $APSC->PluginSlug; ?>" target="_blank"><?php _e( 'Reviews' , $APSC->ltd ); ?></a></li>
				<li><a href="https://twitter.com/gqevu6bsiz" target="_blank">twitter</a></li>
				<li><a href="http://www.facebook.com/pages/Gqevu6bsiz/499584376749601" target="_blank">facebook</a></li>
			</ul>
			<p>
				I am looking for a translator for this plugin.<br />
				<a href="<?php echo $this->get_author_url( 'translation' , 'side' ); ?>" target="_blank">Please translation.</a>
			</p>
			<p><?php echo sprintf( __( 'Do you have a proposal you want to improve? Please contact to %s if it is necessary.' , $APSC->ltd ) , '<a href="http://wordpress.org/support/plugin/' . $APSC->PluginSlug . '" target="_blank">' . __( 'Support Forums' ) . '</a>' ); ?></p>
		</div>
	</div>

	<div class="stuffbox" id="usefulbox">
		<h3><span class="hndle"><?php _e( 'Useful plugins' , $APSC->ltd ); ?></span></h3>
		<div class="inside">
			<p><strong><a href="http://wordpress.org/extend/plugins/wp-admin-ui-customize/" target="_blank">WP Admin UI Customize</a></strong></p>
			<p class="description"><?php _e( 'Customize a variety of screen management.' , $APSC->ltd ); ?></p>
			<p><strong><a href="http://wordpress.org/extend/plugins/post-lists-view-custom/" target="_blank">Post Lists View Custom</a></strong></p>
			<p class="description"><?php _e( 'Customize the list of the post and page. custom post type page, too. You can customize the column display items freely.' , $APSC->ltd ); ?></p>
			<p><strong><a href="http://wordpress.org/extend/plugins/custom-options-plus-post-in/" target="_blank">Custom Options Plus Post in</a></strong></p>
			<p class="description"><?php _e( 'The plugin that allows you to add the value of the options. Option value that you have created, can be used in addition to the template tag, Short code can be used in the body of the article.' , $APSC->ltd ); ?></p>
			<p>&nbsp;</p>
		</div>
	</div>

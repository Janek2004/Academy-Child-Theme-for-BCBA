<?php

/*

Template Name: Registration

*/

?>

<?php get_header(); ?>

<?php if(get_option('users_can_register')) { ?>

<div class="eightcol column">

	<h1><?php _e('New Users Register Here:','academy'); ?></h1>

	<form class="ajax-form formatted-form" action="<?php echo AJAX_URL; ?>" method="POST">

		<div class="message"></div>

      <div class="sixcol column">
 
			<div class="field-wrapper">

				<input type="text" name="user_bcba" placeholder="<?php _e('BCBA No','academy'); ?>" />

			</div>								

		</div>  
  <div class="sixcol column">
 
			<div class="field-wrapper">

				<input type="checkbox" name="bcba_checkbox" value="has_number"/> I don't have a BCBA number

			</div>								
		</div> 
        
<div class="clear"></div>
      <div class="sixcol column last">

			<div class="field-wrapper last">

				<input type="text" name="user_login" placeholder="<?php _e('Username','academy'); ?>" />

			</div>								

		</div>
			<div class="clear"></div>
		
        <div class="sixcol column">

			<div class="field-wrapper">

				<input type="text" name="user_email" placeholder="<?php _e('Email','academy'); ?>" />

			</div>

		</div>	
		<div class="clear"></div>

		<div class="sixcol column last">

			<div class="field-wrapper">
				<input type="password" name="user_password" placeholder="<?php _e('Password','academy'); ?>" />
			</div>

		</div>
			<div class="clear"></div>
		
        <div class="sixcol column last">

			<div class="field-wrapper">

				<input type="password" name="user_password_repeat" placeholder="<?php _e('Repeat Password','academy'); ?>" />

			</div>

		</div>

		<div class="clear"></div>			

		<?php if(ThemexCore::checkOption('user_captcha')) { ?>

		<div class="form-captcha">

			<img src="<?php echo THEMEX_URI; ?>assets/images/captcha/captcha.php" alt="" />

			<input type="text" name="captcha" id="captcha" size="6" value="" />

		</div>

		<div class="clear"></div>

		<?php } ?>
	
    	  <div class="sixcol column">
 
			<div class="field-wrapper">

				<input type="checkbox" name="email_communication" value="optin" checked="checked"/> I want to receive news and updates

			</div>								
		</div> 
		
		<div class="clear"></div>
		<a href="#" class="button submit-button left"><span class="button-icon register"></span><?php _e('Register','academy'); ?></a>

		<div class="form-loader"></div>

		<input type="hidden" name="user_action" value="register_user" />

		<input type="hidden" name="user_redirect" value="<?php echo themex_value($_POST, 'user_redirect'); ?>" />

		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />

		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />

	</form>

</div>

<?php } ?>
<div class="clear"></div>
<div class="eightcol column">

<br><br>
	
	<h1><?php _e('Existing Users Sign In Here:','academy'); ?></h1>
	<form class="ajax-form formatted-form" action="<?php echo AJAX_URL; ?>" method="POST">
 <div class="sixcol column">
		<div class="message"></div>

		<div class="field-wrapper">

			<input type="text" name="user_login" placeholder="<?php _e('Username','academy'); ?>" />

		</div>

		<div class="field-wrapper">

			<input type="password" name="user_password" placeholder="<?php _e('Password','academy'); ?>" />

		</div>			

		<a href="#" class="button submit-button left"><span class="button-icon login"></span><?php _e('Sign In','academy'); ?></a>

		<?php if(ThemexFacebook::isActive()) { ?>

		<a href="<?php echo ThemexFacebook::getURL(); ?>" title="<?php _e('Sign in with Facebook','academy'); ?>" class="button facebook-button left">

			<span class="button-icon facebook"></span>

		</a>

		<?php } ?>

		<div class="form-loader"></div>

		<input type="hidden" name="user_action" value="login_user" />

		<input type="hidden" name="user_redirect" value="<?php echo themex_value($_POST, 'user_redirect'); ?>" />

		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />

		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
</div>
	</form>	

</div>
<div class="clear"></div>
<br />

<div class="eightcol column">
<h1><?php _e('Forgot Password?','academy'); ?></h1>
									 <div class="sixcol column">
										<form action="<?php echo AJAX_URL; ?>" class="ajax-form formatted-form" method="POST">
											<div class="message"></div>
												<div class="field-wrapper">
												<input type="text" name="user_email"  placeholder="Email" />
											</div>
											<div class="button-wrap left nomargin">
												<a href="#" class="button submit-button"><?php _e('Reset Password','academy'); ?></a>
											</div>
											<input type="hidden" name="user_action" value="reset_password" />
											<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
											<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_user" />
										</form>
				</div>
</div>



<?php 

$query=new WP_Query(array(

	'post_type' => 'page',

	'meta_key' => '_wp_page_template',

	'meta_value' => 'template-register.php'

));



if($query->have_posts()) {

	$query->the_post();

	echo '<br />';

	the_content();

}

?>

<?php get_footer(); ?>
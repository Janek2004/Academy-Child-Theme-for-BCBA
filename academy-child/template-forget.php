<?php

/*

Template Name: Registration

*/

?>

<?php get_header(); ?>


<div class="tooltip-wrap password-form">
									<div class="tooltip-text">
										<form action="<?php echo AJAX_URL; ?>" class="ajax-form popup-form" method="POST">
											<div class="message"></div>
											<div class="field-wrap">
												<input type="text" name="user_email" value="<?php _e('Email','academy'); ?>" />
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
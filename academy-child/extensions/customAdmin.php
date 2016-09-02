<?php
add_action( 'admin_menu', 'jmc_add_admin_menu' );
add_action( 'admin_init', 'jmc_settings_init' );


function jmc_add_admin_menu(  ) {

	add_menu_page( 'Multi Paypal Settings', 'Multi Paypal Settings', 'manage_options', 'multipaypal', 'jmc_options_page' );

}


function jmc_settings_init(  ) {

	register_setting( 'pluginPage', 'jmc_settings' );

	add_settings_section(
		'jmc_pluginPage_section',
		__( 'Multiple Paypal Accounts', 'wordpress' ),
		'jmc_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'jmc_text_field_0',
		__( 'Paypal Type 1:', 'wordpress' ),
		'jmc_text_field_0_render',
		'pluginPage',
		'jmc_pluginPage_section'
	);

	add_settings_field(
		'jmc_text_field_1',
		__( 'Paypal Type 2:', 'wordpress' ),
		'jmc_text_field_1_render',
		'pluginPage',
		'jmc_pluginPage_section'
	);


}


function jmc_text_field_0_render(  ) {

	$options = get_option( 'jmc_settings' );
	?>
	<input type='text' name='jmc_settings[jmc_text_field_0]' value='<?php echo $options['jmc_text_field_0']; ?>'>
	<?php

}


function jmc_text_field_1_render(  ) {

	$options = get_option( 'jmc_settings' );
	?>
	<input type='text' name='jmc_settings[jmc_text_field_1]' value='<?php echo $options['jmc_text_field_1']; ?>'>
	<?php

}


function jmc_settings_section_callback(  ) {

	echo __( 'This section description', 'wordpress' );

}


function jmc_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>Additional Paypal Settings</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>

<?php

require_once("extensions/customapi.php");
require_once("extensions/bcba_reports.php"); //generates custom reports
require_once("extensions/custom_profile.php");//adds a bcba nuber to profile page
require_once("extensions/custom_evaluation.php");//registers a sidebar
require_once("extensions/customAdmin.php");
require_once("extensions/class-wc-gateway-paypal.php");

/**HEART and sould of customization - waits for parent's theme to be loaded first*/
add_action( 'wp_loaded', 'parent_prefix_load_classes', 10 );


function custom_override_paypal_email1( $paypal_args, $order ) {
  //global $email;
  // $email = "mobilenterprise@gmail.com";
	$paypal_args['business'] = "janusz@izotx.com";
//	print_r($paypal_args);
//	die();

	//print_r("<h1>Email Override </h1>");
	return $paypal_args;
}

function parent_prefix_load_classes()
{
    include(CHILD_PATH.'/framework/config.php');
		//overwrite the default class model
		$themex=new ThemexCore($config);
}
		add_filter( 'woocommerce_paypal_args' , 'custom_override_paypal_email1', 10, 2  ); // it doesn't work with 1 at the end

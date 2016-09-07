<?php
require_once("extensions/customapi.php");
require_once("extensions/bcba_reports.php"); //generates custom reports
require_once("extensions/custom_profile.php");//adds a bcba nuber to profile page
require_once("extensions/custom_evaluation.php");//registers a sidebar
require_once("extensions/customAdmin.php");
require_once("extensions/class-wc-gateway-paypal.php");

/**HEART and sould of customization - waits for parent's theme to be loaded first*/
add_action( 'wp_loaded', 'parent_prefix_load_classes', 10 );

function saveToFile($text){
	$file =  CHILD_PATH.'/people.txt';
	// Open the file to get existing content
	$current = file_get_contents($file);
	// Append a new person to the file
	$current .= "$text\n";
	// Write the contents back to the file
	file_put_contents($file, $current);

}
function filter_wp_die_ajax_handler( $ajax_wp_die_handler ) {
    // make filter magic happen here...
    return $ajax_wp_die_handler;
};


//add_filter( 'wp_die_ajax_handler', 'filter_wp_die_ajax_handler', 10, 1 );

function custom_override_paypal_email1( $paypal_args, $order ) {
  	// print_r($paypal_args);
 	 //  print_r($order);
	// 	$products = $order->get_items();
	// 	$product = array_values($products)[0];
	// 	print_r($product);
	// 	$terms = get_the_terms($product['product_id'],'product_cat');
	// 	print_r($terms);
	 //
 // 	 die();

	global $woocommerce;

	foreach ( $order->get_items() as $product ) {
			$terms = get_the_terms($product['product_id'],'product_cat');
			if (!is_null($terms)){
					if(count($terms)>0)
					{
						//get first one
  					$term = $terms[0];

  				 if(strtolower($term->name)==='ethics'){
						 $paypal_args['business'] = "janusz@izotx.com";
					 }
					 else{
		 			 	$paypal_args['business'] = "mobilenterprise@izotx.com";
					 }
				}
			}
			break;
		}
//		$woocommerce->add_error( sprintf( "You must add a minimum of %s %s's to your cart to proceed." , 1, "T" ) );
	return $paypal_args;
}

function parent_prefix_load_classes()
{
    include(CHILD_PATH.'/framework/config.php');

		//overwrite the default class model
		$themex=new ThemexCore($config);
}
		add_filter( 'woocommerce_paypal_args' , 'custom_override_paypal_email1', 10, 2  ); // it doesn't work with 1 at the end

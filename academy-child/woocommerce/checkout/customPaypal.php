<?php
// $paypal_args = apply_filters( 'woocommerce_paypal_args', $paypal_args );
// $email = 	$paypal_args['business'];

//add_filter( 'woocommerce_paypal_args' , 'custom_override_paypal_email', 10, 2  );

function paypalEmailSwitch($catName){
  // global $email;
  // if(strtolower($catName)==='ethics'){
  //       $email = 'jchudzynski@uwf.edu';
  //       // Hook in
  //
  //        add_filter('woocommerce_available_payment_gateways','filter_gateways',1);
  // }
  // else{
  //   die("not ethics");
  // }
}
//https://docs.woocommerce.com/wc-apidocs/source-class-WC_Payment_Gateways.html#126-148


function filter_gateways($gateways){
    global $woocommerce;
      //Remove a specific payment option
      // $paypal = $gateways["paypal"];
      // unset($gateways['paypal']);
      // $paypal->description = "Tet Test" ;
      // $paypal->title = "Tet Test1" ;
      // $paypal->email = "jchudzynski@uwf.edu";
      // $paypal->receiver_email = "jmc@uwf.edu";
      //
      // $newPaypal = new WC_Gateway_Paypal_CEU();
      // $gateways['paypal'] = $newPaypal;
      //
      // print_r($gateways['paypal']);
    return $gateways;
}


// Our hooked in function is passed via the filter!
function custom_override_paypal_email( $paypal_args, $order ) {
  //global $email;
  // $email = "mobilenterprise@gmail.com";
	$paypal_args['business'] = "mobilenterprise@gmail.com";

	//print_r("<h1>Email Override </h1>");
	return $paypal_args;
}


//$array = array();


// function action_woocommerce_update_options_payment_gateways( $array ) {
//     // make action magic happen here...
//       print_r($array);
//       die();
// };

// add the action
//add_action( 'woocommerce_update_options_payment_gateways', 'action_woocommerce_update_options_payment_gateways', 10, 1 );

//
// do_action( 'woocommerce_update_options_payment_gateways_paypal', $array );
//
// function action_woocommerce_update_options_payment_gateways_paypal( $array ) {
//
//     print_r($array);
//     die();
//     // make action magic happen here...
// };

//add_action( "woocommerce_update_options_payment_gateways_paypal", 'action_woocommerce_update_options_payment_gateways_id', 10, 1 );

/*
'cmd'           => '_cart',
'business'      => $this->gateway->get_option( 'email' ),
'no_note'       => 1,
'currency_code' => get_woocommerce_currency(),
'charset'       => 'utf-8',
'rm'            => is_ssl() ? 2 : 1,
'upload'        => 1,
'return'        => esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ),
'cancel_return' => esc_url_raw( $order->get_cancel_order_url_raw() ),
'page_style'    => $this->gateway->get_option( 'page_style' ),
'paymentaction' => $this->gateway->get_option( 'paymentaction' ),
'bn'            => 'WooThemes_Cart',
'invoice'       => $this->gateway->get_option( 'invoice_prefix' ) . $order->get_order_number(),
'custom'        => json_encode( array( 'order_id' => $order->id, 'order_key' => $order->order_key ) ),
'notify_url'    => $this->notify_url,
'first_name'    => $order->billing_first_name,
'last_name'     => $order->billing_last_name,
'company'       => $order->billing_company,
'address1'      => $order->billing_address_1,
'address2'      => $order->billing_address_2,
'city'          => $order->billing_city,
'state'         => $this->get_paypal_state( $order->billing_country, $order->billing_state ),
'zip'           => $order->billing_postcode,
'country'       => $order->billing_country,
'email'         => $order->billing_email
*/


//https://docs.woocommerce.com/wc-apidocs/hook-docs.html

//
// /* ===================================================================
//  *
//  * Function to enable item-specific paypal email addresses
//  *
//  * ================================================================ */
//
// function find_alt_paypal_emails() {
//     global $woocommerce;
//     // loop over the items in the cart
//     foreach ( $woocommerce->cart->cart_contents as $item ) {
//         // check to see if any of them has the <code>paypal_email_override</code> meta value
//         $override_email = get_post_meta($item['product_id'], 'paypal_email_override', true);
//         // if there is an override val, build an array of each item name and the
//         // paypal email associated with that item
//         if ( ! empty( $override_email ) ) {
//             $paypal_override = array(
//                 'item_title' => $item['data']->post->post_title,
//                 'email' => $override_email
//                 );
//             $paypal_overrides[] = $paypal_override;
//             // error_log(print_r($paypal_overrides,true));
//         }
//     }
//     // if there are override emails indicated
//     if ( ! empty( $paypal_overrides ) ) {
//         // loop through and build an error message to warn the user of multiple payees
//         if ( count( $paypal_overrides ) > 1 ) {
//             $error_list = '';
//             foreach ( $paypal_overrides as $override ) {
//                 $error_list .= $override['item_title'] . ' → ' . $override['email'] . ', ';
//             }
//             $error_list = rtrim($error_list, ', ');
//             $woocommerce->add_error( __( 'The following items send payment to different '
//                 . 'PayPal Users, please place separate orders: (' . $error_list . ')', 'woocommerce' ) );
//         } else {
//             return $paypal_overrides[0]['email'];
//         }
//     }
// }
// add_action( 'woocommerce_after_cart_contents', 'find_alt_paypal_emails' );
// add_action( 'woocommerce_after_checkout_form', 'find_alt_paypal_emails' );
// add_action( 'woocommerce_after_order_total', 'find_alt_paypal_emails' );
//
// $paypal_args = apply_filters( 'woocommerce_paypal_args', $paypal_args );
// // Hook in
// add_filter( 'woocommerce_paypal_args' , 'custom_override_paypal_email' );
//
// // Our hooked in function is passed via the filter!
// function custom_override_paypal_email( $paypal_args ) {
//     $alt_email = find_alt_paypal_emails();
//     error_log(print_r($alt_email,true));
//     if ( empty( $alt_email ) ) {
//         return $paypal_args;
//     }
//     $paypal_args['business'] = find_alt_paypal_emails();
//     print_r( $paypal_args['business'],true );
//     return $paypal_args;
// }

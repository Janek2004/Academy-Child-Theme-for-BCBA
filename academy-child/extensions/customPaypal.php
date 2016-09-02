<?php
$paypal_args = apply_filters( 'woocommerce_paypal_args', $paypal_args );
// Hook in
add_filter( 'woocommerce_paypal_args' , 'custom_override_paypal_email' );

// Our hooked in function is passed via the filter!
function custom_override_paypal_email( $paypal_args ) {
	$paypal_args['business'] = 'jchudzynski@uwf.edu';
	print_r( $paypal_args['business'] );
	return $paypal_args;
}


https://docs.woocommerce.com/wc-apidocs/hook-docs.html

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

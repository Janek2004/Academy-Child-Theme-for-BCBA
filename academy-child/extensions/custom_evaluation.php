<?php

//registers sidebar
$args = array(
	'name'          => __( 'Evaluation Success Messages', 'theme_text_domain' ),
	'id'            => 'evaluation-sidebar-id',
	'description'   => '',
        'class'         => '',
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => '',
	'after_title'   => '' );

register_sidebar( $args );
// $args = array(
// 	'name'          => __( 'Evaluation Page Error Messages', 'theme_text_domain' ),
// 	'id'            => 'evaluation-sidebar-id_1',
// 	'description'   => '',
//         'class'         => '',
// 	'before_widget' => '',
// 	'after_widget'  => '',
// 	'before_title'  => '',
// 	'after_title'   => '' );
//
// register_sidebar( $args );

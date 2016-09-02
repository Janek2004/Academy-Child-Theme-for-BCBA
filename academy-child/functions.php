<?php

require_once("extensions/customapi.php");
require_once("extensions/bcba_reports.php"); //generates custom reports
require_once("extensions/custom_profile.php");//adds a bcba nuber to profile page
require_once("extensions/custom_evaluation.php");//registers a sidebar
require_once("extensions/customAdmin.php");


/**HEART and sould of customization - waits for parent's theme to be loaded first*/
add_action( 'wp_loaded', 'parent_prefix_load_classes', 10 );

function parent_prefix_load_classes()
{
    include(CHILD_PATH.'/framework/config.php');
		//overwrite the default class model
		$themex=new ThemexCore($config);
}

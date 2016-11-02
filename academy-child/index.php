<?php
if(get_query_var('course_category')) {
	get_template_part('template', 'courses');

} else {
	get_template_part('template', 'posts');
}


/**Update Metabox for Certificate*/
//Course Certificate
	function customizeMeta(){
			//die("METABOX ADDED ");
		// 	array(array(
		// 		'id' => 'certificate_metabox',
		// 		'title' =>  __('Course Certificate', 'academy'),
		// 		'page' => 'course',
		// 		'context' => 'normal',
		// 		'priority' => 'high',
		// 		'options' => array(
		// 			array(
		// 				'name' => __('Content', 'academy'),
		// 				'id' => 'certificate_content',
		// 				'type' => 'textarea',
		// 				'description' => __('Add certificate content, you can use %username%, %title%, %date% and %grade%, %credits%, %ethics_credits% keywords', 'academy'),
		// 			),
		//
		// 			array(
		// 				'name' => __('Background', 'academy'),
		// 				'id' => 'certificate_background',
		// 				'type' => 'uploader',
		// 				'description' => __('Choose background image from WordPress media library', 'academy'),
		// 			),
		// 		),
		// 	));
		// 	foreach($items as $item){
		// 	add_meta_box($item['id'], $item['title'], array('ThemexInterface', 'renderMetabox'), $item['page'], $item['context'], $item['priority'], 		array('ID' => $item['id']));
		//
		// }
//		die("METABOX ADDED ");
	}
	add_action( 'add_meta_boxes', 'customizeMeta', 10, 2 );

?>

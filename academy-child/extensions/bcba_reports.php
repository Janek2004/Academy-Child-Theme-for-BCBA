<?php
//BCBA REPORTS
/**BCBA REPORTS*/
add_action('admin_menu', 'register_submenu_pages');

function register_submenu_pages() {
	add_submenu_page( 'woocommerce', 'BCBA Report', 'BCBA Report', 'manage_options', 'BCBA-REPORT', 'register_bcba_report_submenu_page_callback' );
	add_submenu_page( 'users.php', 'Email Blast', 'Email Blast', 'manage_options', 'EMAIL-BLAST', 'register_users_submenu_page_callback' );
}



function get_courses_for_order($order){
				$items = $order->get_items();
				$products ="";
				foreach($items as $key=>$item){
						//print_r($item);

                    $products =$products." ".$item['name'];


    				}
			return $products;
		}

function getusername($order){
	$user =$order->get_user();

	if($user) {
		return $user->get('first_name')." ".$user->get('last_name');
	}
	else{
		return "Guest";
	}
}

function getuseremail($order){
	$user =$order->get_user();
	if($user) {
		return $user->get('user_email');
	}
}

function get_all_user_courses($order){
			$user =$order->get_user();
			$courses = ThemexCourse::getCourses($user->ID);
			$courses_html ='';
			foreach($courses as $course){
						//$tc = ThemexCourse::getCourse($course);
						//print_r($tc);
						$certified = "";
						if(is_course_certified($course,$user->ID)) $certified = "Certified";
						$courses_html=$courses_html."<p>". get_the_title($course)." <b>". $certified."</b></p>";
			}
		return $courses_html;
	}




function register_bcba_report_submenu_page_callback() {

	echo '<hr><div class="wrap"><div id="icon-tools" class="icon32"></div>';
	echo '<h2>Customer Orders</h2>';


		$args = array(
		  'post_type' => 'shop_order',
		  'post_status' => 'publish',
		  'meta_key' => '_customer_user',
		  'posts_per_page' => '-1',
		  'orderby'   => 'order_date'
	);
		$my_query = new WP_Query($args);




$customer_orders = $my_query->posts;



?>
<table class="wp-list-table widefat fixed posts" cellspacing="0">
    	<thead>
        <tr><th>Date</th><th>Modified Date</th><th>User</th><th>Email</th><th>Course</th><th>Status</th><th>Courses</th></tr>
        </thead>
<?php




foreach ($customer_orders as $customer_order) {
 $order = new WC_Order();

 $order->populate($customer_order);
 $orderdata = (array) $order;
	echo '<tr class="alternate">';
		//Date
		echo '<td class="column-columnname">'.$order->order_date."</td>";
		echo '<td class="column-columnname">'.$order->modified_date."</td>";
		echo '<td class="column-columnname">'.getusername($order)."</td>";
		echo '<td class="column-columnname">'.getuseremail($order)."</td>";
		echo '<td class="column-columnname">'.get_courses_for_order($order)."</td>";
		echo '<td class="column-columnname">'.$order->get_status()."</td>";
		echo '<td class="column-columnname">'.get_all_user_courses($order)."</td>";

	echo "</tr>";
}

	?>
    </table>
    <?php
	echo '</div>';
}

/**END OF BCBA REPORTS*/

function register_users_submenu_page_callback(){
	?>
	<h2>Users that opted in for further commmunication </h2>

	<?php
	$args = array(
		'meta_key'   => 'email_communication',
		'meta_value' => 'optin'
	);
	$query = new WP_User_Query( $args );

	$users = $query->results;
	?>
	<h3>Easy to copy list </h3>
    <p>
	<?php
	foreach ($users  as $user ) {
		echo $user->user_email.",";
	}
	?>
    </p>
    <table class="wp-list-table widefat fixed posts" cellspacing="0">
    	<thead>
        <tr><th>Name</th><th>Last Name</th><th>Email</th></tr>
</thead>
	<?php


foreach ($users  as $user ) {
	$meta = get_user_meta($user->ID);
	echo "<tr>";
	echo "<td>",$meta["first_name"][0]."</td>";
	echo "<td>",$meta["last_name"][0]."</td>";
	echo "<td>",$user->user_email."</td>";
	echo "</tr>";
}
?>
 </table>
<?php
}




/**Checks if course has a certificate or not*/
function is_course_certified($courseid,$userid){
	$course = ThemexCourse::getCourse($courseid);

	$content=ThemexCore::getPostMeta($course['ID'], 'course_certificate_content');
	if(empty($content)) return false; //There is no asssociated certificate
	if(!$course['progress']==100) return false; //Course is not finished yet

	$evaluation_count=get_user_meta($userid,$courseid.'_evaluation_count');
	if($evaluation_count[0]==1):
		return true;
	endif;


	return false;
}


//
// /**
//  * Returns all the orders made by the user
//  *
//  * @param int $user_id
//  * @param string $status (completed|processing|canceled|on-hold etc)
//  * @return array of order ids
//  */
// function fused_get_all_user_orders($user_id,$status='completed'){
//     if(!$user_id)
//         return false;
//
//     $orders=array();//order ids
//
//     $args = array(
//         'numberposts'     => -1,
//         'meta_key'        => '_customer_user',
//         'meta_value'      => $user_id,
//         'post_type'       => 'shop_order',
//         'post_status'     => 'publish',
//         'tax_query'=>array(
//                 array(
//                     'taxonomy'  =>'shop_order_status',
//                     'field'     => 'slug',
//                     'terms'     =>$status
//                     )
//         )
//     );
//
//     $posts=get_posts($args);
//     //get the post ids as order ids
//     $orders=wp_list_pluck( $posts, 'ID' );
//
//     return $orders;
//
// }




//
// /**
//  * Themex User
//  *
//  * Handles users data
//  *
//  * @class ThemexUser
//  * @author Themex
// */
// /*------------------------------ PAGINATION CODE------------------------------------------------*/
// function pagination($totalposts,$p,$lpm1,$prev,$next,$path){
//     $adjacents = 3;
//     if($totalposts > 1)
//     {
//         $pagination .= "<center><div>";
//         //previous button
//         if ($p > 1)
//         $pagination.= "<a href=\"$path&pg=$prev\"><< Previous</a> ";
//         else
//         $pagination.= "<span class=\"disabled\"><< Previous</span> ";
//         if ($totalposts < 7 + ($adjacents * 2)){
//             for ($counter = 1; $counter <= $totalposts; $counter++){
//                 if ($counter == $p)
//                 $pagination.= "<span class=\"current\">$counter</span>";
//                 else
//                 $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";}
//         }elseif($totalposts > 5 + ($adjacents * 2)){
//             if($p < 1 + ($adjacents * 2)){
//                 for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
//                     if ($counter == $p)
//                     $pagination.= " <span class=\"current\">$counter</span> ";
//                     else
//                     $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";
//                 }
//                 $pagination.= " ... ";
//                 $pagination.= " <a href=\"$path&pg=$lpm1\">$lpm1</a> ";
//                 $pagination.= " <a href=\"$path&pg=$totalposts\">$totalposts</a> ";
//             }
//             //in middle; hide some front and some back
//             elseif($totalposts - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
//                 $pagination.= " <a href=\"$path&pg=1\">1</a> ";
//                 $pagination.= " <a href=\"$path&pg=2\">2</a> ";
//                 $pagination.= " ... ";
//                 for ($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
//                     if ($counter == $p)
//                     $pagination.= " <span class=\"current\">$counter</span> ";
//                     else
//                     $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";
//                 }
//                 $pagination.= " ... ";
//                 $pagination.= " <a href=\"$path&pg=$lpm1\">$lpm1</a> ";
//                 $pagination.= " <a href=\"$path&pg=$totalposts\">$totalposts</a> ";
//             }else{
//                 $pagination.= " <a href=\"$path&pg=1\">1</a> ";
//                 $pagination.= " <a href=\"$path&pg=2\">2</a> ";
//                 $pagination.= " ... ";
//                 for ($counter = $totalposts - (2 + ($adjacents * 2)); $counter <= $totalposts; $counter++){
//                     if ($counter == $p)
//                     $pagination.= " <span class=\"current\">$counter</span> ";
//                     else
//                     $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";
//                 }
//             }
//         }
//         if ($p < $counter - 1)
//         $pagination.= " <a href=\"$path&pg=$next\">Next >></a>";
//         else
//         $pagination.= " <span class=\"disabled\">Next >></span>";
//         $pagination.= "</center>\n";
//     }
//     return $pagination;
// }

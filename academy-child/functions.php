<?php
/*-------------------------------USER REGISTER BCBA NO----------------------------------------------*/
add_action('admin_footer_text', 'hack_add_custom_user_profile_fields');

function hack_add_custom_user_profile_fields(){
    global $pagenow;

    # do this only in page user-new.php
    if($pagenow !== 'user-new.php')
        return;

    # do this only if you can
    if(!current_user_can('manage_options'))
        return false;

?>
<table id="table_my_custom_field" style="display:none;">
<!-- My Custom Code { -->
    <tr>
    <th><label for="address"><?php _e("BCBA No."); ?></label></th>
    <td>
    <input type="text" name="user_bcba" id="user_bcba"  value="" class="regular-text" /><br/>
    <span class="description"><?php _e("BCBA No must Be Unique."); ?></span>
    </td>
    </tr>
<!-- } -->
</table>
<script>
jQuery(function($){
    //Move my HTML code below user's role
    $('#table_my_custom_field tr').insertAfter($('#role').parentsUntil('tr').parent());
});
</script>
<?php
}

function save_custom_user_profile_fields($user_id){
    # again do this only if you can
    if(!current_user_can('manage_options'))
        return false;

   
			$bcba_no_flag=0;
			$all_meta_for_user = mysql_query("SELECT meta_value FROM wp_usermeta WHERE meta_key='_themex_bcba_no'");
			while($row = mysql_fetch_array($all_meta_for_user))
			{
				if($row['meta_value']==$_POST['user_bcba'])
				{
					$bcba_no_flag=1;
				}
			}
			
			if($bcba_no_flag==1){
			?>
            <script>alert("Please select a unique BCBA No.");</script>
            <?php
			}else{
				update_usermeta($user_id,'_themex_bcba_no', $_POST['user_bcba'] );
			}
}
add_action('user_register', 'save_custom_user_profile_fields');

/*-------------------------------USER REGISTER BCBA NO------------[END]--------------------------------------*/


/*-------------------------------UPDATE USER BCBA NO-------------------------------------------------*/
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }


			$bcba_no_flag=0;
			$all_meta_for_user = mysql_query("SELECT meta_value FROM wp_usermeta WHERE meta_key='_themex_bcba_no' AND user_id<>$user_id");
			while($row = mysql_fetch_array($all_meta_for_user))
			{
				if($row['meta_value']==$_POST['user_bcba'])
				{
					$bcba_no_flag=1;
				}
			}
			
			if($bcba_no_flag==1){
			?>
            <script>
			alert("Please select a unique BCBA No.");
            </script>
            <a href="javascript:history.go(-1)" class="button button-primary">Go Back</a>
            <?php
			die();
			}else{
				update_usermeta($user_id,'_themex_bcba_no', $_POST['user_bcba'] );
			}


}



add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) {
	 ?>
<h3><?php _e("User BCBA No.", "blank"); ?></h3>

<table class="form-table">
<tr>
<th><label for="address"><?php _e("BCBA No."); ?></label></th>
<td>
<?php
  $bcba_no = get_user_meta($user->ID,'_themex_bcba_no');
?>
<input type="text" name="user_bcba" id="user_bcba"  value="<?php echo $bcba_no[0]; ?>" class="regular-text" /><br />
<span class="description"><?php _e("BCBA No must Be Unique."); ?></span>
</td>
</tr>
</table>
<?php }
/*-------------------------------UPDATE USER BCBA NO-------------[END]------------------------------------*/


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
$args = array(
	'name'          => __( 'Evaluation Page Error Messages', 'theme_text_domain' ),
	'id'            => 'evaluation-sidebar-id_1',
	'description'   => '',
        'class'         => '',
	'before_widget' => '',
	'after_widget'  => '',
	'before_title'  => '',
	'after_title'   => '' ); 

register_sidebar( $args ); 



/**
 * Themex User
 *
 * Handles users data
 *
 * @class ThemexUser
 * @author Themex
*/
/*------------------------------ PAGINATION CODE------------------------------------------------*/
function pagination($totalposts,$p,$lpm1,$prev,$next,$path){
    $adjacents = 3;
    if($totalposts > 1)
    {
        $pagination .= "<center><div>";
        //previous button
        if ($p > 1)
        $pagination.= "<a href=\"$path&pg=$prev\"><< Previous</a> ";
        else
        $pagination.= "<span class=\"disabled\"><< Previous</span> ";
        if ($totalposts < 7 + ($adjacents * 2)){
            for ($counter = 1; $counter <= $totalposts; $counter++){
                if ($counter == $p)
                $pagination.= "<span class=\"current\">$counter</span>";
                else
                $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";}
        }elseif($totalposts > 5 + ($adjacents * 2)){
            if($p < 1 + ($adjacents * 2)){
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
                    if ($counter == $p)
                    $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                    $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";
                }
                $pagination.= " ... ";
                $pagination.= " <a href=\"$path&pg=$lpm1\">$lpm1</a> ";
                $pagination.= " <a href=\"$path&pg=$totalposts\">$totalposts</a> ";
            }
            //in middle; hide some front and some back
            elseif($totalposts - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
                $pagination.= " <a href=\"$path&pg=1\">1</a> ";
                $pagination.= " <a href=\"$path&pg=2\">2</a> ";
                $pagination.= " ... ";
                for ($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
                    if ($counter == $p)
                    $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                    $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";
                }
                $pagination.= " ... ";
                $pagination.= " <a href=\"$path&pg=$lpm1\">$lpm1</a> ";
                $pagination.= " <a href=\"$path&pg=$totalposts\">$totalposts</a> ";
            }else{
                $pagination.= " <a href=\"$path&pg=1\">1</a> ";
                $pagination.= " <a href=\"$path&pg=2\">2</a> ";
                $pagination.= " ... ";
                for ($counter = $totalposts - (2 + ($adjacents * 2)); $counter <= $totalposts; $counter++){
                    if ($counter == $p)
                    $pagination.= " <span class=\"current\">$counter</span> ";
                    else
                    $pagination.= " <a href=\"$path&pg=$counter\">$counter</a> ";
                }
            }
        }
        if ($p < $counter - 1)
        $pagination.= " <a href=\"$path&pg=$next\">Next >></a>";
        else
        $pagination.= " <span class=\"disabled\">Next >></span>";
        $pagination.= "</center>\n";
    }
    return $pagination;
}


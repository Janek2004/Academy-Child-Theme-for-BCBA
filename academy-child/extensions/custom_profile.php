<?php
//Responsible for managing profile operations



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

<?php 
if(isset($_POST['submit']))
{
	$answer_counter=$_POST['answer_counter'];
	$postId=$_POST['post_id'];
	$userId=$_POST['user_id'];

	for($i=1; $i<=$answer_counter; $i++)
	{
		$answer_ratings[]=$_POST['answer'.$i];
	}
	$avg_rating_user=array_sum($answer_ratings)/$answer_counter;
	
	$serialize_ans_rating=serialize($answer_ratings);
	
	$timestamp = strtotime(date("Y-m-d H:i:s"));
	
	$meta_key="rating_".$postId; //rating meta key
	
	$ans_meta_key=$timestamp."_evaluation_ans_".$postId;
	update_user_meta($userId, $meta_key, $avg_rating_user);
	update_user_meta($userId, $postId."_evaluation_count",1);
	update_user_meta($userId,$ans_meta_key,$serialize_ans_rating);
	
	
	
	$all_meta_for_user = mysql_query("SELECT meta_value FROM wp_usermeta WHERE meta_key='rating_$postId'");
	$total_user=0;		
			while($row = mysql_fetch_array($all_meta_for_user))
			{
				$array_total_rating[]=$row['meta_value'];
				$total_user++;		
			}
	}

?>

<?php
/*
Template Name: Evaluation
*/
?>
<?php get_header(); ?>

  	
<?php
$userId=$_REQUEST['userid'];
$postId=$_REQUEST['post_id'];
?>
<?php ThemexCourse::refresh($postId); ?>
	
  
<?php
function displayEvalFields($postId,$userId){?>
<div class="quiz-listing">
<form name="evaluation_form" action="" method="post">
<?php
if( get_field('evaluation', $postId) )
{
	$ans_meta_key="evaluation_ans_".$postId;
	$evaluationUserAnsSeralized=get_user_meta($userId,$ans_meta_key,"true");
	if($evaluationUserAnsSeralized):
	//$evaluationAnsArray=unserialize($evaluationUserAnsSeralized);
	endif; 
	
	$arrayAnswers=mysql_query("SELECT * FROM wp_usermeta WHERE meta_key LIKE '%_evaluation_ans_".$postId."%' AND user_id=$userId ORDER BY meta_key DESC LIMIT 0,1");
	
	$row=mysql_fetch_row($arrayAnswers);
	$evaluationAnsArray=unserialize(unserialize($row[3]));		
	
	$answer_counter=1;
	$counter=0
	
	
?>
	
	<?php 
    while(the_repeater_field('evaluation', $postId) )
    { 	?>
    <div class="quiz-question multiple">
	<div class="question-title">
    	<div class="question-number"><?php echo $answer_counter; ?></div>
        <h4 class="nomargin"><?php  echo get_sub_field('question'); ?></h4>
    </div>
   	
    <ul>
        <?php $i =0; while(has_sub_field('answers')): ?>
        	<input type="hidden" name="answer_counter" value="<?php echo $answer_counter; ?>">
        	<input type="hidden" name="post_id" value="<?php echo $postId; ?>">
          <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
          <?php $rating=get_sub_field('ratings'); ?>
            <li><input type="radio" <?php if($i==0){ ?>checked="checked"<?php } ?> name="answer<?php echo $answer_counter; ?>" value="<?php the_sub_field('ratings'); ?>"><?php the_sub_field('options'); ?></li>
			
        <?php $i++;  endwhile; ?>
        </ul>
        
	</div>
   <?php
   $counter++;
   $answer_counter++;
    }
 	?>
   <div class="margin_top20">
    <input type="submit" value="submit" name="submit">
    </div>
<?php
}
?>
</form>
</div>
<?php
}

 if(ThemexCourse::hasMembers() || is_active_sidebar('course') || !empty(ThemexCourse::$data['sidebar'])) { ?>
<div class="sixcol column">
<?php } else { ?>
<div class="ninecol column">
<?php } ?>
	
	<span class="view_certificate_evaluation">
		<a class="button dark"  href="<?php echo site_url()."/". get_the_slug($postId); ?>"><?php echo "Go Back to ". get_the_title($postId); ?></a>
	</span>
  
	<div class="course-description widget <?php echo ThemexCourse::$data['status']; ?>-course">
		<div class="widget-title">
			<h4 class="nomargin"><?php _e('Evaluation', 'academy'); ?></h4>
		</div>
		<div class="widget-content">
			
<div class="message">
<?php
//$evaluation_count=get_user_meta(ThemexUser::$data['user']['ID'],ThemexCourse::$data['ID'].'_evaluation_count'); 	
$evaluation_count = get_user_meta($userId, $postId."_evaluation_count",false);?> 
<?php //echo evaluation_count[0]; ?>

<?php  if($evaluation_count[0]==1): ?>
	

	
</span>
<?php endif;
 if($success_message==0):?>
<span class="error"></span>
<?php endif; ?>
</div>

			<?php
	
				if( count($evaluation_count)==0)
				{
						displayEvalFields($postId, $userId);			
				}
				else{
									
	?>
      
      <span class="success"><?php dynamic_sidebar('evaluation-sidebar-id'); ?>
            <br />
		
			
						<span class=".view_certificate_evaluation">
    <a href="<?php echo ThemexCore::getURL('certificate', themex_encode($postId, $userId)); ?>" target="_blank" class="button dark "><?php _e('View Certificate', 'academy'); ?></a>
    </span>
						</span>
				

            
         <?php	
				}
			 ?>
	

		</div>						
	</div>
</div>
<?php if(ThemexCourse::hasMembers() || is_active_sidebar('course') || !empty(ThemexCourse::$data['sidebar'])) { ?>
<aside class="sidebar threecol column last">
	<?php
	echo do_shortcode(themex_html(ThemexCourse::$data['sidebar']));
	
	if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('course'));
	
	if(ThemexCourse::hasMembers()) {
		get_template_part('module', 'users');
	}
	?>
</aside>
<?php } ?>
<?php
function get_the_slug( $id=null ){
  if( empty($id) ):
    global $post;
    if( empty($post) )
      return ''; // No global $post var available.
    $id = $post->ID;
  endif;

  $slug = basename( get_permalink($id) );
  return $slug;
}
?>
<!---------------------------Evaluation ------------------------------------------------>

<?php get_footer(); ?>
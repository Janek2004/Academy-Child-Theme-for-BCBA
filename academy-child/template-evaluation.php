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
			//print_r($array_total_rating);
			$final_avg_rating=array_sum($array_total_rating)/$total_user;
			
			$finalCompletion=update_post_meta($postId,"_course_rating", $final_avg_rating);
			if($finalCompletion){ 
				$success_message=1; 
			}
			else{ $success_message=0;	}
			
			
			
			
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
<div class="threecol column">
<?php ThemexCourse::refresh($postId); ?>
<div class="course-preview <?php echo ThemexCourse::$data['status']; ?>-course">
	<div class="course-image">
  		<a href="<?php the_permalink(); ?>"><?php //the_post_thumbnail('normal');
		//echo "[video_lightbox_vimeo5 video_id=".get_field('course_demo_video')."width=800 height=450 auto_thumb='1']"
		$video_id=get_field('course_demo_video',$postId);
		if($video_id!="")
		{
			echo do_shortcode("[video_lightbox_vimeo5 video_id=".get_field('course_demo_video',$postId)." width=800 height=450 auto_thumb='1']");
			
		}else{
			//echo "[video_lightbox_vimeo5 video_id=".get_field('course_demo_video')." width=800 height=450 auto_thumb='1']";
			echo get_the_post_thumbnail( $postId, 'normal'); 
		}
		
		 ?></a>
		<?php if(empty(ThemexCourse::$data['plans']) && ThemexCourse::$data['status']!='private') { ?>
		<div class="course-price product-price">
			<div class="price-text"><?php echo ThemexCourse::$data['price']['text']; ?></div>
			<div class="corner-wrap">
				<div class="corner"></div>
				<div class="corner-background"></div>
			</div>			
		</div>
		<?php } ?>
	</div>
	<div class="course-meta">
		<header class="course-header">
      	<h5 class="nomargin"><a href="<?php echo site_url()."/". get_the_slug($postId); ?>"><?php echo get_the_title($postId); ?></a></h5>
			<?php if(!ThemexCore::checkOption('course_author')) { ?>
			<a href="<?php echo ThemexCourse::$data['author']['profile_url']; ?>" class="author"><?php echo ThemexCourse::$data['author']['profile']['full_name']; ?></a>
			<?php } ?>
		</header>
		<?php if(!ThemexCore::checkOption('course_popularity') || !ThemexCore::checkOption('course_rating')) { ?>
		<footer class="course-footer clearfix">
			<?php if(!ThemexCore::checkOption('course_popularity')) { ?>
			<div class="course-users left">
				<?php echo ThemexCore::getPostMeta($postId, 'course_popularity', '0'); ?>
			</div>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('course_rating')) { ?>
			<?php get_template_part('module', 'rating'); ?>
			<?php } ?>
		</footer>
		<?php } ?>
	</div>
</div>
</div>
<?php if(ThemexCourse::hasMembers() || is_active_sidebar('course') || !empty(ThemexCourse::$data['sidebar'])) { ?>
<div class="sixcol column">
<?php } else { ?>
<div class="ninecol column last">
<?php } ?>
	<div class="course-description widget <?php echo ThemexCourse::$data['status']; ?>-course">
		<div class="widget-title">
			<h4 class="nomargin"><?php _e('Evaluation', 'academy'); ?></h4>
		</div>
		<div class="widget-content">
			
<div class="message">
<?php
$evaluation_count=get_user_meta(ThemexUser::$data['user']['ID'],ThemexCourse::$data['ID'].'_evaluation_count'); 	?> 
<?php  if($success_message==1 || $evaluation_count[0]==1): ?>
<?php if($success_message==1){ ?>
	<span class="success"><?php dynamic_sidebar('evaluation-sidebar-id'); ?>
<?php } ?>
	<span class="view_certificate">
    <a href="<?php echo ThemexCore::getURL('certificate', themex_encode(ThemexCourse::$data['ID'], ThemexUser::$data['user']['ID'])); ?>" target="_blank" class="button dark "><?php _e('View Certificate', 'academy'); ?></a>
    </span>
</span>
<?php endif;
 if($success_message==0):?>
<span class="error"></span>
<?php endif; ?>
</div>
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
        <?php while(has_sub_field('answers')): ?>
        	<input type="hidden" name="answer_counter" value="<?php echo $answer_counter; ?>">
        	<input type="hidden" name="post_id" value="<?php echo $postId; ?>">
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <?php $rating=get_sub_field('ratings'); ?>
            <li><input type="radio" <?php if($evaluationAnsArray[$counter]==$rating){ ?>checked="checked"<?php } ?> name="answer<?php echo $answer_counter; ?>" value="<?php the_sub_field('ratings'); ?>"><?php the_sub_field('options'); ?></li>
        
        <?php  endwhile; ?>
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
<?php
get_header();

the_post();
ThemexLesson::refresh($post->ID, true);
ThemexCourse::refresh(ThemexLesson::$data['course'], true);
$layout=ThemexCore::getOption('lessons_layout', 'right');

if($layout=='left') {
?>

<aside class="sidebar column fourcol">
  <?php get_sidebar('lesson'); ?>
</aside>
<div class="column eightcol last">
<?php } else { ?>
<div class="column eightcol">
  <?php } ?>
  <h1>
    <?php the_title(); ?>
  </h1>
  <?php 
	if(ThemexLesson::$data['prerequisite']['progress']==0 && ThemexLesson::$data['status']!='free' && ThemexCore::checkOption('lesson_hide')) { 
		printf(__('Complete "%s" lesson before taking this lesson.', 'academy'), '<a href="'.get_permalink(ThemexLesson::$data['prerequisite']['ID']).'">'.get_the_title(ThemexLesson::$data['prerequisite']['ID']).'</a>');
	}
	else {
		the_content();
	
	}
	
	
	
	?>
  <?php
	//print_r( ThemexLesson::$data['quiz']);
 		if(!empty(ThemexLesson::$data['quiz']))
		{		
?>
		<a href="<?php echo get_permalink(ThemexLesson::$data['quiz']['ID']); ?>" class="button"> <span class="button-icon edit"> </span>
		<?php _e('Take the Quiz', 'academy'); ?>
		</a>
		
  <?php  
	}
	else{
			if(ThemexLesson::$data['progress']==100){
	?>
			

		<?php
		}
		else{?>
    	<p>Are you done with the lesson? Please mark it as complete by clicking on the button below. </p>
      <form action="<?php echo themex_url(); ?>" method="POST">
      	<a href="#" class="button submit-button"><span class="button-icon check"></span><?php _e('Mark Complete', 'academy'); ?></a>
			<input type="hidden" name="lesson_action" value="complete_lesson" />
			<input type="hidden" name="course_action" value="complete_course" />
      <input type="hidden" name="lesson_id" value="<?php echo ThemexLesson::$data['ID']; ?>" />
	  	<input type="hidden" name="course_id" value="<?php echo ThemexCourse::$data['ID']; ?>" />
		  <input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		  <input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_lesson" />
      
      </form>
		<br>
		
		<?php
    	}
	}
	?>
	<?php
		if((ThemexCourse::$data['progress']==100) &&(ThemexLesson::$data['progress']==100)){
					$post_id_evaluate=ThemexCourse::$data['ID'];
					$evaluation_count=get_user_meta(ThemexUser::$data['user']['ID'],$post_id_evaluate.'_evaluation_count'); 
					if($evaluation_count[0]==1){
					?>
						 <a href="<?php echo ThemexCore::getURL('certificate', themex_encode(ThemexCourse::$data['ID'], ThemexUser::$data['user']['ID'])); ?>" target="_blank" class="button medium certificate-button"><?php _e('View Certificate', 'academy'); ?></a>
						<br>	<br>
					<?php
						
					}
					else{?>
										
						<a href="<?php echo get_page_link(14); ?>&userid=<?php echo ThemexUser::$data['user']['ID']; ?>&post_id=<?php echo ThemexCourse::$data['ID']; ?>" target="_blank" class="button submit-button btn_possition"><?php _e('Course Evaluation', 'academy'); ?></a>	
						<br>
					<?php
					}

	}
?>	
	

</div>
<?php if($layout=='right') { ?>
<aside class="sidebar fourcol column last">
  <?php get_sidebar('lesson'); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>

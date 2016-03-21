<div class="lesson-options">
	<?php if(ThemexCourse::isMember()) { ?>
	<form action="<?php echo themex_url(); ?>" method="POST">
		<?php if(ThemexLesson::$data['progress']!=0) { ?>
			<?php if(!ThemexCore::checkOption('lesson_retake')) { ?>
			<a href="#" class="button finish-lesson submit-button"><?php _e('Mark In`', 'academy'); ?></a>
			<input type="hidden" name="lesson_action" value="uncomplete_lesson" />
			<input type="hidden" name="course_action" value="uncomplete_course" />
			<?php } ?>
		<?php } else if(ThemexLesson::$data['prerequisite']['progress']!=0) { ?>
		<?php if(is_singular('quiz')) { ?>
			<a href="#quiz_form" class="button submit-button"><span class="button-icon check"></span><?php _e('Complete Quiz', 'academy'); ?></a>		
			<?php } else if(!empty(ThemexLesson::$data['quiz'])) { ?>
			<a href="<?php echo get_permalink(ThemexLesson::$data['quiz']['ID']); ?>" class="button"><span class="button-icon edit"></span><?php _e('Take the Quiz', 'academy'); ?></a>
			<?php } else { ?>
			<a href="#" class="button submit-button"><span class="button-icon check"></span><?php _e('Mark Complete', 'academy'); ?></a>
			<input type="hidden" name="lesson_action" value="complete_lesson" />
			<input type="hidden" name="course_action" value="complete_course" />
			<?php } ?>
		<?php } ?>
		<input type="hidden" name="lesson_id" value="<?php echo ThemexLesson::$data['ID']; ?>" />
		<input type="hidden" name="course_id" value="<?php echo ThemexCourse::$data['ID']; ?>" />
		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_lesson" />
	</form>
	<?php } ?>
	<?php if(ThemexLesson::$data['ID']!=0) { ?>
	<a href="<?php echo get_permalink(ThemexCourse::$data['ID']); ?>" title="<?php _e('Close Lesson', 'academy'); ?>" class="button close-lesson secondary"><span class="button-icon nomargin close"></span></a>
	<?php } ?>
	<?php if($next=ThemexCourse::getAdjacentLesson(ThemexLesson::$data['ID'])) { ?>
	<a href="<?php echo get_permalink($next->ID); ?>" title="<?php _e('Next Lesson', 'academy'); ?>" class="button next-lesson secondary"><span class="button-icon nomargin next"></span></a>
	<?php } ?>
	<?php if($prev=ThemexCourse::getAdjacentLesson(ThemexLesson::$data['ID'], false)) { ?>
	<a href="<?php echo get_permalink($prev->ID); ?>" title="<?php _e('Previous Lesson', 'academy'); ?>" class="button prev-lesson secondary"><span class="button-icon nomargin prev"></span></a>
	<?php } ?>
    
	<?php 
     if(!empty(ThemexCourse::$data['lessons'])) { 
            if(ThemexCourse::isMember()) { 
                if(ThemexCourse::$data['progress'] >= 100)
				{
					$user_id=ThemexUser::$data['user']['ID'];
					$course_id=ThemexCourse::$data['ID'];
					$course_progress_key="courseProgress_".$course_id;
					update_user_meta( $user_id, $course_progress_key,1);
				
					$post_id_evaluate=ThemexCourse::$data['ID'];
					$evaluation_count=get_user_meta(ThemexUser::$data['user']['ID'],$post_id_evaluate.'_evaluation_count'); 
					if($evaluation_count[0]==1){
					?>
						 <a href="<?php echo ThemexCore::getURL('certificate', themex_encode(ThemexCourse::$data['ID'], ThemexUser::$data['user']['ID'])); ?>" target="_blank" class="button medium certificate-button"><?php _e('View Certificate', 'academy'); ?></a>
					<?php
						
					}
					else{?>
										
						<a href="<?php echo get_page_link(14); ?>&userid=<?php echo ThemexUser::$data['user']['ID']; ?>&post_id=<?php echo ThemexCourse::$data['ID']; ?>" target="_blank" class="button submit-button btn_possition"><?php _e('Course Evaluation', 'academy'); ?></a>	
					<?php
					}
				
				?>
				
				

            <?php } 
			else{
					$user_id=ThemexUser::$data['user']['ID'];
					$course_id=ThemexCourse::$data['ID'];
					$course_progress_key="courseProgress_".$course_id;
					update_user_meta( $user_id, $course_progress_key,0);
				}
             } 
        } 
    ?>    
</div>

<?php
echo do_shortcode(themex_html(ThemexLesson::$data['sidebar']));

if(!empty(ThemexLesson::$data['attachments'])) {
	get_template_part('module', 'attachments');
}

if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('lesson'));

if(!empty(ThemexCourse::$data['lessons'])) {
	get_template_part('module', 'lessons');
}
?>
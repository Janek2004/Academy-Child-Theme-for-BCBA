<?php 
error_reporting(0);
get_header(); 

the_post();
ThemexLesson::refresh(ThemexCore::getPostRelations($post->ID, 0, 'quiz_lesson', true), true);
ThemexCourse::refresh(ThemexLesson::$data['course'], true);
$layout=ThemexCore::getOption('lessons_layout', 'right');

if($_POST['lesson_action']=="complete_quiz")
{
$user_id = get_current_user_id();
$course_id=$_POST['course_id'];
$lesson_id=$_POST['lesson_id'];

$quiz_id=$post->ID;

$timestamp = strtotime(date("Y-m-d H:i:s"));

$meta_key=$timestamp."_userAns_".$lesson_id."_".$course_id;  // user answers for quiz

$ques_meta_key="userQues_".$lesson_id."_".$course_id;
unset($_POST['course_action'],$_POST['lesson_action'],$_POST['course_id'],$_POST['lesson_id'],$_POST['nonce'],$_POST['action']);
$meta_value = serialize($_POST);
update_user_meta( $user_id, $meta_key, $meta_value);

$ques_meta_array=serialize(ThemexLesson::$data['quiz']['questions']);
update_user_meta( $user_id, $ques_meta_key, $ques_meta_array);

$quiz_id_key="quizId_".$lesson_id."_".$course_id;
update_user_meta( $user_id, $quiz_id_key, $quiz_id);




$result=0;
$ques_counter=0;	
$wrong_array = array();

foreach(ThemexLesson::$data['quiz']['questions'] as $ID => $question) {
							$passed=true;
							
							if($question['type']=='multiple'){
							foreach($question['answers'] as $key => $answer) {
								if((isset($_POST[$ID][$key]) && !isset($answer['result'])) || (isset($answer['result']) && !isset($_POST[$ID][$key]))) {
									$passed=false;
														
								}
							}
							
							if($passed) {
								$result++;
							}
							}else {
							foreach($question['answers'] as $key => $answer) {
														
								$passed = true;
								if(isset($_POST[$ID]) && $_POST[$ID]==$key && isset($answer['result'])) {
									$result++;
									break;
								}
								else{
								  $passed = false;						
								}
							}
						}
						if($passed == false){
							
							array_push($wrong_array, $ques_counter+1);
						
						}
						
													

						$ques_counter++;
}

$quiz_percentage_key=$timestamp."otherDetails_".$lesson_id."_".$course_id;
$percentageResult=($result/$ques_counter)*100;
update_user_meta( $user_id, $quiz_percentage_key, $percentageResult);

}
if($layout=='left') {
?>
<aside class="sidebar column fourcol">
	<?php get_sidebar('lesson'); ?>
</aside>
<div class="column eightcol last">
<?php } else { ?>
<div class="eightcol column">
<?php } ?>

	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
		
	<div class="quiz-listing">
	
		<form id="quiz_form" action="<?php the_permalink(); ?>" method="POST">
			<div class="message">
			<ul class="success"> <li><?php if($ques_counter){ echo $result ." Answers are correct out of ".$ques_counter." Questions";
			
			
			} ?></li></ul>
				<?php // Printing incorrect questions
			if(count($wrong_array)>0){ ?>
			<p>Questions answered incorrectly: </p>
<?php
			$ic =0;				
			foreach($wrong_array as $qid){
				if($ic<count($wrong_array)-1){
				echo $qid.",";														
														
				}
				else{
					echo $qid.".";														

				}
				$ic++;			
							}							
			}				
?>								
			
				<?php ThemexInterface::renderMessages(ThemexLesson::$data['progress']); ?>
		
			</div>
			<?php 
			$counter=0;
		
			foreach(ThemexLesson::$data['quiz']['questions'] as $key => $question) {
			$counter++;
			?>
			<div class="quiz-question <?php echo $question['type']; ?>">
      
      
				<div class="question-title">
					<div class="question-number"><?php echo $counter; ?></div>
					<h4 class="nomargin"><?php echo themex_stripslashes($question['title']); ?></h4>
				</div>
				<?php 
			
				
				ThemexLesson::renderAnswers($key, $question); ?>
			</div>
			<?php } 					
	
			?>
			
    
			<input type="hidden" name="course_action" value="complete_course" />
			<input type="hidden" name="lesson_action" value="complete_quiz" />
			<input type="hidden" name="course_id" value="<?php echo ThemexCourse::$data['ID']; ?>" />
			<input type="hidden" name="lesson_id" value="<?php echo ThemexLesson::$data['ID']; ?>" />
			<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
			<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_lesson" />
		</form><br />
		<form action="<?php echo themex_url(); ?>" method="POST">
				<a href="#quiz_form" class="button submit-button"><span class="button-icon check"></span><?php _e('Complete Quiz', 'academy'); ?></a>	</p>
<input type="hidden" name="lesson_id" value="<?php echo ThemexLesson::$data['ID']; ?>" />
		<input type="hidden" name="course_id" value="<?php echo ThemexCourse::$data['ID']; ?>" />
		<input type="hidden" name="nonce" class="nonce" value="<?php echo wp_create_nonce(THEMEX_PREFIX.'nonce'); ?>" />
		<input type="hidden" name="action" class="action" value="<?php echo THEMEX_PREFIX; ?>update_lesson" />							
</form>
	</div>
</div>
<?php if($layout=='right') { ?>
<aside class="sidebar fourcol column last">
	<?php get_template_part('sidebar', 'lesson'); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>
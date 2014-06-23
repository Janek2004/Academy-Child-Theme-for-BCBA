<div id="themex-statistics" class="themex-statistics wrap">
	<div id="icon-edit" class="icon32"><br></div><h2><?php _e('Statistics', 'academy'); ?></h2>
   <div id="poststuff">
    	<?php if(isset($_GET['course_id']) && isset($_GET['lesson_id'])){ ?>
         <a class="button medium" href='javascript:history.go(-1)'>Go Back</a>
        <div class="note"><h4>Note: <span style="font-style:italic">The Options in Blue Color are the Correct Answers</span></h4></div>
       	<?php 
			$course_id=$_GET['course_id'];
			$lesson_id=$_GET['lesson_id'];
			$user_id=$_GET['user'];
			
			$user_info = get_userdata($user_id); // user data
			$course_data=get_post($course_id); //course data
			$lesson_data=get_post($lesson_id); // lesson data
			
			$user_bcba=get_user_meta($user_id,'_themex_bcba_no', $single);
			
			$quiz_meta_key="quizId_".$lesson_id."_".$course_id;
			$quiz_id=get_user_meta($user_id, $quiz_meta_key,"true");
			
			$course_progress_key="courseProgress_".$course_id;
			$course_progress=get_user_meta($user_id, $course_progress_key,"true");
			
			//}
			
			?>
            <div style="width:49%; float:left;">
            <table width="100%" class="widefat spaced">
              <thead>
                <tr>
                  <th class="total_row">Student Report </th>
                  <th class="total_row">&nbsp;</th>
                </tr>
              </thead>
             <tfoot>
                <tr>
                  <td class="total_row">Name</td>
                  <td class="total_row"><?php echo $user_info->first_name; ?></td>
                </tr>
                <tr>
                  <td class="total_row">Username</td>
                  <td class="total_row"><?php echo $user_info->user_login; ?></td>
                </tr>
                <tr>
                  <td class="total_row">Last Name</td>
                  <td class="total_row"><?php echo $user_info->last_name; ?></td>
                </tr>
                <tr>
                  <td class="total_row">BCBA Number</td>
                  <td class="total_row"><?php echo $user_bcba[0]; ?></td>
                </tr>
              </tfoot>
            </table> 
            </div>
            <div style="width:49%; float:left; margin-left:20px;">
            <table width="100%" class="widefat spaced">
              <thead>
                <tr>
                  <th class="total_row">Courses</th>
                  <th class="total_row">&nbsp;</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td class="total_row">Course Name</td>
                  <td class="total_row"><?php echo $course_data->post_title; ?></td>
                </tr>
                <tr>
                  <td class="total_row">Completed</td>
                  <td class="total_row"><?php if($course_progress==1){ echo "Yes"; }else{ echo "No"; } ?></td>
                </tr>
                <tr>
                  <td class="total_row">Certificate</td>
                  <td class="total_row"> <a href="<?php echo ThemexCore::getURL('certificate', themex_encode($course_id, $user_id)); ?>" target="_blank" ><?php _e('View Certificate', 'academy'); ?></a></td>
                </tr>
                <tr>
                  <td class="total_row">Quiz </td>
                  <td class="total_row"><?php echo get_the_title($quiz_id); ?></td>
                </tr>
            </table>
            </div>
          
          			<?php //echo $lesson_data->post_title; ?> 
			
			
			<?php
			$ques_meta_key="userQues_".$lesson_id."_".$course_id; //get questions for user meta
			$serealizedArrayQues=get_user_meta($user_id, $ques_meta_key,"true");
			$multipleQuesArray=unserialize($serealizedArrayQues); // unserialize question array
			
			/*---------------------------------PAGINATION CODE----------------------------------------*/
			$current_page_path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			list($first,$second) = explode('&pg',$current_page_path,2);
			/*Max Number of results to show*/
			$max =2;
			/*Get the current page eg index.php?pg=4*/
			
			if(isset($_GET['pg'])){
				$p = $_GET['pg'];
			}else{
				$p = 1;
			}
			
			$limit = ($p - 1) * $max;
			$prev = $p - 1;
			$next = $p + 1;
			$limits = (int)($p - 1) * $max;
			
			//Get total records from db
			$totalres = mysql_result(mysql_query("SELECT COUNT(umeta_id) AS tot FROM wp_usermeta WHERE meta_key LIKE '%_userAns_".$lesson_id."_".$course_id."%' AND user_id=$user_id"),0);
			//devide it with the max value & round it up
			$totalposts = ceil($totalres / $max);
			$lpm1 = $totalposts - 1;
			
			/*---------------------------------PAGINATION CODE----------------------------------------*/
			
			$arrayAnswers=mysql_query("SELECT * FROM wp_usermeta WHERE meta_key LIKE '%_userAns_".$lesson_id."_".$course_id."%' AND user_id=$user_id ORDER BY meta_key DESC limit $limits,$max");
			
			$quizPercentage=mysql_query("SELECT * FROM wp_usermeta WHERE meta_key LIKE '%otherDetails_".$lesson_id."_".$course_id."%' AND user_id=$user_id ORDER BY meta_key DESC limit $limits,$max ");
			
			while($quizValue=mysql_fetch_array($quizPercentage))
			{
				$quizPercentageArray[]=$quizValue[3];
			}
			
			//print_r($quizPercentageArray);
			
			//print_r(mysql_fetch_array($arrayAnswers));
			$percentageCounter=0;
			if(!empty($multipleQuesArray)){
				if(mysql_num_rows($arrayAnswers)>0){
			while($row=mysql_fetch_array($arrayAnswers))
			{
			$arrayUnserializeAns=unserialize(unserialize($row[3]));
			$userKey=$row[2];
			$attemptTimeStamp=explode("_",$userKey);
			$date=date('F j, Y', $attemptTimeStamp[0]);
			$time=date('g:i a',$attemptTimeStamp[0]);
			
			?>
        	<!--<h3>This attempt is taken on <?php echo $date; ?> at <?php echo $time; ?></h3>   --> 
            <table width="100%" class="widefat spaced">
          		 <tr>
                  <td class="total_row">Quiz Date/Time </td>
                  <td class="total_row"><?php echo $date.", ".$time; ?></td>
                </tr>
                <tr class="border-bottom">
                  <td class="total_row">Quiz  Score </td>
                  <td class="total_row"><?php echo $quizPercentageArray[$percentageCounter]; ?>%</td>
                </tr>
               <?php
			   $quesCounter=1;
				foreach($multipleQuesArray as $multipleQuesArrayKey=>$printMultipleQues)
                { 
				
                ?>
                 <tr>
                	<td class="total_row">
                		<?php echo "Q".$quesCounter. ". " .$printMultipleQues['title']; ?>
                	</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                    <ul>
                    <?php
                     foreach($printMultipleQues['answers'] as $printMultipleQuesKey=>$printMultipleAns): 
                    //  echo "A=".$printMultipleQuesKey;
                    //echo 	$count=count($multipleArrayUserAns[$multipleQuesArrayKey]);
                    $child_array_ans='';
                    if(!empty($arrayUnserializeAns[$multipleQuesArrayKey])){
					foreach($arrayUnserializeAns[$multipleQuesArrayKey] as $ke => $v)
                        {
                        
                        $child_array_ans[]=$ke;
                        }
					}
                    //	echo 	"<br>"."Ans-".$ke."-".$v."<br>";
                    ?>
                    <li><input type="checkbox" disabled="disabled" <?php if(!empty($child_array_ans) && in_array($printMultipleQuesKey , $child_array_ans)){ ?>checked="checked"<?php } ?>  >
                    <label <?php if($printMultipleAns['result']=="true"){ ?> style="font-weight:bold; font-size:14px; color:#00F" <?php } ?> ><?php  echo $printMultipleAns['title']; ?></label>
                    <span style="margin-left:10px;"> <?php if($printMultipleAns['result']=="true" && !empty($child_array_ans) && in_array($printMultipleQuesKey , $child_array_ans)){ ?>
                    	Correct
                    <?php }elseif($printMultipleAns['result']!="true" && !empty($child_array_ans) && in_array($printMultipleQuesKey , $child_array_ans)){ echo "Incorrect"; } ?></span>
                    
                    </li>
                    
                    
                    <?php
                      endforeach; ?>
                    </ul>
                    </td>
                   
                    <td>
                    
                    </td>
                </tr>    
                  
              
        	<?php	 $quesCounter++;
					}
			?>
			</table>
             <div style="height:10px; float:left; width:100%"></div>
			<?php $percentageCounter++;
				} //end while
				?>
                
                 <div class="paginate"><?php echo pagination($totalposts,$p,$lpm1,$prev,$next,$first); ?></div>
                <?php 
			  }
			  else{
				   echo "The User Didn't attempt the quiz yet.";
				  }
			}else
			{
			?>	
            <h3>This Lesson doens't contain any Quiz. </h3>
			<?php }
		?>
        
        
    	<?php }elseif(isset($_GET['course_id'])){
			
				$course_id=$_GET['course_id'];
				$user_id=$_GET['user'];
				
				$user_info = get_userdata($user_id); // user data
				$course_data=get_post($course_id); //course data
				$user_bcba=get_user_meta($user_id,'_themex_bcba_no', $single);
				
				$course_progress_key="courseProgress_".$course_id;
				$course_progress=get_user_meta($user_id, $course_progress_key,"true");
			?>
     <a class="button medium" href='javascript:history.go(-1)' style="position:absolute; right:20px; top:20px;">Go Back</a>
	 <div style="width:49%; float:left;">
            <table width="100%" class="widefat spaced">
              <thead>
                <tr>
                  <th class="total_row">Student Report </th>
                  <th class="total_row">&nbsp;</th>
                </tr>
              </thead>
              <tfoot>
               <tr>
                  <td class="total_row">Name</td>
                  <td class="total_row"><?php echo $user_info->first_name; ?></td>
                </tr>
                <tr>
                  <td class="total_row">Username</td>
                  <td class="total_row"><?php echo $user_info->user_login; ?></td>
                </tr>
                <tr>
                  <td class="total_row">Last Name</td>
                  <td class="total_row"><?php echo $user_info->last_name; ?></td>
                </tr>
                <tr>
                  <td class="total_row">BCBA Number</td>
                  <td class="total_row"><?php echo $user_bcba[0]; ?></td>
                </tr>
              </tfoot>
            </table>
            </div>
     <div style="width:49%; float:left; margin-left:20px;">
            <table width="100%" class="widefat spaced">
                  <thead>
                    <tr>
                      <th class="total_row">Courses</th>
                      <th class="total_row">&nbsp;</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <td class="total_row">Course Name</td>
                      <td class="total_row"><?php echo $course_data->post_title; ?></td>
                    </tr>
                    <tr>
                      <td class="total_row">Completed</td>
                      <td class="total_row"><?php if($course_progress==1){ echo "Yes"; }else{ echo "No"; } ?></td>
                    </tr>
                    <tr>
                      <td class="total_row">Certificate</td>
                      <td class="total_row"> <a href="<?php echo ThemexCore::getURL('certificate', themex_encode($course_id, $user_id)); ?>" target="_blank" class="button medium certificate-button"><?php _e('View Certificate', 'academy'); ?></a></td>
                    </tr>
                </table>
                </div>
     <div style="height:20px; float:left; width:100%"></div>
           <?php
				$ans_meta_key="evaluation_ans_".$course_id;
				$evaluationUserAnsSeralized=get_user_meta($user_id,$ans_meta_key,"true");
				
				/*---------------------------------PAGINATION CODE----------------------------------------*/
				$current_page_path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				list($first,$second) = explode('&pg',$current_page_path,2);
				/*Max Number of results to show*/
				$max =2;
				/*Get the current page eg index.php?pg=4*/
				
				if(isset($_GET['pg'])){
					$p = $_GET['pg'];
				}else{
					$p = 1;
				}
				
				$limit = ($p - 1) * $max;
				$prev = $p - 1;
				$next = $p + 1;
				$limits = (int)($p - 1) * $max;
				
				//Get total records from db
				$totalres = mysql_result(mysql_query("SELECT COUNT(umeta_id) AS tot FROM wp_usermeta WHERE meta_key LIKE '%_evaluation_ans_".$course_id."%' AND user_id=$user_id"),0);
				//devide it with the max value & round it up
				$totalposts = ceil($totalres / $max);
				$lpm1 = $totalposts - 1;
				
				/*---------------------------------PAGINATION CODE----------------------------------------*/
				
				
				$arrayAnswers=mysql_query("SELECT * FROM wp_usermeta WHERE meta_key LIKE '%_evaluation_ans_".$course_id."%' AND user_id=$user_id ORDER BY meta_key DESC limit $limits,$max");
			$new_counter=1;
			while($row=mysql_fetch_array($arrayAnswers))
			{
			
			$arrayUnserializeAns=unserialize(unserialize($row[3]));
			$userKey=$row[2];
			$attemptTimeStamp=explode("_",$userKey);
			$date=date('F j, Y', $attemptTimeStamp[0]);
			$time=date('g:i a',$attemptTimeStamp[0]);
			?>
        	<!--<h3>This attempt is taken on <?php echo $date; ?> at <?php echo $time; ?></h3>-->
             <table width="100%" class="widefat spaced">
          		 <tr>
                  <td class="total_row">Evaluation Date/Time </td>
                  <td align="right" class="total_row"><?php echo $date.", ".$time; ?></td>
                </tr>
              <?php
				if( get_field('evaluation',$_GET['course_id']) )
					{
						$answer_counter=1;
						$counter=0
						
					?>
                   
					                    
						
						<?php 
						while(the_repeater_field('evaluation', $_GET['course_id']) )
						{ 	?>
                        <tr>
						<td><?php echo "Q".$answer_counter. ". " .get_sub_field('question'); ?></td>
                        <td>&nbsp;</td>
                        </tr>
                        <tr>
							<td>
                            <ul>
							<?php while(has_sub_field('answers')): ?>
								<?php $rating=get_sub_field('ratings'); ?>
								<li><input type="radio" <?php if($arrayUnserializeAns[$counter]==$rating){ ?>checked="checked"<?php } ?> name="answer<?php echo $answer_counter.$new_counter; ?>" value="<?php the_sub_field('ratings'); ?>"><?php the_sub_field('options'); ?></li>
							
							<?php  endwhile; ?>
							</ul>
                            </td>
                            </tr>
					   <?php
					   $counter++;
					   $answer_counter++;
						}
						?>
						
                        
                        
                     
                        
					<?php
					} ?>
                </table>
             <div style="height:10px; float:left; width:100%"></div>
			<?php			
			$new_counter++;
			}
		
		?>		
		<div class="paginate"><?php echo pagination($totalposts,$p,$lpm1,$prev,$next,$first); ?></div>
		
        
        
		<?php			
			} else{ ?>	
		<div id="post-body" class="columns-2">
			<div id="post-body-content">
				<table class="widefat spaced">
					<?php if(ThemexCourse::$data['statistics']['user']['ID']!=0 || ThemexCourse::$data['statistics']['course']['ID']!=0) { ?>
					<thead>
						<tr>
							<?php if(ThemexCourse::$data['statistics']['user']['ID']==0) { ?>
							<th><?php _e('Username', 'academy'); ?></th>
							<?php } ?>
							<?php if(ThemexCourse::$data['statistics']['course']['ID']==0) { ?>
							<th class="total_row"><?php _e('Course', 'academy'); ?></th>
							<?php } ?>
							<th class="total_row"><?php _e('Lesson', 'academy'); ?></th>
							<th class="total_row"><?php _e('Grade', 'academy'); ?></th>
						</tr>
					</thead>
					<tfoot>
                    <?php //print_r(ThemexCourse::$data['statistics']['lessons'][0][' course']); ?>
                    	<?php foreach(ThemexCourse::$data['statistics']['lessons'] as $lesson) { ?>
                        <?php  $lesson_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$lesson['lesson']."'");
								   $course_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$lesson['course']."'");
							 ?>
						<tr>
							<?php if(ThemexCourse::$data['statistics']['user']['ID']==0) { ?>
							<td><?php echo $lesson['username']; ?></td>
							<?php } ?>
							<?php if(ThemexCourse::$data['statistics']['course']['ID']==0) { ?>
                            <?php  $lesson_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$lesson['lesson']."'");
								   $course_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_title = '".$lesson['course']."'");
							 ?>
							<td class="total_row"><a href="<?php echo get_site_url(); ?>/wp-admin/edit.php?user=<?php echo $_GET['user']; ?>&course=0&post_type=course&page=statistics&course_id=<?php echo $course_id; ?>"><?php echo $lesson['course']; ?></a></td>
                            
							<?php } ?>
							<td class="total_row"><a href="<?php echo get_site_url(); ?>/wp-admin/edit.php?user=<?php echo $_GET['user']; ?>&course=0&post_type=course&page=statistics&course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>"><?php echo $lesson['lesson']; ?></a></td>
							<td class="total_row"><?php echo $lesson['grade']; ?>%</td>
						</tr>
						<?php } ?>
						<tr>
							<?php if(ThemexCourse::$data['statistics']['user']['ID']==0) { ?>
							<th><?php _e('Username', 'academy'); ?></th>
							<?php } ?>
							<?php if(ThemexCourse::$data['statistics']['course']['ID']==0) { ?>
							<th class="total_row"><?php _e('Course', 'academy'); ?></th>
							<?php } ?>
							<th class="total_row"><?php _e('Lesson', 'academy'); ?></th>
							<th class="total_row"><?php _e('Grade', 'academy'); ?></th>
						</tr>
					</tfoot>
					<?php } else { ?>
					<thead>
						<tr>
							<th><?php _e('Username', 'academy'); ?></th>
							<th class="total_row"><?php _e('Active Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Completed Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Average Grade', 'academy'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<?php foreach(ThemexCourse::$data['statistics']['users'] as $user) { 
							$user_data = get_user_by( 'login', $user['username'] );
						 ?>
						<tr>
							<td><a href="<?php echo get_site_url(); ?>/wp-admin/edit.php?user=<?php echo $user_data->ID; ?>&course=0&post_type=course&page=statistics"><?php echo $user['username']; ?></a></td>
							<td class="total_row"><?php echo $user['active']; ?></td>
							<td class="total_row"><?php echo $user['completed']; ?></td>
							<td class="total_row"><?php echo $user['grade']; ?>&#37;</td>
						</tr>
						<?php } ?>
						<tr>
							<th><?php _e('Username', 'academy'); ?></th>
							<th class="total_row"><?php _e('Active Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Completed Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Average Grade', 'academy'); ?></th>
						</tr>
					</tfoot>
					<?php } ?>					
				</table>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Filter','academy'); ?></h3>
					<div class="inside noborder">
						<form action="<?php echo themex_url(); ?>" method="GET">
							<p>
							<?php
							wp_dropdown_users(array(
								'show_option_all'=>__('All Students', 'academy'),
								'selected' => ThemexCourse::$data['statistics']['user']['ID'],
								'class'=>'widefat themex-submit-select',
							)); 
							?>
							</p>
							<p>
							<?php
							themex_dropdown_posts(array(
								'post_type' => 'course',
								'show_option_all'=>__('All Courses', 'academy'),
								'selected' => ThemexCourse::$data['statistics']['course']['ID'],
								'name' => 'course',
								'class'=>'widefat themex-submit-select',								
							));
							?>
							</p>
							<input type="hidden" name="post_type" value="course" />
							<input type="hidden" name="page" value="statistics" />
						</form>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Courses','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Total', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['total']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Completed', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['completed']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Per User', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['average']; ?></span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Students','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Total', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['total']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Active', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['active']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Grade', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['grade']; ?>%</span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Options','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<form action="<?php echo themex_url(); ?>" method="GET" target="_blank">
								<a class="button themex-refresh-button" href="#"><?php _e('Update', 'academy'); ?></a>&nbsp;&nbsp;
								<a class="button themex-submit-button" href="#"><?php _e('Export', 'academy'); ?></a>
								<input type="hidden" name="export" value="1" />
								<input type="hidden" name="user" value="<?php echo ThemexCourse::$data['statistics']['user']['ID']; ?>" />
								<input type="hidden" name="course" value="<?php echo ThemexCourse::$data['statistics']['course']['ID']; ?>" />
								<input type="hidden" name="post_type" value="course" />
								<input type="hidden" name="page" value="statistics" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>		
        <?php } ?>
  </div>	
</div>

<style type="text/css">
.paginate{width:100%; float:left; margin:10px 0;}
.paginate span, .paginate a{padding:5px 10px; float:left; background:#fff; border-radius:3px; -webkit-border-radius:3px; margin-right:5px; text-decoration:none;}



.widefat tr td { border-bottom:1px solid #ccc;}
.widefat tr:last-child td { border-bottom:0px;}
.spaced { margin-bottom:10px;}
.border_bottom1 { border-bottom:1px solid #ccc;}

</style>


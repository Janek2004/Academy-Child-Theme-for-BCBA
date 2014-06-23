<div class="widget">
	<div class="widget-title">
		<h4 class="nomargin"><?php _e('Lessons', 'academy'); ?></h4>
	</div>
	<div class="widget-content">
		<ul class="styled-list style-3">
			<?php foreach(ThemexCourse::$data['lessons'] as $lesson) { 
				
			?>
            
			<li class="<?php if($lesson->post_parent!=0) { ?>child<?php } ?> <?php if($lesson->ID==ThemexLesson::$data['ID']) { ?>current<?php } ?>">
				<a href="<?php echo get_permalink($lesson->ID); ?>"><?php echo get_the_title($lesson->ID); ?></a>
        <img src="
				<?php 
					$l = ThemexLesson:: getLesson($lesson->ID);
								
					if($l['progress'] == 100){
				 ?>
					<?php echo THEME_URI.'images/bullet_5.png';	
					}
					else{
	          	echo THEME_URI.'images/bullet_4.png';					
	 			 }
      	?>"/>
			
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php ThemexCourse::refresh($post->ID); ?>
<div class="course-preview <?php echo ThemexCourse::$data['status']; ?>-course">
	<div class="course-image">
		<a href="<?php the_permalink(); ?>"><?php //the_post_thumbnail('normal');
		//echo "[video_lightbox_vimeo5 video_id=".get_field('course_demo_video')."width=800 height=450 auto_thumb='1']"
		$video_id=get_field('course_demo_video',$post->ID);
		if($video_id!="")
		{
			echo do_shortcode("[video_lightbox_vimeo5 video_id=".get_field('course_demo_video')." width=800 height=450 auto_thumb='1']");
			
		}else{
			//echo "[video_lightbox_vimeo5 video_id=".get_field('course_demo_video')." width=800 height=450 auto_thumb='1']";
			the_post_thumbnail('normal');	
		}
		
		 ?></a>
		<?php //if(empty(ThemexCourse::$data['plans']) && ThemexCourse::$data['status']!='private') { ?>
		<div class="course-price product-price">
			<div class="price-text"><?php echo ThemexCourse::$data['price']['text']; ?></div>
			<div class="corner-wrap">
				<div class="corner"></div>
				<div class="corner-background"></div>
			</div>			
		</div>
		<?php //} ?>
	</div>
	<div class="course-meta">
		<header class="course-header">
			<h5 class="nomargin"><a href="<?php the_permalink(); ?>">
		<?php 
		 $title_string =get_the_title($post->ID);
			$words = preg_split("/[\s,]+/", $title_string);
			$title_string ="";
			for($i=0; $i<5;$i++)
			{	
				$word = $words[$i];
				//echo $word;
				if($i==0) {$title_string= $word;}
				else{
								$title_string=	$title_string." ".$word;			
				}

				
			}
				//print_r($words);
			echo $title_string; ?>
       
        
        
      </a>
      </h5>
		
		</header>
		<?php if(!ThemexCore::checkOption('course_popularity') || !ThemexCore::checkOption('course_rating')) { ?>
		<footer class="course-footer clearfix">
			<?php if(!ThemexCore::checkOption('course_popularity')) { ?>
			<div class="course-users left">
				<?php echo ThemexCore::getPostMeta($post->ID, 'course_popularity', '0'); ?>
			</div>
			<?php } ?>
			<?php if(!ThemexCore::checkOption('course_rating')) { ?>
			<?php get_template_part('module', 'rating'); ?>
			<?php } ?>
		</footer>
		<?php } ?>
	</div>
</div>
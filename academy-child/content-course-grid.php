<?php ThemexCourse::refresh($post->ID); ?>
<div class="course-preview <?php echo ThemexCourse::$data['status']; ?>-course">
	<div class="course-image">
		<a href="<?php the_permalink(); ?>"><?php //the_post_thumbnail('normal');
	
		//Check if there is a video associated with the course
		$video_id= get_post_meta($post->ID, 'course_demo_video', true);
		if($video_id!="")
		{
			echo do_shortcode("[video_lightbox_vimeo5 video_id=".$video_id." width=800 height=450 auto_thumb='1']");
			//http://blog.teamtreehouse.com/using-jquery-asynchronously-loading-image
			//http://blog.teamtreehouse.com/learn-asynchronous-image-loading-javascript
			//echo do_shortcode("[video_lightbox_vimeo5 video_id=".$video_id." width=800 height=450 ]");
			
		}else{
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
        <div class="course-credit product-credit">
			<div class="credit-text"><?php echo get_field("number_of_credits", $post->ID); ?>CEUs</div>
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
			$max_words =15;
			if(count($words)<$max_words){
				$max_words = count($words);
			}
			for($i=0; $i<$max_words;$i++)
			{	
				$word = $words[$i];
	
				if($i==0) {$title_string= $word;}
				else{
								$title_string=	$title_string." ".$word;			
				}
	
			}
			if(count($words)>$max_words){
				$title_string = $title_string."... ";
			}
			
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
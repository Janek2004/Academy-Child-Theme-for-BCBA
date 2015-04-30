<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//print_r($_REQUEST);
/*
Template Name: Courses
*/

get_header();

$layout=ThemexCore::getOption('courses_layout', 'fullwidth');
$view=ThemexCore::getOption('courses_view', 'grid');
$columns=intval(ThemexCore::getOption('courses_columns', '4'));
//$page_id  = 0;


if($layout=='left') {
?>
<aside class="sidebar sixcol column">
	<?php get_sidebar(); ?>
</aside>
<div class="eightcol column last">
<?php } else if($layout=='right') { ?>
<div class="eightcol column">
<?php } else { ?>
<div class="fullwidth-section">
<?php } ?>
	<?php 
	 	$taxonomies = array("course_category");
		$terms = get_terms($taxonomies);
		
	 ?>
     <div class="clear"></div>

   
    <form method="post"  action="<?php echo get_permalink();?>">
	<div class= "sevencol column">
    <div class="sixcol column">
    		<img src="http://behavior.uwf.edu/wp-content/uploads/2014/03/PearseStreet_Behavior_Logo_52_102909_BWIsolated-1-300x87.jpg">
	</div>
   
    <div class="sixcol column last">		
	    <p>Choose course category</p>
            <div class="field-wrapper">
			<select id="course_category" name="course_category">
			<option value="All">All</option>

			<?php	
				foreach ($terms as $term) {
					print_r( "<option value=".$term->term_id.">".$term->name."</option>");	
				}
			 ?> 
   		  </select>
			</div>
		</div>
    <div class="sixcol column last">
    	 <input type="submit" value="Filter Courses"/> 
    </div>
    </div>
     
     </form>
     
    <div class="clear"></div>
	<br /><br />
    
	<?php ThemexCourse::queryCourses(); ?>
	<?php if($view=='list') { ?>
	<div class="posts-listing clearfix">
	<?php
	while (have_posts()) {
		the_post();
		get_template_part('content', 'course-list');
	}
	?>
	</div>
	<?php } else { ?>
	<div class="courses-listing clearfix">
	<?php
	$counter=0;
	if(in_array($layout, array('left', 'right'))) {
		$columns=$columns-1;
	}
	
	if($columns==4) {
		$width='three';
	} else if($columns==3) {
		$width='four';
	} else {
		$width='six';
	}
		
	while (have_posts()) {
		the_post();
		$counter++;
		?>
		<div class="column <?php echo $width; ?>col <?php echo $counter==$columns ? 'last':''; ?>">
		<?php get_template_part('content', 'course-grid'); ?>
		</div>
		<?php
		if($counter==$columns) {
			$counter=0;
			echo '<div class="clear"></div>';
		}
	}
	?>
	</div>
	<?php } ?>
	<?php ThemexInterface::renderPagination(); ?>
</div>
<?php if($layout=='right') { ?>
<aside class="sidebar fourcol column last">
	<?php get_sidebar(); ?>
</aside>
<?php } ?>
<?php get_footer(); ?>
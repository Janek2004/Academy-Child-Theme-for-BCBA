<?php if($_GET['format']=='pdf'){ ?>
<?php 
include("mpdf/mpdf.php");
$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
$mpdf->SetDisplayMode('fullpage');

?>
<?php

$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
 
$stylesheet = file_get_contents('http://localhost/Academy/wp-content/themes/academy-child/style.css'); // external css

$mpdf->WriteHTML($stylesheet,1);
 
$mpdf->WriteHTML(file_get_contents('http://localhost/Academy/?certificate='.$_GET['certificate']));
         
$mpdf->Output();

?>
<?php } else{?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
<?php wp_head(); ?>
</head>
<body <?php body_class('single-certificate'); ?>>
<?php $ID=ThemexCore::getRewriteRule('certificate'); 
		$post_id=themex_decode($ID);
 ?>
<?php $certificate=ThemexCourse::getCertificate(themex_decode($ID), themex_decode($ID, true)); ?>
<?php if(isset($certificate['user'])) { ?>
	
		<?php if(!empty($certificate['background'])) { ?>
		<div class="substrate">
			<img src="<?php echo $certificate['background']; ?>" class="fullwidth" alt="" />
		</div>
		<?php } ?>
		<div class="certificate-text">
			 <?php 
			$today_date=date("F j, Y");
			$credits=get_field('number_of_credits',$post_id);
			$teacher=get_field('presenter_teacher',$post_id);
			$name_of_course=get_field('name_of_course',$post_id);
			$array_bcba_no = get_user_meta($certificate['user'],'_themex_bcba_no');
			$bcba_no=$array_bcba_no[0];
			
			$replaceContent=array($credits,$teacher,$name_of_course,$bcba_no,$today_date);
			$replacingWords=array("%credits%","%teacher%","%course_name%","%bcba_no%","%certificate_date%");
			
			echo $certificateContent = str_replace($replacingWords,$replaceContent,$certificate['content']);
			
			?>	
		</div>
	
	<?php if($certificate['user']==get_current_user_id()) { ?>
	<a href="#" class="button print-button"><?php _e('Print Certificate', 'academy'); ?></a>
   
    <a href="<?php echo get_site_url(); ?>?certificate=<?php echo $ID; ?>&format=pdf" class="button" ><?php _e('Download Certificate', 'academy'); ?></a>
	<?php } ?>
<?php } else { ?>
<div class="certificate-error">
	<h1><?php _e('Certificate not found', 'academy'); ?>.</h1>
</div>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>
<?php } ?>
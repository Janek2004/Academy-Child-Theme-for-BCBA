<?php

/**Returns a date for current user and course*/
function getCertificateDate($post_id,$user){

	global $wpdb;
	$query = "SELECT *
        FROM $wpdb->comments
        WHERE $wpdb->comments.user_id =$user AND $wpdb->comments.comment_type = 'user_certificate' AND $wpdb->comments.comment_post_ID = $post_id ";
	$results = $wpdb->get_results($query);
	//comment_post_ID
	if($results){
		$result = $results[0]->comment_content;

		return $result;
	}
}



if($_GET['format']=='pdf'){ ?>
<?php
include("mpdf/mpdf.php");
$mpdf=new mPDF('utf-8', 'Letter-L', '8', '', 1, 1, 1, 1, 1, 1);
$mpdf->SetDisplayMode('fullpage');

?>
<?php
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
;

$stylesheet = file_get_contents(get_stylesheet_directory_uri().'/css/certificate_pdf.css'); // external css
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML(file_get_contents( site_url().'?certificate='.$_GET['certificate']));

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
 		$user =  themex_decode($ID,true)
 ?>
 <script>
	// console.log(<?php echo $post_id; ?>)
	// console.log(<?php echo $user; ?>)
 </script>
<?php
	$certificate=ThemexCourse::getCertificate(themex_decode($ID), themex_decode($ID, true));
	// print_r($certificate);
?>
<?php if(isset($certificate['user'])) { ?>
		<?php
			$user = $certificate['user'];
  		$username=trim(get_user_meta($user, 'first_name', true).' '.get_user_meta($user, 'last_name', true));

			$certificateText = '<p class="logoText">The Office of Applied Behavior Analysis at the University of West Florida Office </p> <p class="certifiesThat">certifies that </p>
						<div class="text_area"><h1>%username%</h1><p>has viewed the online presentation by %presenter%
						and answered review questions to proficiency and is hereby awarded this Certificate of Completion';

  		$credits=get_field('number_of_credits',$post_id);
			$number_of_ethics_credits=get_field('number_of_ethics_credits',$post_id);
			$number_of_supervision_credits=get_field('number_of_supervision_credit',$post_id);
			$presenter=get_field('presenter_teacher',$post_id);



			if ($credits > 0){
					$certificateText .= ' and <big>%credits% Continuing Education Units (CEUS)</big>';
			}
			if ($number_of_ethics_credits > 0){
					$certificateText .= ' and <big> %ethics_credits% Ethics Credits</big>';
			}
			if ($number_of_supervision_credits > 0){
					$certificateText .= ' and <big> %supervision_credits% Supervision Credits</big>';
			}

				$certificateText .= ' for participation in</p><h2 class="courseName">%course_name% </h2></div>';

			// <p class="logoText">The Office of Applied Behavior Analysis at the University of West Florida Office </p> <p class="certifiesThat">certifies that </p>
			// <div class="text_area"><h1>%username%</h1><p>has viewed the online presentation by Robert K. Ross, Ed.D.
			// and answered review questions to proficiency and is hereby awarded this Certificate of Completion and
			// <big>%credits% Continuing Education Units (CEUS)</big> and <big> %ethics_credits% Ethics Credits</big>
			// for participation in</p><h2>%course_name% </h2>
			// </div>





			$timestamp = getCertificateDate($post_id, $user);
			$today_date=date("F j, Y",$timestamp);




			$name_of_course=get_field('name_of_course',$post_id);
			$array_bcba_no = get_user_meta($certificate['user'],'_themex_bcba_no');

			$bcba_no=$array_bcba_no[0];

			$replaceContent=array($credits,$presenter,$name_of_course,$bcba_no,$today_date,$number_of_ethics_credits, $number_of_supervision_credits, $username);
			$replacingWords=array("%credits%","%presenter%","%course_name%","%bcba_no%","%certificate_date%","%ethics_credits%","%supervision_credits%","%username%");


			$certificateContent = str_replace($replacingWords,$replaceContent, $certificateText);

		//	print_r($certificate['content']);
		//	die();
			// $certificateContent = str_replace($replacingWords,$replaceContent,$certificate['content']);

		?>

		<?php if(!empty($certificate['background'])) { ?>
		<div class="substrate">
			<img src="<?php echo $certificate['background']; ?>" class="fullwidth" alt="" />
		</div>
		<?php } ?>
		<div class="certificate-text">
			<div id="certificate_main" style="background:url(<?php echo get_stylesheet_directory_uri(); ?>/images/certificate/regualmodernrback-noblue.jpg) center; background-repeat:no-repeat; background-image-resize:6">
                <div class="logo"><img width="500px" height="57px" src="<?php echo get_stylesheet_directory_uri();  ?>/images/certificate/cert.png" /></div>
               		<?php echo $certificateContent; ?>
				<!--container for dates and etc -->
                <div class="cert_container">
                  <div class="dir_logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/certificate/director_logo.png" /></div>
                  <div class="date">
                    <h3><?php echo $today_date; ?></h3>
                    <small>DATE</small>
                  </div>

                  <div class="date">
					<h3>OP-11-2135</h3>
                    <small>CEU PROVIDER NO.</small>
                  </div>

                  <div class="date">
                    <h3><?php echo $bcba_no; ?></h3>
                    <small>BCBA NO.</small>
                  </div>

                 </div>
                <p id="contact_info">Contact info:  aba@uwf.edu or (850) 474-2704</p>

             </div>
		</div>

	<?php if($certificate['user']==get_current_user_id()) { ?>

    <a href="<?php echo get_site_url(); ?>?certificate=<?php echo $ID; ?>&format=pdf" class="button" ><?php _e('Print Certificate', 'academy'); ?></a>
	<?php } ?>
<?php } else {
//echo $certificate['user'];

?>
<div class="certificate-error">


	<h1><?php _e('Certificate not found', 'academy'); ?>.</h1>
</div>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>
<?php } ?>

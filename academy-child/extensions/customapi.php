<?php
// Add a custom controller
add_filter('json_api_controllers', 'add_my_controller');

function add_my_controller($controllers) {
  // Corresponds to the class JSON_API_MyController_Controller
  $controllers[] = 'mobileapi';
  return $controllers;
}

/**Course*/
class Course{
  var $course;
  var $video_id;
  var $title;
}


class Lesson{
  var $postData;
  var $title;
}

class EvalQuestion{
  var $question;
  var $answers;
}


class JSON_API_mobileapi_Controller {

/**Evaluation*/
function get_evaluation() {

  global $json_api;
  global $wpdb;
  $id = $json_api->query->id;
  if (!$id) {
        $json_api->error("Missing course id parameter.");
  }
  else{
  $results = array();
  while(the_repeater_field('evaluation', $id) )
  {
      $question = new EvalQuestion();
      $question->question = get_sub_field('question');
      $answers = array();
      while(has_sub_field('answers')){
          $answers[] = get_sub_field('options');
      }
      $question->answers = $answers;
      $results[] = $question;
  }
  return $results;
 }
}

/**Quiz*/
function get_quiz() {

 global $json_api;
  global $wpdb;
  $id = $json_api->query->id;
  if (!$id) {
        $json_api->error("Missing course id parameter.");
  }
  else{
    ThemexLesson::refresh(ThemexCore::getPostRelations($id, 0, 'quiz_lesson', true), true);
    ThemexCourse::refresh(ThemexLesson::$data['course'], true);
//    ThemexLesson::getQuiz($id);
  //  print_r(ThemexLesson::$data['quiz']);
    return ThemexLesson::$data['quiz'];

  }
}


public function getLessons(){
  global $json_api;
  global $wpdb;
  $id = $json_api->query->id;
    if (!$id) {
        $json_api->error("Missing course id parameter.");
    }else{

      $lessons = ThemexCourse::getLessons($id);
      return $lessons;
    }
}

/**Gets all available*/
public function getCourses() {

  $args=array(
    'post_type' => 'course',
    'posts_per_page' => -1,
  );

  $posts = get_posts($args);
  $results =  array();
  foreach($posts as $post){
    $course = new Course();
    $course->title = $post->post_title;
    $course->video_id= get_post_meta($post->ID, 'course_demo_video', true);
    $themex = ThemexCourse::getCourse($post->ID);
    $course->course = $themex;
    $results[]=$course;
  }

  return $results;
 }
}


function set_mobileapi_controller_path() {
//echo "url is
 //echo get_stylesheet_directory()."\customapi.php";
 //die();
  return get_stylesheet_directory()."/customapi.php";
}
add_filter('json_api_mobileapi_controller_path', 'set_mobileapi_controller_path');
//get_stylesheet_directory_uri()

?>

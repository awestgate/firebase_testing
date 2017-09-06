<style>
.wpdberror {display: none;}
</style>
<?php

get_header();

?>
<div class="container">
    <?php

    $courses = array();

      $args = array(
      'post_type'    => 'course',
      'post_status'  => 'publish',
      'order_by'     => 'ID',
      'order'        => 'DESC',
      'posts_per_page' => -1
      );
      $courses_query = new WP_Query( $args );

    while( $courses_query->have_posts() ) : $courses_query->the_post();

    $title = get_the_title();
    $instructor = get_the_author();
    $instructor_id = get_the_author_meta('ID');
    $course_id = $post->ID;
    $duration = get_post_meta($post->ID, 'ko_course_duration', true);
    $jwtoken = get_post_meta($post->ID, 'ko_course_jw_token', true);

      $courses[$course_id] = array(
        'title' => $title,
        'duration' => $duration,
        'instructor_id' => $instructor_id,
        'instructor_name' => $instructor,
        'jwtoken' => $jwtoken,
        'times_completed' => 0,
        'CourseCompletions' => array()
      );

    endwhile;

    $js_courses = json_encode($courses);

?>

<?php

$user_query = new WP_User_Query( array( 'number' => 10 ) );
$users = $user_query->get_results();
$results_array = array();

foreach($users as $user) {
    $id = $user->data->ID;
    $results_array[$id]['email'] = $user->data->user_email;
    $results_array[$id]['fname'] = get_user_meta($user->data->ID, 'first_name', TRUE);
    $results_array[$id]['lname'] = get_user_meta($user->data->ID, 'last_name', TRUE);

}

$js_users = json_encode($results_array);

?>


</div>
<?php get_footer(); ?>

<script src="https://www.gstatic.com/firebasejs/3.2.1/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBPmkL7ujXiA5bYffuhTXI0bLZwejmhx2o",
    authDomain: "akwpluralcourse.firebaseapp.com",
    databaseURL: "https://akwpluralcourse.firebaseio.com",
    storageBucket: "akwpluralcourse.appspot.com",
  };
  firebase.initializeApp(config);
  var email = 'awestgate@kelbymediagroup.com';
  var password = 'firebase_test';
  firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
  // Handle Errors here.
  var errorCode = error.code;
  var errorMessage = error.message;
  // ...
});

var users = <?php //echo $js_users; ?>;

for (id in users) {
    writeUserData(id);
}

function writeUserData(user_id) {
    var userData = {
        email: users[id].email,
        firstname: users[id].fname,
        lastname: users[id].lname,
    };
    firebase.database().ref('users/' + user_id).set(userData);
}

</script>



<script src="https://www.gstatic.com/firebasejs/3.2.1/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBPmkL7ujXiA5bYffuhTXI0bLZwejmhx2o",
    authDomain: "akwpluralcourse.firebaseapp.com",
    databaseURL: "https://akwpluralcourse.firebaseio.com",
    storageBucket: "akwpluralcourse.appspot.com",
  };
  firebase.initializeApp(config);
  var email = 'awestgate@kelbymediagroup.com';
  var password = 'firebase_test';
  firebase.auth().signInWithEmailAndPassword(email, password).catch(function(error) {
  // Handle Errors here.
  var errorCode = error.code;
  var errorMessage = error.message;
  // ...
});

var courses = <?php echo $js_courses; ?>;

for (id in courses) {
    writeCourseData(id);
}

function writeCourseData(course_id) {
    var token_id = courses[id].jwtoken;
    var courseData = {
        course_id: course_id,
        title: courses[id].title,
        duration: courses[id].duration,
        instructor_id: courses[id].instructor_id,
        instructor_name: courses[id].instructor_name,
        times_completed: courses[id].times_completed,
        CourseCompletions: courses[id].CourseCompletions
    };
    firebase.database().ref('courses/' + token_id).set(courseData);
}

</script>

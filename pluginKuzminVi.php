<?php
/*
Plugin Name: kvtests
*/
?>
<?php
register_activation_hook(__FILE__,'kvtests_activate');

require(plugin_dir_path(__FILE__).'activate.php');
require(plugin_dir_path(__FILE__).'deactivate.php');
function kvtests_activate()
{
	setup_db();
	add_shortcodes();
}
require(plugin_dir_path(__FILE__).'testshortcodes.php');
function add_shortcodes()
{
	add_shortcode('get_last_test_result','get_last_test_result');//get last result of current user;
	add_shortcode('show_all_test_results','show_all_test_results'); // get all test results;
}

function restore_test_type_posts()
{
	global $wpdb;
	$tests = $wpdb ->get_results($wpdb->prepare('SELECT * FROM kv_tests' ),'ARRAY_A'); // get all tests
	foreach ($tests as $t)
	{
	if ($t['IsCompleted'] == 1)
	{
	wp_insert_post( array(
				'post_title' => $t['name'],
				'post_content' => $t['Html Display Code'],
				'post_type' => 'kv_test'
				));
	}
	}
	
}


add_action('init', 'add_shortcodes');
add_action('init','register_tests_type');
add_action('admin_init', 'register_mysettings' );
function register_mysettings() { 
  register_setting( 'myoption-group', 'Submission_successful_page' );
  register_setting( 'myoption-group', 'Submission_failed_page' );
}
 function register_tests_type()
 {
	register_post_type('kv_test',array (
		'labels' => array ('name' => 'Тесты',
							'singular_name' => 'Тест'),
		'public' => true,
		'supports' => array('title','author','thumbnail','excerpt','custom-fields'),
		'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
		'map_meta_cap' => true,
		'has_archive' => true 
		)
	);
 }

function kvtests_enqueue_admin_scripts() {

	wp_enqueue_script('jsformbuilder', plugins_url('/js/jsformbuilder.js',__FILE__),array('jquery'));
	
}
function kvtests_enqueue_user_scripts()
{
wp_enqueue_script('jsTestAllResultShortcodeLogic', plugins_url('/js/jsTestAllResultShortcodeLogic',__FILE__),array('jquery'));
}
add_action( 'wp_enqueue_scripts', 'kvtests_enqueue_user_scripts' );
add_action('admin_enqueue_scripts', 'kvtests_enqueue_admin_scripts');
add_action('admin_menu','kvtests_create_menu');



function kvtests_create_menu()
{
	add_menu_page('Настройка тестов','Настройка тестов','manage_options','kvtests_main_page','kvtests_main_plugin_page');
	add_submenu_page('kvtests_main_page','Помощь','Помощь','manage_options','kvtests_main_page','kvtests_main_plugin_page');
	add_submenu_page( 'kvtests_main_page', 'Созданные тесты', 'Созданные тесты','manage_options', 'edit.php?post_type=kv_test', NULL );
	add_submenu_page('kvtests_main_page','Создать тест','Создать тест','manage_options','kvtests_create_page','kvtests_create');
	add_submenu_page('kvtests_main_page','Удалить тест','Удалить тест','manage_options','kvtests_delete_page','kvtests_delete');
	add_submenu_page('kvtests_main_page','Дополнительные настройки','Дополнительные настройки','manage_options','kvtests_options_page','kvtests_options');
	remove_menu_page('edit.php?post_type=kv_test'); 
	

}
function kvtests_main_plugin_page()
{
	require(plugin_dir_path( __FILE__ ).'testmainpage.php');
}
function kvtests_create()
{
	add_filter( 'admin_footer_text', '__return_empty_string', 11 ); // remove default footers
	add_filter( 'update_footer', '__return_empty_string', 11 ); // remove default footers
	global $wpdb;
	$wpdb->delete( 'kv_tests', array( 'IsCompleted' => 0 ) ); // 	Delete all uncompleted and abandoned tests
	require(plugin_dir_path( __FILE__ ).'testcreatepage.php');
}
function kvtests_delete()
{
	add_filter( 'admin_footer_text', '__return_empty_string', 11 ); // remove default footers
	add_filter( 'update_footer', '__return_empty_string', 11 ); // remove default footers
	require(plugin_dir_path( __FILE__ ).'testdeletepage.php');
}
function kvtests_options()
{
	add_filter( 'admin_footer_text', '__return_empty_string', 11 ); // remove default footers
	add_filter( 'update_footer', '__return_empty_string', 11 ); // remove default footers
	require(plugin_dir_path( __FILE__ ).'testoptionspage.php');
}

register_deactivation_hook(__FILE__,'pluginKuzminVi_deactivate');

function pluginKuzminVi_deactivate()
{
	move_test_type_posts_to_draft();
}


add_action( 'wp_ajax_createTestDB', 'createTestDB' );
add_action( 'wp_ajax_createTestResultDB', 'createTestResultDB' );
add_action( 'wp_ajax_finishTestDB', 'finishTestDB' );
add_action( 'wp_ajax_deleteTestQuestionDB', 'deleteTestQuestionDB' );
add_action( 'wp_ajax_deleteTestResultDB', 'deleteTestResultDB' );
add_action( 'wp_ajax_deleteTestDB', 'deleteTestDB' );
function createTestDB()
{
	global $wpdb;
	
	$testname = $_POST['testname'];
	$questionname = $_POST['question'];
	$answerlist = $_POST['answers'];
	if ($wpdb->get_var($wpdb->prepare("SELECT IsCompleted FROM kv_tests WHERE name=%s",$testname)) == 1)
	{
		$s = "Уже есть такой тест";
		echo $s;
		wp_die();
	}
	if ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM kv_tests WHERE name=%s",$testname)) == 0)
	{
	$wpdb->insert('kv_tests', array(
						'name' => $testname,
						'IsCompleted' => 0
						));
	}
	$testid = $wpdb->get_var($wpdb->prepare("SELECT Id FROM kv_tests WHERE name=%s",$testname));
	$questionnum = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM kv_questions WHERE Test=%d",$testid)) + 1;
	$wpdb->insert('kv_questions', array(
						'Id' => $questionnum,
						'Test' => $testid,
						'name' => $questionname
						));
	
	
	for ( $i = 0; $i < count($answerlist); $i++)
		{
			$currans = $answerlist[$i]['answer'];
			$wpdb->insert('kv_answers', array(
						'Test' => $testid,
						'Question' => $questionnum,
						'Answer' => $currans
						));
			$answerid = ($wpdb->get_var("SELECT MAX(Id) FROM kv_answers"));	
			for ($j = 0; $j < count($answerlist[$i]['categories']); $j++)
			{
				$currcat = $answerlist[$i]['categories'][$j]['name'];
				$currcatweigth = $answerlist[$i]['categories'][$j]['weigth'];
				
				$wpdb->insert('kv_answers_categories', array(
						'Answer id' => $answerid,
						'Category' => $currcat,
						'Points' => $currcatweigth
						));
				
			
			}
		}
	echo json_encode($wpdb->get_col($wpdb->prepare('SELECT DISTINCT Category FROM kv_answers_categories JOIN kv_answers ON kv_answers_categories.`Answer Id` = kv_answers.Id WHERE Test = %d',$testid)));
	wp_die();
	}
function deleteTestQuestionDB()
{
	global $wpdb;
	$testname = $_POST['testname'];
	$testid = $wpdb->get_var($wpdb->prepare("SELECT Id FROM kv_tests WHERE name=%s",$testname));
	if ( $wpdb -> get_var($wpdb->prepare('SELECT IsCompleted FROM kv_tests WHERE name = %s',$testname)) == 0)
	{
	$lastid = $wpdb -> get_var($wpdb->prepare('SELECT MAX(Id) FROM kv_questions WHERE Test = %d',$testid));
	$wpdb -> delete('kv_questions',array( 'Test' => $testid, 'Id' => $lastid));
	}
	echo json_encode($wpdb -> get_col($wpdb->prepare("SELECT DISTINCT Category FROM kv_answers JOIN kv_answers_categories ON kv_answers.Id=kv_answers_categories.`Answer id` WHERE Test=%d",$testid)));
	wp_die();
}
function deleteTestResultDB()
{
 global $wpdb;
	$testname = $_POST['testname'];
	$testid = $wpdb->get_var($wpdb->prepare("SELECT Id FROM kv_tests WHERE name=%s",$testname));
	if ( $wpdb -> get_var($wpdb->prepare('SELECT IsCompleted FROM kv_tests WHERE name = %s',$testname)) == 0)
	{
	$lastid = $wpdb -> get_var($wpdb->prepare('SELECT MAX(Id) FROM kv_results WHERE Test = %d',$testid));
	$wpdb -> delete('kv_results',array( 'Test' => $testid, 'Id' => $lastid));
	}
	wp_die();
}
function CreateTestResultDB()
{
	global $wpdb;
	
	$testname = $_POST['testname'];
	$resultname = $_POST['resultname'];
	$resultdesc = $_POST['resultdesc'];
	$resultformula = $_POST['resultformula'];
	if ($wpdb->get_var($wpdb->prepare("SELECT IsCompleted FROM kv_tests WHERE name=%s",$testname)) == 1)
	{
		$s = "Уже есть такой тест";
		echo $s;
		wp_die();
	}
	if ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM kv_tests WHERE name=%s",$testname)) == 0)
	{
	$wpdb->insert('kv_tests', array(
						'name' => $testname,
						'description' => '',
						'IsCompleted' => 0
						));
	}
	$testid = $wpdb->get_var($wpdb->prepare("SELECT Id FROM kv_tests WHERE name=%s",$testname));
	if ($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM kv_results WHERE Test=%d AND Result=%s",$testid,$resultname)) == 0)
	{
		$resultnum = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM kv_results WHERE Test=%d",$testid)) + 1;
		$wpdb->insert('kv_results', array(
						'Test' => $testid,
						'Id' => $resultnum,
						'Result' => $resultname,
						'Description' => $resultdesc,
						'Formula' => $resultformula
						));
	}
	wp_die();
}	
function finishTestDB()
{
	global $wpdb;
	$testname = $_POST['testname'];
	$code = $_POST['htmlcode'];
	if ($wpdb->get_var($wpdb->prepare("SELECT IsCompleted FROM kv_tests WHERE name=%s",$testname)) == 1)
	{
		$s = "Тест уже сохранен, перезагрузите страницу";
		echo $s;
		wp_die();
	}
	else
	{
	$wpdb->update( 'kv_tests', array( 'IsCompleted' => 1,
										'Html Display Code' => $code), array( 'name' => $testname ) );
	$wpdb->delete( 'kv_tests', array( 'IsCompleted' => 0 ) ); // 	Delete all uncompleted and abandoned tests
	wp_insert_post( array(
				'post_title' => $testname,
				'post_content' => $code,
				'post_type' => 'kv_test'
				));
	
	echo 'Сохранено';
	}
	wp_die();
}
function deleteTestDB()
{
	$testname = $_POST['testname'];
	global $wpdb;
	$results = $wpdb->get_col($wpdb->prepare( 'SELECT ID FROM wp_posts WHERE post_type = "kv_test" AND post_title = %s',$testname));
	foreach ( $results as $result ) 
	{
	wp_delete_post( $result,true );
	}
	$wpdb->delete( 'kv_tests', array( 'name' => $testname ) );
	
	wp_die();
}
add_action( 'admin_post_kv_test_submit', 'kv_test_submit' );
add_action( 'admin_post_nopriv_kv_test_submit', 'kv_test_submit' );
function kv_test_submit()
{
	global $wpdb;
	
	$answertext = '';
	$currentuser = wp_get_current_user();
	
	$currentuserid = $currentuser->ID;
	$date = date('Y-m-d', time());
	
	$testid = $wpdb->get_var($wpdb->prepare("SELECT Id FROM kv_tests WHERE name=%s",$_POST['testname']));
	$categories = $wpdb -> get_col($wpdb->prepare("SELECT DISTINCT Category FROM kv_answers JOIN kv_answers_categories ON kv_answers.Id=kv_answers_categories.`Answer id` WHERE Test=%d",$testid));
	
	$CategoryResults = array();
	foreach ( $categories as $cat)
	{
		$CategoryResults[$cat] = 0;
	}
	foreach($_POST as $key=>$value)
	{
		if ($key != 'testname' && $key != 'action')
		{
		if (is_array($value) == false) // question is a radio question
		{		
		$question = $wpdb->get_var( $wpdb->prepare(" SELECT Id FROM kv_questions WHERE Test=%d and Id=%s",$testid,$key));
		$answerid = $wpdb->get_var( $wpdb->prepare(" SELECT Id FROM kv_answers WHERE Test=%d and Question=%s and Answer=%s",$testid,$question,$value));
		$category = $wpdb->get_var( $wpdb->prepare(" SELECT Category FROM kv_answers_categories WHERE `Answer id`=%s",$answerid));
		$points = $wpdb->get_var( $wpdb->prepare(" SELECT Points FROM kv_answers_categories WHERE `Answer id`=%s",$answerid));
		$CategoryResults[$category] += $points;
		}
		else // question is a checkbox question
		{
			foreach($value as $k => $v)
			{
				$question = $wpdb->get_var( $wpdb->prepare(" SELECT Id FROM kv_questions WHERE Test=%d and Id=%s",$testid,$key));
				$answerid = $wpdb->get_var( $wpdb->prepare(" SELECT Id FROM kv_answers WHERE Test=%d and Question=%s and Answer=%s",$testid,$question,$v));
				$category = $wpdb->get_var( $wpdb->prepare(" SELECT Category FROM kv_answers_categories WHERE `Answer id`=%s",$answerid));
				$points = $wpdb->get_var( $wpdb->prepare(" SELECT Points FROM kv_answers_categories WHERE `Answer id`=%s",$answerid));
				$CategoryResults[$category] += $points;
			}
		}
		}
	}
	$fr=kv_GetTestFinalResult($CategoryResults);
	if (count($fr) == 0) {$fr[] = (int)$wpdb -> get_var($wpdb->prepare("SELECT Id FROM kv_results WHERE Test=%d and Formula='Default'",$testid));}
	if (is_user_logged_in()) // for logged in user
	{
	$existsvar = $wpdb->get_var( $wpdb->prepare( " SELECT COUNT(*) FROM kv_users_results WHERE UserId=%d and Date=%s and Test=%d", $currentuserid, $date , $testid));
	if ($existsvar == 0)
	{
	$rowsaffected = $wpdb->query( $wpdb->prepare( " INSERT INTO kv_users_results VALUES (%d,%s,%d,%s) ", $currentuserid, $date , $testid , json_encode($fr) ));
	}
	else
	{
	$rowsaffected = $wpdb->query( $wpdb->prepare( " UPDATE kv_users_results SET FinalResults=%s WHERE UserId=%d and Date=%s and Test=%d",json_encode($fr), $currentuserid, $date , $testid ));
	}
	if ($rowsaffected == 0)
	{
		wp_redirect(get_permalink(get_option('Submission_failed_page')));
	}
	else
	{
		wp_redirect(  add_query_arg( 'Test', $testid,
													get_permalink(get_option('Submission_successful_page'))));
	}
	
	}
	else // for anonymous user
	{
		wp_redirect(  add_query_arg( array('results' => base64_encode(serialize($fr)), // encode because wordpress removes some charcters automatically
													'Test' => $testid),
													get_permalink(get_option('Submission_successful_page'))  ) );
		
	}
}
function kv_GetTestFinalResult($CategoryResults)
{
	global $wpdb;
	$testid = $wpdb->get_var($wpdb->prepare("SELECT Id FROM kv_tests WHERE name=%s",$_POST['testname']));
	$formulas = $wpdb -> get_col($wpdb->prepare("SELECT Formula FROM kv_results WHERE Test=%d",$testid));
	
	$FinalResults = array();
	
	foreach ( $formulas as $f)
	{
		if ($f != 'Default')
		{
		$evalf = $f;
		foreach($CategoryResults as $key=>$value)
		{
			$evalf = str_replace($key."=",$value."=",$evalf);
			$evalf = str_replace($key.">",$value.">",$evalf);
			$evalf = str_replace($key."<",$value."<",$evalf);
		}
		$r = null;
		eval("\$r=($evalf);");
		if ($r == true)
		{
			$resultname = $wpdb -> get_var($wpdb->prepare("SELECT Id FROM kv_results WHERE Test=%d and Formula=%s",$testid,$f));
			$FinalResults[] = (int)$resultname;
		}
		}
	}
	return $FinalResults;

}
?>

<?php
function get_last_test_result()
{
	$result = "";
	global $wpdb;
	if (is_user_logged_in())
	{
	$result = $wpdb ->get_row($wpdb->prepare('SELECT * FROM kv_users_results WHERE UserId=%s  AND Test=%d ORDER BY Date DESC LIMIT 1',wp_get_current_user() -> ID,$_GET['Test']),'ARRAY_A'); // get last taken test
	}
	else
	{
		$result['FinalResults'] = json_encode(unserialize(base64_decode($_GET['results'])));
		$result['Test'] = $_GET['Test'];
	}
	echo '<h3>Результаты теста</h3>';
	if ($result == null)
	{
		echo '<h4>'.'&nbsp&nbsp&nbsp&nbspВы не прошли еще ни одного теста</h4>';
	}
	for ($i=0; $i < count(json_decode($result['FinalResults'])); $i++)
	{
		$name = $wpdb ->get_var($wpdb->prepare('SELECT Result FROM kv_results WHERE Test=%d and Id=%d',$result['Test'],json_decode($result['FinalResults'])[$i]));
		$desc = $wpdb ->get_var($wpdb->prepare('SELECT Description FROM kv_results WHERE Test=%d and Id=%d',$result['Test'],json_decode($result['FinalResults'])[$i]));
		echo '<div class="result_single">';
		echo '<h4>Результат</h4>';
		echo '<h4>'.'&nbsp&nbsp&nbsp&nbsp'.$name.'</h4>';
		echo '<h4>Описание</h4>';
		echo '<h4>'.'&nbsp&nbsp&nbsp&nbsp'.$desc.'</h4>';
		echo '<hr/>';
		echo '</div>';
	}
}
function show_all_test_results()
{
	global $wpdb;
	$result = $wpdb ->get_results($wpdb->prepare('SELECT * FROM kv_users_results WHERE UserId=%s ORDER BY Test, Date DESC ',wp_get_current_user() -> ID),'ARRAY_A'); // get last taken test
	echo '<h2>Результаты тестов</h2>';
	if ($result == null)
	{
		echo '<h4>'.'&nbsp&nbsp&nbsp&nbspВы не прошли еще ни одного теста</h4>';
	}
	else
	{
	$currtest = "";
	$currdate = "";
	for ($a=0; $a < count($result); $a++)
	{
		if ( $result[$a]['Test'] != $currtest)
		{
		$currtestname = $wpdb->get_var($wpdb->prepare("SELECT name FROM kv_tests WHERE Id=%d",$result[$a]['Test']));
		echo '<div class="kv_results" onclick="showDateResults(event,this)">';
		echo '<h3> Тест "'.$currtestname.'"</h3>';
		$currtest = $result[$a]['Test'] ;
		}
		echo '<div class = "kv_test_result_date" style="display:none; margin-left:20px" onclick=showFullResults(event,this)>';
		echo '<h4> Дата : '.$result[$a]['Date'].'</h4>';
	for ($i=0; $i < count(json_decode($result[$a]['FinalResults'])); $i++)
	{
		$name = $wpdb ->get_var($wpdb->prepare('SELECT Result FROM kv_results WHERE Test=%d and Id=%d',$result[$a]['Test'],json_decode($result[$a]['FinalResults'])[$i]));
		$desc = $wpdb ->get_var($wpdb->prepare('SELECT Description FROM kv_results WHERE Test=%d and Id=%d',$result[$a]['Test'],json_decode($result[$a]['FinalResults'])[$i]));
		echo '<div class = "kv_test_result_full" style="display:none; margin-left:20px">';
		echo '<h4 class="result_name">Результат</h4>';
		echo '<h5 class="result_desc">'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$name.'</h5>';
		echo '<h4 class="result_name">Описание</h4>';
		echo '<h5 class="result_desc">'.'&nbsp;&nbsp;&nbsp;&nbsp;'.$desc.'</h5>';
		echo '<hr/>';
		echo '</div>';
		
	}
	echo '</div>';
	if ( $result[$a + 1]['Test'] != $currtest)
	{
		echo '</div>';
	}
		
	}
	}
}
/* function get_test_result($atts)
{
	$a = shortcode_atts ( array(
				'testname' => null,
				'date' => null),
				$atts);
	if ($a['testname'] == null ||
} */
?>
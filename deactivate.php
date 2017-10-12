<?php

function move_test_type_posts_to_draft()
{
	global $wpdb;
	$wpdb->update( 'wp_posts',array('post_status' => 'draft'), array( 'post_type' => 'kv_test' ) );
	
	$wpdb->delete( 'wp_posts', array( 'post_type' => 'kv_test' ) );
	$wpdb->query('DROP TABLE `kv_users_results`');
	$wpdb->query('DROP TABLE `kv_answers_categories`');
	$wpdb->query('DROP TABLE `kv_results`');
	$wpdb->query('DROP TABLE `kv_answers`');
	$wpdb->query('DROP TABLE `kv_questions`');
	$wpdb->query('DROP TABLE `kv_tests`');
}
?>
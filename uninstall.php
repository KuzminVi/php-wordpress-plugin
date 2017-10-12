<?php

global $wpdb;
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
delete_option('Submission_successful_page');
delete_option('Submission_failed_page');

$wpdb->delete( 'wp_posts', array( 'post_type' => 'kv_test' ) );

	$wpdb->query('DROP TABLE `kv_users_results`');
	$wpdb->query('DROP TABLE `kv_answers_categories`');
	$wpdb->query('DROP TABLE `kv_results`');
	$wpdb->query('DROP TABLE `kv_answers`');
	$wpdb->query('DROP TABLE `kv_questions`');
	$wpdb->query('DROP TABLE `kv_tests`');

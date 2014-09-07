<?php
/**
 * Plugin Name: bbPress Permalinks
 * Plugin URI: http://korobochkin.com/
 * Description: Change bbPress permalinks. ID number instead of topic slug. This links better than default if you have Cyrilic or other non english charackters in forum's and topic's slugs. forums/forum/FORUM_SLUG/ &rarr; forums/forum/ID/. forums/topic/TOPIC_SLUG/&rarr;forums/topic/ID/
 * Author: Kolya Korobochkin
 * Author URI: http://korobochkin.com/
 * Version: 1.0.0
 * Text Domain: bbpress_permalinks
 * Domain Path: /languages/
 */

defined ('ABSPATH') OR exit;

function bbp_permalinks_init () {
	// This plugin work only if bbPress activated
	if (class_exists ('bbPress')) {
		$structure = get_option ('permalink_structure');
		if ($structure) {
			// And run (add rewrite rules) only if WordPress permalink settings not default (site.com/?p=123)	
			add_action ('bbp_init', 'bbp_permalinks_rewrites_init', 29); // bbPress add self rules at 30
		}
		// if rewrite rules disabled just fix links (change slugs to id)
		add_filter ('post_type_link', 'bbp_permalinks_post_type_link', 99, 2);
	}
}
add_action ('init', 'bbp_permalinks_init', 0);

function bbp_permalinks_post_type_link ($link, $post = 0) {
	
	if ($post->post_type == bbp_get_forum_post_type ()) {
		// forums/forum/ID/

		$structure = get_option ('permalink_structure');
		if (!$structure) {
			// Permalinks looks like site.com/?p=123
			return home_url ('?post_type=' . bbp_get_forum_post_type () . '&p=' . $post->ID);
			// site.com/post_type=forum&p=ID
		}
		else {
			return home_url (bbp_get_forum_slug () . '/' . $post->ID . '/');
			// forums/forum/ID/
		}
	}
	elseif ($post->post_type == bbp_get_topic_post_type () ) {
		// forums/topic/ID/
		$structure = get_option ('permalink_structure');
		if (!$structure) {
			return home_url ('?post_type=' . bbp_get_topic_post_type () . '&p=' . $post->ID);
			// ?post_type=topic&p=ID
		}
		else {
			return home_url (bbp_get_topic_slug () . '/' . $post->ID . '/');
			// forums/topic/ID/
		}

	}
	else {
		return $link;
	}
	return $link;
}

function bbp_permalinks_rewrites_init () {
	$priority = 'top';
	$edit_slug = 'edit';
	$ids_regex = '/([0-9]+)/';

	$forum_slug = bbp_get_forum_slug (); // string 'forum'
	$topic_slug = bbp_get_topic_slug (); // string 'topic'
	$reply_slug = bbp_get_reply_slug (); // string 'slug'

	$paged_slug = bbp_get_paged_slug ();

	$paged_rule = '/([^/]+)/' . $paged_slug . '/?([0-9]{1,})/?$';
	$paged_rule_ids =  $ids_regex . $paged_slug . '/?([0-9]{1,})/?$';

	$view_id = bbp_get_view_rewrite_id();
	$paged_id = bbp_get_paged_rewrite_id();

	$edit_rule = $ids_regex . $edit_slug  . '/?$'; // for edit links
	$edit_id = bbp_get_edit_rewrite_id (); // for edit links


	/* From bbpress/bbpress.php (816 line)
	 * Edit Forum|Topic|Reply|Topic-tag
	 * forums/forum/ID/edit/
	 */
	add_rewrite_rule (
		$forum_slug . $edit_rule,
		'index.php?post_type=' . bbp_get_forum_post_type() . '&p=$matches[1]&' . $edit_id . '=1',
		$priority
	);
	// forums/topic/ID/edit/
	add_rewrite_rule (
		$topic_slug . $edit_rule,
		'index.php?post_type=' . bbp_get_topic_post_type() . '&p=$matches[1]&' . $edit_id . '=1',
		$priority
	);
	// forums/reply/ID/edit/
	add_rewrite_rule (
		$reply_slug . $edit_rule,
		'index.php?post_type=' . bbp_get_reply_post_type() . '&p=$matches[1]&' . $edit_id . '=1',
		$priority
	);


	/* Forums
	 * /forums/forum/ID/page/2
	 */
	add_rewrite_rule (
		$forum_slug . $paged_rule_ids,
		'index.php?post_type=' . bbp_get_forum_post_type () . '&p=$matches[1]&' . $paged_id .'=$matches[2]',
		$priority
	);
	// /forums/forum/ID/
	add_rewrite_rule (
		$forum_slug . $ids_regex . '?$',
		'index.php?post_type=' . bbp_get_forum_post_type () . '&p=$matches[1]',
		$priority
	);


	/* Topics
	 * /forums/topic/ID/page/2/
	 */
	add_rewrite_rule (
		$topic_slug . $paged_rule_ids,
		'index.php?post_type=' . bbp_get_topic_post_type () . '&p=$matches[1]&' . $paged_id . '=$matches[2]',
		$priority
	);
	// /forums/topic/ID/
	add_rewrite_rule(
		$topic_slug . $ids_regex . '?$',
		'index.php?post_type=' . bbp_get_topic_post_type () .'&p=$matches[1]',
		$priority
	);
}

// Activation and deactivation hooks
function bbp_permalinks_activate () {
	flush_rewrite_rules (false);
}
register_activation_hook (__FILE__, 'bbp_permalinks_activate');

function bbp_permalinks_deactivate () {
	flush_rewrite_rules (false);
}
register_deactivation_hook (__FILE__, 'bbp_permalinks_deactivate');
?>
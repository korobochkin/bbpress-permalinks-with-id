<?php
/**
 * Plugin Name: bbPress Permalinks with ID
 * Plugin URI: https://github.com/korobochkin/bbpress-permalinks-with-id
 * Description: Transforms default bbPress permalinks (URLs) that use slugs into permalinks that use numeric IDs.
 * Author: Nicolas Korobochkin
 * Author URI: https://korobochkin.wordpress.com/
 * Version: 1.0.5
 * Text Domain: bbpress-permalinks-with-id
 * Domain Path: /languages/
 * Requires at least: 4.1.1
 * Tested up to: 4.5.0
 * Requires PHP:
 * Requires Plugins: bbpress
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Add plugin actions and filters at bbp_init action which triggered only if bbPress activated.
 *
 * @since 1.0.0
 */
function bbp_permalinks_init() {
	$structure = get_option( 'permalink_structure' );
	if ( $structure ) {
		// Run (add rewrite rules) only if WordPress permalink settings not default (default looks like site.com/?p=123)
		add_action( 'bbp_add_rewrite_rules', 'bbp_permalinks_rewrites_init', 3 );
		// Create valid URL for our new rewrite rules
		add_filter( 'post_type_link', 'bbp_permalinks_post_type_link_pretty', 99, 2 );
	} else {
		// If permalink settings is default only change permalinks
		add_filter( 'post_type_link', 'bbp_permalinks_post_type_link_not_pretty', 99, 2 );
	}
}
add_action( 'bbp_init', 'bbp_permalinks_init' );

/**
 * Generate pretty permalinks for forums and topics.
 *
 * @since 1.0.0
 * @param string $link URL.
 * @param object $post An WordPress post object.
 */
function bbp_permalinks_post_type_link_pretty( $link, $post = 0 ) {
	if ( bbp_get_forum_post_type() === $post->post_type ) {
		// site.com/forums/forum/ID/
		return home_url(
			user_trailingslashit( bbp_get_forum_slug() . '/' . $post->ID )
		);
	} elseif ( bbp_get_topic_post_type() === $post->post_type ) {
		// site.com/forums/topic/ID/
		return home_url(
			user_trailingslashit( bbp_get_topic_slug() . '/' . $post->ID )
		);
	}
	return $link;
}

/**
 * Generate default permalinks for forums and topics.
 *
 * @since 1.0.0
 * @param string $link URL.
 * @param object $post An WordPress post object.
 */
function bbp_permalinks_post_type_link_not_pretty( $link, $post = 0 ) {
	if ( bbp_get_forum_post_type() === $post->post_type ) {
		// site.com/?post_type=forum&p=ID
		return home_url( '?post_type=' . bbp_get_forum_post_type() . '&p=' . $post->ID );
	} elseif ( bbp_get_topic_post_type() === $post->post_type ) {
		// site.com/?post_type=topic&p=ID
		return home_url( '?post_type=' . bbp_get_topic_post_type() . '&p=' . $post->ID );
	}
	return $link;
}

/**
 * Generate rewrite rules for forums and topics based on bbPress settings.
 *
 * @since 1.0.0
 */
function bbp_permalinks_rewrites_init() {
	$priority  = 'top';
	$edit_slug = 'edit';
	$ids_regex = '/([0-9]+)/';

	$forum_slug = bbp_get_forum_slug(); // string 'forum'
	$topic_slug = bbp_get_topic_slug(); // string 'topic'
	$reply_slug = bbp_get_reply_slug(); // string 'slug'

	$paged_slug = bbp_get_paged_slug(); // string 'page'

	$paged_rule     = '/([^/]+)/' . $paged_slug . '/?([0-9]{1,})/?$';
	$paged_rule_ids = $ids_regex . $paged_slug . '/?([0-9]{1,})/?$';

	$view_id  = bbp_get_view_rewrite_id();
	$paged_id = bbp_get_paged_rewrite_id();

	$edit_rule = $ids_regex . $edit_slug . '/?$'; // for edit links
	$edit_id   = bbp_get_edit_rewrite_id(); // for edit links

	/**
	 * From bbpress/bbpress.php
	 *
	 * @see \bbPress::add_rewrite_rules
	 * Edit Forum|Topic|Reply|Topic-tag
	 * forums/forum/ID/edit/
	 */
	add_rewrite_rule(
		$forum_slug . $edit_rule,
		'index.php?post_type=' . bbp_get_forum_post_type() . '&p=$matches[1]&' . $edit_id . '=1',
		$priority
	);
	// forums/topic/ID/edit/
	add_rewrite_rule(
		$topic_slug . $edit_rule,
		'index.php?post_type=' . bbp_get_topic_post_type() . '&p=$matches[1]&' . $edit_id . '=1',
		$priority
	);
	// forums/reply/ID/edit/
	add_rewrite_rule(
		$reply_slug . $edit_rule,
		'index.php?post_type=' . bbp_get_reply_post_type() . '&p=$matches[1]&' . $edit_id . '=1',
		$priority
	);

	/**
	 * Forums
	 * /forums/forum/ID/page/2
	 */
	add_rewrite_rule(
		$forum_slug . $paged_rule_ids,
		'index.php?post_type=' . bbp_get_forum_post_type() . '&p=$matches[1]&' . $paged_id . '=$matches[2]',
		$priority
	);
	// /forums/forum/ID/
	add_rewrite_rule(
		$forum_slug . $ids_regex . '?$',
		'index.php?post_type=' . bbp_get_forum_post_type() . '&p=$matches[1]',
		$priority
	);

	/**
	 * Topics
	 * /forums/topic/ID/page/2/
	 */
	add_rewrite_rule(
		$topic_slug . $paged_rule_ids,
		'index.php?post_type=' . bbp_get_topic_post_type() . '&p=$matches[1]&' . $paged_id . '=$matches[2]',
		$priority
	);
	// /forums/topic/ID/
	add_rewrite_rule(
		$topic_slug . $ids_regex . '?$',
		'index.php?post_type=' . bbp_get_topic_post_type() . '&p=$matches[1]',
		$priority
	);
}

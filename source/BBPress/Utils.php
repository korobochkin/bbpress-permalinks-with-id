<?php
namespace Korobochkin\BBPressPermalinksWithID\BBPress;

/**
 * Class Utils
 */
class Utils
{

    /**
     * Generate pretty permalinks for forums and topics.
     *
     * @since 2.0.0
     *
     * @param string     $link URL.
     * @param object|int $post An WordPress post object.
     *
     * @return string The URL.
     */
    public static function linkPretty($link, $post = 0)
    {
        // @codingStandardsIgnoreStart
        if (bbp_get_forum_post_type() === $post->post_type) {
        // @codingStandardsIgnoreEnd
            // Example site.com/forums/forum/ID/.
            return home_url(
                user_trailingslashit(bbp_get_forum_slug().'/'.$post->ID)
            );
        // @codingStandardsIgnoreStart
        } elseif (bbp_get_topic_post_type() == $post->post_type) {
        // @codingStandardsIgnoreEnd
            // Example site.com/forums/topic/ID/.
            return home_url(
                user_trailingslashit(bbp_get_topic_slug().'/'.$post->ID)
            );
        }

        return $link;
    }

    /**
     * Generate default permalinks for forums and topics.
     *
     * @since 2.0.0
     *
     * @param string     $link URL.
     * @param object|int $post An WordPress post object.
     *
     * @return string The URL.
     */
    public static function linkNotPretty($link, $post = 0)
    {
        // @codingStandardsIgnoreStart
        if (bbp_get_forum_post_type() == $post->post_type) {
        // @codingStandardsIgnoreEnd
            // Example site.com/?post_type=forum&p=ID.
            return home_url('?post_type='.bbp_get_forum_post_type().'&p='.$post->ID);
        // @codingStandardsIgnoreStart
        } elseif (bbp_get_topic_post_type() == $post->post_type) {
        // @codingStandardsIgnoreEnd
            // Example site.com/?post_type=topic&p=ID.
            return home_url('?post_type='.bbp_get_topic_post_type().'&p='.$post->ID);
        }

        return $link;
    }

    /**
     * Generate rewrite rules for forums and topics based on bbPress settings.
     *
     * @since 2.0.0
     */
    // @codingStandardsIgnoreStart
    public static function rewritesInit()
    {
        $priority  = 'top';
        $edit_slug = 'edit';
        $ids_regex = '/([0-9]+)/';

        $forum_slug = bbp_get_forum_slug(); // string 'forum'
        $topic_slug = bbp_get_topic_slug(); // string 'topic'
        $reply_slug = bbp_get_reply_slug(); // string 'slug'

        $paged_slug = bbp_get_paged_slug(); // string 'page'

        $paged_rule     = '/([^/]+)/'.$paged_slug.'/?([0-9]{1,})/?$';
        $paged_rule_ids = $ids_regex.$paged_slug.'/?([0-9]{1,})/?$';

        $view_id  = bbp_get_view_rewrite_id();
        $paged_id = bbp_get_paged_rewrite_id();

        $edit_rule = $ids_regex.$edit_slug.'/?$'; // for edit links
        $edit_id   = bbp_get_edit_rewrite_id(); // for edit links


        /* From bbpress/bbpress.php (816 line)
         * Edit Forum|Topic|Reply|Topic-tag
         * forums/forum/ID/edit/
         */
        add_rewrite_rule(
            $forum_slug.$edit_rule,
            'index.php?post_type='.bbp_get_forum_post_type().'&p=$matches[1]&'.$edit_id.'=1',
            $priority
        );
        // forums/topic/ID/edit/
        add_rewrite_rule(
            $topic_slug.$edit_rule,
            'index.php?post_type='.bbp_get_topic_post_type().'&p=$matches[1]&'.$edit_id.'=1',
            $priority
        );
        // forums/reply/ID/edit/
        add_rewrite_rule(
            $reply_slug.$edit_rule,
            'index.php?post_type='.bbp_get_reply_post_type().'&p=$matches[1]&'.$edit_id.'=1',
            $priority
        );


        /* Forums
         * /forums/forum/ID/page/2
         */
        add_rewrite_rule(
            $forum_slug.$paged_rule_ids,
            'index.php?post_type='.bbp_get_forum_post_type().'&p=$matches[1]&'.$paged_id.'=$matches[2]',
            $priority
        );
        // /forums/forum/ID/
        add_rewrite_rule(
            $forum_slug.$ids_regex.'?$',
            'index.php?post_type='.bbp_get_forum_post_type().'&p=$matches[1]',
            $priority
        );


        /* Topics
         * /forums/topic/ID/page/2/
         */
        add_rewrite_rule(
            $topic_slug.$paged_rule_ids,
            'index.php?post_type='.bbp_get_topic_post_type().'&p=$matches[1]&'.$paged_id.'=$matches[2]',
            $priority
        );
        // /forums/topic/ID/
        add_rewrite_rule(
            $topic_slug.$ids_regex.'?$',
            'index.php?post_type='.bbp_get_topic_post_type().'&p=$matches[1]',
            $priority
        );
        // @codingStandardsIgnoreEnd
    }
}

=== bbPress Permalinks with ID ===
Contributors: korobochkin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40korobochkin%2ecom&item_name=bbPress%20Permalinks%20with%20ID&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: bbpress, permalinks, links, url, rewrite rule, id, forums, topics, slugs, characters
Requires at least: 4.1.1
Tested up to: 4.1.1
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

ID instead of slug in bbPress topic and forum links.

== Description ==

By default bbPress URLs contains slugs. It's not good if your slugs (titles) containts something that not present in english alphabet. Sometimes links doesn't open or you can't send the link to your friend because URL contains this mystery symbols like cyrillic or chinese letters. Search engines also not love this types of URLs.

The bad URL examples:

* korobochkin.com/forums/forum/привет-заголовок
* korobochkin.com/forums/topic/название-топика-с-кириллицей

This plugin automaticly add custom rewrite rules to WordPress and your links rocks.

The good URL examples (after plugin activation):

* korobochkin.com/forums/forum/123/
* korobochkin.com/forums/topic/456/

The plugin works with any configuration of permalinks.

* korobochkin.com/forums/forum/123/
* korobochkin.com/?post_type=forum&p=123

The plugin featured and starred on Github by Stephen Edgar — bbPress core developer. [Discussion about this plugin](https://bbpress.org/forums/topic/topic-id-instead-of-slugs/) at bbPress official support forum.

On deinstallation this plugin completely removes custom rewrite rules.

[Plugin on Github](https://github.com/korobochkin/bbpress-permalinks-with-id)

== Installation ==

1. Install bbPress Permalinks with ID either via the WordPress.org plugin directory, or by uploading the files to your server.
2. Activate bbPress and after it activate bbPress Permalinks with ID.

== Changelog ==


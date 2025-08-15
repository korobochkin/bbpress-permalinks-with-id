=== bbPress Permalinks with ID ===
Contributors: korobochkin
Tags: bbpress, permalink, url, rewrite rule
Requires at least: 4.1.1
Tested up to: 6.8.2
Stable tag: 1.0.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Transforms default bbPress permalinks (URLs) that use slugs into permalinks that use numeric IDs.

== Description ==

This plugin transforms default bbPress permalinks (URLs) that use slugs into permalinks that use numeric IDs.

Please star the [GitHub repository](https://github.com/korobochkin/bbpress-permalinks-with-id/) to support continued development of this project.

== Installation ==

### How to install the plugin? ###

1. Download the plugin via the WordPress admin interface or execute `wp plugin install bbpress-permalinks-with-id`.
2. Activate bbPress and this plugin via the WordPress admin interface or execute `wp plugin activate bbpress-permalinks-with-id`.
3. Flush rewrite rules using one of the following methods:
   - Visit the `/wp-admin/options-permalink.php` page. WordPress automatically flushes rewrite rules every time you access this page, without requiring you to press the "Save Changes" button.
   - Alternatively, execute the `wp rewrite flush` command from WP-CLI.

### How to uninstall the plugin? ###

1. Deactivate the plugin via the WordPress admin interface or execute `wp plugin deactivate bbpress-permalinks-with-id`.
2. Delete the plugin via the WordPress admin interface or execute `wp plugin delete bbpress-permalinks-with-id`.
3. Flush rewrite rules using one of the following methods:
   - Visit the `/wp-admin/options-permalink.php` page. WordPress automatically flushes rewrite rules every time you access this page, without requiring you to press the "Save Changes" button.
   - Alternatively, execute the `wp rewrite flush` command from WP-CLI.

**Important:** URLs with IDs may have been shared on the internet, indexed by search engines, or referenced in your forum content. After uninstalling this plugin, those links will no longer function.

== Screenshots ==

1. Forum URL. The plugin adopts to your permalinks settings (you can change it on default bbPress settings page).
2. Topic edit URL. With this plugin all pages and links correctly opens.

== Frequently Asked Questions ==

### What URLs are supported? ###

The following table shows currently supported URL structures and their original counterparts. Note that the original URLs remain accessible.

The URL components `forums`, `forum`, `topic`, `reply`, and `paged` are dynamic values that can be modified through bbPress settings.

| URL + ID                          | URL + slug (bbPress default)           |
|:----------------------------------|:---------------------------------------|
| `/forums/forum/`                  | `/forums/forum/`                       |
| `/forums/forum/111/`              | `/forums/forum/test/`                  |
| `/forums/forum/111/edit/`         | `/forums/forum/test/edit/`             |
| `/forums/forum/111/page/2/`       | `/forums/forum/test/page/2/`           |
|                                   |                                        |
| `/forums/topic/222/`              | `/forums/topic/test/`                  |
| `/forums/topic/222/edit/`         | `/forums/topic/test/edit/`             |
| `/forums/topic/222/page/2/`       | `/forums/topic/test/page/2/`           |
|                                   |                                        |
| `/forums/reply/333/`              | `/forums/reply/test/`                  |
| `/forums/reply/333/edit/`         | `/forums/reply/test/edit/`             |
|                                   |                                        |
| `/?post_type=forum`               | `/?post_type=forum`                    |
| `/?post_type=forum&p=111`         | `/?post_type=forum&forum=test`         |
| `/?post_type=forum&p=111&edit=1`  | `/?post_type=forum&forum=test&edit=1`  |
| `/?post_type=forum&p=111&paged=2` | `/?post_type=forum&forum=test&paged=2` |
|                                   |                                        |
| `/?post_type=topic&p=222`         | `/?post_type=topic&forum=test`         |
| `/?post_type=topic&p=222&edit=1`  | `/?post_type=topic&forum=test&edit=1`  |
| `/?post_type=topic&p=222&paged=2` | `/?post_type=topic&forum=test&paged=2` |
|                                   |                                        |
| `/?reply=333`                     | `/post_type=reply&reply=test`          |
| `/?reply=333&edit=1`              | `/post_type=reply&reply=test&edit=1`   |

### Does the plugin change URLs in the interface? ###

Yes. URLs within theme templates that are generated using the `get_post_permalink()` function will adopt the ID-based structure.

### Do the old URLs in static content change? ###

No. Static content stored within the `wp_posts` table remains unaltered. The plugin only modifies the code responsible for generating and handling URLs.

### Do the default URLs with slugs continue to work? ###

Yes. The original URLs with slugs remain accessible. However, the plugin does not provide automatic redirection from slug-based URLs to ID-based URLs.

### How is the plugin tested? ###

The plugin undergoes manual testing with the latest WordPress and bbPress versions using the official Twenty Twelve (`twentytwelve`) theme. Additionally, automated code analysis tools test compatibility with both legacy and current versions, including Psalm and PHP Code Style validation (see `.github/workflows/tests.yml`).

### Why use URLs with IDs instead of slugs? ###

* Your forum operates in a language other than English and contains non-Latin characters.
* Forum and topic titles include non-letter symbols (emojis, special characters such as `<`).
* You frequently update slugs and titles of forums and topics and require more reliable and permanent URLs.
* Shorter, cleaner URLs that are easier to share and remember.

== Changelog ==

= 1.0.5 =

Update plugin text domain.

= 1.0.4 =

First version of plugin for wordpress.org. Without automatically flushing rewrite rules.

== Upgrade Notice ==

= 1.0.5 =

Update plugin text domain.

= 1.0.4 =

Basic functionality of plugin.
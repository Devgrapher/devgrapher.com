=== Category Specific RSS feed Subscription ===
Contributors: Tips and Tricks HQ, Ruhul Amin, drsassafras
Donate link: https://www.tipsandtricks-hq.com/development-center
Tags: Category Specific RSS, Wordpress category feed, custom category feed, WordPress Plugin, feed, category, rss feed, category feed, list category, tag, rss, rss2, tag cloud, post tags,
Requires at least: 3.0
Tested up to: 4.5
Stable tag: 2.0
License: GPLv2 or later

Wordpress plugin to add Category Specific RSS feed subscription menu to your posts, pages and sidebar.

== Description ==

This WordPress Plugin allows you to present a menu with multiple RSS feed subscription option to your site's visitors in addition to your normal RSS subscription option.

If your site covers multiple topics then your subscribed readers may get annoyed when you update your site with content that they are not interested in and they get a notification in their RSS reader.

I found that most of the time I never subscribe to a site's RSS feed when it doesn't have the topic/category specific subscription option, specially when the site covers multiple topics cause I don't like to be hammered with all the unwanted content updates.

This plugin allows you to show category specific RSS feed for all your categories. Alternatively, you can configure up to 8 different custom topic specific RSS feeds.

= RSS Feed for Your Tags = 

You can also create a tag specific RSS feed menu using this plugin. Create an rss feed menu for all of your tags on the blog using a simple shortcode.

There is an option to create a tag cloud with RSS feed of each tag also.

= Tag RSS Feeds for a Specific Post/Article =

You can use this plugin to show the tags and the rss feed of each tag for a particular article. This is very helpful if you currently show the tags of an article/post.

For information, updates and detailed documentation, please visit the [Category specific rss feed plugin](https://www.tipsandtricks-hq.com/wordpress-plugin-for-category-specific-rss-feed-subscription-menu-325) page.

== Usage ==

There are three ways you can use this plugin:

1. Add the Category Specific RSS Widget to your sidebar from the Widget menu
2. Add the shortcode [category_specific_rss_menu] to your posts or pages
3. Call the function from template files: &lt;?php echo show_cat_specific_rss_menu(); ?&gt;
4. Use the [tag_specific_rss_menu] shortcode to your posts, pages, sidebar widget to add a tag specific rss feed menu
5. Use the [tag_specific_rss_cloud] shortcode to your posts, pages, sidebar widget to add a tag cloud with rss feed of each tag

== Installation ==

1. Unzip and Upload the folder 'Category-specific-rss-wp' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings and configure the options eg. your category names and RSS link of the specific topic.
4. Go to the Widgets menu and add the 'Category Specific RSS' widget to the sidebar or add the shortcode [category_specific_rss_menu] to a post or page where you want it to appear.

== Frequently Asked Questions ==
None

== Screenshots ==
Check out this Plugin in action at https://www.tipsandtricks-hq.com/wordpress-plugin-for-category-specific-rss-feed-subscription-menu-325

== Upgrade Notice ==
Make sure to visit the plugin settings and select the RSS feed options accordingly after you update the plugin.

== Changelog ==

= 2.0 =

- WordPress 4.4 compatibility
- Changed the simple html dom class name to make it more unique.

= 1.13 =
- Changed a class name to make it more unique.

= 1.12 =
- Changed some function names so they are more unique and doesn't conflict with another plugin.

= 1.11 =
- Fixed an error with the rss widget.

= 1.10 =
- RSS Menu Now has the option of Providing Author RSS links
- RSS Menu Now has the option of supplying Custom RSS links while using Automatic Category and/or Author links
- Simplification of some code
- Now removes empty brackets on Custom RSS links
- RSS images now display uniformly, regardless of Option Chosen.
- Losless Image compression reduces size of file and is now SEO friendly
- Big Thank You to Brendan for making the above enhancements.

= 1.9 =
- Added a new feature to display a list of post specific tag rss links. This will be handy if you want to show the tag rss for an individual post.
- Added the option to show post count of the category when using the custom category option.

= 1.8 =
- Added a new tag specific rss feed option.
- Added a new shortcode to show a tag cloud with RSS feed links of each tag.
- WordPress 3.9 compatibility

= 1.7 =
- WordPress 3.7 compatibility

Changelog for old versions can be found at the following URL
https://www.tipsandtricks-hq.com/wordpress-plugin-for-category-specific-rss-feed-subscription-menu-325

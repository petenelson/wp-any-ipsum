=== Any Ipsum ===
Contributors: gungeekatx, shooper
Tags: content, ipsum
Donate link: https://baconipsum.com/
Requires at least: 4.0
Tested up to: 5.5
Stable tag: 1.9.3
Text Domain: any-ipsum
Domain Path: /lang
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Roll your own custom lorem ipsum generator

== Description ==

If you've ever had an idea for your own custom lorem ipsum generator (like Bacon Ipsum), this plugin will allow you to do so without any coding.  Just fill in a list of words, drop
the `[anyipsum-form]` shortcode in a post or page, and you have your very own custom ipsum generator. Forked from [Bacon Ipsum](http://baconipsum.com)

Includes:

* [anyipsum-form] shortcode for end-users to use, fully configurable
* custom words and filler text
* API (returns JSON, HTML or plain text)
* oEmbed support
* WP-CLI support via the 'wp any-ipsum' command

The plugin installs Bacon Ipsum filler by default, so be sure to check Settings/Any Ipsum to fully customize your ipsum.


== Installation ==

1. Upload the wp-any-ipsum directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the `[anyipsum-form]` shortcode in a page or post
4. Check Settings/Any Ipsum to fully customize your ipsum


== Changelog ==

= v1.9.3 July 31, 2018 =
* Fixed bugs in the code that loads default filler and custom words and an array count bug (props @colinusher)

= v1.9.1 November 15, 2017 =
* Added 'anyipsum-generated-filler' filter to API output.

= v1.9.0 September 23, 2016 =
* Added 'anyipsum-generated-filler' filter to allow modification of generated filler before display.
* Added 'anyipsum-after-starts-with-row' action to HTML form.
* Reformatted some of the label tags in the HTML form.

= v1.8.1 January 8, 2016 =
* Fixed a bug that was always showing the 'Starts with' text (thanks @ghost_harrenhal)

= v1.8.0 December 1, 2015 =
* Changed the WP-CLI generate-posts output to display a progress bar instead of individual posts (can be toggled via --no-progress-bar)

= v1.7.0 November 30, 2015 =
* Added --category support to WP-CLI, pass in either an ID, slug, or name
* Added --excerpt support to WP-CLI, pass this flag to use the first sentence of the post content as the post excerpt
* Added 'anyipsum-filler-wp-cli-insert-post-args' filter to allow modifications of the post insert args before each new post is created
* Added 'anyipsum-filler-wp-cli-post-inserted' action which is fired after each new post is created, passes post_id

= v1.6.1 September 18, 2015 =
* Added WP-CLI support for generating posts.  Try 'wp help any-ipsum generate-posts' for more info
* Fixed a bug with sentence mode generating a random number of sentences

= v1.5.0 June 19, 2015 =
* Action 'anyipsum-filler-generated' is fired after either the shortcode or API generates any custom filler (useful for logging)

= v1.4.1 July 30, 2015 =
* Fixed a missing </div> tag in the form

= v1.4.0 June 9, 2015 =
* Added 'html' format to API, which wraps each paragraph in p tags.
* Added range values for the 'paras' parameter to allow random number of paragraphs, ex: paras=3-7
* Added charset to Content-Type header for all API calls
* Added Content-Length header for all API calls

= v1.3.0 May 29, 2015 =
* API now supports a 'format' parameter to specify return format
* 'text' return format added to return ipsum in plain text

= v1.2.3 March 27, 2015 =
* Trim each word before adding it to a sentence

= v1.2.2 March 6, 2015 =
* Bux fix for API POST requests

= v1.2.1 March 4, 2015 =
* Code refactoring to use filter_input() and better WordPress coding standards
* Fixed activation bug that was not setting up the initial oEmbed settings

= v1.2.0 January 7, 2015 =
* Added admin notice to visit settings page on plugin activation
* Bug fix for sentences in JSON API

= v1.1.0 October 22, 2014 =
* Added a header for the form shortcode, ex: [anyipsum-form]Give our ipsum a try!  It's the best![/anyipsum-form]
* Added sentence mode for ipsum generators that want to use full sentences as custom words.
* The ipsum generator on baconipsum.com site is now powered by this plugin!

= 1.0.1 =
* Bug fixes for translations

= 1.0.0 =
* Initial release


== Upgrade Notice ==

= v1.9.3 July 31, 2018 =
* Fixed bugs in the code that loads default filler and custom words and an array count bug (props @colinusher)

= v1.9.1 November 15, 2017 =
* Added 'anyipsum-generated-filler' filter to API output.

= v1.9.0 September 23, 2016 =
* Added 'anyipsum-generated-filler' filter to allow modification of generated filler before display.
* Added 'anyipsum-after-starts-with-row' action to HTML form.
* Reformatted some of the label tags in the HTML form.


== Frequently Asked Questions ==

= Do you have any questions? =
We can answer them here!


== Screenshots ==

1. Configure your custom ipsum
2. Sample form
3. Sample output

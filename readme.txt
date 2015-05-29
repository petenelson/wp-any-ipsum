=== Any Ipsum ===
Contributors: gungeekatx, shooper
Tags: content, ipsum
Donate link: http://baconipsum.com/
Requires at least: 4.0
Tested up to: 4.2
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Roll your own custom lorem ipsum generator

== Description ==

If you've ever had an idea for your own custom lorem ipsum generator (like Bacon Ipsum), this plugin will allow you to do so without any coding.  Just fill in a list of words, drop
the `[anyipsum-form]` shortcode in a post or page, and you have your very own custom ipsum generator.  Forked from [Bacon Ipsum](http://baconipsum.com/)

Includes:

* [anyipsum-form] shortcode for end-users to use, fully configurable
* custom words and filler text
* API (returns JSON or plain text)
* oEmbed support

The plugin installs Bacon Ipsum filler by default, so be sure to check Settings/Any Ipsum to fully customize your ipsum.


== Installation ==

1. Upload the wp-any-ipsum directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the `[anyipsum-form]` shortcode in a page or post
4. Check Settings/Any Ipsum to fully customize your ipsum


== Changelog ==

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

= v1.2.1 March 4, 2015 =
* Code refactoring to use filter_input() and better WordPress coding standards
* Fixed activation bug that was not setting up the initial oEmbed settings


== Frequently Asked Questions ==

= Do you have any questions? =
We can answer them here!


== Screenshots ==

1. Configure your custom ipsum
2. Sample form
3. Sample output

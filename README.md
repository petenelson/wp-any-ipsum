# Any Ipsum
[WordPress plugin](https://wordpress.org/plugins/any-ipsum/) to roll your own custom lorem ipsum generator - forked from [Bacon Ipsum](http://baconipsum.com/)

[![Code Climate](https://codeclimate.com/github/petenelson/wp-any-ipsum/badges/gpa.svg)](https://codeclimate.com/github/petenelson/wp-any-ipsum)

## What's in here?

- **wp-anyipsum.php** - WordPress plugin wrapper (loades classes, registers core hooks, etc)
- **readme.txt** - Info for the WordPress repo
- **uninstall.php** - WordPress uninstall code
- **assets/** - Assets for the WordPress plugin repository
- **lang/** - For future translastions
- **lib/**
  - **admin-help** - Help page for the plugin
  - **default-custom.txt** - Default custom text (meat)
  - **default-filler.txt** - Default filler text (lorem ipsum)
  - **class-WPAnyIpsumCore.php** - Exposes filters used by other classes
  - **class-WPAnyIpsumForm.php** - Shortcode for generating the form to allow visitors to generate your custom filler
  - **class-WPAnyIpsumSettings.php** - Admin settings for managing your custom filler text
  - **class-WPAnyIpsumAPI.php** - JSON API endpoint
  - **class-WPAnyIpsumOembed.php** - oEmbed endpoint
  - **class-WPAnyIpsumGenerator.php** - The WPAnyIpsumGenerator class for generating custom filler text.

## Revision History

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

= v1.0.1 September 12, 2014 =
* Initial Release

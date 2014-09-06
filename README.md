WordPress plugin to roll your own custom lorem ipsum generator - forked from Bacon Ipsum

= What's in here? =

index.php - WordPress plugin wrapper

class-AnyIpsumGenerator.php - Contains the WPAnyIpsumGenerator class for generating custom filler text.  For example:

	require_once 'class-AnyIpsumGenerator.php';
	$any_ipsum_generator = new AnyIpsumGenerator();
	$custom_filler = $any_ipsum_generator->Make_Some_Custom_Filler('custom-and-filler', 3, true);


class-WPAnyIpsumForm.php - Shortcode for generating the form to allow visitors to generate your custom ipsum

class-WPAnyIpsumAPI.php - Optional JSON API endpoint

class-WPAnyIpsumSettings.php - Admin settings for managing your custom filler text


Revision History

= v0.0.1 September 6, 2014 =
* Initial fork of Bacon Ipsum

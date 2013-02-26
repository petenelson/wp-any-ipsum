A repository for the baconipsum.com code 

= What's in here? =

gga-BaconIpsumGenerator.php - Contains the BaconIpsumGenerator class for generating meaty filler text.  For example:

	require_once 'gga-BaconIpsumGenerator.php';
	$bacon_ipsum_generator = new BaconIpsumGenerator();
	$meaty_filler = $bacon_ipsum_generator->Make_Some_Meaty_Filler('meat-and-filler', 3, true);


gga-bacon-ipsum-form.php - WordPress plugin for generating the form you see on our home page as well as processing the form and outputting bacon ipsum filler.

gga-bacon-ipsum-api.php - WordPress plugin for our JSON API.

jquery-sample.html - Sample HTML/jQuery code for the jQuery plugin


Revision History

= v2.1.3 February 26, 2013 =
* Added CORS support to the API (thanks to Jim Smart jim@jimsmart.org)
* Updated jQuery sample to 1.9.1, removed our Google Analytics tracking

= v2.1.2 November 16, 2012 =
* Added 'no_tags' option to jQuery plugin (thanks to Joel Lord joel@engrenage.com)

= v2.1.1 October 18, 2012 =
* Added 'doner' to the generator's list of meat (http://en.wikipedia.org/wiki/Doner_kebab)

= v2.1 July 21, 2012 =
* Changed the Make_Some_Meaty_Filler() function to return an array of paragraphs instead of straight HTML, added default parameters
* Added support for individual sentences
* Added sentence support to the API code, minor code refactoring
* Added more randomness to commas in a sentence.  Instead of always adding a 
  comma to a sentence with seven or more words, it will add one about 2/3rds of the time.
* Updated jQuery plugin and sample code to support sentences

= July 14, 2012 = 
* First public release (generator, form plugin and API plugin)


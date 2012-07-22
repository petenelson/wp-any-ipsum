A repository for the baconipsum.com code 

= What's in here? =

gga-BaconIpsumGenerator.php - Contains the BaconIpsumGenerator class for generating meaty filler text.  For example:

	require_once 'gga-BaconIpsumGenerator.php';
	$bacon_ipsum_generator = new BaconIpsumGenerator();
	$meaty_filler = $bacon_ipsum_generator->Make_Some_Meaty_Filler('meat-and-filler', 3, true);


gga-bacon-ipsum-form.php - WordPress plugin for generating the form you see on our home page as well as processing the form and outputting bacon ipsum filler.

gga-bacon-ipsum-api.php - WordPress plugin for our JSON API.



Revision History

= v2.1 July 21, 2012 =
* Changed the Make_Some_Meaty_Filler() function to return an array of paragraphs instead of straight HTML, added default parameters
* Added support for individual sentences
* Added sentence support to the API code, minor code refactoring
* Added more randomness to commas in a sentence.  Instead of always adding a 
  comma to a sentence with seven or more words, it will add one about 2/3rds of the time.
* Updated jQuery plugin and sample code to support sentences

= July 14, 2012 = 
* First public release (generator, form plugin and API plugin)


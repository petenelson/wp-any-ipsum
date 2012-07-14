A repository for the baconipsum.com code 

= What's in here? =

gga-BaconIpsumGenerator.php - Contains the BaconIpsumGenerator class for generating meaty filler text.  For example:

	$bacon_ipsum_generator = new BaconIpsumGenerator();
	$meaty_filler = $bacon_ipsum_generator->Make_Some_Meaty_Filler('meat-and-filler', 3, true);


gga-bacon-ipsum-form.php - WordPress plugin for generating the form you see on our home page as well as processing the form and outputting bacon ipsum filler.

gga-bacon-ipsum-api.php - WordPress plugin for our JSON API.



Revision History

= July 14, 2012 = 
* First public release (generator, form plugin and API plugin)


module.exports = function( grunt ) {

	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),

		makepot: {
			target: {
				options: {
					type:       'wp-plugin',
					mainFile:   'wp-anyipsum.php'
				}
			}
		},

		clean:  {
			wp: [ "release" ]
		},

		wp_readme_to_markdown: {
			options: {
				screenshot_url: "https://raw.githubusercontent.com/petenelson/wp-any-ipsum/master/assets/{screenshot}.png",
				},
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		insert: {
			options: {},
			badges: {
				src: "badges.md",
				dest: "README.md",
				match: "**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  "
			},
		},

		copy: {

			// create release for WordPress repository
			wp: {
				files: [

					// directories
					{ expand: true, src: ['lang/**'], dest: 'release/' },
					{ expand: true, src: ['lib/**'], dest: 'release/' },

					// root dir files
					{
						expand: true,
						src: [
							'*.php',
							'readme.txt',
							],
						dest: 'release/'
					}

				]
			} // wp

		}

	} ); // grunt.initConfig


	// Load tasks
	var tasks = [
		'grunt-contrib-clean',
		'grunt-contrib-copy',
		'grunt-wp-readme-to-markdown',
		'grunt-insert',
		'grunt-wp-i18n'
		];

	for	( var i = 0; i < tasks.length; i++ ) {
		grunt.loadNpmTasks( tasks[ i ] );
	};


	// Register tasks

	// create release for WordPress repository
	grunt.registerTask( 'wp', [ 'makepot', 'clean', 'copy' ] );

	grunt.registerTask( 'readme', ['wp_readme_to_markdown', 'insert:badges'] );

	grunt.util.linefeed = '\n';

};

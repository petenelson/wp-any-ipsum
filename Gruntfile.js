module.exports = function( grunt ) {

	grunt.initConfig( {
		pkg:    grunt.file.readJSON( 'package.json' ),

		clean:  {
			wp: [ "release" ]
		},

		copy:   {
			main: {
				files: [
					// lib
					{ expand: true, src: ['lib/**'], dest: 'release/' }
				]
			}
		}

	} ); // grunt.initConfig




	// Load tasks
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');

	// Register tasks
	grunt.registerTask( 'wp', [ 'clean', 'copy' ] );

	// grunt.registerTask( 'test', ['phpunit', 'qunit'] );

	grunt.util.linefeed = '\n';
};

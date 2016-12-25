module.exports = function (grunt) {
	'use strict';

	// Force use of Unix newlines
	grunt.util.linefeed = '\n';

	grunt.initConfig ({
		pkg: grunt.file.readJSON ('package.json'),

		compress: {
			plugin: {
				options: {
					archive: 'dist/<%= pkg.name %>.<%= pkg.version %>.zip',
					mode: 'zip',
					pretty: true
				},
				files: [
					{
						expand: true,
						cwd: 'plugin/',
						src: '**',
						dest: '<%= pkg.name %>/',
						dot: false
					}
				]
			}
		}

		// TODO: create a composer task for building production version of vendor
	});

	require('load-grunt-tasks')(grunt, { scope: 'devDependencies' });

	grunt.registerTask('default', [
		'compress:plugin'
	]);

};
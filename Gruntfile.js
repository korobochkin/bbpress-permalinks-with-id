module.exports = function (grunt) {

	grunt.initConfig ({
		pkg: grunt.file.readJSON ('package.json'),

		po2mo: {
			languages: {
				src: 'plugin/languages/*.po',
				expand: true,
			},
		},
		potomo: {
			dev: {
				options: {
					poDel: false,
				},
				files: [{
					expand: true,
					cwd: 'plugin/languages/',
					src: ['*.po'],
					dest: 'plugin/languages/',
					ext: '.mo',
					nonull: true,
				}],
			},
		},
		compress: {
			dist: {
				options: {
					archive: 'dist/<%= pkg.name %>.zip',
					mode: 'zip',
					pretty: true,
				},
				files: [
					{
						expand: true,
						cwd: 'plugin/',
						src: '**',
						dest: '<%= pkg.name %>/',
						dot: false,
					},
				],
			},
		},
	});

	grunt.loadNpmTasks ('grunt-po2mo');
	grunt.loadNpmTasks ('grunt-potomo');
	grunt.loadNpmTasks ('grunt-contrib-compress');

	grunt.registerTask ('default',
		[
			'po2mo:languages',
			'compress:dist'
		]
	);

};
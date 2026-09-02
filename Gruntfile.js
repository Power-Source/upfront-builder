/*global module, require */
module.exports = function(grunt) {
	'use strict';

	var fileURLToPath = require('url').fileURLToPath,
		path = require('path'),
		sass = require('sass');

	grunt.registerTask('sass', 'Compile Sass with Dart Sass', function() {
		var destination = 'styles/exporter.css',
			mapDestination = destination + '.map',
			result = sass.compile('styles/sass/exporter.scss', {
				style: 'expanded',
				sourceMap: true,
				sourceMapIncludeSources: true
			});

		result.sourceMap.file = path.basename(destination);
		result.sourceMap.sources = result.sourceMap.sources.map(function(source) {
			return path.relative(path.dirname(destination), fileURLToPath(source)).replace(/\\/g, '/');
		});

		grunt.file.write(destination, result.css + '\n/*# sourceMappingURL=' + path.basename(mapDestination) + ' */\n');
		grunt.file.write(mapDestination, JSON.stringify(result.sourceMap));
		grunt.log.ok('Compiled ' + destination + ' with source map.');
	});

	grunt.registerTask('build', ['sass']);
	grunt.registerTask('default', ['build']);
};

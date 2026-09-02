'use strict';

var fs = require('fs'),
	pairs = [
		['vendor/dompurify/purify.min.js', 'node_modules/dompurify/dist/purify.min.js'],
		['vendor/dompurify/LICENSE', 'node_modules/dompurify/LICENSE']
	];

pairs.forEach(function(files) {
	var bundled = fs.readFileSync(files[0]),
		installed = fs.readFileSync(files[1]);

	if (!bundled.equals(installed)) {
		throw new Error(files[0] + ' does not match ' + files[1]);
	}
});

console.log('Bundled vendor files match installed packages.');

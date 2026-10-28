'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const source = fs.readFileSync(path.join(__dirname, '../templates/plugin/js/create_edit.js'), 'utf8');

function createEditor(options = {}) {
	const handlers = new Map();
	const navigations = [];
	const errors = [];
	const requests = [];
	const theme = options.theme || 'webmaster-theme';
	const actionSlug = options.actionSlug || 'create_new/psource-theme';
	const window = {
		location: {
			href: 'https://builder.test/wp-admin/admin.php?page=upfront-builder',
			origin: 'https://builder.test',
			search: options.search || '',
			assign: url => navigations.push(url)
		},
		_thx: {
			editor_base: options.base || 'https://builder.test/' + actionSlug,
			action_slug: actionSlug,
			admin_ajax: '/wp-admin/admin-ajax.php',
			l10n: {oops: 'Navigation failed'}
		}
	};
	const chain = {
		find() { return this; },
		on(event, selector, handler) {
			if (handler) handlers.set(selector, handler);
			return this;
		},
		text() { return ''; },
		attr(name) { return name === 'data-theme' ? theme : undefined; },
		each() { return this; },
		closest() { return this; },
		is() { return false; },
		remove() { return this; },
		offset() { return {top: 0}; },
		height() { return 0; },
		scrollTop() { return this; }
	};
	function $(value) {
		if (typeof value === 'function') value.call({});
		return chain;
	}
	$.post = (url, data) => {
		requests.push(data);
		const result = {
			done(callback) {
				callback(data.action === 'upfront_thx-check-theme'
					? {error: 0}
					: Object.hasOwn(options, 'response') ? options.response : {theme: {directory: theme}});
				return this;
			},
			fail() { return this; },
			always(callback) { callback(); return this; }
		};
		return result;
	};
	vm.runInNewContext(source, {
		window, jQuery: $, _thx: window._thx, URL, escape,
		_: {map: (data, callback) => Object.entries(data).map(([key, value]) => callback(value, key))},
		document: {
			querySelector: () => null,
			createElement: tag => {
				const element = {appendChild() {}};
				if (tag === 'p') {
					Object.defineProperty(element, 'textContent', {set(value) { errors.push(value); }});
				}
				return element;
			}
		}
	});
	return {
		navigations, errors, requests,
		click(selector) {
			handlers.get(selector).call({}, {target: {}, preventDefault() {}, stopPropagation() {}});
		}
	};
}

for (const selector of ['button.edit.theme', 'button.create.theme']) {
	for (const active of ['psource-theme', 'source-theme', 'theme', 'upfront']) {
		test(`${selector}: opens requested theme instead of ${active}`, () => {
			const editor = createEditor({actionSlug: 'create_new/' + active});
			editor.click(selector);
			assert.deepEqual(editor.navigations, ['https://builder.test/create_new/webmaster-theme']);
			assert.deepEqual(editor.errors, []);
			assert.equal(editor.requests.length, selector === 'button.create.theme' ? 2 : 0);
		});
	}
	test(`${selector}: preserves subdirectory and query routing`, () => {
		const editor = createEditor({
			base: 'https://builder.test/site/?upfront=create_new/psource-theme'
		});
		editor.click(selector);
		assert.deepEqual(editor.navigations, ['https://builder.test/site/?upfront=create_new/webmaster-theme']);
	});
	test(`${selector}: rejects cross-origin editor URL`, () => {
		const editor = createEditor({base: 'https://other.test/create_new/psource-theme'});
		editor.click(selector);
		assert.deepEqual(editor.navigations, []);
		assert.deepEqual(editor.errors, ['Navigation failed']);
	});
}

for (const response of [null, {}, {theme: {}}, {theme: {directory: '../psource-theme'}}, {error: {message: 'Failed'}}]) {
	test(`creation: invalid response does not open existing theme: ${JSON.stringify(response)}`, () => {
		const editor = createEditor({response});
		editor.click('button.create.theme');
		assert.deepEqual(editor.navigations, []);
		assert.deepEqual(editor.errors, ['Navigation failed']);
	});
}

test('editing: dev flag preserves existing routing query', () => {
	const editor = createEditor({
		base: 'https://builder.test/site/?upfront=create_new/psource-theme',
		search: '?page=upfront-builder&dev=true'
	});
	editor.click('button.edit.theme');
	const url = new URL(editor.navigations[0]);
	assert.equal(url.searchParams.get('upfront'), 'create_new/webmaster-theme');
	assert.equal(url.searchParams.get('dev'), 'true');
});

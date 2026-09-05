/* global DOMPurify */
;(function ($, undefined) {

function show_error (msg) {
	var error,
		heading,
		paragraph;

	msg = msg || ((window._thx || {}).l10n || {}).oops;
	if (!msg) return false;

	hide_errors();

	error = document.createElement('div');
	error.className = 'error after-h2 upfront-error';
	paragraph = document.createElement('p');
	paragraph.textContent = msg;
	error.appendChild(paragraph);
	heading = document.querySelector('.upfront_admin.upfront-builder h1');
	if (heading) heading.parentNode.insertBefore(error, heading.nextSibling);

	var root_pos = ($(".upfront_admin.upfront-builder .upfront-error").offset() || {}).top || 0,
	 	top_pos = root_pos - $("#wpadminbar").height()
	;

	$(window).scrollTop(top_pos > 0 ? top_pos : 0);

	return true;
}

function hide_errors () { return $(".upfront_admin.upfront-builder .upfront-error").remove(); }

function get_safe_navigation_url (value) {
	var url;

	if (typeof value !== 'string' || !value) return false;

	try {
		url = new URL(value, window.location.href);
	} catch (error) {
		return false;
	}

	if (!url.protocol.match(/^https?:$/) || url.origin !== window.location.origin) return false;

	return url.href;
}

function is_valid_theme_slug (slug) {
	return typeof slug === 'string' && /^[a-z0-9][a-z0-9_-]*$/i.test(slug);
}

function edit_theme (theme) {
	var search = window.location.search.toString(),
		edbase = (window._thx || {}).editor_base,
		slug = (window._thx || {}).action_slug,
		url
	;
	if (!edbase || !slug || !is_valid_theme_slug(theme)) return false;

	url = edbase.replace(slug, slug.replace(/\/theme/, '/' + theme));

	hide_errors();

	if (search.length && search.match(/[?&]dev=/)) {
		url += '?dev=true';
	}
	url = get_safe_navigation_url(url);
	if (url) window.location.assign(url);
}

function get_data (new_theme) {
	new_theme = undefined === new_theme ? true : new_theme; // Do *not* use ES6 syntax for defaults
	var data = {},
		$ins = ( new_theme )
			? $("#new-theme input, #new-theme textarea, #new-theme select")
			: $("#edit-theme input, #edit-theme textarea, #edit-theme select")
	;
	$ins.each(function () {
		var $me = $(this),
			idx = $me.attr("id"),
			value = $me.is(":checkbox") ? $me.is(":checked") : $me.val()
		;
		data["thx-" + idx] = value;
	});
	return data;
}

function init_new () {
	var frame,
		$screenshot = $(".uf-thx-theme_screenshot").find('img'),
		$selected = $("#existing-theme").find('.uf-thx-theme.selected'),
		$name = $selected.find('.uf-thx-caption > span').text(),
		themeName = document.querySelector('#existing-theme .theme-name');

	if (themeName) themeName.textContent = $name;

	if ($screenshot.attr("src")) { $screenshot.addClass('nostyle'); }

	$("#new-theme")
		.on("click", "button.create.theme", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var $me = $(this),
				data = get_data(true),
				base_url = (window._thx || {}).editor_base || window.location.pathname,
				slug
			;
			if ('thx-theme-slug' in data) {
				slug = data['thx-theme-slug'];
			}
			data.add_global_regions = true;

			hide_errors();
			$me.text(((_thx || {}).l10n || {}).checking || 'Checking');

			$.post(_thx.admin_ajax, {
				action: 'upfront_thx-check-theme',
				name: data['thx-theme-name'] || '',
				mode: "theme"
			}).done(function(response) {
				var error = parseInt((response || {}).error, 10),
					msg = (response || {}).msg || ''
				;
				if (error > 0) return show_error(msg);

				$me.text(((_thx || {}).l10n || {}).creating || 'Creating');

				$.post(_thx.admin_ajax, {
					action: 'upfront_thx-create-theme',
					mode: "theme",
					form: _.map(data, function(value, key){ return key + '=' + escape(value); }).join('&')
				}).done(function(response) {
					if (!slug && response && "theme" in response) {
						slug = (response.theme || {directory: false}).directory;
					}
					var editorUrl = is_valid_theme_slug(slug)
						? get_safe_navigation_url(base_url.replace(/\/theme/, '/' + slug))
						: false;
					if (editorUrl) window.location.assign(editorUrl);
					else window.location.reload();
				}).fail(function(){
					show_error();
					$me.text(((_thx || {}).l10n || {}).start_building || 'Start Building');
				});

			}).fail(function(){
				show_error();
			}).always(function () {
				$me.text(((_thx || {}).l10n || {}).start_building || 'Start Building');
			});

			return false;
		})
	;
}

function init_existing () {
	$("#existing-theme")
		.find(".uf-thx-theme a").on("click", function (e) {
			e.preventDefault();
			e.stopPropagation();

			return false;
		})
		.on("click", "button.edit.info", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var $edit_form = $('#edit-theme'),
				$edit_form_content = $edit_form.find('.form_content'),
				$edit_form_container = $edit_form.closest('.postbox-modal-container'),
				selected_theme = $(e.target).closest('.uf-thx-theme').attr('data-theme'),

				$me = $(this),
				old_text_label = false // Will hold the old label
			;

			// Swap the old label with working indicator
			if ($me.length && $me.is("button")) {
				old_text_label = $me.text();
				$me.text(((_thx || {}).l10n || {}).loading || 'Loading...');
			}

			$.post(_thx.admin_ajax, {
				action: 'upfront_thx-get-edit-theme-form',
				mode: "theme",
				selected: selected_theme,
			}).done(function(response) {
				var cleanForm = DOMPurify.sanitize(response, {
					ALLOWED_TAGS: ['a', 'button', 'div', 'h2', 'img', 'input', 'label', 'p', 'span', 'textarea'],
					ALLOWED_ATTR: ['alt', 'checked', 'class', 'for', 'href', 'id', 'placeholder', 'readonly', 'rel', 'src', 'target', 'type', 'value'],
					RETURN_DOM_FRAGMENT: true
				}),
					contentElement = $edit_form_content.get(0);
				if (!contentElement) return show_error();
				contentElement.textContent = '';
				contentElement.appendChild(cleanForm);
				$edit_form_container.show();
			}).fail(function(){
				show_error();
			}).always(function () {
				// Just clean up the swapped label
				if ($me.length && $me.is("button") && old_text_label) {
					$me.text(old_text_label);
				}
			});

			return false;
		})
		.on("click", "button.edit.theme", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var current = $(e.target).closest('.uf-thx-theme').attr('data-theme');
			if (!current) return false;

			edit_theme(current);

			return false;
		})
		.on("click", "button.download", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var current = get_safe_navigation_url($(e.target).closest('a').attr('data-download_url'));
			if (!current) return false;

			window.location.assign(current);

			return false;
		})
	;
	$(".postbox-modal-container")
		.on("click", "#postbox-modal-close", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var $edit_form = $('#edit-theme'),
				$edit_form_content = $edit_form.find('.form_content'),
				$edit_form_container = $edit_form.closest('.postbox-modal-container')
			;

			if ($edit_form_content[0]) $edit_form_content[0].textContent = '';
			$edit_form_container.hide();

			return false;
		})
		.on("click", "button.edit.info", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var data = get_data(false);
			if (!data['thx-theme-slug']) return false;

			data.add_global_regions = true;

			hide_errors();

			$.post(_thx.admin_ajax, {
				action: 'upfront_thx-update-theme',
				mode: "theme",
				form: _.map(data, function(value, key){ return key + '=' + escape(value); }).join('&')
			}).done(function(response) {
				window.location.reload();
			}).fail(function(response){
				var error = (((response || {}).responseJSON || {}).error || {}).message || false;
				if (window.console && window.console.error) window.console.error('Theme update failed:', response);
				show_error(error);
			});

			return false;
		})
		.on("click", "button.conflict.fix", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var data = get_data(false);
			if (!data['thx-theme-slug']) return false;

			data.add_global_regions = true;

			hide_errors();

			$.post(_thx.admin_ajax, {
				action: 'upfront_thx-clone-theme',
				mode: "theme",
				form: _.map(data, function(value, key){ return key + '=' + escape(value); }).join('&')
			}).done(function (response) {
				window.location.reload();
			}).fail(function (rsp) {
				var error = (((rsp || {}).responseJSON || {}).error || {}).message || false;
				show_error(error);
				$(".postbox-modal-container #postbox-modal-close").click(); // Close popup modal
			});

			return false;
		})
		.on("click", ".uf-thx-theme_screenshot button.change-image", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var frame,
				$me = $(this).closest('.uf-thx-theme_screenshot'),
				$img = $me.find("img"),
				$use = $me.find('#theme-screenshot')
			;

			if (frame) {
				frame.open();
				return false;
			}

			frame = wp.media({
				title: ((_thx || {}).l10n || {}).select_media || 'Select or Upload Media Of Your Chosen Persuasion',
				library: {
		      	type: 'image'
		   	},
				button: {
					text: ((_thx || {}).l10n || {}).use_media || 'Use this media'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			frame.on("select", function () {
				var img = frame.state().get('selection').first().toJSON(),
					url = (img || {}).url || false,
					id = (img || {}).id || false
				;
				if (!url || !id) return false;

				$use.val(id);

				// We may or may not have an <img /> el now.
				// Let's check and see if we need to add one
				if (!$img.length) {
					// The case of initial shot adding - there's no <img /> el,
					// so let's add it now and nuke the no-image placeholder
					$img = $('<img />');
					$me.find(".no-image").replaceWith($img);
				}

				// By now we should be all good either way
				// Let's just do this
				$img.attr("src", url);
				$img.addClass('nostyle');
			});

			frame.open();

			return false;
		})
	;
}

function init () {
	if (this._initialized) {
		return false;
	}
	this._initialized = true;
	init_new();
	init_existing();
}

$(init);

})(jQuery);

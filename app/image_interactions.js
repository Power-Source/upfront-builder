define(['thx-interact'], function (interact) {
	'use strict';

	var $ = jQuery,
		DEFAULT_CANCEL = 'input, textarea, button, select, option, [contenteditable]';

	function Draggable($element, options) {
		this.$element = $element;
		this.element = $element[0];
		this.options = $.extend({
			disabled: false,
			cancel: DEFAULT_CANCEL,
			helper: 'original',
			appendTo: 'body',
			revert: false,
			zIndex: false
		}, options);
		this.interactable = interact(this.element);
		this.drag = null;
		this.refresh();
	}

	Draggable.prototype.refresh = function () {
		var self = this;
		this.interactable.draggable({
			enabled: !this.options.disabled,
			ignoreFrom: this.options.cancel || DEFAULT_CANCEL,
			listeners: {
				start: function (event) { self.start(event); },
				move: function (event) { self.move(event); },
				end: function (event) { self.stop(event); }
			}
		});
	};

	Draggable.prototype.createHelper = function (event) {
		var helper = this.options.helper,
			$helper;

		if ($.isFunction(helper)) {
			$helper = $(helper.call(this.element, event));
		} else if (helper === 'clone') {
			$helper = this.$element.clone().removeAttr('id');
		} else {
			return this.$element;
		}

		if (!$helper.parent().length) $helper.appendTo(this.options.appendTo || 'body');
		$helper.css({
			position: 'absolute',
			left: this.$element.offset().left,
			top: this.$element.offset().top,
			width: this.$element.outerWidth(),
			height: this.$element.outerHeight()
		});
		return $helper;
	};

	Draggable.prototype.getCallbackData = function () {
		return {
			helper: this.drag.$helper,
			position: this.drag.position,
			offset: this.drag.offset,
			originalPosition: this.drag.originalPosition
		};
	};

	Draggable.prototype.start = function (event) {
		var position = this.$element.position(),
			offset = this.$element.offset(),
			$helper = this.createHelper(event),
			callbackData;

		this.drag = {
			$helper: $helper,
			isOriginal: $helper[0] === this.element,
			originalPosition: {left: position.left, top: position.top},
			position: {left: position.left, top: position.top},
			offset: {left: offset.left, top: offset.top}
		};
		$helper.addClass('upfront-dragging');
		if (this.options.zIndex !== false) $helper.css('z-index', this.options.zIndex);
		callbackData = this.getCallbackData();
		if ($.isFunction(this.options.start)) this.options.start.call(this.element, event, callbackData);
	};

	Draggable.prototype.move = function (event) {
		var callbackData;
		if (!this.drag) return;

		this.drag.position.left += event.dx;
		this.drag.position.top += event.dy;
		this.drag.offset.left += event.dx;
		this.drag.offset.top += event.dy;
		callbackData = this.getCallbackData();
		if ($.isFunction(this.options.drag)) this.options.drag.call(this.element, event, callbackData);
		this.drag.$helper.css({left: callbackData.offset.left, top: callbackData.offset.top});
	};

	Draggable.prototype.stop = function (event) {
		var callbackData;
		if (!this.drag) return;

		callbackData = this.getCallbackData();
		if ($.isFunction(this.options.stop)) this.options.stop.call(this.element, event, callbackData);
		this.drag.$helper.removeClass('upfront-dragging');
		if (!this.drag.isOriginal) {
			this.drag.$helper.remove();
		} else if (this.options.revert) {
			this.$element.css(this.drag.originalPosition);
		}
		this.drag = null;
	};

	Draggable.prototype.setDisabled = function (disabled) {
		this.options.disabled = !!disabled;
		this.interactable.draggable({enabled: !this.options.disabled});
	};

	Draggable.prototype.destroy = function () {
		this.interactable.draggable(false);
		if (this.drag && !this.drag.isOriginal) this.drag.$helper.remove();
		this.drag = null;
	};

	function Resizable($element, options) {
		this.$element = $element;
		this.element = $element[0];
		this.options = $.extend({
			disabled: false,
			handles: {},
			minWidth: 10,
			minHeight: 10
		}, options);
		this.interactable = interact(this.element);
		this.axis = null;
		this.originalPosition = null;
		this.originalSize = null;
		this.position = null;
		this.size = null;
		this.refresh();
	}

	Resizable.prototype.getEdges = function () {
		var edges = {top: false, right: false, bottom: false, left: false},
			handles = this.options.handles;

		$.each(handles, function (direction, selector) {
			if (direction.indexOf('n') !== -1) edges.top = selector;
			if (direction.indexOf('e') !== -1) edges.right = selector;
			if (direction.indexOf('s') !== -1) edges.bottom = selector;
			if (direction.indexOf('w') !== -1) edges.left = selector;
		});
		return edges;
	};

	Resizable.prototype.refresh = function () {
		var self = this;
		this.interactable.resizable({
			enabled: !this.options.disabled,
			edges: this.getEdges(),
			listeners: {
				start: function (event) { self.start(event); },
				move: function (event) { self.move(event); },
				end: function (event) { self.stop(event); }
			}
		});
	};

	Resizable.prototype.getAxis = function (event) {
		var axis = '';
		if (event.edges.top) axis += 'n';
		if (event.edges.bottom) axis += 's';
		if (event.edges.left) axis += 'w';
		if (event.edges.right) axis += 'e';
		return axis;
	};

	Resizable.prototype.getCallbackData = function () {
		return {
			originalElement: this.$element,
			element: this.$element,
			helper: this.$element,
			originalPosition: this.originalPosition,
			originalSize: this.originalSize,
			position: this.position,
			size: this.size
		};
	};

	Resizable.prototype.start = function (event) {
		var position = this.$element.position();
		this.axis = this.getAxis(event);
		this.originalPosition = {left: position.left, top: position.top};
		this.originalSize = {width: this.$element.width(), height: this.$element.height()};
		this.position = $.extend({}, this.originalPosition);
		this.size = $.extend({}, this.originalSize);
		if ($.isFunction(this.options.start)) this.options.start.call(this.element, event, this.getCallbackData());
	};

	Resizable.prototype.constrain = function (value, min, max) {
		value = Math.max(typeof min === 'number' ? min : 0, value);
		return typeof max === 'number' && max > 0 ? Math.min(max, value) : value;
	};

	Resizable.prototype.move = function (event) {
		var width = this.constrain(event.rect.width, this.options.minWidth, this.options.maxWidth),
			height = this.constrain(event.rect.height, this.options.minHeight, this.options.maxHeight),
			callbackData;

		this.position = {
			left: this.position.left + event.deltaRect.left,
			top: this.position.top + event.deltaRect.top
		};
		this.size = {width: width, height: height};
		callbackData = this.getCallbackData();
		if ($.isFunction(this.options.resize)) this.options.resize.call(this.element, event, callbackData);
		this.$element.css({
			left: callbackData.position.left,
			top: callbackData.position.top,
			width: callbackData.size.width,
			height: callbackData.size.height
		});
	};

	Resizable.prototype.stop = function (event) {
		if (!this.originalSize) return;
		if ($.isFunction(this.options.stop)) this.options.stop.call(this.element, event, this.getCallbackData());
		this.originalSize = null;
	};

	Resizable.prototype.updateCache = function (values) {
		if (values.left !== undefined) this.position.left = values.left;
		if (values.top !== undefined) this.position.top = values.top;
		if (values.width !== undefined) this.size.width = values.width;
		if (values.height !== undefined) this.size.height = values.height;
	};

	Resizable.prototype.setDisabled = function (disabled) {
		this.options.disabled = !!disabled;
		this.interactable.resizable({enabled: !this.options.disabled});
	};

	Resizable.prototype.destroy = function () {
		this.interactable.resizable(false);
	};

	return {
		draggable: function ($element, options) {
			return new Draggable($element, options);
		},
		resizable: function ($element, options) {
			return new Resizable($element, options);
		}
	};
});

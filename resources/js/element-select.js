/* global $ */
var elementSelect = {
  options: {
    name: 'No name'
  },

  init: function(options, container) {
    var obj = this;
    this.container = container;
    this.$container = $(container);
    this.options = $.extend({}, this.options, this.$container.data('options') || {}, options);
    this.$childElements = this.$container.find('.ElementSelect-option');
    this.multi = this.options.limit !== 1;
    this.$childElements.each(function() {
      obj.updateLabel.call(obj, this);
      obj.setStates.call(obj, this);
    });

    this.$childElements.on('click', function(event) {
      event.preventDefault();
      obj.toggleState.call(obj, this);
    });

    return this;
  },

  updateLabel: function(el, state) {
    var label = $(el).data('label');
    if (label) {
      $(el).find('.title').html(label);
    }
  },

  setState: function(el, state) {
    state = state === undefined ? this.isSelected(el) : state;
    $(el).find('.ElementSelect-input').prop('checked', state);
    $(el).toggleClass('sel', state);
  },

  setStates: function(el, state) {
    this.setState(el, state);

    if (this.multi) {
      this.toggleDisabled(state);
    }
  },

  toggleDisabled: function(state) {
    if (this.options.limit !== null && this.selected().length >= this.options.limit) {
      this.notSelected().addClass('disabled');
    } else if (!state) {
      this.notSelected().removeClass('disabled');
    }
  },

  toggleState: function(el) {
    if (!this.canToggle(el)) {
      return;
    }

    this.setStates(el, !this.isSelected(el));

    if (!this.multi) {
      var obj = this;
      this.$childElements
      .filter(function() {
        return this !== el;
      })
      .each(function() {
        obj.setState(this, false);
      });
    }
  },

  selected: function() {
    var obj = this;

    return this.$childElements.filter(function() {
      return obj.isSelected(this);
    });
  },

  notSelected: function() {
    var obj = this;

    return this.$childElements.not(function() {
      return obj.isSelected(this);
    });
  },

  canToggle: function(el) {
    return this.isSelected(el) || !this.multi || (this.options.limit === null || this.selected().length < this.options.limit);
  },

  isSelected: function(el) {
    return $(el).find('.ElementSelect-input').prop('checked');
  },
};

// Create a plugin based on a defined object
$.plugin = function(name, object) {
  $.fn[name] = function(options) {
    return this.each(function() {
      if (!$.data(this, name)) {
        $.data(this, name, Object.create(object).init(options, this));
      }
    });
  };
};

// jQuery plugin
$.plugin('elementSelect', elementSelect);

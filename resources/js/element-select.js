/* global window, jQuery */
var ElementSelect = function(elem, options) {
    this.elem = elem;
    this.$elem = $(elem);
    this.options = options;
    this.metadata = this.$elem.data('options');
  };

ElementSelect.prototype = {
  defaults: {
    limit: 1
  },
  init: function() {
    var instance = this;
    this.config = $.extend({}, this.defaults, this.options, this.metadata);
    this.multi = this.config.limit !== 1;
    this.$childElements = this.$elem.find('.ElementSelect-option');
    this.$childElements.each(function() {
      instance.updateLabel.call(instance, this);
      instance.setStates.call(instance, this);
    });
    this.$childElements.on('click', function(event) {
      event.preventDefault();
      instance.toggleState.call(instance, this);
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
    $(el).find('.element').toggleClass('sel', state);
  },

  setStates: function(el, state) {
    this.setState(el, state);

    if (this.multi) {
      this.toggleDisabled(state);
    }
  },

  toggleDisabled: function(state) {
    if (this.config.limit !== null && this.selected().length >= this.config.limit) {
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
      var instance = this;
      this.$childElements
      .filter(function() {
        return this !== el;
      })
      .each(function() {
        instance.setState(this, false);
      });
    }
  },

  selected: function() {
    var instance = this;

    return this.$childElements.filter(function() {
      return instance.isSelected(this);
    });
  },

  notSelected: function() {
    var instance = this;

    return this.$childElements.not(function() {
      return instance.isSelected(this);
    });
  },

  canToggle: function(el) {
    return this.isSelected(el) || !this.multi || (this.config.limit === null || this.selected().length < this.config.limit);
  },

  isSelected: function(el) {
    return $(el).find('.ElementSelect-input').prop('checked');
  }
};

ElementSelect.defaults = ElementSelect.prototype.defaults;

$.fn.elementSelect = function(options) {
  return this.each(function() {
    new ElementSelect(this, options).init();
  });
};

window.ElementSelect = ElementSelect;

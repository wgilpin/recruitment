'use strict';

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Base = function () {
  function Base(scope, selector) {
    _classCallCheck(this, Base);

    this.states = {
      loaded: 1,
      unloaded: 2
    };
    this.scope = scope;
    this.state = this.states.unloaded;
    this.dom = $(selector).parent().find('.content');;
  }

  _createClass(Base, [{
    key: 'onClick',
    value: function onClick(data, cb) {
      if (window.global.id) {
        if (this.state == this.states.unloaded) {
          return this.get(data).done(cb);
        }
      }
      alert('No character selected\n\nPlease choose an alt');
    }
  }, {
    key: 'onLoaded',
    value: function onLoaded(context, templateName) {
      console.log(context);
      this.state = this.states.loaded;

      // create html via template
      window.templates.prepareAndApply('./templates/{0}.hbs'.format(templateName), templateName, this.dom, { result: context });
    }
  }, {
    key: 'get',
    value: function get(data) {
      data = _extends({}, data, { scope: this.scope, id: window.global.id });
      return $.post({
        url: 'Pullpage.php',
        data: data,
        dataType: 'json'
      });
    }
  }, {
    key: 'resetState',
    value: function resetState() {
      this.state = this.states.unloaded;
    }
  }]);

  return Base;
}();
//# sourceMappingURL=base.js.map
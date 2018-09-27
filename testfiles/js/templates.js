'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Templates = function () {
  function Templates() {
    var _this = this;

    _classCallCheck(this, Templates);

    this.items = {};
    // return odd if index is odd, even if even
    Handlebars.registerHelper('oddEven', function (index, odd, even) {
      return index % 2 == 0 ? even : odd;
    });

    // format an ISK value with commas etc
    Handlebars.registerHelper('isk', function (amount) {
      return _this.formatMoney(amount);
    });

    // return posClass if value > 0, else negClass
    Handlebars.registerHelper('posNeg', function (value, posClass, negClass) {
      return value > 0 ? posClass : negClass;
    });

    // pretty format a date in the local locale
    Handlebars.registerHelper('dateFormat', function (date) {
      var newdate = new Date(date);
      return newdate.toLocaleDateString() + ' ' + newdate.toLocaleTimeString();
    });

    // Add method to prototype. this allows you to use this function on numbers and strings directly
    Number.prototype.commarize = this.commarize;
    String.prototype.commarize = this.commarize;
  }

  // compile a template and store locally


  _createClass(Templates, [{
    key: 'prepare',
    value: function prepare(templateFile, templateId) {
      var _this2 = this;

      // templateFile: url
      // templateId: string label for the template in the list

      // don't prepare if already done
      if (this.items[templateId]) return;
      return $.get(templateFile, function (source) {
        _this2.items[templateId] = Handlebars.compile(source);
      });
    }
  }, {
    key: 'apply',


    // apply a template given a context object
    value: function apply(domId, templateId, data) {
      // domId: form jQuery $(..) or find()
      // templateId: string stored id when we complied it
      // data: json object
      var html = this.items[templateId](data);

      domId.html(html);
    }
  }, {
    key: 'prepareAndApply',


    // shortcut to apply and prep
    value: function prepareAndApply(templateFile, templateId, domId, data) {
      var _this3 = this;

      // don't prepare if already done
      if (this.items[templateId]) {
        try {
          this.apply(domId, templateId, data);
          return;
        } catch (e) {
          console.error(e);
        }
      }

      // first time
      this.prepare(templateFile, templateId).then(function () {
        try {
          _this3.apply(domId, templateId, data);
          return;
        } catch (e) {
          console.error(e);
        }
      });
    }
  }, {
    key: 'formatMoney',


    // format a money value
    value: function formatMoney(n, c, d, t) {
      // n: currency amount
      // c: char comma
      // d: char decimal point
      // from https://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-dollars-currency-string-in-javascript
      c = isNaN(c = Math.abs(c)) ? 2 : c;
      d = d == undefined ? '.' : d;
      t = t == undefined ? ',' : t;
      var s = n < 0 ? '-' : '',
          i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
          j = (j = i.length) > 3 ? j % 3 : 0;

      return s + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
    }
  }, {
    key: 'commarize',
    value: function commarize(min) {
      // from https://gist.github.com/MartinMuzatko/1060fe584d17c7b9ca6e
      min = min || 1e3;
      // Alter numbers larger than 1k
      if (this >= min) {
        var units = ["k", "M", "B", "T"];

        var order = Math.floor(Math.log(this) / Math.log(1000));

        var unitname = units[order - 1];
        var num = Math.floor(this / Math.pow(1000, order));

        // output number remainder + unitname
        return num + unitname;
      }

      // return formatted original number
      return this.toLocaleString();
    }
  }]);

  return Templates;
}();
//# sourceMappingURL=templates.js.map
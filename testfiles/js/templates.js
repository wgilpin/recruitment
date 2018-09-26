'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Templates = function () {
  function Templates() {
    _classCallCheck(this, Templates);

    templates.items = {};
    // return odd if index is odd, even if even
    Handlebars.registerHelper('oddEven', function (index, odd, even) {
      return index % 2 == 0 ? even : odd;
    });

    // format an ISK value with commas etc
    Handlebars.registerHelper('isk', function (amount) {
      return templates.formatMoney(amount);
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
  }

  // compile a template and store locally


  _createClass(Templates, [{
    key: 'prepare',
    value: function prepare(templateFile, templateId) {
      // templateFile: url
      // templateId: string label for the template in the list

      // don't prepare if already done
      if (templates.items[templateId]) return;
      return $.get(templateFile, function (source) {
        templates.items[templateId] = Handlebars.compile(source);
      });
    }
  }, {
    key: 'apply',


    // apply a template given a context object
    value: function apply(domId, templateId, data) {
      // domId: form jQuery $(..) or find()
      // templateId: string stored id when we complied it
      // data: json object
      var html = templates.items[templateId](data);

      domId.html(html);
    }
  }, {
    key: 'prepareAndApply',


    // shortcut to apply and prep
    value: function prepareAndApply(templateFile, templateId, domId, data) {
      // don't prepare if already done
      if (templates.items[templateId]) {
        try {
          templates.apply(domId, templateId, data);
          return;
        } catch (e) {
          console.error(e);
        }
      }

      // first time
      templates.prepare(templateFile, templateId).then(function () {
        try {
          templates.apply(domId, templateId, data);
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
  }]);

  return Templates;
}();
//# sourceMappingURL=templates.js.map
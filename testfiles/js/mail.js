'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Mail = function (_Base) {
  _inherits(Mail, _Base);

  function Mail() {
    _classCallCheck(this, Mail);

    var dom = $('#MAIL').parent().find('.content');

    var _this = _possibleConstructorReturn(this, (Mail.__proto__ || Object.getPrototypeOf(Mail)).call(this, 'mail', dom));

    _this.mailId = null;

    // who the mail is from
    Handlebars.registerHelper('mailTypes', function (rType, rId) {
      return rType == 'corporation' || rType == 'alliance' ? rId : '';
    });

    return _this;
  }

  _createClass(Mail, [{
    key: 'click',
    value: function click() {
      if (global.id) {
        if (_get(Mail.prototype.__proto__ || Object.getPrototypeOf(Mail.prototype), 'state', this) == _get(Mail.prototype.__proto__ || Object.getPrototypeOf(Mail.prototype), 'states', this).unloaded) {
          // fetch all mail for alt
          _get(Mail.prototype.__proto__ || Object.getPrototypeOf(Mail.prototype), 'get', this).call(this).done(this.onLoaded);
        }
      } else {
        alert('No character selected\n\nPlease choose an alt');
      }
    }
  }, {
    key: 'onLoaded',
    value: function onLoaded(result) {
      _get(Mail.prototype.__proto__ || Object.getPrototypeOf(Mail.prototype), 'onLoaded', this).call(this, result.info);
    }
  }, {
    key: 'clickMail',
    value: function clickMail(MailID) {
      this.mailId = MailID;
      // toggle header
      $(this).toggleClass('active');
      // is the mail already loaded?
      var mailLoaded = !$('#_' + mail.mailId).html().trim();
      if (!mailLoaded) {
        // not loaded - get it
        _get(Mail.prototype.__proto__ || Object.getPrototypeOf(Mail.prototype), 'get', this).call(this, { MailID: MailID }).done(this.mailLoaded);
      } else {
        // loaded - show it
        $('#_' + mail.mailId).toggle();
      }
    }
  }, {
    key: 'mailLoaded',
    value: function mailLoaded(result) {
      var malm = '<div>' + result + '</div>';
      // TODO: we should return mailID in the json data, to prevent timing errors
      $('#_' + mail.mailId).show().html(malm);
    }
  }]);

  return Mail;
}(Base);
//# sourceMappingURL=mail.js.map
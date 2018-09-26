'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Wallet = function (_Base) {
  _inherits(Wallet, _Base);

  function Wallet() {
    _classCallCheck(this, Wallet);

    var dom = $('#WALLET').parent().find('.content');
    return _possibleConstructorReturn(this, (Wallet.__proto__ || Object.getPrototypeOf(Wallet)).call(this, 'wallet', dom));
  }

  _createClass(Wallet, [{
    key: 'onLoaded',
    value: function onLoaded(result) {
      // update with the correct third party for the item
      var transactions = result.info.map(function (el) {
        el.threeParty = el.first_party_id.id == global.myName ? el.second_party_id.id : el.first_party_id.id;
        return el;
      });

      var last = transactions[0];
      var context = {
        balance: last ? last.balance : 0,
        balance_words: last ? last.balance.commarize() : 'Zero',
        transactions: transactions
      };

      _get(Wallet.prototype.__proto__ || Object.getPrototypeOf(Wallet.prototype), 'onLoaded', this).call(this, context, 'walletList');
    }
  }, {
    key: 'loadError',
    value: function loadError(xhr, status, error) {
      console.log(status);
      console.log(error);
    }
  }, {
    key: 'load',
    value: function load() {
      if (this.state == _get(Wallet.prototype.__proto__ || Object.getPrototypeOf(Wallet.prototype), 'states', this).unloaded) {
        if (global.id) {
          _get(Wallet.prototype.__proto__ || Object.getPrototypeOf(Wallet.prototype), 'get', this).call(this).done(this.onLoaded).fail(this.loadError);
        } else {
          alert('No character selected\n\nPlease choose an alt');
        }
      }
    }
  }]);

  return Wallet;
}(Base);
//# sourceMappingURL=wallet.js.map
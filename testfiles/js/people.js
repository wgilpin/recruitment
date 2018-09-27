'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var People = function () {
  function People() {
    _classCallCheck(this, People);

    $('.Profilepic').on('click', this.click, bind(this));
  }

  _createClass(People, [{
    key: 'click',
    value: function click() {
      // store list index
      window.global.id = $(this).attr('id');
      // store selected char name
      window.global.myName = $(this).find('.char-name').html();
      console.log(window.global.id);
      $('.allcontent').find('.content div').hide();
      $('.Profilepic').removeClass('active');
      $('.collapsible').removeClass('active');
      $(this).addClass('active');

      mail.resetState();
      assets.resetState();
      wallet.resetState();
    }
  }]);

  return People;
}();
//# sourceMappingURL=people.js.map
$(document).ready(function() {

  window.global = new Global();
  window.templates = new Templates();
  window.people = new People();
  window.login = new Login('#LOGIN');
  window.wallet = new Wallet('#WALLET');
  window.assets = new Assets('#ASSETS');
  window.titles = new Titles('#TITELS');
  window.mail = new Mail('#MAIL');
  window.blueprints = new Blueprints('#BLUEPINTS');
  window.bookmarks = new Bookmarks('#BOOKMARKS');

  $('.collapsible').each(function() {
    $(this).next().hide();
  });

  $('.collapsible').each(function() {
    $(this).on('click', function() {
      $(this).toggleClass('active').next().toggle();
    });
  });
});

String.prototype.format = function() {
  let args = [].slice.call(arguments);
  return this.replace(/(\{\d+\})/g, function(a) {
    return args[+a.substr(1, a.length - 2) || 0];
  });
};


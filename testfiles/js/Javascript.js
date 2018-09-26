$(document).ready(function() {
  global.mailState = 0;
  $('.Profilepic').on('click', people.click);

  $('#LOGIN').on('click', login.click);

  $('#WALLET').on('click', wallet.load);

  $('#ASSETS').on('click', assets.click);

  $('#TITELS').on('click', titles.click);

  $('#MAIL').on('click', mail.click);

  $('#BLUEPINTS').on('click', blueprints.click);

  $('#BOOKMARKS').on('click', bookmarks.click);

  $('.collapsible').each(function() {
    $(this).on('click', function() {
      $(this).toggleClass('active').next().toggle();
    });
  });
});

String.prototype.format = function() {
  var args = [].slice.call(arguments);
  return this.replace(/(\{\d+\})/g, function(a) {
    return args[+a.substr(1, a.length - 2) || 0];
  });
};

function commarize(min) {
  // from https://gist.github.com/MartinMuzatko/1060fe584d17c7b9ca6e
  min = min || 1e3;
  // Alter numbers larger than 1k
  if (this >= min) {
    var units = ["k", "M", "B", "T"];
    
    var order = Math.floor(Math.log(this) / Math.log(1000));

    var unitname = units[(order - 1)];
    var num = Math.floor(this / (Math.pow(1000, order)));
    
    // output number remainder + unitname
    return num + unitname
  }
  
  // return formatted original number
  return this.toLocaleString()
}

// Add method to prototype. this allows you to use this function on numbers and strings directly
Number.prototype.commarize = commarize
String.prototype.commarize = commarize
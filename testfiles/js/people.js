people = {};

people.click = function() {
  // store list index
  global.id = $(this).attr('id');
  // store selected char name
  global.myName = $(this).find('.char-name').html()
  console.log(global.id);
  $('.allcontent')
    .find('.content')
    .html('<div></div>')
    .hide();
  $('.Profilepic').removeClass('active');
  $('.collapsible').removeClass('active');
  $(this).addClass('active');
  
  mail.state = 'unloaded';
  assets.state = 'unloaded';
  wallet.state = 'unloaded';
};

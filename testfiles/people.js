people = {};

people.click = function() {
  // store list index
  global.id = $(this).attr('id');
  // store selected char name
  global.myName = $(this).find('.char-name').html()
  console.log(global.id);
  $('.allcontent')
    .find('.content')
    .hide();
  mail = 0;
};

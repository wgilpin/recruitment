login = {};

login.click = function() {
  var scope = 'login';
  $.post({
    url: 'Pullpage.php',
    data: { id: global.id, scope: scope },
    dataType: 'json',
    success: login.onLoad
  });
};

login.onLoad = function(result) {
  
  var loginDom = $('#LOGIN')
    .parent()
    .find('.content');

  // create html via template
  templates.prepareAndApply(
    './templates/kvp.hbs', 
    'kvp', 
    loginDom, 
    { result: result }
  );
  
};
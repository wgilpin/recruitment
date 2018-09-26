titles = {}

titles.click = function() {
  var scope = 'titles';
  $.post({
    url: 'Pullpage.php',
    data: { id: global.id, scope: scope },
    dataType: 'json',
    success: login.onLoad
  });
};

login.onLoad = function(result) {
  
  var titlesDom = $('#TITELS')
    .parent()
    .find('.content');

  // create html via template
  templates.prepareAndApply(
    './templates/kvp.hbs', 
    'kvp', 
    titlesDom, 
    { result: result }
  );
  
};
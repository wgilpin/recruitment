global = {
  id: null
};

global.onCLick = function(scope, cb) {
  return function (){
    if (global.id) {
      $.post({
        url: 'Pullpage.php',
        data: { id: global.id, scope: scope },
        dataType: 'json',
        success: cb,
      });
      return;
    }
    alert('No character selected\n\nPlease choose an alt');
}};

global.onLoaded = function(result, domId, templateName) {
  console.log(result);
  var dom = $(domId)
    .parent()
    .find('.content');

  // create html via template
  templates.prepareAndApply(
    './templates/{0}.hbs'.format(templateName), 
    templateName, 
    dom, 
    { result: result }
  );
};
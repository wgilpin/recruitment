assets = {};

assets.click = global.onCLick('assets', assets.onLoaded);

assets.onLoaded = function(result) {
  console.log(result);
  var assetDom = $('#ASSETS')
    .parent()
    .find('.content');

  // create html via template
  templates.prepareAndApply('./templates/assetList.hbs', 'assets', assetDom, {
    result: result,
  });
};

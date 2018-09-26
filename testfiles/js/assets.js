assets = {
  state: 'unloaded'
};

assets.click = function(){
  assets.assetDom = $('#ASSETS')
    .parent()
    .find('.content');

  if (assets.state == 'unloaded'){
    global.onCLick('assets', assets.onLoaded);
  }
  else {
    // show or hide already loaded mail list
    assets.mailDom.toggle();
  }
}

assets.onLoaded = function(result) {
  console.log(result);
  
  assets.state = 'loaded';
  // create html via template
  templates.prepareAndApply('./templates/assetList.hbs', 'assets', assets.assetDom, {
    result: result,
  });
};

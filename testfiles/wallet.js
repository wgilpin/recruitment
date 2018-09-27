wallet = {};

wallet.load = function() {
  // call server to get list of wallet items
  var scope = 'wallet';
  $.ajax({
    type: 'POST',
    url: 'Pullpage.php',
    data: { id: global.id, scope: scope },
    dataType: 'json',
    success: wallet.onLoaded,
  });
};

wallet.onLoaded = function(result) {
  // we have the list of wallet items from the ajax call
  var walletDom = $('#WALLET')
    .parent()
    .find('.content');

  if (result.error) {
    var html =
      "<div class='behinditall'>" +
      "<div class='error'>" +
      result.error +
      '</div>';
    walletDom.html(html);
    return;
  }

  // update with the correct third party for the item
  fullResult = result.map(function(el) {
    el.threeParty =
      el.first_party_id.id == global.myName
        ? el.second_party_id.name
        : el.first_party_id.name;
    return el;
  });

  // create html via template
  templates.prepareAndApply('./templates/walletList.hbs', 'wallet', walletDom, {
    result: fullResult,
    balance: result[0].balance,
    id: global.name,
  });
};

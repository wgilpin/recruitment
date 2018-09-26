wallet = {
  state: 'unloaded',
};

wallet.onLoaded = function(result) {
  // update with the correct third party for the item
  var transactions = result.info.map(function(el) {
    el.threeParty =
      el.first_party_id.id == global.myName
        ? el.second_party_id.id
        : el.first_party_id.id;
    return el;
  });

  var fullResult = {
    balance: transactions[0].balance,
    balance_words: transactions[0].balance.commarize(),
    transactions: transactions,
  };

  global.onLoaded(fullResult, '#WALLET', 'walletList');
  wallet.state = 'loaded';
};

wallet.loadError = function(xhr, status, error) {
  console.log(status);
  console.log(error);
};

wallet.load = function() {
  if (wallet.state == 'unloaded') {
    if (global.id) {
      $.post({
        url: 'Pullpage.php',
        data: { id: global.id, scope: 'wallet' },
        dataType: 'json',
        success: wallet.onLoaded,
      });
    } else {
      alert('No character selected\n\nPlease choose an alt');
    }
  } 
};

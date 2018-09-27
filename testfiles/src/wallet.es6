'use strict';

class Wallet extends Base {
  constructor(selector) {
    let dom = $(selector).parent().find('.content');

    super('wallet', dom);
    $(selector).on('click', this.click.bind(this));
  }

  click() {
    super.onClick({}, this.onLoaded.bind(this));
  };

  onLoaded(result) {
    // update with the correct third party for the item
    let transactions = result.info.map((el) => {
      el.threeParty =
        el.first_party_id.id == window.global.myName
          ? el.second_party_id.id
          : el.first_party_id.id;
      return el;
    });

    // the most recent transaction has the current balance
    let last = transactions[0];
    let context = {
      balance: last ? last.balance : 0,
      balance_words: last ? last.balance.commarize() : 'Zero',
      transactions,
    };

    super.onLoaded(context, 'walletList');
  };

}


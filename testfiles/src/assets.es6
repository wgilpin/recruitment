'use strict';

class Assets extends Base {
  constructor(selector) {
    super('assets', selector);

    $(selector).on('click', this.click.bind(this));
  };

  click() {
    if (super.state == super.states.unloaded) {
      super.onCLick(super.onLoaded);
    }
    else {
      // show or hide already loaded mail list
      super.dom.toggle();
    }
  }
}
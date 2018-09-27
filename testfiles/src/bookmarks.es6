'use strict';

class Bookmarks extends Base {
  constructor(selector) {
    super('bookmarks', selector);

    $(selector).on('click', this.click.bind(this));
  };

  click() {
    super.onClick({}, super.onLoaded);
  }
};
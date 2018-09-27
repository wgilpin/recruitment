class Titles extends Base {
  constructor(selector) {
    super('titles', selector);

    $(selector).on('click', this.click.bind(this));
  };

  click() {
    super.onClick({}, super.onLoaded);
  }

}
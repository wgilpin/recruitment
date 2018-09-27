class Blueprints extends Base {
  constructor(selector) {
    super('blueprints', selector);

    $(selector).on('click', this.click.bind(this));
  };

  click() {
    super.onClick(super.onLoaded);
  }

}
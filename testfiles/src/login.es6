class Login extends Base {
  constructor(selector) {
    super('login', selector);

    $(selector).on('click', this.click.bind(this));
  };

  click () {
    super.get().done(super.onLoaded)
  };

}

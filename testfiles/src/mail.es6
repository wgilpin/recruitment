'use strict';

class Mail extends Base {
  constructor(selector) {
    super('mail', selector);

    this.mailId = null;
    $(selector).on('click', this.click.bind(this));

    // who the mail is from
    Handlebars.registerHelper(
      'mailTypes',
      (rType, rId) => rType == 'corporation' || rType == 'alliance' ? rId : ''
    );

  };

  click() {
    super.onClick({}, this.onLoaded.bind(this));
  };

  onLoaded(result) {
    super.onLoaded(result.info, 'mailList');
  };

  clickMail(MailID) {
    this.mailId = MailID;
    // toggle header
    $(this).toggleClass('active');
    // is the mail already loaded?
    let mailLoaded = $('#_' + MailID).html().trim();
    if (!mailLoaded) {
      // not loaded - get it
      super.get({MailID}).done(this.mailLoaded);
    } else {
      // loaded - show it
      $(`#_${MailID}`).toggle();
    }
  };

  mailLoaded(result) {
    let malm = `<div>${result}</div>`;
    // TODO: we should return mailID in the json data, to prevent timing errors
    $(`#_${mail.mailId}`)
      .show()
      .html(malm);
  };
}

mail = {
  state: 'unloaded',
};

mail.click = function() {
  mail.mailDom = $('#MAIL')
    .parent()
    .find('.content');

  if (global.id) {
    if (mail.state == 'unloaded') {
      // fetch all mail for alt
      $.post({
        url: 'Pullpage.php',
        data: { id: global.id, scope: 'mail' },
        dataType: 'json',
        success: mail.onLoaded,
      });
      return;
    }
  } else {
    alert('No character selected\n\nPlease choose an alt');
  }
};

mail.onLoaded = function(result) {
  console.log(result);
  mail.state = 'loaded';

  // create html via template
  templates.prepareAndApply('./templates/mailList.hbs', 'mail', mail.mailDom, {
    result: result.info,
  });
};

mail.clickMail = function(MailID) {
  $(this).toggleClass('active');
  mail.mailId = MailID;
  //var content = this.nextElementSibling;
  //content.style.display = content.style.display === 'block' ? 'none' : 'block';
  if (
    !$('#_' + mail.mailId)
      .html()
      .trim()
  ) {
    $.post({
      url: 'Pullpage.php',
      data: {
        id: global.id,
        scope: 'mail',
        MailID: MailID,
      },
      dataType: 'json',
      success: mail.mailLoaded,
    });
  } else {
    $('#_' + mail.mailId).toggle();
  }
};

mail.mailLoaded = function(result) {
  var malm = '<div>{0}</div>'.format(result);
  // TODO: we should return mailID in the json data, to prevent timing errors
  $('#_' + mail.mailId)
    .show()
    .html(malm);
};

// who the mail is from
Handlebars.registerHelper('mailTypes', function(recipient_type, recipient_id) {
  if (recipient_type == 'corporation' || recipient_type == 'alliance') {
    return recipient_id;
  }
  return '';
});

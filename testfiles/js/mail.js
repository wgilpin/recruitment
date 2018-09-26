mail = {};

mail.click = function() {
  if (global.id) {
    $.post({
      url: 'Pullpage.php',
      data: { id: global.id, scope: 'mail' },
      dataType: 'json',
      success: mail.onLoaded,
    });
    return;
  }
  alert('No character selected\n\nPlease choose an alt');
};

mail.onLoaded = function(result) {
  console.log(result);

  mail.mailDom = $('#MAIL')
    .parent()
    .find('.content');

  // create html via template
  templates.prepareAndApply('./templates/mailList.hbs', 'mail', mail.mailDom, {
    result: result.info,
  });

  // var coll = document.getElementsByClassName('mails');
  // var i;
  // for (i = 0; i < coll.length; i++) {
  //   coll[i].addEventListener('click', function() {
  //     this.classList.toggle('active');

  //     var content = this.nextElementSibling;
  //     if (content.style.display === 'block') {
  //       content.style.display = 'none';
  //     } else {
  //       content.style.display = 'block';
  //     }
  //   });
  // }
};

mail.clickMail = function(MailID) {
  $(this).toggleClass('active');
  mail.mailId = MailID;
  //var content = this.nextElementSibling;
  //content.style.display = content.style.display === 'block' ? 'none' : 'block';

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
};

mail.mailLoaded = function(result) {
  var malm = '<div>{0}</div>'.format(result.body);
  // TODO: we should return mailID in the json data, to prevent timing errors
  $('#mail' + mail.mailID).html(malm);
};

// who the mail is from
Handlebars.registerHelper('mailTypes', function(recipient_type, recipient_id) {
  if (recipient_type == 'corporation' || recipient_type == 'alliance') {
    return recipient_id;
  }
  return '';
});
